<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\FundManager;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function createManager()
    {
        return view('admin.create-manager');
    }

    public function storeManager(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            // Add any additional validation rules for manager creation
        ]);

        FundManager::create($data);

        return redirect()->route('admin.create-manager')->with('success', 'Manager created successfully.');
    }

    public function createCompany()
    {
        return view('admin.create-company');
    }

    public function storeCompany(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            // Add any additional validation rules for company creation
        ]);

        Company::create($data);

        return redirect()->route('admin.create-company')->with('success', 'Company created successfully.');
    }
}
