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

        /* Profile dropdown animation */
        #profile-menu {
            transform-origin: top right;
            transition: opacity 0.15s ease, transform 0.15s ease;
        }

        #profile-menu.hidden {
            opacity: 0;
            transform: scale(0.95) translateY(-4px);
            pointer-events: none;
        }

        #profile-menu.open {
            opacity: 1;
            transform: scale(1) translateY(0);
            pointer-events: auto;
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
        <div class="flex-1 max-w-xl px-8 hidden md:block">
            <div class="relative group">
            
            </div>
        </div>
        <div class="flex items-center gap-4">
            <!-- Profile button + dropdown wrapper -->
            <div class="relative" id="profile-wrapper">
                <!-- Profile Avatar Button -->
                <button
                    id="profile-btn"
                    onclick="toggleProfileMenu()"
                    class="bg-center bg-no-repeat bg-cover rounded-full size-9 ring-2 ring-slate-100 cursor-pointer hover:ring-primary/50 transition-all focus:outline-none focus:ring-primary/60"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDdOXcPh06FSp-zWoRlX-ZR94Xk6sFcHjNA7SPIwT4ZCFiOEwhbnP9qqe3z_JqWsj8VziPZxcbnADTEVyDwJL5cOnH9jdTNo9ToZWboOBYA9jkVKjKaSsBrNjU4O8Ke06Zablgt-2uQ_BafhNyqu9OL4h2WjLstaq5sYjo5SwdfJkO8Ud-pClwDioZrD4o2JZRDbmoHBXCz4lJE8VZmQ-ruSA-im_TpfDejOY01i5yzyt05jp1xlQCG1_2w8Hej-9a-uPjxJ89ZqUs7");'
                    aria-haspopup="true"
                    aria-expanded="false">
                </button>

                <!-- Dropdown Menu -->
                <div
                    id="profile-menu"
                    class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 z-50 overflow-hidden"
                    role="menu">

                    <!-- User Info Header -->
                    <div class="px-4 py-4 bg-gradient-to-br from-primary/5 to-blue-50 dark:from-primary/10 dark:to-slate-800 border-b border-slate-100 dark:border-slate-700 flex items-center gap-3">
                        <div class="bg-center bg-no-repeat bg-cover rounded-full size-11 ring-2 ring-white shadow-sm flex-shrink-0"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDdOXcPh06FSp-zWoRlX-ZR94Xk6sFcHjNA7SPIwT4ZCFiOEwhbnP9qqe3z_JqWsj8VziPZxcbnADTEVyDwJL5cOnH9jdTNo9ToZWboOBYA9jkVKjKaSsBrNjU4O8Ke06Zablgt-2uQ_BafhNyqu9OL4h2WjLstaq5sYjo5SwdfJkO8Ud-pClwDioZrD4o2JZRDbmoHBXCz4lJE8VZmQ-ruSA-im_TpfDejOY01i5yzyt05jp1xlQCG1_2w8Hej-9a-uPjxJ89ZqUs7");'>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ auth()->user()->name ?? 'ผู้ใช้งาน' }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                            <span class="inline-flex items-center gap-1 mt-0.5 px-1.5 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-medium">
                                <span class="size-1.5 rounded-full bg-primary inline-block"></span>
                                {{ auth()->user()->role ?? 'user' }}
                            </span>
                        </div>
                    </div>
                        <!-- Menu Items -->
                        <div class="py-1.5">
                            @if(Auth::check())
                                <a href="{{ url('/demo/profile') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group"
                                    role="menuitem">
                                    <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors" style="font-size:18px;">manage_accounts</span>
                                    <span>โปรไฟล์ของฉัน</span>
                                </a>
                                @if(Auth::user()->role == null)
                                    <a href="{{ url('/sos') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group"
                                        role="menuitem">
                                        <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors" style="font-size:18px;">sos</span>
                                        <span>ขอความช่วยเหลือ</span>
                                    </a>
                                    <a href="{{ url('/sos/history') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group"
                                        role="menuitem">
                                        <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors" style="font-size:18px;">history</span>
                                        <span>ประวัติการขอความช่วยเหลือ</span>
                                    </a>
                                @elseif(Auth::user()->role == "officer")
                                    <a href="{{ url('/user_officers/scan') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group"
                                        role="menuitem">
                                        <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors" style="font-size:18px;">how_to_reg</span>
                                        <span>ลงทะเบียนพื้นที่</span>
                                    </a>
                                    <a href="{{ url('/officer/open_status') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group"
                                        role="menuitem">
                                        <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors" style="font-size:18px;">toggle_on</span>
                                        <span>เปิดสถานะ</span>
                                    </a>
                                    <a href="{{ url('/officer/officer_history') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group"
                                        role="menuitem">
                                        <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors" style="font-size:18px;">assignment_turned_in</span>
                                        <span>ประวัติการช่วยเหลือ</span>
                                    </a>
                                @endif
                            @endif
                        </div>

                    <div class="border-t border-slate-100 dark:border-slate-700 py-1.5">
                        <form method="POST" action="{{ route('logout') ?? '#' }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors group"
                                role="menuitem">
                                <span class="material-symbols-outlined text-[18px]" style="font-size:18px;">logout</span>
                                <span>ออกจากระบบ</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @yield('content')

    <script>
        function toggleProfileMenu() {
            const menu = document.getElementById('profile-menu');
            const btn = document.getElementById('profile-btn');
            const isOpen = menu.classList.contains('open');

            if (isOpen) {
                menu.classList.remove('open');
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            } else {
                menu.classList.remove('hidden');
                // Force reflow for animation
                menu.offsetHeight;
                menu.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
            }
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('profile-wrapper');
            const menu = document.getElementById('profile-menu');
            if (!wrapper.contains(e.target)) {
                menu.classList.remove('open');
                menu.classList.add('hidden');
                document.getElementById('profile-btn').setAttribute('aria-expanded', 'false');
            }
        });
    </script>
</body>

</html>