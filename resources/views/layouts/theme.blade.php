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
        <div class="flex-1 max-w-xl px-8 hidden md:block">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                </div>
                <input class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-lg leading-5 bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm shadow-sm" placeholder="Search by ID, location, or type..." type="text" />
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                    <kbd class="inline-flex items-center border border-slate-200 rounded px-2 text-[10px] font-sans font-medium text-slate-400">⌘K</kbd>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-2">
                <button class="flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Priority
                </button>
                <button class="flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">map</span>
                    District
                </button>
            </div>
            <div class="h-8 w-px bg-slate-200 mx-1"></div>
            <button class="relative p-2 text-slate-400 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </span>
            </button>
            <div class="bg-center bg-no-repeat bg-cover rounded-full size-9 ring-2 ring-slate-100 cursor-pointer hover:ring-primary/30 transition-all" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDdOXcPh06FSp-zWoRlX-ZR94Xk6sFcHjNA7SPIwT4ZCFiOEwhbnP9qqe3z_JqWsj8VziPZxcbnADTEVyDwJL5cOnH9jdTNo9ToZWboOBYA9jkVKjKaSsBrNjU4O8Ke06Zablgt-2uQ_BafhNyqu9OL4h2WjLstaq5sYjo5SwdfJkO8Ud-pClwDioZrD4o2JZRDbmoHBXCz4lJE8VZmQ-ruSA-im_TpfDejOY01i5yzyt05jp1xlQCG1_2w8Hej-9a-uPjxJ89ZqUs7");'></div>
        </div>
    </header>
            @yield('content')
   

</body>

</html>