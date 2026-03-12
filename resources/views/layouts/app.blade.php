<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'iFix Inventory')</title>
    <link rel="icon" href="{{ asset('images/ifix-logo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/ifix-logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = { theme: { extend: {} } }
    </script>
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        code, .mono { font-family: 'DM Mono', monospace; }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 99px; }

        /* Pagination */
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

        /* Sidebar fixed */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 256px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Sidebar texture overlay */
        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 40% at 50% -10%, rgba(20, 184, 166, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 30% at 80% 110%, rgba(16, 185, 129, 0.07) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .sidebar > * { position: relative; z-index: 1; }

        /* Nav item active glow */
        .nav-active {
            background: linear-gradient(135deg, #0f766e, #0d9488);
            box-shadow: 0 2px 12px rgba(15, 118, 110, 0.4);
        }

        /* Nav item hover */
        .nav-item:hover:not(.nav-active) {
            background: rgba(255,255,255,0.05);
        }

        /* Section divider */
        .nav-section-label {
            position: relative;
        }
        .nav-section-label::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 60%;
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(100, 116, 139, 0.3));
        }

        /* Logo shimmer */
        @keyframes shimmer {
            0% { opacity: 0.7; }
            50% { opacity: 1; }
            100% { opacity: 0.7; }
        }
        .logo-ring {
            animation: shimmer 3s ease-in-out infinite;
        }

        /* User card */
        .user-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.04), rgba(255,255,255,0.01));
            border: 1px solid rgba(255,255,255,0.07);
            backdrop-filter: blur(8px);
        }

        /* Badge pulse */
        @keyframes pulse-badge {
            0%, 100% { box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.5); }
            50% { box-shadow: 0 0 0 4px rgba(244, 63, 94, 0); }
        }
        .badge-pulse { animation: pulse-badge 2s ease-in-out infinite; }

        /* Main content offset for fixed sidebar */
        .main-content {
            margin-left: 256px;
        }

        @media (max-width: 1023px) {
            .main-content { margin-left: 0; }
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
        }

        /* Topbar glass */
        .topbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Page enter animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .page-content { animation: fadeUp 0.3s ease-out; }

        /* Alert animations */
        .alert-enter {
            animation: fadeUp 0.25s ease-out;
        }

        /* Scrollbar for sidebar */
        .sidebar::-webkit-scrollbar { width: 3px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.2); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 z-40 lg:hidden backdrop-blur-sm"
         @click="sidebarOpen = false"
         style="display: none;"></div>

    <!-- Sidebar -->
    <aside
        class="sidebar bg-slate-900 text-white z-50 flex flex-col"
        :class="sidebarOpen ? 'open' : ''"
    >
        <!-- Logo Area -->
        <div class="px-5 py-5 border-b border-slate-800/80">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <!-- Logo with ring effect -->
                    <div class="relative flex-shrink-0">
                        <div class="logo-ring absolute inset-0 rounded-xl bg-teal-500/20 blur-sm"></div>
                        <div class="relative w-10 h-10 rounded-xl bg-gradient-to-br from-slate-700 to-slate-800 border border-slate-600/50 flex items-center justify-center overflow-hidden shadow-lg">
                            <img src="{{ asset('images/ifix-logo.png') }}" alt="iFix Logo" class="w-8 h-8 rounded-lg object-cover">
                        </div>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm tracking-tight">iFix Inventory</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400 inline-block"></span>
                            <p class="text-slate-400 text-xs">Parts Trading</p>
                        </div>
                    </div>
                </div>

                <button @click="sidebarOpen = false"
                        class="lg:hidden w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">

            <!-- Main Menu -->
            <div>
                <p class="nav-section-label text-slate-500 text-[10px] font-semibold uppercase tracking-[0.12em] px-3 mb-2">Main Menu</p>
                <div class="space-y-0.5">
                    <a href="{{ route('dashboard') }}"
                       class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('dashboard') ? 'nav-active text-white' : 'text-slate-400 hover:text-white' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            {{ request()->routeIs('dashboard') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </span>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('products.*') ? 'nav-active text-white' : 'text-slate-400 hover:text-white' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            {{ request()->routeIs('products.*') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </span>
                        <span>Products</span>
                    </a>

                    <a href="{{ route('transactions.index') }}"
                       class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('transactions.*') ? 'nav-active text-white' : 'text-slate-400 hover:text-white' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            {{ request()->routeIs('transactions.*') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </span>
                        <span>Transactions</span>
                    </a>
                </div>
            </div>

            <!-- Management -->
            <div>
                <p class="nav-section-label text-slate-500 text-[10px] font-semibold uppercase tracking-[0.12em] px-3 mb-2">Management</p>
                <div class="space-y-0.5">
                    <a href="{{ route('suppliers.index') }}"
                       class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('suppliers.*') ? 'nav-active text-white' : 'text-slate-400 hover:text-white' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            {{ request()->routeIs('suppliers.*') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </span>
                        <span>Suppliers</span>
                    </a>

                    <a href="{{ route('categories.index') }}"
                       class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('categories.*') ? 'nav-active text-white' : 'text-slate-400 hover:text-white' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            {{ request()->routeIs('categories.*') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </span>
                        <span>Categories</span>
                    </a>
                </div>
            </div>

            <!-- Reports -->
            <div>
                <p class="nav-section-label text-slate-500 text-[10px] font-semibold uppercase tracking-[0.12em] px-3 mb-2">Reports</p>
                <div class="space-y-0.5">
                    <a href="{{ route('reports.index') }}"
                       class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('reports.*') ? 'nav-active text-white' : 'text-slate-400 hover:text-white' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            {{ request()->routeIs('reports.*') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </span>
                        <span>Reports</span>
                    </a>

                    <a href="{{ route('alerts.index') }}"
                       class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                       {{ request()->routeIs('alerts.*') ? 'nav-active text-white' : 'text-slate-400 hover:text-white' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                            {{ request()->routeIs('alerts.*') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </span>
                        <span>Low-Stock Alerts</span>
                        @php $unread = \App\Models\LowStockAlert::where('is_read', false)->count(); @endphp
                        @if($unread > 0)
                            <span class="ml-auto badge-pulse bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $unread }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Footer -->
        <div class="p-3 border-t border-slate-800/80">
            <div class="user-card rounded-2xl p-3">
                <!-- User Info Row -->
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="relative flex-shrink-0">
                        <div class="w-9 h-9 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-teal-400 rounded-full border-2 border-slate-900"></span>
                    </div>
                    <div class="overflow-hidden flex-1 min-w-0">
                        <p class="text-white text-sm font-semibold truncate leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-slate-400 text-xs capitalize mt-0.5">{{ auth()->user()->role }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-1.5">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center justify-center gap-1.5 px-2.5 py-2 text-slate-400 hover:text-white bg-slate-800/60 hover:bg-slate-700/80 rounded-xl transition-all duration-150 text-xs font-medium">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Are you sure you want to logout?')">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-1.5 px-2.5 py-2 text-slate-400 hover:text-rose-300 bg-slate-800/60 hover:bg-rose-500/10 rounded-xl transition-all duration-150 text-xs font-medium">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content min-h-screen flex flex-col">
        <!-- Topbar -->
        <header class="sticky top-0 z-30 topbar border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true"
                        class="lg:hidden w-9 h-9 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:text-slate-900 hover:border-slate-300 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div>
                    <h1 class="text-slate-800 font-semibold text-base leading-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-slate-400 text-xs leading-tight">@yield('page-subtitle', 'Welcome back') — {{ auth()->user()->name }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Date pill -->
                <div class="hidden sm:flex items-center gap-2 bg-slate-100 rounded-xl px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-slate-500 text-xs font-medium">{{ now()->format('D, M d Y') }}</span>
                </div>

                @php $unreadTop = \App\Models\LowStockAlert::where('is_read', false)->count(); @endphp
                @if($unreadTop > 0)
                <a href="{{ route('alerts.index') }}"
                   class="relative w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 border border-rose-100 text-rose-500 hover:bg-rose-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $unreadTop }}</span>
                </a>
                @endif
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 p-4 sm:p-6 lg:p-8 page-content">
            @if(session('success'))
            <div class="alert-enter mb-5 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-start gap-3 shadow-sm">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-emerald-800 text-sm font-medium">Success</p>
                    <p class="text-emerald-600 text-xs mt-0.5">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600 transition flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif

            @if(session('error') || $errors->any())
            <div class="alert-enter mb-5 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3 flex items-start gap-3 shadow-sm">
                <div class="w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-rose-800 text-sm font-medium">Error</p>
                    <p class="text-rose-600 text-xs mt-0.5">{{ session('error') ?? $errors->first() }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-rose-400 hover:text-rose-600 transition flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        // Sync Alpine sidebar state with CSS class for mobile
        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => {
                const sidebar = document.querySelector('aside');
                // handled by Alpine :class binding
            });
        });
    </script>
</body>
</html>