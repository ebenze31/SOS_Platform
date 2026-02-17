@extends('layouts.theme_login')

@section('content')
<div class="flex min-h-full items-center justify-center p-4 bg-background-light dark:bg-background-dark">
    
    <div class="flex w-full max-w-4xl overflow-hidden rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
        <div class="w-full p-8 md:p-12 md:w-1/2 lg:p-16">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Register</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">สร้างบัญชีเพื่อเริ่มต้นใช้งาน</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Full Name</label>
                    <input id="name" name="name" type="text" required value="{{ old('name') }}" autofocus
                        class="block w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all sm:text-sm @error('name') border-red-500 @enderror"
                        placeholder="">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Username</label>
                    <input id="username" name="username" type="text" required value="{{ old('username') }}"
                        class="block w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all sm:text-sm @error('username') border-red-500 @enderror"
                        placeholder="">
                    @error('username')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Email</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                        class="block w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all sm:text-sm @error('email') border-red-500 @enderror"
                        placeholder="">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Password</label>
                    <input id="password" name="password" type="password" required
                        class="block w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all sm:text-sm @error('password') border-red-500 @enderror"
                        placeholder="">
                    @error('password')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Confirm Password</label>
                    <input id="password-confirm" name="password_confirmation" type="password" required
                        class="block w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all sm:text-sm"
                        placeholder="">
                </div>

                <button type="submit" 
                    class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98] mt-2">
                    Create Account
                </button>

                <div class="relative py-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white dark:bg-slate-900 px-4 text-slate-400 font-medium">มีบัญชีอยู่แล้วใช่ไหม</span>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-slate-500">
                        <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">
                            เข้าสู่ระบบ
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <div class="hidden w-1/2 md:block relative">
            <img class="absolute inset-0 h-full w-full object-cover" 
                 src="{{ url('/image/Theme/cover_login.png') }}"
                 alt="Register Visual">
            
            <div class="absolute inset-0 bg-gradient-to-tr from-primary/60 to-transparent mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            
            <div class="absolute bottom-10 left-10 text-white z-10">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl font-bold tracking-tight drop-shadow-md">{{ env('ORGANIZATION') }}</span>
                </div>
                <p class="text-sm text-slate-100 font-medium opacity-100 drop-shadow-sm">SOS Platform</p>
            </div>
        </div>
    </div>
</div>
@endsection