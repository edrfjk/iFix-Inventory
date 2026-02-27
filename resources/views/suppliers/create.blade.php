@extends('layouts.app')
@section('title', 'Add Supplier — iFix')
@section('page-title', 'Add New Supplier')
@section('page-subtitle', 'Register a new supplier')

@section('content')
<div class="max-w-xl">
    <div class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('suppliers.index') }}" class="text-slate-500 hover:text-slate-300 transition">Suppliers</a>
        <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-white">Add New</span>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-xl px-5 py-4">
        @foreach($errors->all() as $error)
        <p class="text-red-300 text-sm">• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8">
        <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-slate-400 text-xs font-medium mb-1.5">Supplier Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                    placeholder="Company or supplier name">
            </div>
            <div>
                <label class="block text-slate-400 text-xs font-medium mb-1.5">Contact Person</label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                    class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                    placeholder="Full name">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        placeholder="09xx-xxx-xxxx">
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        placeholder="email@example.com">
                </div>
            </div>
            <div>
                <label class="block text-slate-400 text-xs font-medium mb-1.5">Address</label>
                <textarea name="address" rows="3"
                    class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"
                    placeholder="Business address">{{ old('address') }}</textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-400 hover:to-blue-600 text-white px-8 py-3 rounded-xl font-medium transition shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Save Supplier
                </button>
                <a href="{{ route('suppliers.index') }}" class="border border-slate-700 text-slate-400 hover:text-white px-6 py-3 rounded-xl font-medium transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection