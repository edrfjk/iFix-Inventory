@extends('layouts.app')
@section('title', 'Transactions — iFix')
@section('page-title', 'Stock Transactions')
@section('page-subtitle', 'All stock-in and stock-out records')

@section('content')
{{-- Filter Bar --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 flex-1">
        <select name="type" class="bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
            <option value="">All Types</option>
            <option value="stock_in"  {{ request('type') === 'stock_in'  ? 'selected' : '' }}>↑ Stock In</option>
            <option value="stock_out" {{ request('type') === 'stock_out' ? 'selected' : '' }}>↓ Stock Out</option>
        </select>

        <input type="date" name="from" value="{{ request('from') }}"
            class="bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">

        <input type="date" name="to" value="{{ request('to') }}"
            class="bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">

        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product / ref..."
            class="bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm w-52">

        <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
            Filter
        </button>

        <a href="{{ route('transactions.index') }}" class="border border-slate-300 bg-white text-slate-700 hover:text-teal-700 hover:border-teal-300 hover:bg-teal-50 px-4 py-2.5 rounded-xl text-sm transition">
            Reset
        </a>
    </form>

    <a href="{{ route('transactions.create') }}"
        class="bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Transaction
    </a>
</div>

{{-- Quick Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3 shadow-sm">
        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <p class="text-slate-900 font-bold text-lg">{{ $transactions->total() }}</p>
            <p class="text-slate-500 text-xs">Total Records</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3 shadow-sm">
        <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
            </svg>
        </div>
        <div>
            <p class="text-emerald-700 font-bold text-lg">{{ $stockInTotal }}</p>
            <p class="text-slate-500 text-xs">Units In (filtered)</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3 shadow-sm">
        <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
            </svg>
        </div>
        <div>
            <p class="text-rose-700 font-bold text-lg">{{ $stockOutTotal }}</p>
            <p class="text-slate-500 text-xs">Units Out (filtered)</p>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50">
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Reference</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Type</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Product</th>
                <th class="text-right text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Qty</th>
                <th class="text-right text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Unit Price</th>
                <th class="text-right text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Total</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">By</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Date</th>
                <th class="px-6 py-4"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $txn)
            <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                <td class="px-6 py-4">
                    <span class="text-slate-600 text-xs font-mono">{{ $txn->reference_no }}</span>
                </td>

                <td class="px-6 py-4">
                    @if($txn->type === 'stock_in')
                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-1.5 rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                        IN
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold px-2.5 py-1.5 rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                        OUT
                    </span>
                    @endif
                </td>

                <td class="px-6 py-4">
                    <p class="text-slate-900 text-sm font-medium">{{ $txn->product->name }}</p>
                    <p class="text-slate-500 text-xs font-mono">{{ $txn->product->sku }}</p>
                </td>

                <td class="px-6 py-4 text-right">
                    <span class="font-bold text-sm {{ $txn->type === 'stock_in' ? 'text-emerald-700' : 'text-rose-700' }}">
                        {{ $txn->type === 'stock_in' ? '+' : '-' }}{{ $txn->quantity }}
                    </span>
                    <span class="text-slate-500 text-xs"> {{ $txn->product->unit }}</span>
                </td>

                <td class="px-6 py-4 text-right">
                    <span class="text-slate-700 text-sm">{{ $txn->unit_price ? '₱' . number_format($txn->unit_price, 2) : '—' }}</span>
                </td>

                <td class="px-6 py-4 text-right">
                    <span class="text-slate-900 text-sm font-medium">
                        {{ $txn->unit_price ? '₱' . number_format($txn->unit_price * $txn->quantity, 2) : '—' }}
                    </span>
                </td>

                <td class="px-6 py-4">
                    <span class="text-slate-600 text-sm">{{ $txn->user->name }}</span>
                </td>

                <td class="px-6 py-4">
                    <p class="text-slate-700 text-sm">{{ $txn->created_at->format('M d, Y') }}</p>
                    <p class="text-slate-500 text-xs">{{ $txn->created_at->format('h:i A') }}</p>
                </td>

                <td class="px-6 py-4">
                    <a href="{{ route('transactions.show', $txn) }}"
                        class="text-slate-500 hover:text-teal-700 transition p-1.5 rounded-lg hover:bg-teal-50 inline-flex">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                </td>
            </tr>

            @if($txn->remarks)
            <tr class="border-b border-slate-200 bg-slate-50/70">
                <td colspan="9" class="px-6 pb-2.5 pt-0">
                    <p class="text-slate-500 text-xs italic">↳ {{ $txn->remarks }}</p>
                </td>
            </tr>
            @endif

            @empty
            <tr>
                <td colspan="9" class="text-center py-20">
                    <div class="inline-flex flex-col items-center">
                        <svg class="w-10 h-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        <p class="text-slate-600 text-sm">No transactions found</p>
                        <a href="{{ route('transactions.create') }}" class="text-teal-700 hover:text-teal-800 text-sm mt-2 transition font-medium">Record first transaction →</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between">
        <p class="text-slate-500 text-xs">
            Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }}
        </p>
        {{ $transactions->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection