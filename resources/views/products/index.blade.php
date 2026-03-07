@extends('layouts.app')
@section('title', 'Products — iFix')
@section('page-title', 'Products')
@section('page-subtitle', 'Manage inventory products')
 
@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" class="flex gap-3">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by name or SKU..."
            class="bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm w-72"
        >

        <select
            name="category_id"
            class="bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm"
        >
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @endforeach
        </select>

        <select
            name="status"
            class="bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm"
        >
            <option value="">All Status</option>
            <option value="low_stock" {{ request('status')=='low_stock'?'selected':'' }}>Low Stock</option>
            <option value="out_of_stock" {{ request('status')=='out_of_stock'?'selected':'' }}>Out of Stock</option>
        </select>

        <button
            type="submit"
            class="bg-teal-600 hover:bg-teal-500 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition"
        >
            Filter
        </button>

        <a
            href="{{ route('products.index') }}"
            class="border border-slate-300 bg-white text-slate-700 hover:text-teal-700 hover:border-teal-300 hover:bg-teal-50 px-4 py-2.5 rounded-xl text-sm transition"
        >
            Reset
        </a>
    </form>

    <a href="{{ route('products.create') }}"
        class="bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add Product
    </a>
</div>
 
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50">
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Product</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">SKU</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Category</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Price</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Qty</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Status</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($products as $product)
            <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                <td class="px-6 py-4">
                    <p class="text-slate-900 font-medium text-sm">{{ $product->name }}</p>
                    <p class="text-slate-500 text-xs">{{ $product->unit }}</p>
                </td>

                <td class="px-6 py-4">
                    <span class="text-slate-700 text-sm font-mono">{{ $product->sku }}</span>
                </td>

                <td class="px-6 py-4">
                    <span class="text-slate-600 text-sm">{{ $product->category->name ?? '—' }}</span>
                </td>

                <td class="px-6 py-4">
                    <p class="text-slate-900 text-sm font-medium">₱{{ number_format($product->selling_price, 2) }}</p>
                    <p class="text-slate-500 text-xs">Cost: ₱{{ number_format($product->cost_price, 2) }}</p>
                </td>

                <td class="px-6 py-4">
                    <span class="text-slate-900 font-semibold text-sm">{{ $product->quantity }}</span>
                    <span class="text-slate-500 text-xs"> / min {{ $product->low_stock_threshold }}</span>
                </td>

                <td class="px-6 py-4">
                    @if($product->quantity == 0)
                    <span class="bg-rose-50 text-rose-700 border border-rose-200 text-xs font-medium px-2.5 py-1 rounded-full">Out of Stock</span>
                    @elseif($product->isLowStock())
                    <span class="bg-amber-50 text-amber-700 border border-amber-200 text-xs font-medium px-2.5 py-1 rounded-full">Low Stock</span>
                    @else
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-medium px-2.5 py-1 rounded-full">In Stock</span>
                    @endif
                </td>

                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('products.show', $product) }}"
                           class="text-slate-500 hover:text-slate-800 transition p-1.5 rounded-lg hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>

                        <a href="{{ route('products.edit', $product) }}"
                           class="text-teal-600 hover:text-teal-700 transition p-1.5 rounded-lg hover:bg-teal-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Archive this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:text-rose-700 transition p-1.5 rounded-lg hover:bg-rose-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-slate-500 py-16">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $products->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection