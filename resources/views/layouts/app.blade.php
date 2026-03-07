<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'iFix Inventory')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: {} } }</script>
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 99px; }

        .pagination { display: flex; gap: 4px; }
        [aria-current="page"] span {
            background: #0f766e !important;
            color: white !important;
            border-color: #0f766e !important;
            border-radius: 8px;
        }
        .pagination span, .pagination a {
            background: #ffffff;
            color: #475569;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 13px;
            transition: all 0.15s;
        }
        .pagination a:hover {
            background: #f0fdfa;
            color: #0f766e;
            border-color: #99f6e4;
        }
        .pagination [disabled] span {
            opacity: 0.4;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex">

<!-- Sidebar -->
<aside class="fixed left-0 top-0 h-full w-64 bg-gradient-to-b from-slate-900 via-slate-800 to-teal-900 text-white border-r border-slate-700 flex flex-col z-30 shadow-xl">
    <!-- Logo -->
    <div class="p-6 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/10 backdrop-blur flex items-center justify-center overflow-hidden shadow-lg">
                <img src="{{ asset('images/ifix-logo.png') }}" alt="iFix Logo" class="w-10 h-10 rounded-full object-cover">
            </div>
            <div>
                <p class="text-white font-bold text-sm leading-tight">iFix Inventory</p>
                <p class="text-teal-200 text-xs">Parts Trading</p>
            </div>
        </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider px-4 mb-2">Main Menu</p>

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150
            {{ request()->routeIs('dashboard') ? 'bg-white text-teal-700 shadow-lg' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150
            {{ request()->routeIs('products.*') ? 'bg-white text-teal-700 shadow-lg' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            Products
        </a>

        <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150
            {{ request()->routeIs('transactions.*') ? 'bg-white text-teal-700 shadow-lg' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
            </svg>
            Transactions
        </a>

        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider px-4 mt-4 mb-2">Management</p>

        <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150
            {{ request()->routeIs('suppliers.*') ? 'bg-white text-teal-700 shadow-lg' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Suppliers
        </a>

        <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150
            {{ request()->routeIs('categories.*') ? 'bg-white text-teal-700 shadow-lg' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Categories
        </a>

        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider px-4 mt-4 mb-2">Reports</p>

        <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150
            {{ request()->routeIs('reports.*') ? 'bg-white text-teal-700 shadow-lg' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Reports
        </a>

        <a href="{{ route('alerts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150
            {{ request()->routeIs('alerts.*') ? 'bg-white text-teal-700 shadow-lg' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            Low-Stock Alerts
            @php $unread = \App\Models\LowStockAlert::where('is_read', false)->count(); @endphp
            @if($unread > 0)
                <span class="ml-auto bg-rose-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unread }}</span>
            @endif
        </a>
    </nav>

    <!-- User -->
    <div class="p-4 border-t border-white/10">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-md">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-slate-300 text-xs capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-slate-200 hover:text-red-200 hover:bg-red-500/10 rounded-lg transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<!-- Main Content -->
<main class="ml-64 flex-1 min-h-screen">
    <!-- Topbar -->
    <header class="sticky top-0 z-20 bg-white/90 backdrop-blur-sm border-b border-slate-200 px-8 py-4 flex items-center justify-between">
        <div>
            <h1 class="text-slate-800 font-semibold text-lg">@yield('page-title', 'Dashboard')</h1>
            <p class="text-slate-500 text-xs">@yield('page-subtitle', 'Welcome back, ') {{ auth()->user()->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-slate-500 text-sm">{{ now()->format('D, M d Y') }}</span>
        </div>
    </header>

    <!-- Page Content -->
    <div class="p-8">
        @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-4 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error') || $errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 rounded-xl px-5 py-4 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-rose-700 text-sm">{{ session('error') ?? $errors->first() }}</p>
        </div>
        @endif

        @yield('content')
    </div>
</main>
</body>
</html>