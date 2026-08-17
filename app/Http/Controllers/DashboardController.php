<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_bikes' => Bike::count(),
            'in_stock' => Bike::where('status', 'in_stock')->count(),
            'in_reconditioning' => Bike::where('status', 'in_reconditioning')->count(),
            'ready_for_sale' => Bike::where('status', 'ready_for_sale')->count(),
            'sold' => Bike::where('status', 'sold')->count(),
            'total_customers' => Customer::count(),
            'sellers' => Customer::where('type', 'seller')->count(),
            'buyers' => Customer::where('type', 'buyer')->count(),
        ];

        $recentBikes = Bike::with('seller')
            ->latest()
            ->take(5)
            ->get();

        $recentTransactions = Transaction::with('bike')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentBikes', 'recentTransactions'));
    }
}
