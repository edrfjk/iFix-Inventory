@extends('layouts.app')
@section('title', $product->name . ' — iFix')
@section('page-title', $product->name)
@section('page-subtitle', 'Product Details & Transaction History')

@section('content')
{{-- Breadcrumb --}}
<div class="flex items-center gap-2 text-sm mb-6">
    <a href="{{ route('products.index') }}" class="text-slate-500 hover:text-slate-300 transition">Products</a>
    <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-white truncate max-w-xs">{{ $product->name }}</span>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- LEFT: Product Info Card --}}
    <div class="col-span-1 space-y-5">

        {{-- Main Info --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            {{-- Status Badge --}}
            <div class="flex items-center justify-between mb-5">
                @if($product->quantity == 0)
                <span class="bg-red-500/20 text-red-400 text-xs font-semibold px-3 py-1.5 rounded-full border border-red-500/30">Out of Stock</span>
                @elseif($product->isLowStock())
                <span class="bg-amber-500/20 text-amber-400 text-xs font-semibold px-3 py-1.5 rounded-full border border-amber-500/30">⚠ Low Stock</span>
                @else
                <span class="bg-green-500/20 text-green-400 text-xs font-semibold px-3 py-1.5 rounded-full border border-green-500/30">✓ In Stock</span>
                @endif
                <span class="text-slate-500 text-xs">{{ $product->is_active ? 'Active' : 'Archived' }}</span>
            </div>

            {{-- Stock Level Visual --}}
            <div class="mb-5 p-4 bg-slate-800/60 rounded-xl text-center">
                <p class="text-5xl font-black text-white">{{ $product->quantity }}</p>
                <p class="text-slate-400 text-sm mt-1">{{ $product->unit }} in stock</p>
                @php
                    $pct = $product->low_stock_threshold > 0
                        ? min(100, round(($product->quantity / ($product->low_stock_threshold * 3)) * 100))
                        : 100;
                    $barColor = $product->quantity == 0 ? 'bg-red-500' : ($product->isLowStock() ? 'bg-amber-500' : 'bg-green-500');
                @endphp
                <div class="mt-3 h-1.5 bg-slate-700 rounded-full overflow-hidden">
                    <div class="{{ $barColor }} h-full rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
                <p class="text-slate-500 text-xs mt-1">Alert at {{ $product->low_stock_threshold }} {{ $product->unit }}</p>
            </div>

            {{-- Details List --}}
            <dl class="space-y-3">
                <div class="flex justify-between items-start">
                    <dt class="text-slate-500 text-xs">SKU</dt>
                    <dd class="text-white text-xs font-mono">{{ $product->sku }}</dd>
                </div>
                <div class="flex justify-between items-start">
                    <dt class="text-slate-500 text-xs">Category</dt>
                    <dd class="text-white text-xs text-right">{{ $product->category->name ?? '—' }}</dd>
                </div>
                <div class="flex justify-between items-start">
                    <dt class="text-slate-500 text-xs">Supplier</dt>
                    <dd class="text-white text-xs text-right">{{ $product->supplier->name ?? '—' }}</dd>
                </div>
                <div class="border-t border-slate-800 pt-3">
                    <div class="flex justify-between items-start mb-2">
                        <dt class="text-slate-500 text-xs">Cost Price</dt>
                        <dd class="text-slate-300 text-xs">₱{{ number_format($product->cost_price, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-start">
                        <dt class="text-slate-500 text-xs">Selling Price</dt>
                        <dd class="text-green-400 text-sm font-bold">₱{{ number_format($product->selling_price, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-start mt-1">
                        <dt class="text-slate-500 text-xs">Margin</dt>
                        @php $margin = $product->selling_price - $product->cost_price; @endphp
                        <dd class="text-xs {{ $margin >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $margin >= 0 ? '+' : '' }}₱{{ number_format($margin, 2) }}
                        </dd>
                    </div>
                </div>
                @if($product->description)
                <div class="border-t border-slate-800 pt-3">
                    <dt class="text-slate-500 text-xs mb-1">Description</dt>
                    <dd class="text-slate-300 text-xs leading-relaxed">{{ $product->description }}</dd>
                </div>
                @endif
                <div class="border-t border-slate-800 pt-3">
                    <div class="flex justify-between">
                        <dt class="text-slate-500 text-xs">Added</dt>
                        <dd class="text-slate-400 text-xs">{{ $product->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between mt-1">
                        <dt class="text-slate-500 text-xs">Last Updated</dt>
                        <dd class="text-slate-400 text-xs">{{ $product->updated_at->diffForHumans() }}</dd>
                    </div>
                </div>
            </dl>

            {{-- Action Buttons --}}
            <div class="flex flex-col gap-2 mt-5">
                <a href="{{ route('transactions.create') }}?product_id={{ $product->id }}"
                    class="w-full text-center bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-400 hover:to-blue-600 text-white py-2.5 rounded-xl text-sm font-medium transition shadow-lg">
                    + Record Transaction
                </a>
                <a href="{{ route('products.edit', $product) }}"
                    class="w-full text-center border border-slate-700 text-slate-300 hover:text-white hover:border-slate-600 py-2.5 rounded-xl text-sm font-medium transition">
                    Edit Product
                </a>
                <a href="{{ route('products.index') }}"
                    class="w-full text-center text-slate-500 hover:text-slate-400 py-2 rounded-xl text-sm transition">
                    ← Back to Products
                </a>
            </div>
        </div>

        {{-- Stock Summary --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-3">Stock Summary</p>
            @php
                $totalIn  = $product->transactions->where('type', 'stock_in')->sum('quantity');
                $totalOut = $product->transactions->where('type', 'stock_out')->sum('quantity');
            @endphp
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-green-500/10 border border-green-500/20 rounded-xl p-3 text-center">
                    <p class="text-green-400 text-xl font-bold">+{{ $totalIn }}</p>
                    <p class="text-slate-500 text-xs mt-0.5">Total In</p>
                </div>
                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3 text-center">
                    <p class="text-red-400 text-xl font-bold">-{{ $totalOut }}</p>
                    <p class="text-slate-500 text-xs mt-0.5">Total Out</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Transaction History --}}
    <div class="col-span-2">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-800">
                <h3 class="text-white font-semibold">Transaction History</h3>
                <span class="text-slate-500 text-sm">{{ $product->transactions->count() }} total</span>
            </div>

            @if($product->transactions->count() > 0)
            <div class="overflow-y-auto max-h-[calc(100vh-280px)]">
                <table class="w-full">
                    <thead class="sticky top-0 bg-slate-900/95 backdrop-blur-sm">
                        <tr class="border-b border-slate-800">
                            <th class="text-left text-slate-400 text-xs font-semibold uppercase tracking-wider px-6 py-3">Type</th>
                            <th class="text-left text-slate-400 text-xs font-semibold uppercase tracking-wider px-6 py-3">Reference</th>
                            <th class="text-left text-slate-400 text-xs font-semibold uppercase tracking-wider px-6 py-3">Quantity</th>
                            <th class="text-left text-slate-400 text-xs font-semibold uppercase tracking-wider px-6 py-3">Unit Price</th>
                            <th class="text-left text-slate-400 text-xs font-semibold uppercase tracking-wider px-6 py-3">By</th>
                            <th class="text-left text-slate-400 text-xs font-semibold uppercase tracking-wider px-6 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->transactions->sortByDesc('created_at') as $txn)
                        <tr class="border-b border-slate-800/50 hover:bg-slate-800/30 transition">
                            <td class="px-6 py-4">
                                @if($txn->type === 'stock_in')
                                <span class="inline-flex items-center gap-1.5 bg-green-500/15 text-green-400 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                                    IN
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 bg-red-500/15 text-red-400 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                                    OUT
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-400 text-xs font-mono">{{ $txn->reference_no }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-white font-bold text-sm {{ $txn->type === 'stock_in' ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $txn->type === 'stock_in' ? '+' : '-' }}{{ $txn->quantity }} {{ $product->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-300 text-sm">{{ $txn->unit_price ? '₱' . number_format($txn->unit_price, 2) : '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-400 text-sm">{{ $txn->user->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-slate-300 text-sm">{{ $txn->created_at->format('M d, Y') }}</p>
                                <p class="text-slate-500 text-xs">{{ $txn->created_at->format('h:i A') }}</p>
                            </td>
                        </tr>
                        @if($txn->remarks)
                        <tr class="border-b border-slate-800/50 bg-slate-800/10">
                            <td colspan="6" class="px-6 pb-3 pt-0">
                                <p class="text-slate-500 text-xs italic">"{{ $txn->remarks }}"</p>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="py-20 text-center">
                <div class="w-14 h-14 bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="text-white font-medium">No transactions yet</p>
                <p class="text-slate-500 text-sm mt-1">Record the first stock movement for this product</p>
                <a href="{{ route('transactions.create') }}"
                    class="inline-flex items-center gap-2 mt-4 bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
                    + New Transaction
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection