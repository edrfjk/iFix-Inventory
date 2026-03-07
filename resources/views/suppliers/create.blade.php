@extends('layouts.app')
@section('title', 'Add Supplier — iFix')
@section('page-title', 'Add New Supplier')
@section('page-subtitle', 'Register a new supplier')

@section('content')
<div class="max-w-xl">
    <div class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('suppliers.index') }}" class="text-slate-500 hover:text-teal-700 transition">Suppliers</a>
        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">Add New</span>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 rounded-xl px-5 py-4">
        <p class="text-rose-700 text-sm font-medium mb-2">Please fix the following errors:</p>
        @foreach($errors->all() as $error)
        <p class="text-rose-600 text-sm">• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
        <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-slate-700 text-xs font-medium mb-1.5">
                    Supplier Name <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm"
                    placeholder="Company or supplier name"
                >
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-medium mb-1.5">
                    Contact Person <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="contact_person"
                    value="{{ old('contact_person') }}"
                    required
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm"
                    placeholder="Full name"
                >
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-700 text-xs font-medium mb-1.5">
                        Phone <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        required
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm"
                        placeholder="09xx-xxx-xxxx"
                    >
                </div>

                <div>
                    <label class="block text-slate-700 text-xs font-medium mb-1.5">
                        Email <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm"
                        placeholder="email@example.com"
                    >
                </div>
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-medium mb-1.5">
                    Address <span class="text-rose-500">*</span>
                </label>
                <textarea
                    name="address"
                    rows="3"
                    required
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm resize-none"
                    placeholder="Business address"
                >{{ old('address') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white px-8 py-3 rounded-xl font-medium transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Save Supplier
                </button>

                <a href="{{ route('suppliers.index') }}"
                   class="border border-slate-300 text-slate-700 hover:text-teal-700 hover:border-teal-300 hover:bg-teal-50 px-6 py-3 rounded-xl font-medium transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection