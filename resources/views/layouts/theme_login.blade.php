<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="shortcut icon" href="{{ asset('/image/Theme/logo_navbar.png') }}" type="image/x-icon" />
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

<body class="bg-background-light dark:bg-background-dark text-slate-900 h-screen flex flex-col overflow-hidden">
    <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 bg-white dark:bg-slate-900 px-6 py-3 shrink-0 z-20 shadow-sm relative">
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
    </header>

    @yield('content')

</body>

</html>