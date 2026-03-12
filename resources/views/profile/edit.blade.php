@extends('layouts.app')
@section('title', 'My Profile — iFix')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Manage your account details')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Profile Info --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
        <h3 class="text-slate-800 text-lg font-semibold mb-6">Profile Information</h3>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-slate-700 text-sm font-medium mb-2">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required>
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-medium mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required>
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-medium mb-2">Role</label>
                <input type="text" value="{{ ucfirst($user->role) }}"
                    class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-slate-500"
                    disabled>
            </div>

            <button type="submit"
                class="bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white px-6 py-3 rounded-xl font-medium transition">
                Save Changes
            </button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
        <h3 class="text-slate-800 text-lg font-semibold mb-6">Change Password</h3>

        <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-slate-700 text-sm font-medium mb-2">Current Password</label>
                <input type="password" name="current_password"
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required>
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-medium mb-2">New Password</label>
                <input type="password" name="password"
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required>
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-medium mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required>
            </div>

            <button type="submit"
                class="bg-slate-800 hover:bg-slate-700 text-white px-6 py-3 rounded-xl font-medium transition">
                Update Password
            </button>
        </form>
    </div>

</div>
@endsection