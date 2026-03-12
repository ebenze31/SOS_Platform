<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('/image/Theme/logo_navbar.png') }}" type="image/x-icon" />
    
    <style>
        *:not(i){
            font-family: "Kanit", sans-serif;
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
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
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 flex flex-col ">
    <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 bg-white dark:bg-slate-900 px-6 py-3 shrink-0 z-20 shadow-sm w-full fixed ">
      <div class="flex items-center gap-6 text-slate-900 dark:text-white">
            <div class="flex items-center gap-3">
                <img class="h-10 w-auto object-contain" 
                    src="{{ url('/image/Theme/logo_navbar.png') }}">
                <div>
                    <h2 class="text-lg font-bold leading-tight tracking-tight">{{ env('ORGANIZATION') }}</h2>
                    <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider">SOS Platform</p>
                </div>
            </div>
        </div>
        
        @php
            $profileImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuDdOXcPh06FSp-zWoRlX-ZR94Xk6sFcHjNA7SPIwT4ZCFiOEwhbnP9qqe3z_JqWsj8VziPZxcbnADTEVyDwJL5cOnH9jdTNo9ToZWboOBYA9jkVKjKaSsBrNjU4O8Ke06Zablgt-2uQ_BafhNyqu9OL4h2WjLstaq5sYjo5SwdfJkO8Ud-pClwDioZrD4o2JZRDbmoHBXCz4lJE8VZmQ-ruSA-im_TpfDejOY01i5yzyt05jp1xlQCG1_2w8Hej-9a-uPjxJ89ZqUs7';
            
            if (Auth::check()) {
                if (!empty(Auth::user()->photo) && file_exists(public_path(Auth::user()->photo))) {
                    $profileImage = asset(Auth::user()->photo);
                } elseif (!empty(Auth::user()->avatar)) {
                    $profileImage = Auth::user()->avatar;
                }
            }
        @endphp

        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-2">
                
                <a href="{{ url('/dashboard') }}" class="flex items-center justify-center gap-2 w-36 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    Dashboard
                </a>

                <a href="{{ url('/monitor') }}" class="flex items-center justify-center gap-2 w-36 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">monitor_heart</span>
                    Monitor
                </a>

                <div class="relative group z-50">
                    <button class="flex items-center justify-center gap-2 w-36 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 group-hover:bg-slate-50 group-hover:text-slate-900 transition-all">
                        <span class="material-symbols-outlined text-[18px]">layers</span>
                        พื้นที่
                        <span class="material-symbols-outlined text-[16px] transition-transform group-hover:rotate-180">arrow_drop_down</span>
                    </button>
                    
                    <div class="absolute top-full left-0 mt-1 w-48 bg-white border border-slate-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all transform origin-top-left group-hover:scale-100 scale-95">
                        <a href="{{ url('/demo/area_main') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary rounded-t-lg border-b border-slate-50 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">format_list_bulleted</span> พื้นที่ทั้งหมด
                        </a>
                        <a href="{{ url('/area/create_polygon') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary rounded-b-lg transition-colors">
                            <span class="material-symbols-outlined text-[18px]">add_location</span> สร้างพื้นที่ใหม่
                        </a>
                    </div>
                </div>

                <a href="{{ url('/command/requests') }}" class="flex items-center justify-center gap-2 w-36 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                    คำขอลงทะเบียน
                </a>

            </div>

            <div class="h-8 w-px bg-slate-200 mx-1"></div>

            <div class="relative group z-50">
                <button class="relative p-2 text-slate-400 group-hover:text-slate-600 transition-colors focus:outline-none">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    </span>
                </button>

                <div class="absolute top-full right-0 mt-2 w-80 bg-white border border-slate-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all overflow-hidden transform origin-top-right group-hover:scale-100 scale-95">
                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                        <h3 class="font-bold text-sm text-slate-800">การแจ้งเตือนล่าสุด</h3>
                    </div>
                    
                    <div class="max-h-72 overflow-y-auto custom-scrollbar">
                        <a href="#" class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 border-b border-slate-50 transition-colors">
                            <div class="shrink-0 w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500 mt-0.5">
                                <span class="material-symbols-outlined text-[20px]">emergency</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 leading-tight">ขอความช่วยเหลือฉุกเฉิน!</p>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">เกิดอุบัติเหตุรถชน มีผู้ได้รับบาดเจ็บ บริเวณสี่แยกไฟแดง...</p>
                                <p class="text-[10px] font-medium text-primary mt-1.5">2 นาทีที่แล้ว</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 border-b border-slate-50 transition-colors">
                            <div class="shrink-0 w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 mt-0.5">
                                <span class="material-symbols-outlined text-[20px]">person_add</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 leading-tight">คำขอลงทะเบียนใหม่</p>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">คุณสมชาย ใจดี ได้ส่งคำขอสมัครเป็นเจ้าหน้าที่อาสาสมัคร</p>
                                <p class="text-[10px] font-medium text-primary mt-1.5">15 นาทีที่แล้ว</p>
                            </div>
                        </a>
                    </div>
                    
                    <div class="px-4 py-2 border-t border-slate-100 text-center bg-slate-50 hover:bg-slate-100 transition-colors">
                        <a href="#" class="text-xs font-bold text-primary">ดูการแจ้งเตือนทั้งหมด</a>
                    </div>
                </div>
            </div>

            <div class="relative group z-50">
                <div class="bg-center bg-no-repeat bg-cover rounded-full size-9 ring-2 ring-slate-200 cursor-pointer hover:ring-primary transition-all shadow-sm" 
                     style='background-image: url("{{ $profileImage }}");'>
                </div>
                
                <div class="absolute top-full right-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                    <div class="px-4 py-2 border-b border-slate-100">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'ผู้ดูแลระบบ' }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ Auth::user()->role ?? 'ไม่ระบุสถานะ' }}</p>
                    </div>
                    <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-lg transition-colors" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="material-symbols-outlined text-[18px]">logout</span> ออกจากระบบ
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

    </header>
            @yield('content')
   

</body>

</html>