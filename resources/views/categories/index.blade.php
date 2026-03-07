@extends('layouts.app')
@section('title', 'Categories — iFix')
@section('page-title', 'Product Categories')
@section('page-subtitle', 'Organize your parts by category')

@section('content')
<div class="grid grid-cols-5 gap-6">

    {{-- Add Category Form --}}
    <div class="col-span-2 space-y-5">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-slate-900 font-semibold mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Category
            </h3>

            <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-slate-700 text-xs font-medium mb-1.5">
                        Category Name <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full bg-slate-50 border {{ $errors->has('name') ? 'border-rose-400' : 'border-slate-300' }} rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm transition"
                        placeholder="e.g. Smartphone Parts"
                    >
                    @error('name')
                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-slate-700 text-xs font-medium mb-1.5">Description</label>
                    <textarea
                        name="description"
                        rows="2"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm resize-none transition"
                        placeholder="Short description (optional)"
                    >{{ old('description') }}</textarea>
                </div>

                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white py-2.5 rounded-xl text-sm font-medium transition shadow-sm"
                >
                    Add Category
                </button>
            </form>
        </div>

        {{-- Stats Card --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-3">Quick Stats</p>
            <div class="space-y-2">
                <div class="flex justify-between items-center py-2 border-b border-slate-200">
                    <span class="text-slate-600 text-sm">Total Categories</span>
                    <span class="text-slate-900 font-bold">{{ $categories->total() }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-600 text-sm">With Products</span>
                    <span class="text-slate-900 font-bold">{{ $categories->where('products_count', '>', 0)->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories List --}}
    <div class="col-span-3">
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-slate-900 font-semibold">All Categories</h3>
            </div>

            <div class="divide-y divide-slate-200">
                @forelse($categories as $category)
                <div class="px-6 py-4 hover:bg-slate-50 transition">
                    {{-- View Mode --}}
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-slate-900 font-medium text-sm">{{ $category->name }}</p>
                            <p class="text-slate-500 text-xs">{{ $category->description ?: 'No description' }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-slate-900 text-sm font-bold">{{ $category->products_count }}</p>
                                <p class="text-slate-500 text-xs">products</p>
                            </div>

                            <button
                                type="button"
                                onclick="toggleEdit('edit-{{ $category->id }}')"
                                class="text-teal-700 hover:text-teal-800 text-xs border border-teal-200 hover:border-teal-300 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition"
                            >
                                Edit
                            </button>

                            <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                onsubmit="return confirm('Delete \'{{ $category->name }}\' category?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="text-rose-700 hover:text-rose-800 text-xs border border-rose-200 hover:border-rose-300 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition"
                                >
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Inline Edit Form --}}
                    <div id="edit-{{ $category->id }}" class="hidden mt-3 pl-14">
                        <form method="POST" action="{{ route('categories.update', $category) }}" class="flex gap-2 items-end">
                            @csrf
                            @method('PUT')

                            <div class="flex-1">
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ $category->name }}"
                                    required
                                    class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                                    placeholder="Category name"
                                >
                            </div>

                            <div class="flex-1">
                                <input
                                    type="text"
                                    name="description"
                                    value="{{ $category->description }}"
                                    class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                                    placeholder="Description (optional)"
                                >
                            </div>

                            <button
                                type="submit"
                                class="bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0"
                            >
                                Save
                            </button>

                            <button
                                type="button"
                                onclick="toggleEdit('edit-{{ $category->id }}')"
                                class="border border-slate-300 text-slate-700 hover:text-teal-700 hover:border-teal-300 hover:bg-teal-50 px-3 py-2 rounded-lg text-sm transition flex-shrink-0"
                            >
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-16">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <p class="text-slate-700 text-sm font-medium">No categories yet</p>
                    <p class="text-slate-500 text-xs mt-1">Add your first category using the form</p>
                </div>
                @endforelse
            </div>

            @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleEdit(id) {
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
}
</script>
@endsection