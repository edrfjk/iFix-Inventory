@extends('layouts.app')
@section('title', 'Reports — iFix')
@section('page-title', 'Inventory Reports')
@section('page-subtitle', 'Track stock movements and performance')

@section('content')

{{-- Filter Bar --}}
<div class="bg-white border border-slate-200 rounded-2xl p-5 mb-6 shadow-sm">
    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-slate-700 text-xs font-medium mb-1.5">From Date</label>
            <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                class="bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
        </div>

        <div>
            <label class="block text-slate-700 text-xs font-medium mb-1.5">To Date</label>
            <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                class="bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
        </div>

        <div>
            <label class="block text-slate-700 text-xs font-medium mb-1.5">Product</label>
            <select name="product_id" class="bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm min-w-[180px]">
                <option value="">All Products</option>
                @foreach($allProducts as $p)
                <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-slate-700 text-xs font-medium mb-1.5">Type</label>
            <select name="type" class="bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                <option value="">All Types</option>
                <option value="stock_in"  {{ request('type') === 'stock_in'  ? 'selected' : '' }}>Stock In</option>
                <option value="stock_out" {{ request('type') === 'stock_out' ? 'selected' : '' }}>Stock Out</option>
            </select>
        </div>

        {{-- Quick Range Shortcuts --}}
        <div class="flex gap-2">
            @php
                $ranges = [
                    'today' => ['Today', now()->format('Y-m-d'), now()->format('Y-m-d')],
                    'week'  => ['This Week', now()->startOfWeek()->format('Y-m-d'), now()->format('Y-m-d')],
                    'month' => ['This Month', now()->startOfMonth()->format('Y-m-d'), now()->format('Y-m-d')],
                ];
            @endphp
            @foreach($ranges as $key => [$label, $rf, $rt])
            <a href="{{ route('reports.index', array_merge(request()->all(), ['from' => $rf, 'to' => $rt])) }}"
               class="border border-slate-300 bg-white text-slate-700 hover:text-teal-700 hover:border-teal-300 hover:bg-teal-50 px-3 py-2.5 rounded-xl text-xs transition">
               {{ $label }}
            </a>
            @endforeach
        </div>

        <div class="flex gap-2 ml-auto">
            <button type="submit"
                class="bg-teal-600 hover:bg-teal-500 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Generate
            </button>

            <button type="button" onclick="printReport()"
                class="border border-slate-300 bg-white text-slate-700 hover:text-teal-700 hover:border-teal-300 hover:bg-teal-50 px-4 py-2.5 rounded-xl text-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </button>
        </div>
    </form>
</div>

{{-- Print Header --}}
<div id="print-header" class="hidden print:block mb-6">
    <div style="display:flex; align-items:center; gap:16px; border-bottom:2px solid #0f766e; padding-bottom:16px; margin-bottom:20px;">
        <div style="width:50px;height:50px;background:#0f766e;border-radius:12px;display:flex;align-items:center;justify-content:center;overflow:hidden;">
            <img src="{{ asset('images/ifix-logo.png') }}" alt="iFix Logo" style="width:42px;height:42px;object-fit:cover;border-radius:9999px;">
        </div>
        <div>
            <h1 style="margin:0;font-size:22px;font-weight:800;color:#0f172a;">iFix Inventory Management System</h1>
            <p style="margin:4px 0 0;color:#64748b;font-size:13px;">Telecommunication Parts Trading</p>
        </div>
        <div style="margin-left:auto;text-align:right;">
            <p style="font-size:13px;font-weight:600;color:#0f172a;">INVENTORY REPORT</p>
            <p style="font-size:12px;color:#64748b;">{{ $from->format('M d, Y') }} — {{ $to->format('M d, Y') }}</p>
            <p style="font-size:11px;color:#94a3b8;">Generated: {{ now()->format('M d, Y h:i A') }}</p>
        </div>
    </div>
</div>

<div id="report-content">
<div class="grid grid-cols-4 gap-5 mb-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
            </div>
            <span class="text-emerald-700 text-xs font-medium bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">IN</span>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ number_format($totalStockIn) }}</p>
        <p class="text-slate-600 text-xs mt-0.5">Total Units Received</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
            </div>
            <span class="text-rose-700 text-xs font-medium bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">OUT</span>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ number_format($totalStockOut) }}</p>
        <p class="text-slate-600 text-xs mt-0.5">Total Units Released</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-8 h-8 bg-teal-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
        @php $net = $totalStockIn - $totalStockOut; @endphp
        <p class="text-2xl font-bold {{ $net >= 0 ? 'text-teal-700' : 'text-amber-700' }}">
            {{ $net >= 0 ? '+' : '' }}{{ number_format($net) }}
        </p>
        <p class="text-slate-600 text-xs mt-0.5">Net Movement</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-8 h-8 bg-violet-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ $transactions->total() }}</p>
        <p class="text-slate-600 text-xs mt-0.5">Total Transactions</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-5 mb-6">
    {{-- Top Moving Products --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-slate-900 font-semibold text-sm">Most Active Products</h3>
            <p class="text-slate-500 text-xs mt-0.5">By total transaction count</p>
        </div>
        <div class="p-4 space-y-2">
            @forelse($topMovingProducts->take(8) as $i => $prod)
            @php $maxMov = $topMovingProducts->first()->total_movements ?: 1; @endphp
            <div class="flex items-center gap-3">
                <span class="text-slate-400 text-xs w-4 text-right flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-slate-800 text-xs font-medium truncate">{{ $prod->name }}</p>
                        <span class="text-slate-500 text-xs ml-2 flex-shrink-0">{{ $prod->total_movements }} txn</span>
                    </div>
                    <div class="h-1 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-500 rounded-full" style="width: {{ $maxMov > 0 ? round(($prod->total_movements / $maxMov) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-slate-500 text-sm text-center py-4">No data for this period</p>
            @endforelse
        </div>
    </div>

    {{-- Low Stock Products --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-slate-900 font-semibold text-sm">Low / Out of Stock</h3>
                <p class="text-slate-500 text-xs mt-0.5">Products needing attention</p>
            </div>
            @if($lowStockProducts->count() > 0)
            <span class="bg-amber-50 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full border border-amber-200">{{ $lowStockProducts->count() }}</span>
            @endif
        </div>
        <div class="divide-y divide-slate-200">
            @forelse($lowStockProducts->take(8) as $prod)
            <div class="flex items-center gap-3 px-5 py-3">
                <div class="w-7 h-7 {{ $prod->quantity == 0 ? 'bg-rose-50' : 'bg-amber-50' }} rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 {{ $prod->quantity == 0 ? 'text-rose-600' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-800 text-xs font-medium truncate">{{ $prod->name }}</p>
                    <p class="text-xs {{ $prod->quantity == 0 ? 'text-rose-700' : 'text-amber-700' }}">
                        {{ $prod->quantity }} {{ $prod->unit }} left (min: {{ $prod->low_stock_threshold }})
                    </p>
                </div>
                <a href="{{ route('transactions.create') }}?product_id={{ $prod->id }}" class="text-xs text-teal-700 hover:text-teal-800 flex-shrink-0 font-medium">Restock</a>
            </div>
            @empty
            <div class="px-5 py-8 text-center">
                <svg class="w-8 h-8 text-emerald-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-slate-500 text-xs">All products have sufficient stock</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Daily Transaction Chart 
@if($dailyData->count() > 0)
<div class="bg-white border border-slate-200 rounded-2xl p-6 mb-6 shadow-sm">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-slate-900 font-semibold text-sm">Daily Activity</h3>
            <p class="text-slate-500 text-xs mt-0.5">Stock in vs stock out per day</p>
        </div>
        <div class="flex items-center gap-4 text-xs">
            <span class="flex items-center gap-1.5 text-slate-500"><span class="w-3 h-2 bg-emerald-500 rounded-sm inline-block"></span>Stock In</span>
            <span class="flex items-center gap-1.5 text-slate-500"><span class="w-3 h-2 bg-rose-500 rounded-sm inline-block"></span>Stock Out</span>
        </div>
    </div>
    @php
        $maxVal = max(
            $dailyData->max('stock_in') ?: 1,
            $dailyData->max('stock_out') ?: 1
        );
    @endphp
    <div class="flex items-end gap-1 h-32 overflow-x-auto pb-2">
        @foreach($dailyData as $day)
        @php
            $inH  = $maxVal > 0 ? round(($day->stock_in  / $maxVal) * 100) : 0;
            $outH = $maxVal > 0 ? round(($day->stock_out / $maxVal) * 100) : 0;
        @endphp
        <div class="flex items-end gap-0.5 flex-shrink-0 group relative" style="min-width: 20px;">
            <div class="bg-emerald-500 rounded-t w-2.5 transition-all hover:bg-emerald-400" style="height: {{ $inH }}%"
                 title="{{ $day->date }}: +{{ $day->stock_in }} IN"></div>
            <div class="bg-rose-500 rounded-t w-2.5 transition-all hover:bg-rose-400" style="height: {{ $outH }}%"
                 title="{{ $day->date }}: -{{ $day->stock_out }} OUT"></div>

            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-10">
                <div class="bg-slate-800 rounded-lg px-3 py-2 text-xs whitespace-nowrap shadow-xl">
                    <p class="text-white font-medium">{{ \Carbon\Carbon::parse($day->date)->format('M d') }}</p>
                    <p class="text-emerald-400">+{{ $day->stock_in }} in</p>
                    <p class="text-rose-400">-{{ $day->stock_out }} out</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-between text-slate-500 text-xs mt-1">
        <span>{{ $from->format('M d') }}</span>
        <span>{{ $to->format('M d') }}</span>
    </div>
</div>
@endif

--}}

{{-- Transaction Table --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
        <div>
            <h3 class="text-slate-900 font-semibold text-sm">Transaction Log</h3>
            <p class="text-slate-500 text-xs mt-0.5">
                Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }} records
                · {{ $from->format('M d, Y') }} to {{ $to->format('M d, Y') }}
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-3">Reference</th>
                    <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-3">Type</th>
                    <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-3">Product</th>
                    <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-3">Category</th>
                    <th class="text-right text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-3">Qty</th>
                    <th class="text-right text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-3">Unit Price</th>
                    <th class="text-right text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-3">Total Value</th>
                    <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-3">Recorded By</th>
                    <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                    <td class="px-6 py-3">
                        <a href="{{ route('transactions.show', $txn) }}" class="text-slate-600 hover:text-teal-700 text-xs font-mono transition">
                            {{ $txn->reference_no }}
                        </a>
                    </td>
                    <td class="px-6 py-3">
                        @if($txn->type === 'stock_in')
                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-200">↑ IN</span>
                        @else
                        <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-rose-200">↓ OUT</span>
                        @endif
                    </td>
                    <td class="px-6 py-3">
                        <p class="text-slate-800 text-sm font-medium">{{ $txn->product->name }}</p>
                        <p class="text-slate-500 text-xs font-mono">{{ $txn->product->sku }}</p>
                    </td>
                    <td class="px-6 py-3">
                        <span class="text-slate-600 text-xs">{{ $txn->product->category->name ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <span class="font-bold text-sm {{ $txn->type === 'stock_in' ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $txn->type === 'stock_in' ? '+' : '-' }}{{ $txn->quantity }}
                        </span>
                        <span class="text-slate-500 text-xs"> {{ $txn->product->unit }}</span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <span class="text-slate-700 text-sm">{{ $txn->unit_price ? '₱' . number_format($txn->unit_price, 2) : '—' }}</span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <span class="text-slate-900 text-sm font-medium">
                            {{ $txn->unit_price ? '₱' . number_format($txn->unit_price * $txn->quantity, 2) : '—' }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <span class="text-slate-600 text-sm">{{ $txn->user->name }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <p class="text-slate-700 text-sm">{{ $txn->created_at->format('M d, Y') }}</p>
                        <p class="text-slate-500 text-xs">{{ $txn->created_at->format('h:i A') }}</p>
                    </td>
                </tr>
                @if($txn->remarks)
                <tr class="border-b border-slate-200 bg-slate-50/70">
                    <td colspan="9" class="px-6 pb-2 pt-0">
                        <p class="text-slate-500 text-xs italic">↳ {{ $txn->remarks }}</p>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="9" class="text-center py-16">
                        <div class="inline-flex flex-col items-center">
                            <svg class="w-10 h-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-slate-600 text-sm">No transactions found for this period</p>
                            <p class="text-slate-500 text-xs mt-1">Try adjusting your date range or filters</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>

            @if($transactions->count() > 0)
            <tfoot>
                <tr class="border-t-2 border-slate-300 bg-slate-50">
                    <td colspan="4" class="px-6 py-3 text-slate-600 text-xs font-semibold uppercase">Period Totals</td>
                    <td class="px-6 py-3 text-right">
                        <p class="text-emerald-700 text-xs font-bold">+{{ number_format($totalStockIn) }}</p>
                        <p class="text-rose-700 text-xs font-bold">-{{ number_format($totalStockOut) }}</p>
                    </td>
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3 text-right">
                        <span class="text-slate-900 text-sm font-bold">₱{{ number_format($totalValue, 2) }}</span>
                        <p class="text-slate-500 text-xs">Total Value</p>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between">
        <p class="text-slate-500 text-xs">
            Page {{ $transactions->currentPage() }} of {{ $transactions->lastPage() }}
        </p>
        {{ $transactions->withQueryString()->links() }}
    </div>
    @endif
</div>
</div>

<style>
@media print {
    aside, header, form, .no-print { display: none !important; }
    main { margin-left: 0 !important; }
    body { background: white !important; color: black !important; }
    #print-header { display: block !important; }
    .bg-white, .bg-slate-50 {
        background: white !important;
    }
    .border-slate-200, .border-slate-300 {
        border-color: #e2e8f0 !important;
    }
    .text-slate-900, .text-slate-800, .text-slate-700 {
        color: black !important;
    }
    .text-slate-600, .text-slate-500 {
        color: #64748b !important;
    }
    .text-emerald-700 { color: #15803d !important; }
    .text-rose-700 { color: #be123c !important; }
    .text-teal-700 { color: #0f766e !important; }
    .text-amber-700 { color: #b45309 !important; }
    table { width: 100% !important; font-size: 11px !important; }
    .rounded-2xl, .rounded-xl { border-radius: 8px !important; }
    * { box-shadow: none !important; }
}
</style>

<script>
function printReport() {
    window.print();
}
</script>

@endsection