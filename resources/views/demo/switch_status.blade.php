@extends('layouts.theme')

@section('content')
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#F7F8F9",
                        "background-dark": "#13191f",
                        "safety-green": "#2EB854",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "4px",
                        "sm": "4px",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                },
            },
        }
    </script>

<div class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display antialiased">
    <div class="relative flex h-screen w-full flex-col overflow-hidden">
        <!-- Map Background -->
        <div class="absolute inset-0 z-0 bg-cover bg-center bg-black" >
           
        </div>
       
        <!-- Centered Floating Status Card -->
        <main class="relative z-20 flex flex-1 flex-col items-center justify-end px-4 pb-8 ">
            <div class="w-full max-w-md rounded-sm bg-white p-6 shadow-[0px_4px_12px_rgba(0,0,0,0.08)]">
                <!-- Responder Info -->
                <div class="mb-6 flex items-center gap-4 border-b border-slate-100 pb-4">
                    <div class="h-14 w-14 overflow-hidden rounded-full border-2 border-slate-100 bg-slate-200" data-alt="Professional portrait of responder">
                        <img alt="Responder Portrait" class="h-full w-full object-cover" src="" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 leading-tight">Officer Somchai</h3>
                        <p class="text-sm font-normal text-slate-500">Unit 402 • Central District</p>
                    </div>
                </div>
                <!-- Status Toggle Section -->
                <div class="flex flex-col items-center gap-4">
                    <div class="flex w-full items-center justify-between rounded-sm border border-slate-200 bg-slate-50 px-5 py-4">
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-bold uppercase tracking-wider text-slate-500">สถานะปัจจุบัน</span>
                            <div class="flex items-center gap-2">
                                <!-- Status Indicator Dot -->
                               <div class="h-2.5 w-2.5 rounded-full bg-safety-green" id="status-dot"></div>
<span class="text-lg font-bold text-slate-900" id="status-text">พร้อมปฏิบัติงาน</span>
                            </div>
                        </div>
                        <!-- Large Toggle Switch -->
                        <label id="toggle-label" class="relative flex h-10 w-16 cursor-pointer items-center rounded-full border-none bg-primary p-1 shadow-inner transition-colors focus-within:ring-2 focus-within:ring-primary/50 focus-within:ring-offset-2">
                           <input checked="" class="peer sr-only" type="checkbox" id="status-toggle" />
                            <div class="h-8 w-8 rounded-full bg-white shadow-sm transition-transform duration-200 ease-in-out peer-checked:translate-x-6"></div>
                        </label>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</html>
<script>
    const toggle = document.getElementById('status-toggle');
    const toggleLabel = document.getElementById('toggle-label');
    const statusDot = document.getElementById('status-dot');
    const statusText = document.getElementById('status-text');

    toggle.addEventListener('change', function () {
        if (this.checked) {
            toggleLabel.classList.remove('bg-slate-400');
            toggleLabel.classList.add('bg-primary');
            statusDot.classList.remove('bg-red-500');
            statusDot.classList.add('bg-safety-green');
            statusText.textContent = 'พร้อมปฏิบัติงาน';
        } else {
            toggleLabel.classList.remove('bg-primary');
            toggleLabel.classList.add('bg-slate-400');
            statusDot.classList.remove('bg-safety-green');
            statusDot.classList.add('bg-red-500');
            statusText.textContent = 'ไม่พร้อมปฏิบัติงาน';
        }
    });
</script>
@endsection