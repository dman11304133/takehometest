<?php

namespace App\Http\Controllers\API;

use App\Events\DuplicateFundWarning;
use App\Http\Controllers\Controller;
use App\Jobs\CreateFundJob;
use App\Jobs\UpdateFundJob;
use App\Models\Alias;
use App\Models\Company;
use App\Models\Fund;
use App\Models\FundCompanyInvestment;
use App\Models\FundManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FundController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->query('name');
        $managerId = $request->query('manager_id');
        $year = $request->query('year');

        $funds = Fund::query();

        if ($name) {
            $funds->where('name', 'like', '%' . $name . '%');
        }

        if ($managerId) {
            $funds->where('manager_id', $managerId);
        }

        if ($year) {
            $funds->where('start_year', $year);
        }

        $funds = $funds->get();

        return response()->json($funds);
    }

    public function show($id)
    {
        $fund = Fund::with('manager', 'companies')->findOrFail($id);
        return response()->json($fund);
    }

    public function store(Request $request)
    {
        try {
            $fundData = $this->validateFundData($request);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }

        $fund = new Fund($fundData);

        if ($this->isDuplicateFund($fund)) {
            $this->sendDuplicateFundWarningQueue($fund->name, $fund->manager->name);
            $this->handleDuplicateFundWarning($fund->name, $fund->manager->name);
            session()->flash('message', 'Duplicate fund detected!');
        }

        $fund->save();

        $aliases = $fundData['alias_name'] ?? [];
        foreach ($aliases as $aliasName) {
            $alias = new Alias();
            $alias->alias = $aliasName;
            $alias->fund()->associate($fund); // Associate the alias with the fund
            $alias->save();
        }

        $companyIds = $fundData['company_ids'] ?? [];
        foreach ($companyIds as $companyId) {
            $fundCompanyInvestment = new FundCompanyInvestment();
            $fundCompanyInvestment->fund_id = $fund->id;
            $fundCompanyInvestment->company_id = $companyId;
            $fundCompanyInvestment->save();
        }

        dispatch(new CreateFundJob($fundData, $fundData['alias_name'] ?? []))
            ->onQueue('rabbitmq');

        return response()->json($fund, 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $fundData = $this->validateFundData($request);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }

        $fund = Fund::findOrFail($id);
        $fund->update($fundData);

        if ($this->isDuplicateFund($fund)) {
            $this->handleDuplicateFundWarning($fund->name, $fund->manager->name);
            session()->flash('message', 'Duplicate fund detected!');
        }

        dispatch(new UpdateFundJob($fundData, $fundData['alias_name'] ?? []));

        return response()->json($fund);
    }

    public function destroy($id)
    {
        $fund = Fund::findOrFail($id);
        $fund->delete();

        return response()->json(null, 204);
    }

    private function validateFundData(Request $request)
    {
        return $request->validate([
            'name' => 'required|string',
            'start_year' => 'required|integer',
            'manager_id' => 'required|exists:fund_managers,id',
            'company_ids' => 'required|array',
            'company_ids.*' => 'exists:companies,id',
            'alias_name' => 'array',
            'alias_name.*' => 'nullable|string',
        ]);
    }

    private function isDuplicateFund(Fund $fund)
    {
        $existingFunds = Fund::where('name', $fund->name)
            ->where('manager_id', $fund->manager_id)
            ->where('id', '!=', $fund->id)
            ->get();

        return $existingFunds->isNotEmpty();
    }

    private function sendDuplicateFundWarningQueue($fundName, $managerName)
    {
        // Send a duplicate fund warning message to a queue for further processing
        // You can implement the code to send the message to a queue service of your choice
        // For example, using Laravel's built-in job queues or a message broker like RabbitMQ
    }

    private function handleDuplicateFundWarning($fundName, $managerName)
    {
        // Handle the duplicate fund warning event
        // You can implement the code to perform any necessary actions, such as logging or notifications
        // For example, you can use Laravel's event system or any other custom logic based on your requirements
        event(new DuplicateFundWarning($fundName, $managerName));
    }
}
