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
            keyframes: { fadeIn: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } } }
        }}
    }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 flex items-center justify-center p-4">
 
    <!-- Background decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
    </div>
 
    <div class="w-full max-w-md relative animate-fade-in">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-2xl mb-4">
                <span class="text-white text-3xl font-black tracking-tight">iF</span>
            </div>
            <h1 class="text-white text-3xl font-bold tracking-tight">iFix Inventory</h1>
            <p class="text-blue-400 text-sm mt-1">Telecommunication Parts Trading</p>
        </div>
 
        <!-- Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-8 shadow-2xl">
            <h2 class="text-white text-xl font-semibold mb-6">Sign in to your account</h2>
 
            @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500/40 rounded-xl p-4 mb-5 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <p class="text-red-300 text-sm">{{ $errors->first() }}</p>
            </div>
            @endif
 
            @if (session('success'))
            <div class="bg-green-500/20 border border-green-500/40 rounded-xl p-4 mb-5">
                <p class="text-green-300 text-sm">{{ session('success') }}</p>
            </div>
            @endif
 
            <form method="POST" action="/login" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-blue-200 text-sm font-medium mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-blue-300/40 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                        placeholder="owner@ifix.com">
                </div>
                <div>
                    <label class="block text-blue-200 text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-blue-300/40 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                        placeholder="••••••••">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/30 bg-white/10 text-blue-500 focus:ring-blue-400">
                        <span class="text-blue-200 text-sm">Remember me</span>
                    </label>
                </div>
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-400 hover:to-blue-600 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-blue-500/30 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Sign In
                </button>
            </form>
        </div>
        <p class="text-center text-blue-400/60 text-xs mt-6">iFix Telecommunication Parts Trading &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
