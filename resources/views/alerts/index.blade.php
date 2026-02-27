@extends('layouts.app')
@section('title', 'Low-Stock Alerts — iFix')
@section('page-title', 'Low-Stock Alerts')
@section('page-subtitle', 'Products that need restocking attention')

@section('content')

{{-- Summary Bar --}}
@php
    $unread = $alerts->where('is_read', false)->count();
    $total  = $alerts->total();
@endphp

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        @if($unread > 0)
        <div class="flex items-center gap-2 bg-amber-500/10 border border-amber-500/30 rounded-xl px-4 py-2">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="text-amber-400 text-sm font-semibold">{{ $unread }} unread alert{{ $unread != 1 ? 's' : '' }}</span>
        </div>
        @else
        <div class="flex items-center gap-2 bg-green-500/10 border border-green-500/30 rounded-xl px-4 py-2">
            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-green-400 text-sm font-semibold">All alerts read</span>
        </div>
        @endif
    </div>

    @if($unread > 0)
    <form method="POST" action="{{ route('alerts.read-all') }}">
        @csrf
        <button type="submit"
            class="border border-slate-700 text-slate-400 hover:text-white hover:border-slate-600 px-5 py-2.5 rounded-xl text-sm font-medium transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Mark All as Read
        </button>
    </form>
    @endif
</div>

{{-- Alert Cards --}}
@if($alerts->count() > 0)
<div class="space-y-3">
    @foreach($alerts as $alert)
    <div class="bg-slate-900 border {{ $alert->is_read ? 'border-slate-800' : 'border-amber-500/40' }} rounded-2xl p-5 flex items-center gap-5 transition">

        {{-- Icon --}}
        <div class="w-12 h-12 {{ $alert->is_read ? 'bg-slate-800' : ($alert->product->quantity == 0 ? 'bg-red-500/10' : 'bg-amber-500/10') }} rounded-xl flex items-center justify-center flex-shrink-0">
            @if($alert->product->quantity == 0)
            <svg class="w-6 h-6 {{ $alert->is_read ? 'text-slate-600' : 'text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            @else
            <svg class="w-6 h-6 {{ $alert->is_read ? 'text-slate-600' : 'text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            @endif
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-start gap-2 mb-1">
                <p class="text-white font-semibold text-sm">{{ $alert->product->name }}</p>
                @if(!$alert->is_read)
                <span class="w-2 h-2 bg-amber-400 rounded-full flex-shrink-0 mt-1.5"></span>
                @endif
            </div>
            <p class="text-slate-400 text-sm">
                Current stock:
                <span class="font-semibold {{ $alert->product->quantity == 0 ? 'text-red-400' : 'text-amber-400' }}">
                    {{ $alert->product->quantity }} {{ $alert->product->unit }}
                </span>
                ·
                <span class="text-slate-500">Minimum threshold: {{ $alert->product->low_stock_threshold }} {{ $alert->product->unit }}</span>
            </p>
            <div class="flex items-center gap-3 mt-2">
                <span class="text-xs text-slate-600">{{ $alert->product->sku ?? '' }}</span>
                @if($alert->product->category)
                <span class="text-xs text-slate-600">· {{ $alert->product->category->name }}</span>
                @endif
                <span class="text-xs text-slate-600">· {{ $alert->created_at->diffForHumans() }}</span>
            </div>
        </div>

        {{-- Stock Level Mini Bar --}}
        <div class="hidden sm:block w-24 flex-shrink-0">
            @php
                $pct = $alert->product->low_stock_threshold > 0
                    ? min(100, round(($alert->product->quantity / $alert->product->low_stock_threshold) * 50))
                    : 0;
                $barClr = $alert->product->quantity == 0 ? 'bg-red-500' : 'bg-amber-500';
            @endphp
            <div class="text-center mb-1">
                <span class="text-xs font-bold {{ $alert->product->quantity == 0 ? 'text-red-400' : 'text-amber-400' }}">
                    {{ $alert->product->quantity }}/{{ $alert->product->low_stock_threshold }}
                </span>
            </div>
            <div class="h-1.5 bg-slate-700 rounded-full overflow-hidden">
                <div class="{{ $barClr }} h-full rounded-full" style="width: {{ $pct }}%"></div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col gap-2 flex-shrink-0">
            <a href="{{ route('transactions.create') }}?product_id={{ $alert->product->id }}"
                class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-medium px-4 py-2 rounded-lg transition text-center">
                + Restock
            </a>
            <a href="{{ route('products.show', $alert->product) }}"
                class="border border-slate-700 text-slate-400 hover:text-white text-xs px-4 py-2 rounded-lg transition text-center">
                View Product
            </a>
            @if(!$alert->is_read)
            <form method="POST" action="{{ route('alerts.read', $alert->id) }}">
                @csrf
                <button type="submit" class="w-full text-xs text-slate-500 hover:text-slate-300 py-1.5 transition">
                    Mark Read
                </button>
            </form>
            @else
            <span class="text-xs text-slate-600 text-center py-1.5">Read ✓</span>
            @endif
        </div>
    </div>
    @endforeach
</div>

@if($alerts->hasPages())
<div class="mt-6">{{ $alerts->withQueryString()->links() }}</div>
@endif

@else
{{-- Empty State --}}
<div class="bg-slate-900 border border-slate-800 rounded-2xl py-24 text-center">
    <div class="w-16 h-16 bg-green-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <h3 class="text-white font-semibold text-lg">All Clear!</h3>
    <p class="text-slate-400 text-sm mt-2">No low-stock alerts at this time.</p>
    <p class="text-slate-500 text-xs mt-1">All your products have sufficient stock levels.</p>
    <a href="{{ route('products.index') }}"
        class="inline-flex items-center gap-2 mt-6 border border-slate-700 text-slate-300 hover:text-white px-5 py-2.5 rounded-xl text-sm transition">
        View All Products
    </a>
</div>
@endif
@endsection