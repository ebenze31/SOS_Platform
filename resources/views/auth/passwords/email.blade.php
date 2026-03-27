@extends('layouts.theme_login')

@section('content')

<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: "#135bec",
                        hover: "#0f4bbd",
                        light: "#e7effd"
                    }
                },
                fontFamily: {
                    "headline": ["Inter", "sans-serif"],
                    "body": ["Inter", "sans-serif"],
                    "label": ["Inter", "sans-serif"]
                },
                borderRadius: {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
                },
            },
        },
    }
</script>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>
<div class="bg-slate-50 font-body antialiased   flex flex-col" style="min-height: calc(100dvh - 65px);">

    <!-- Main Content Canvas -->
    <main class="flex-grow flex items-center justify-center px-6 py-24">
        <!-- Background Decorative Element (Subtle Glassmorphism Influence) -->
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[5%] w-[40%] h-[40%] bg-blue-50 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute bottom-[5%] right-[0%] w-[30%] h-[30%] bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
        </div>
        <!-- Forgot Password Card -->
        <div class="relative z-10 w-full max-w-lg bg-white rounded-xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 p-10 md:p-14">
            <div class="flex flex-col items-center text-center">
                <!-- Prominent Icon -->
                <div class="w-20 h-20 bg-primary-light text-primary flex items-center justify-center rounded-full mb-8">
                    <span class="material-symbols-outlined !text-4xl" data-icon="lock_reset" style="font-variation-settings: 'wght' 300;">lock_reset</span>
                </div>
                <!-- Title & Description -->
                <h1 class="text-3xl font-bold text-slate-900 mb-3 tracking-tight">ลืมรหัสผ่าน</h1>
                <p class="text-slate-500 text-base mb-10 leading-relaxed">กรุณากรอกอีเมลที่ใช้ลงทะเบียนในระบบ<br class="hidden sm:block" /> เพื่อรับลิงก์สำหรับสร้างรหัสผ่านใหม่</p>
                <!-- Form -->
                <form class="w-full space-y-6">
                    <div class="text-left">
                        <label class="block text-sm font-semibold text-slate-700 mb-2 ml-1" for="email">อีเมล</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400 text-xl" data-icon="mail">mail</span>
                            </div>
                            <input  class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400" id="email" name="email" placeholder="name@example.com" required="" type="email" />
                        </div>
                    </div>
                    
                        @error('email')
                        <div class="w-full text-right" style="margin-top: 0px !important;">
                            <span class="invalid-feedback text-right text-[#db2d2e]" role="alert" >
                                <strong >{{ $message }}</strong>
                            </span>
                        </div>
                        @enderror
                    <div class="pt-4 space-y-4">
                        <!-- Primary Button -->
                        <button class="w-full py-4 px-6 bg-[#135bec] hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98]" type="submit">
                            ส่งลิงก์รีเซ็ตรหัสผ่าน
                        </button>
                        <!-- Cancel Action -->
                        <div class="pt-2">
                            <a href="{{url('login')}}" class="inline-flex items-center text-slate-500 hover:text-slate-800 font-medium transition-colors text-sm group" href="#">
                                <span class="material-symbols-outlined text-lg mr-2 transition-transform group-hover:-translate-x-1" data-icon="arrow_back">arrow_back</span>
                                เข้าสู่ระบบ
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection