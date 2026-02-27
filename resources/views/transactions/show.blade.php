@extends('layouts.app')
@section('title', 'Transaction ' . $transaction->reference_no . ' — iFix')
@section('page-title', 'Transaction Details')
@section('page-subtitle', $transaction->reference_no)

@section('content')
<div class="max-w-2xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('transactions.index') }}" class="text-slate-500 hover:text-slate-300 transition">Transactions</a>
        <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-white font-mono text-xs">{{ $transaction->reference_no }}</span>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">

        {{-- Header --}}
        <div class="px-8 py-6 border-b border-slate-800 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0
                {{ $transaction->type === 'stock_in' ? 'bg-green-500/10' : 'bg-red-500/10' }}">
                @if($transaction->type === 'stock_in')
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                @else
                <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="text-2xl font-black {{ $transaction->type === 'stock_in' ? 'text-green-400' : 'text-red-400' }}">
                        {{ $transaction->type === 'stock_in' ? '+' : '-' }}{{ $transaction->quantity }} {{ $transaction->product->unit }}
                    </span>
                    @if($transaction->type === 'stock_in')
                    <span class="bg-green-500/20 text-green-400 text-xs font-semibold px-3 py-1 rounded-full border border-green-500/30">Stock In</span>
                    @else
                    <span class="bg-red-500/20 text-red-400 text-xs font-semibold px-3 py-1 rounded-full border border-red-500/30">Stock Out</span>
                    @endif
                </div>
                <p class="text-white font-semibold">{{ $transaction->product->name }}</p>
                <p class="text-slate-500 text-xs font-mono mt-0.5">{{ $transaction->reference_no }}</p>
            </div>
        </div>

        {{-- Details --}}
        <div class="p-8">
            <dl class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-800/50 rounded-xl p-4">
                        <dt class="text-slate-500 text-xs mb-1">Product</dt>
                        <dd class="text-white text-sm font-medium">{{ $transaction->product->name }}</dd>
                        <dd class="text-slate-400 text-xs font-mono">{{ $transaction->product->sku }}</dd>
                    </div>
                    <div class="bg-slate-800/50 rounded-xl p-4">
                        <dt class="text-slate-500 text-xs mb-1">Quantity</dt>
                        <dd class="text-white text-2xl font-bold">{{ $transaction->quantity }}</dd>
                        <dd class="text-slate-400 text-xs">{{ $transaction->product->unit }}</dd>
                    </div>
                    <div class="bg-slate-800/50 rounded-xl p-4">
                        <dt class="text-slate-500 text-xs mb-1">Unit Price</dt>
                        <dd class="text-white text-sm font-medium">{{ $transaction->unit_price ? '₱' . number_format($transaction->unit_price, 2) : 'Not specified' }}</dd>
                        @if($transaction->unit_price)
                        <dd class="text-slate-400 text-xs">Total: ₱{{ number_format($transaction->unit_price * $transaction->quantity, 2) }}</dd>
                        @endif
                    </div>
                    <div class="bg-slate-800/50 rounded-xl p-4">
                        <dt class="text-slate-500 text-xs mb-1">Recorded By</dt>
                        <dd class="text-white text-sm font-medium">{{ $transaction->user->name }}</dd>
                        <dd class="text-slate-400 text-xs capitalize">{{ $transaction->user->role }}</dd>
                    </div>
                </div>

                <div class="bg-slate-800/50 rounded-xl p-4">
                    <dt class="text-slate-500 text-xs mb-1">Date & Time</dt>
                    <dd class="text-white text-sm font-medium">{{ $transaction->created_at->format('F d, Y') }}</dd>
                    <dd class="text-slate-400 text-xs">{{ $transaction->created_at->format('h:i A') }} · {{ $transaction->created_at->diffForHumans() }}</dd>
                </div>

                @if($transaction->remarks)
                <div class="bg-slate-800/50 rounded-xl p-4">
                    <dt class="text-slate-500 text-xs mb-1">Remarks</dt>
                    <dd class="text-white text-sm leading-relaxed">{{ $transaction->remarks }}</dd>
                </div>
                @endif
            </dl>

            <div class="flex items-center gap-3 mt-6">
                <a href="{{ route('transactions.index') }}"
                    class="border border-slate-700 text-slate-300 hover:text-white hover:border-slate-600 px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    ← Back to Transactions
                </a>
                <a href="{{ route('products.show', $transaction->product) }}"
                    class="text-blue-400 hover:text-blue-300 text-sm transition">
                    View Product →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection