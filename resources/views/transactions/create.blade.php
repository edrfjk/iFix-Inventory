@extends('layouts.app')
@section('title', 'New Transaction — iFix')
@section('page-title', 'Record Stock Transaction')
@section('page-subtitle', 'Record stock-in or stock-out movement')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('transactions.index') }}" class="text-slate-500 hover:text-teal-700 transition">Transactions</a>
        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">New Transaction</span>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 rounded-xl px-5 py-4">
        @foreach($errors->all() as $error)
        <p class="text-rose-600 text-sm flex items-center gap-2">
            <span class="w-1 h-1 bg-rose-500 rounded-full flex-shrink-0"></span>{{ $error }}
        </p>
        @endforeach
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">

        {{-- Type Toggle --}}
        <div class="mb-7">
            <p class="text-slate-700 text-xs font-medium mb-2">Transaction Type <span class="text-rose-500">*</span></p>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="type_display" value="stock_in" class="peer sr-only"
                        {{ old('type', 'stock_in') === 'stock_in' ? 'checked' : '' }}
                        onchange="setType('stock_in')">
                    <div class="flex items-center gap-3 p-4 bg-slate-50 border-2 border-slate-200 rounded-xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-900 font-semibold text-sm">Stock In</p>
                            <p class="text-slate-500 text-xs">Receive / add inventory</p>
                        </div>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="type_display" value="stock_out" class="peer sr-only"
                        {{ old('type') === 'stock_out' ? 'checked' : '' }}
                        onchange="setType('stock_out')">
                    <div class="flex items-center gap-3 p-4 bg-slate-50 border-2 border-slate-200 rounded-xl peer-checked:border-rose-500 peer-checked:bg-rose-50 transition">
                        <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-900 font-semibold text-sm">Stock Out</p>
                            <p class="text-slate-500 text-xs">Release / sell inventory</p>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <form method="POST" action="{{ route('transactions.store') }}" class="space-y-5" id="txn-form">
            @csrf
            <input type="hidden" name="type" id="type-hidden" value="{{ old('type', 'stock_in') }}">

            {{-- Product Select --}}
            <div>
                <label class="block text-slate-700 text-xs font-medium mb-1.5">Product <span class="text-rose-500">*</span></label>
                <select name="product_id" id="product-select" required
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm transition"
                    onchange="updateProductInfo()">
                    <option value="">— Select a Product —</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}"
                        data-qty="{{ $product->quantity }}"
                        data-unit="{{ $product->unit }}"
                        data-threshold="{{ $product->low_stock_threshold }}"
                        data-price="{{ $product->selling_price }}"
                        {{ old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} ({{ $product->sku }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Product Info Panel --}}
            <div id="product-info" class="hidden bg-slate-50 border border-slate-200 rounded-xl p-4">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-slate-500 text-xs mb-1">Current Stock</p>
                        <p id="info-qty" class="text-slate-900 font-bold text-xl"></p>
                        <p id="info-unit" class="text-slate-600 text-xs"></p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs mb-1">Low Stock At</p>
                        <p id="info-threshold" class="text-amber-700 font-bold text-xl"></p>
                        <p class="text-slate-600 text-xs">threshold</p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs mb-1">Stock Status</p>
                        <p id="info-status" class="font-bold text-sm mt-1"></p>
                    </div>
                </div>
            </div>

            {{-- Quantity & Price --}}
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-slate-700 text-xs font-medium mb-1.5">Quantity <span class="text-rose-500">*</span></label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" required
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm transition"
                        onchange="checkAvailability()">
                    <p id="qty-warning" class="hidden text-rose-600 text-xs mt-1"></p>
                </div>

                <div>
                    <label class="block text-slate-700 text-xs font-medium mb-1.5">Unit Price (₱) <span class="text-slate-400">Optional</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">₱</span>
                        <input type="number" name="unit_price" id="unit-price" value="{{ old('unit_price') }}" step="0.01" min="0"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-8 pr-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm transition"
                            placeholder="0.00"
                            onchange="calcTotal()">
                    </div>
                </div>
            </div>

            {{-- Total Value Display --}}
            <div id="total-display" class="hidden bg-teal-50 border border-teal-200 rounded-xl px-4 py-3 flex items-center justify-between">
                <span class="text-teal-700 text-sm font-medium">Estimated Total Value:</span>
                <span id="total-value" class="text-slate-900 font-bold text-lg"></span>
            </div>

            {{-- Remarks --}}
            <div>
                <label class="block text-slate-700 text-xs font-medium mb-1.5">Remarks <span class="text-slate-400">Optional</span></label>
                <textarea name="remarks" rows="3"
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm resize-none transition"
                    placeholder="Optional notes about this transaction...">{{ old('remarks') }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" id="submit-btn"
                    class="bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white px-8 py-3 rounded-xl font-medium transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Transaction
                </button>

                <a href="{{ route('transactions.index') }}"
                    class="border border-slate-300 text-slate-700 hover:text-teal-700 hover:border-teal-300 hover:bg-teal-50 px-6 py-3 rounded-xl font-medium transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const productData = {
    @foreach($products as $product)
    {{ $product->id }}: {
        qty:       {{ $product->quantity }},
        unit:      '{{ $product->unit }}',
        threshold: {{ $product->low_stock_threshold }},
        price:     {{ $product->selling_price }},
    },
    @endforeach
};

function setType(type) {
    document.getElementById('type-hidden').value = type;
    checkAvailability();
}

function updateProductInfo() {
    const select = document.getElementById('product-select');
    const id     = parseInt(select.value);
    const panel  = document.getElementById('product-info');

    if (!id || !productData[id]) {
        panel.classList.add('hidden');
        return;
    }

    const d = productData[id];
    panel.classList.remove('hidden');

    document.getElementById('info-qty').textContent       = d.qty;
    document.getElementById('info-unit').textContent      = d.unit;
    document.getElementById('info-threshold').textContent = d.threshold;

    const statusEl = document.getElementById('info-status');
    if (d.qty === 0) {
        statusEl.textContent  = 'Out of Stock';
        statusEl.className    = 'font-bold text-sm mt-1 text-rose-700';
    } else if (d.qty <= d.threshold) {
        statusEl.textContent  = 'Low Stock';
        statusEl.className    = 'font-bold text-sm mt-1 text-amber-700';
    } else {
        statusEl.textContent  = 'In Stock';
        statusEl.className    = 'font-bold text-sm mt-1 text-emerald-700';
    }

    if (!document.getElementById('unit-price').value) {
        document.getElementById('unit-price').value = d.price.toFixed(2);
        calcTotal();
    }

    checkAvailability();
}

function checkAvailability() {
    const type    = document.getElementById('type-hidden').value;
    const select  = document.getElementById('product-select');
    const id      = parseInt(select.value);
    const qty     = parseInt(document.getElementById('quantity').value) || 0;
    const warn    = document.getElementById('qty-warning');
    const btn     = document.getElementById('submit-btn');

    warn.classList.add('hidden');
    btn.disabled = false;

    if (type === 'stock_out' && id && productData[id]) {
        const available = productData[id].qty;
        if (qty > available) {
            warn.textContent = `Not enough stock! Only ${available} ${productData[id].unit} available.`;
            warn.classList.remove('hidden');
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    } else {
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    calcTotal();
}

function calcTotal() {
    const qty   = parseInt(document.getElementById('quantity').value) || 0;
    const price = parseFloat(document.getElementById('unit-price').value) || 0;
    const total = qty * price;
    const el    = document.getElementById('total-display');
    const valEl = document.getElementById('total-value');

    if (qty > 0 && price > 0) {
        el.classList.remove('hidden');
        valEl.textContent = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 });
    } else {
        el.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateProductInfo();
});
</script>
@endsection