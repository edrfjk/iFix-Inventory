@extends('layouts.app')
@section('title', 'Edit: ' . $product->name . ' — iFix')
@section('page-title', 'Edit Product')
@section('page-subtitle', 'Update product information')

@section('content')
<div class="max-w-3xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('products.index') }}" class="text-slate-500 hover:text-slate-300 transition">Products</a>
        <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.show', $product) }}" class="text-slate-500 hover:text-slate-300 transition truncate max-w-xs">{{ $product->name }}</a>
        <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-white">Edit</span>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-xl px-5 py-4">
        <p class="text-red-400 text-sm font-medium mb-2">Please fix the following errors:</p>
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
            <li class="text-red-300 text-sm flex items-center gap-2">
                <span class="w-1 h-1 bg-red-400 rounded-full flex-shrink-0"></span>{{ $error }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8">

        {{-- Current Stock Info Bar --}}
        <div class="flex items-center gap-4 p-4 bg-slate-800/60 rounded-xl mb-7 border border-slate-700/50">
            <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-white text-sm font-medium">{{ $product->name }}</p>
                <p class="text-slate-400 text-xs font-mono">SKU: {{ $product->sku }}</p>
            </div>
            <div class="text-right">
                <p class="text-white font-bold text-lg">{{ $product->quantity }} <span class="text-slate-400 text-sm font-normal">{{ $product->unit }}</span></p>
                <p class="text-slate-500 text-xs">Current Stock</p>
            </div>
            <div>
                @if($product->quantity == 0)
                <span class="bg-red-500/20 text-red-400 text-xs font-medium px-2.5 py-1 rounded-full">Out of Stock</span>
                @elseif($product->isLowStock())
                <span class="bg-amber-500/20 text-amber-400 text-xs font-medium px-2.5 py-1 rounded-full">Low Stock</span>
                @else
                <span class="bg-green-500/20 text-green-400 text-xs font-medium px-2.5 py-1 rounded-full">In Stock</span>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Info --}}
            <div>
                <h3 class="text-slate-300 text-sm font-semibold uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-blue-600 rounded flex items-center justify-center text-white text-xs font-bold">1</span>
                    Basic Information
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Product Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition"
                            placeholder="e.g. iPhone 14 Screen Replacement">
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">SKU <span class="text-red-400">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm transition"
                            placeholder="e.g. IPH14-SCR-001">
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Unit</label>
                        <input type="text" name="unit" value="{{ old('unit', $product->unit) }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition"
                            placeholder="pcs / set / unit">
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Category</label>
                        <select name="category_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                            <option value="">— Select Category —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Supplier</label>
                        <select name="supplier_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                            <option value="">— Select Supplier —</option>
                            @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id', $product->supplier_id) == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none transition"
                            placeholder="Optional product description...">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800"></div>

            {{-- Pricing --}}
            <div>
                <h3 class="text-slate-300 text-sm font-semibold uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-green-600 rounded flex items-center justify-center text-white text-xs font-bold">2</span>
                    Pricing
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Cost Price (₱) <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">₱</span>
                            <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" step="0.01" min="0" required
                                class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-8 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Selling Price (₱) <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">₱</span>
                            <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" step="0.01" min="0" required
                                class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-8 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800"></div>

            {{-- Stock Settings --}}
            <div>
                <h3 class="text-slate-300 text-sm font-semibold uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-amber-600 rounded flex items-center justify-center text-white text-xs font-bold">3</span>
                    Stock Settings
                    <span class="text-slate-500 text-xs font-normal normal-case ml-1">(To change quantity, use a Stock Transaction)</span>
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Low Stock Threshold <span class="text-red-400">*</span></label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="1" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                        <p class="text-slate-500 text-xs mt-1">Alert triggers when stock falls at or below this number</p>
                    </div>
                    <div class="flex items-start pt-6">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-700 peer-checked:bg-blue-600 rounded-full transition peer-focus:ring-2 peer-focus:ring-blue-500"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-slate-300 text-sm group-hover:text-white transition">Product is Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800"></div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-400 hover:to-blue-600 text-white px-8 py-3 rounded-xl font-medium transition shadow-lg hover:shadow-blue-500/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Update Product
                </button>
                <a href="{{ route('products.show', $product) }}"
                    class="border border-slate-700 text-slate-400 hover:text-white hover:border-slate-600 px-6 py-3 rounded-xl font-medium transition">
                    Cancel
                </a>
            </div>
        </form>

        {{-- Delete Product --}}
        <form method="POST"
              action="{{ route('products.destroy', $product) }}"
              onsubmit="return confirm('Delete this product permanently? This action cannot be undone.')"
              class="mt-6 flex justify-end">

            @csrf
            @method('DELETE')

            <button type="submit"
                class="text-red-400 hover:text-red-300 border border-red-500/30 hover:border-red-400/60 px-4 py-3 rounded-xl text-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete Product
            </button>
        </form>
    </div>
</div>
@endsection