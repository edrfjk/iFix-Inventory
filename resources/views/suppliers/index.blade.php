@extends('layouts.app')
@section('title', 'Suppliers — iFix')
@section('page-title', 'Suppliers')
@section('page-subtitle', 'Manage product suppliers')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-slate-600 text-sm">{{ $suppliers->total() }} supplier(s) registered</p>
    <a href="{{ route('suppliers.create') }}"
        class="bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Supplier
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50">
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Supplier</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Contact Person</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Phone / Email</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Address</th>
                <th class="text-center text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Products</th>
                <th class="text-left text-slate-600 text-xs font-semibold uppercase tracking-wider px-6 py-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                <td class="px-6 py-4">
                    <p class="text-slate-900 font-medium text-sm">{{ $supplier->name }}</p>
                    <p class="text-slate-500 text-xs">Added {{ $supplier->created_at->format('M d, Y') }}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-slate-700 text-sm">{{ $supplier->contact_person ?? '—' }}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-slate-700 text-sm">{{ $supplier->phone ?? '—' }}</p>
                    <p class="text-slate-500 text-xs">{{ $supplier->email ?? '' }}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-slate-600 text-sm max-w-[180px] truncate">{{ $supplier->address ?? '—' }}</p>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-teal-50 text-teal-700 border border-teal-200 text-sm font-bold rounded-lg">
                        {{ $supplier->products_count }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('suppliers.edit', $supplier) }}"
                            class="text-teal-700 hover:text-teal-800 text-xs border border-teal-200 hover:border-teal-300 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                            onsubmit="return confirm('Delete {{ $supplier->name }}? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-rose-700 hover:text-rose-800 text-xs border border-rose-200 hover:border-rose-300 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-16">
                    <div class="inline-flex flex-col items-center">
                        <svg class="w-10 h-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <p class="text-slate-600 text-sm">No suppliers yet</p>
                        <a href="{{ route('suppliers.create') }}" class="text-teal-700 hover:text-teal-800 text-sm mt-2 transition font-medium">Add your first supplier →</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($suppliers->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $suppliers->links() }}
    </div>
    @endif
</div>
@endsection