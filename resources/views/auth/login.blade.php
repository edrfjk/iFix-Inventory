<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iFix Inventory — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: { extend: {
            animation: { 'fade-in': 'fadeIn 0.5s ease-out' },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' }
                }
            }
        }}
    }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-teal-950 flex items-center justify-center p-4">

    <!-- Background decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-teal-400 rounded-full mix-blend-screen blur-3xl opacity-10"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-400 rounded-full mix-blend-screen blur-3xl opacity-10"></div>
    </div>

    <div class="w-full max-w-md relative animate-fade-in">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-white/10 border border-white/10 backdrop-blur shadow-2xl mb-4 overflow-hidden">
                <img src="{{ asset('images/ifix-logo.png') }}" alt="iFix Logo" class="w-20 h-20 rounded-full object-cover">
            </div>
            <h1 class="text-white text-3xl font-bold tracking-tight">iFix Inventory</h1>
            <p class="text-teal-200 text-sm mt-1">Telecommunication Parts Trading</p>
        </div>

        <!-- Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-3xl p-8 shadow-2xl">
            <h2 class="text-white text-xl font-semibold mb-6">Sign in to your account</h2>

            @if ($errors->any())
            <div class="bg-rose-500/15 border border-rose-400/30 rounded-xl p-4 mb-5 flex items-start gap-3">
                <svg class="w-5 h-5 text-rose-300 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-rose-200 text-sm">{{ $errors->first() }}</p>
            </div>
            @endif

            @if (session('success'))
            <div class="bg-emerald-500/15 border border-emerald-400/30 rounded-xl p-4 mb-5">
                <p class="text-emerald-200 text-sm">{{ session('success') }}</p>
            </div>
            @endif

            <form method="POST" action="/login" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-slate-200 text-sm font-medium mb-2">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full bg-white/10 border border-white/15 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent transition"
                        placeholder="owner@ifix.com"
                    >
                </div>

                <div>
                    <label class="block text-slate-200 text-sm font-medium mb-2">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full bg-white/10 border border-white/15 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent transition"
                        placeholder="••••••••"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-teal-500/30 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-slate-400 text-xs mt-6">
            iFix Telecommunication Parts Trading &copy; {{ date('Y') }}
        </p>
    </div>
</body>
</html>