<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::orderBy('name')->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:customers,name',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Customer::create($validatedData);

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return redirect()->route('admin.customers.edit', $customer->customer_id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:customers,name,' . $customer->customer_id . ',customer_id',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validatedData);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // Add a check here to ensure a customer with associated sales isn't deleted.
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
