<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BikeController extends Controller
{
    public function index()
    {
        $bikes = Bike::with(['seller', 'buyer'])->latest()->paginate(20);
        return view('bikes.index', compact('bikes'));
    }

    public function create()
    {
        $sellers = Customer::sellers()->get();
        return view('bikes.create', compact('sellers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vin' => 'required|unique:bikes',
            'make' => 'required',
            'model' => 'required',
            'year' => 'required|integer',
            'color' => 'required',
            'purchase_price' => 'required|numeric',
            'estimated_selling_price' => 'required|numeric',
            'seller_id' => 'required|exists:customers,id',
            'purchase_date' => 'required|date',
        ]);

        $bike = Bike::create($validated);

        // Record purchase transaction
        Transaction::create([
            'bike_id' => $bike->id,
            'type' => 'purchase',
            'amount' => $validated['purchase_price'],
            'description' => 'Initial purchase from seller',
            'date' => $validated['purchase_date'],
        ]);

        return redirect()->route('bikes.show', $bike)
            ->with('success', 'Bike added successfully!');
    }

    public function show(Bike $bike)
    {
        $bike->load(['seller', 'buyer', 'reconditioningLogs', 'transactions']);
        return view('bikes.show', compact('bike'));
    }

    public function edit(Bike $bike)
    {
        $sellers = Customer::sellers()->get();
        $buyers = Customer::buyers()->get();
        return view('bikes.edit', compact('bike', 'sellers', 'buyers'));
    }

    public function update(Request $request, Bike $bike)
    {
        $validated = $request->validate([
            'vin' => 'required|unique:bikes,vin,' . $bike->id,
            'make' => 'required',
            'model' => 'required',
            'year' => 'required|integer',
            'color' => 'required',
            'purchase_price' => 'required|numeric',
            'estimated_selling_price' => 'required|numeric',
            'sold_price' => 'nullable|numeric',
            'status' => 'required|in:in_stock,in_reconditioning,ready_for_sale,sold,scrapped',
            'seller_id' => 'required|exists:customers,id',
            'buyer_id' => 'nullable|exists:customers,id',
            'purchase_date' => 'required|date',
            'sale_date' => 'nullable|date',
        ]);

        $bike->update($validated);

        // Record sale transaction if bike is sold
        if ($validated['status'] === 'sold' && isset($validated['sold_price'])) {
            Transaction::create([
                'bike_id' => $bike->id,
                'type' => 'sale',
                'amount' => $validated['sold_price'],
                'description' => 'Sold to buyer',
                'date' => $validated['sale_date'] ?? now(),
            ]);
        }

        return redirect()->route('bikes.show', $bike)
            ->with('success', 'Bike updated successfully!');
    }

    public function destroy(Bike $bike)
    {
        $bike->delete();
        return redirect()->route('bikes.index')
            ->with('success', 'Bike deleted successfully!');
    }
}