@extends('layouts.app')
@section('title', 'Dashboard — iFix')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Inventory Overview')
 
@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-4 gap-5 mb-8">
    <!-- Total Products -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <span class="text-emerald-700 text-xs font-medium bg-emerald-50 px-2 py-1 rounded-full border border-emerald-200">Active</span>
        </div>
        <p class="text-3xl font-bold text-slate-900">{{ $totalProducts }}</p>
        <p class="text-slate-600 text-sm mt-1">Total Products</p>
    </div>

    <!-- Total Stock -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-sky-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </div>
            <span class="text-sky-700 text-xs font-medium bg-sky-50 px-2 py-1 rounded-full border border-sky-200">Units</span>
        </div>
        <p class="text-3xl font-bold text-slate-900">{{ number_format($totalStock) }}</p>
        <p class="text-slate-600 text-sm mt-1">Total Stock Units</p>
    </div>

    <!-- Low Stock -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <span class="text-amber-700 text-xs font-medium bg-amber-50 px-2 py-1 rounded-full border border-amber-200">Needs Restock</span>
        </div>
        <p class="text-3xl font-bold text-slate-900">{{ $lowStockCount }}</p>
        <p class="text-slate-600 text-sm mt-1">Low Stock Items</p>
    </div>

    <!-- Out of Stock -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <span class="text-rose-700 text-xs font-medium bg-rose-50 px-2 py-1 rounded-full border border-rose-200">Out of Stock</span>
        </div>
        <p class="text-3xl font-bold text-slate-900">{{ $outOfStockCount }}</p>
        <p class="text-slate-600 text-sm mt-1">Out of Stock</p>
    </div>
</div>
 
<!-- Today Activity -->
<div class="grid grid-cols-2 gap-5 mb-8">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <p class="text-slate-600 text-sm mb-1">Today's Stock In</p>
        <p class="text-2xl font-bold text-emerald-600">+{{ $todayStockIn }} units</p>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <p class="text-slate-600 text-sm mb-1">Today's Stock Out</p>
        <p class="text-2xl font-bold text-rose-600">-{{ $todayStockOut }} units</p>
    </div>
</div>
 
<!-- Bottom Grid -->
<div class="grid grid-cols-5 gap-5">
    <!-- Recent Transactions -->
    <div class="col-span-3 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-slate-900 font-semibold">Recent Transactions</h3>
            <a href="{{ route('transactions.index') }}" class="text-teal-700 text-sm hover:text-teal-800 font-medium">View all</a>
        </div>

        <div class="space-y-3">
            @forelse($recentTransactions as $txn)
            <div class="flex items-center gap-4 py-3 border-b border-slate-200 last:border-0">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $txn->type === 'stock_in' ? 'bg-emerald-50' : 'bg-rose-50' }}">
                    <svg class="w-4 h-4 {{ $txn->type === 'stock_in' ? 'text-emerald-600' : 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $txn->type === 'stock_in' ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M17 13l-5 5m0 0l-5-5m5 5V6' }}"></path>
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 text-sm font-medium truncate">{{ $txn->product->name }}</p>
                    <p class="text-slate-500 text-xs">{{ $txn->reference_no }} · {{ $txn->created_at->diffForHumans() }}</p>
                </div>

                <span class="text-sm font-semibold {{ $txn->type === 'stock_in' ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ $txn->type === 'stock_in' ? '+' : '-' }}{{ $txn->quantity }}
                </span>
            </div>
            @empty
            <p class="text-slate-500 text-sm text-center py-8">No transactions yet</p>
            @endforelse
        </div>
    </div>
 
    <!-- Low Stock Alerts -->
    <div class="col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-slate-900 font-semibold">Low Stock Alert</h3>
            <a href="{{ route('alerts.index') }}" class="text-amber-700 text-sm hover:text-amber-800 font-medium">View all</a>
        </div>

        <div class="space-y-3">
            @forelse($lowStockProducts as $product)
            <div class="flex items-center gap-3 py-2 border-b border-slate-200 last:border-0">
                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 text-sm font-medium truncate">{{ $product->name }}</p>
                    <p class="text-xs {{ $product->quantity == 0 ? 'text-rose-700' : 'text-amber-700' }}">
                        {{ $product->quantity }} {{ $product->unit }} left
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="w-8 h-8 text-emerald-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-slate-600 text-sm font-medium">All stocks are sufficient!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection