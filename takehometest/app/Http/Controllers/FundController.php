<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\FundManager;
use App\Models\Company;
use App\Models\Alias;
use App\Events\DuplicateFundWarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Jobs\CreateFundJob;
use App\Jobs\UpdateFundJob;
use App\Models\FundCompanyInvestment;

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

        $funds = $funds->with('manager', 'aliases', 'fundCompanyInvestments')->get();
        $managers = FundManager::all();
        $companies = Company::all();

        return view('funds.index', compact('funds', 'managers', 'companies'));
    }


    public function create()
    {
        $managers = FundManager::all();
        $companies = Company::all();

        return view('funds.create', compact('managers', 'companies'));
    }


    public function getPotentialDuplicates()
    {
        // Logic to retrieve potentially duplicate funds
        // You can modify this query as per your requirements
        $potentialDuplicates = Fund::select('name', 'manager_id', 'start_year')
            ->groupBy('name', 'manager_id', 'start_year')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        return $potentialDuplicates;
    }



    public function potentialDuplicates()
    {
        // Retrieve the potentially duplicate funds
        $funds = $this->getPotentialDuplicates();

        return view('funds.potential_duplicates', compact('funds'));
    }

    public function store(Request $request)
    {
        try {
            $fundData = $this->validateFundData($request);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
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

        $companyIds = $fundData['company_id'] ?? [];
        foreach ($companyIds as $companyId) {
            $fundCompanyInvestment = new FundCompanyInvestment();
            $fundCompanyInvestment->fund_id = $fund->id;
            $fundCompanyInvestment->company_id = $companyId;
            $fundCompanyInvestment->save();
        }

        dispatch(new CreateFundJob($fundData, $fundData['alias_name'] ?? [], $fund))
            ->onQueue('rabbitmq');

        return redirect()->route('funds.index');
    }

    public function edit($id)
    {
        $fund = Fund::with('companies')->findOrFail($id);
        $managers = FundManager::all();
        $companies = Company::all();
        $aliases = Alias::all();

        return view('funds.edit', compact('fund', 'managers', 'companies', 'aliases', 'id'));
    }






    public function update(Fund $fund, Request $request)
    {
        // Retrieve and validate the request data
        $requestData = $request->validate([
            'name' => 'required|string',
            'manager_id' => 'required|exists:fund_managers,id',
            'aliases' => 'nullable|array',
            'aliases.*' => 'string',
            'company_ids' => 'nullable|array',
            'company_ids.*' => 'exists:companies,id',
        ]);

        // Update the fund data
        $fund->update($requestData);

        // Update the aliases
        $aliases = $requestData['aliases'] ?? [];
        $fund->aliases()->delete(); // Delete existing aliases
        $fund->aliases()->saveMany(
            collect($aliases)->map(function ($alias) {
                return new Alias(['alias' => $alias]);
            })
        );

        // Update the companies
        $companyIds = $requestData['company_ids'] ?? [];
        $fund->fundCompanyInvestments()->delete(); // Delete existing company investments
        $fund->fundCompanyInvestments()->saveMany(
            collect($companyIds)->map(function ($companyId) {
                return new FundCompanyInvestment(['company_id' => $companyId]);
            })
        );

        // Redirect to the funds index page with a success message
        return redirect()->route('funds.index')->with('message', 'Fund updated successfully.');
    }


    private function updateAliases(Fund $fund, array $aliases)
    {
        // Remove existing aliases not present in the request
        $existingAliases = $fund->aliases->pluck('id')->toArray();
        $aliasesToRemove = array_diff($existingAliases, $aliases);
        Alias::whereIn('id', $aliasesToRemove)->delete();

        // Add new aliases and update existing ones
        foreach ($aliases as $alias) {
            Alias::updateOrCreate(['fund_id' => $fund->id, 'alias' => $alias]);
        }
    }

    private function updateCompanies(Fund $fund, array $companyIds)
    {
        // Sync the pivot table for companies
        $fund->companies()->sync($companyIds);
    }
    private function validateFundData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string',
            'start_year' => 'required|integer',
            'manager_id' => 'required|exists:fund_managers,id',
            'company_id' => 'required|array',
            'company_id.*' => 'exists:companies,id',
            'alias_name' => 'array',
            'alias_name.*' => 'nullable|string',
        ]);
    }

    private function isDuplicateFund(Fund $fund): bool
    {
        return Fund::where('manager_id', $fund->manager_id)
            ->where(function ($query) use ($fund) {
                $query->where('name', $fund->name)
                    ->orWhereHas('aliases', function ($query) use ($fund) {
                        $query->where('alias', $fund->name);
                    });
            })->exists();
    }

    private function handleDuplicateFundWarning($fundName, $fundManagerName)
    {
        Log::info("Duplicate fund warning: Fund '$fundName' with manager '$fundManagerName'");
    }

    private function sendDuplicateFundWarningQueue($fundName, $fundManagerName)
    {
        try {
            $data = [
                'fundName' => $fundName,
                'fundManagerName' => $fundManagerName,
            ];

            $connection = stream_socket_client('tcp://rat-01.rmq2.cloudamqp.com:5672', $errno, $errstr, 30);

            if ($connection === false) {
                throw new \Exception("Failed to connect to rabbitmq server: $errstr", $errno);
            }

            $jsonPayload = json_encode($data);

            fwrite($connection, $jsonPayload);
            fclose($connection);
        } catch (\Exception $e) {
            // Handle the connection error
            $message = $e->getMessage();
            $code = $e->getCode();

            // Log the error
            Log::error("RabbitMQ connection error: $message");
        }
    }
}
