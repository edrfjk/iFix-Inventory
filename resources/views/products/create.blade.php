@extends('layouts.app')
@section('title', 'Add Product — iFix')
@section('page-title', 'Add New Product')
@section('page-subtitle', 'Register a new telecommunication part')

@section('content')
<div class="max-w-3xl">

    <div class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('products.index') }}" class="text-slate-500 hover:text-slate-300 transition">Products</a>
        <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-white">Add New</span>
    </div>

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
        <form method="POST" action="{{ route('products.store') }}" class="space-y-7">
            @csrf

            {{-- Section 1: Basic Info --}}
            <div>
                <h3 class="text-slate-300 text-sm font-semibold uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-blue-600 rounded flex items-center justify-center text-white text-xs font-bold">1</span>
                    Basic Information
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Product Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition"
                            placeholder="e.g. iPhone 14 Pro Screen Replacement">
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">SKU <span class="text-red-400">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm transition"
                            placeholder="e.g. IPH14P-SCR-001"
                            oninput="this.value = this.value.toUpperCase()">
                        <p class="text-slate-600 text-xs mt-1">Unique identifier for this product</p>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Unit</label>
                        <input type="text" name="unit" value="{{ old('unit', 'pcs') }}"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition"
                            placeholder="pcs / set / unit / kg">
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Category</label>
                        <select name="category_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                            <option value="">— Select Category —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Supplier</label>
                        <select name="supplier_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                            <option value="">— Select Supplier —</option>
                            @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none transition"
                            placeholder="Optional description of this product...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800"></div>

            {{-- Section 2: Pricing --}}
            <div>
                <h3 class="text-slate-300 text-sm font-semibold uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-green-600 rounded flex items-center justify-center text-white text-xs font-bold">2</span>
                    Pricing
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Cost Price (₱) <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-medium">₱</span>
                            <input type="number" name="cost_price" value="{{ old('cost_price', 0) }}" step="0.01" min="0" required
                                id="cost_price"
                                class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-8 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition"
                                onchange="calcMargin()">
                        </div>
                        <p class="text-slate-600 text-xs mt-1">Price you pay for the product</p>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Selling Price (₱) <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-medium">₱</span>
                            <input type="number" name="selling_price" value="{{ old('selling_price', 0) }}" step="0.01" min="0" required
                                id="selling_price"
                                class="w-full bg-slate-800 border border-slate-700 rounded-xl pl-8 pr-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition"
                                onchange="calcMargin()">
                        </div>
                        <p class="text-slate-600 text-xs mt-1">Price you charge the customer</p>
                    </div>
                    <div class="col-span-2">
                        <div id="margin-display" class="hidden bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-3 flex items-center gap-4">
                            <span class="text-slate-400 text-xs">Profit Margin:</span>
                            <span id="margin-val" class="text-sm font-bold"></span>
                            <span id="margin-pct" class="text-xs"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800"></div>

            {{-- Section 3: Stock --}}
            <div>
                <h3 class="text-slate-300 text-sm font-semibold uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-5 h-5 bg-amber-600 rounded flex items-center justify-center text-white text-xs font-bold">3</span>
                    Stock Settings
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Initial Quantity <span class="text-red-400">*</span></label>
                        <input type="number" name="quantity" value="{{ old('quantity', 0) }}" min="0" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                        <p class="text-slate-600 text-xs mt-1">Starting stock level</p>
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-medium mb-1.5">Low Stock Threshold <span class="text-red-400">*</span></label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 10) }}" min="1" required
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
                        <p class="text-slate-600 text-xs mt-1">Trigger alert when qty reaches this</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800"></div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-400 hover:to-blue-600 text-white px-8 py-3 rounded-xl font-medium transition shadow-lg hover:shadow-blue-500/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Save Product
                </button>
                <a href="{{ route('products.index') }}"
                    class="border border-slate-700 text-slate-400 hover:text-white hover:border-slate-600 px-6 py-3 rounded-xl font-medium transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function calcMargin() {
    const cost   = parseFloat(document.getElementById('cost_price').value) || 0;
    const sell   = parseFloat(document.getElementById('selling_price').value) || 0;
    const margin = sell - cost;
    const pct    = cost > 0 ? ((margin / cost) * 100).toFixed(1) : 0;

    const display = document.getElementById('margin-display');
    const valEl   = document.getElementById('margin-val');
    const pctEl   = document.getElementById('margin-pct');

    display.classList.remove('hidden');
    valEl.textContent = (margin >= 0 ? '+' : '') + '₱' + margin.toFixed(2);
    valEl.className   = 'text-sm font-bold ' + (margin >= 0 ? 'text-green-400' : 'text-red-400');
    pctEl.textContent = '(' + pct + '%)';
    pctEl.className   = 'text-xs ' + (margin >= 0 ? 'text-green-500' : 'text-red-500');
}
</script>
@endsection