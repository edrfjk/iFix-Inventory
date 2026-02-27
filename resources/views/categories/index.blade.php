@extends('layouts.app')
@section('title', 'Categories — iFix')
@section('page-title', 'Product Categories')
@section('page-subtitle', 'Organize your parts by category')

@section('content')
<div class="grid grid-cols-5 gap-6">

    {{-- Add Category Form --}}
    <div class="col-span-2 space-y-5">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h3 class="text-white font-semibold mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Category
            </h3>
            <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5">Category Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-slate-800 border {{ $errors->has('name') ? 'border-red-500' : 'border-slate-700' }} rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition"
                        placeholder="e.g. Smartphone Parts">
                    @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5">Description</label>
                    <textarea name="description" rows="2"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none transition"
                        placeholder="Short description (optional)">{{ old('description') }}</textarea>
                </div>
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-400 hover:to-blue-600 text-white py-2.5 rounded-xl text-sm font-medium transition shadow-lg">
                    Add Category
                </button>
            </form>
        </div>

        {{-- Stats Card --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-3">Quick Stats</p>
            <div class="space-y-2">
                <div class="flex justify-between items-center py-2 border-b border-slate-800">
                    <span class="text-slate-400 text-sm">Total Categories</span>
                    <span class="text-white font-bold">{{ $categories->total() }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-400 text-sm">With Products</span>
                    <span class="text-white font-bold">{{ $categories->where('products_count', '>', 0)->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories List --}}
    <div class="col-span-3">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800">
                <h3 class="text-white font-semibold">All Categories</h3>
            </div>
            <div class="divide-y divide-slate-800/60">
                @forelse($categories as $category)
                <div class="px-6 py-4 hover:bg-slate-800/20 transition" x-data="{ editing: false }">
                    {{-- View Mode --}}
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium text-sm">{{ $category->name }}</p>
                            <p class="text-slate-500 text-xs">{{ $category->description ?: 'No description' }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-white text-sm font-bold">{{ $category->products_count }}</p>
                                <p class="text-slate-500 text-xs">products</p>
                            </div>
                            {{-- Edit button (toggles inline form) --}}
                            <button type="button"
                                onclick="toggleEdit('edit-{{ $category->id }}')"
                                class="text-blue-400 hover:text-blue-300 text-xs border border-blue-500/30 hover:border-blue-400/60 px-3 py-1.5 rounded-lg transition">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                onsubmit="return confirm('Delete \'{{ $category->name }}\' category?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-red-400 hover:text-red-300 text-xs border border-red-500/30 hover:border-red-400/60 px-3 py-1.5 rounded-lg transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Inline Edit Form --}}
                    <div id="edit-{{ $category->id }}" class="hidden mt-3 pl-14">
                        <form method="POST" action="{{ route('categories.update', $category) }}" class="flex gap-2 items-end">
                            @csrf @method('PUT')
                            <div class="flex-1">
                                <input type="text" name="name" value="{{ $category->name }}" required
                                    class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Category name">
                            </div>
                            <div class="flex-1">
                                <input type="text" name="description" value="{{ $category->description }}"
                                    class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Description (optional)">
                            </div>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex-shrink-0">
                                Save
                            </button>
                            <button type="button"
                                onclick="toggleEdit('edit-{{ $category->id }}')"
                                class="border border-slate-600 text-slate-400 hover:text-white px-3 py-2 rounded-lg text-sm transition flex-shrink-0">
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-16">
                    <svg class="w-10 h-10 text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <p class="text-slate-500 text-sm">No categories yet</p>
                    <p class="text-slate-600 text-xs mt-1">Add your first category using the form</p>
                </div>
                @endforelse
            </div>
            @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">{{ $categories->links() }}</div>
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