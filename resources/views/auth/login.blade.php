@extends('layouts.theme_login')

@section('content')
<div class="flex min-h-full items-center justify-center p-4 bg-background-light dark:bg-background-dark">
    
    <div class="flex w-full max-w-4xl overflow-hidden rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
        
        <div class="hidden w-1/2 md:block relative">
            <img class="absolute inset-0 h-full w-full object-cover" 
                 src="{{ url('/image/Theme/cover_login.png') }}"
                 alt="Login Visual">
            
            <div class="absolute inset-0 bg-gradient-to-tr from-primary/60 to-transparent mix-blend-multiply"></div>
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            
            <div class="absolute bottom-10 left-10 text-white z-10">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl font-bold tracking-tight drop-shadow-md">{{ env('ORGANIZATION') }}</span>
                </div>
                <p class="text-sm text-slate-100 font-medium opacity-100 drop-shadow-sm">SOS Platform</p>
            </div>
        </div>

        <div class="w-full p-8 md:p-12 md:w-1/2 lg:p-16">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Login</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">เข้าสู่ระบบบัญชีของคุณ</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Username</label>
                    <input id="username" name="username" type="text" required value="{{ old('username') }}"
                        class="block w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all sm:text-sm @error('username') border-red-500 @enderror"
                        placeholder="Enter your username" autofocus>
                    @error('username')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Password</label>
                    <input id="password" name="password" type="password" required
                        class="block w-full px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all sm:text-sm"
                        placeholder="***************">
                </div>

                <button type="submit" 
                    class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98] mt-2">
                    Log in
                </button>

                <div class="relative py-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white dark:bg-slate-900 px-4 text-slate-400 font-medium">หรือ</span>
                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <a href="{{ url('login/line') }}" 
                       class="group flex items-center bg-[#00c300] hover:bg-[#00b300] transition-all rounded-lg overflow-hidden shadow-md active:scale-[0.98]">
                        
                        <div class="bg-white/10 p-1 flex items-center justify-center border-r border-black/5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 48 48">
                                <path fill="#00c300" d="M12.5,42h23c3.59,0,6.5-2.91,6.5-6.5v-23C42,8.91,39.09,6,35.5,6h-23C8.91,6,6,8.91,6,12.5v23C6,39.09,8.91,42,12.5,42z"></path>
                                <path fill="#fff" d="M37.113,22.417c0-5.865-5.88-10.637-13.107-10.637s-13.108,4.772-13.108,10.637c0,5.258,4.663,9.662,10.962,10.495c0.427,0.092,1.008,0.282,1.155,0.646c0.132,0.331,0.086,0.85,0.042,1.185c0,0-0.153,0.925-0.187,1.122c-0.057,0.331-0.263,1.296,1.135,0.707c1.399-0.589,7.548-4.445,10.298-7.611h-0.001C36.203,26.879,37.113,24.764,37.113,22.417z M18.875,25.907h-2.604c-0.379,0-0.687-0.308-0.687-0.688V20.01c0-0.379,0.308-0.687,0.687-0.687c0.379,0,0.687,0.308,0.687,0.687v4.521h1.917c0.379,0,0.687,0.308,0.687,0.687C19.562,25.598,19.254,25.907,18.875,25.907z M21.568,25.219c0,0.379-0.308,0.688-0.687,0.688s-0.687-0.308-0.687-0.688V20.01c0-0.379,0.308-0.687,0.687-0.687s0.687,0.308,0.687,0.687V25.219z M27.838,25.219c0,0.297-0.188,0.559-0.47,0.652c-0.071,0.024-0.145,0.036-0.218,0.036c-0.215,0-0.42-0.103-0.549-0.275l-2.669-3.635v3.222c0,0.379-0.308,0.688-0.688,0.688c-0.379,0-0.688-0.308-0.688-0.688V20.01c0-0.296,0.189-0.558,0.47-0.652c0.071-0.024,0.144-0.035,0.218-0.035c0.214,0,0.42,0.103,0.549,0.275l2.67,3.635V20.01c0-0.379,0.309-0.687,0.688-0.687c0.379,0,0.687,0.308,0.687,0.687V25.219z M32.052,21.927c0.379,0,0.688,0.308,0.688,0.688c0,0.379-0.308,0.687-0.688,0.687h-1.917v1.23h1.917c0.379,0,0.688,0.308,0.688,0.687c0,0.379-0.309,0.688-0.688,0.688h-2.604c-0.378,0-0.687-0.308-0.687-0.688v-2.603c0-0.001,0-0.001,0-0.001c0,0,0-0.001,0-0.001v-2.601c0-0.001,0-0.001,0-0.002c0-0.379,0.308-0.687,0.687-0.687h2.604c0.379,0,0.688,0.308,0.688,0.687s-0.308,0.687-0.688,0.687h-1.917v1.23H32.052z"></path>
                            </svg>
                        </div>

                        <span class="flex-1 text-center text-white font-bold text-sm pr-[44px]">
                            Log in with LINE
                        </span>
                    </a>
                </div>

                <div class="mt-6 text-center space-y-2">
                    <p><a href="{{ route('password.request') }}" class="text-sm font-medium text-primary hover:underline">ลืมรหัสผ่านใช่ไหม?</a></p>
                    <p class="text-sm text-slate-500">ยังไม่มีบัญชี <a href="{{ url('/register') }}" class="text-primary font-bold hover:underline">สร้างบัญชี</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection