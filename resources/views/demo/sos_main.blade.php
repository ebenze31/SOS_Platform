@extends('layouts.theme')

@section('content')

<body class="bg-background-light dark:bg-background-dark font-display text-[#0d141b] dark:text-white transition-colors duration-200 ">
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden p-4 md:p-8 h-full">
        <div class="mx-auto w-full max-w-[1200px] mt-20">
            <div>
                <div class="md:col-span-8 flex flex-col gap-6">
                    <section class="h-full rounded-xl bg-white p-1 shadow-sm ring-1 ring-slate-900/5 dark:bg-[#1a2632] dark:ring-white/10">
                        <div class="flex items-center justify-between px-5 py-4">
                            <h3 class="text-sm font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Location</h3>
                            <button class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors">
                                Edit Location
                            </button>
                        </div>
                        <div class="relative overflow-hidden rounded-lg mx-1 mb-1">
                            <div class="aspect-[16/9] md:aspect-auto md:h-[400px] w-full bg-slate-200 bg-cover bg-center" data-alt="Map view showing streets and a location pin" data-location="Tokyo, Japan" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDXxYNmAO4CTHmN0Rzcy7hH-Y2t8QgMxxUf5PWrwgh3QJBAjl5cOGXsNgEluXtWq7_TjT0f9Wxp4jPUlzjpaHDN5KBxiKEyVP_2-4372vXLMqrSkHrDxYgnjgKzmSu6nlkPYSQoKjGyl5jOcDEjfi8k8aMePArH9-Br5-txfCQavPR2OuTZvWG4XNbK5jBiOBVm23aL8H5hd8aAHMWVmsACtm_dpLuMgjKf0ygi0HXpwr5uCSI_NMCJKpfxdDkqg2Dnac4orCse2t-p");'>
                            </div>
                        </div>

                    </section>
                </div>
                <div class="block md:flex mt-4">

                    <div class="block w-full px-2 order-last mb-5">

                        <div class="relative  bg-white/95 px-5 py-4 backdrop-blur-sm rounded-xl dark:bg-[#1a2632]/95 border border-slate-100 dark:border-slate-700 shadow-lg flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined">location_on</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold leading-none">Detected Location</p>
                                    <p class="text-sm text-slate-500 mt-1">123, Shibuya Crossing, Tokyo</p>

                                </div>
                            </div>
                        </div>
                        <div class="flex w-full md:w-auto flex-col sm:flex-row items-center gap-3 mt-3">

                            <button onclick="openModal()" class="flex w-full min-w-[200px] cursor-pointer items-center justify-center gap-2 rounded-lg bg-primary px-8 py-3.5 text-base font-bold text-white shadow-lg transition-all hover:bg-blue-700 active:scale-[0.98] order-1 sm:order-2">
                                <span>Confirm and Send</span>
                                <span class="material-symbols-outlined text-lg">send</span>
                            </button>
                        </div>
                    </div>
                    <div class="md:col-span-4 flex flex-col gap-6 w-full px-2 order-first">
                        <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 dark:bg-[#1a2632] dark:ring-white/10">
                            <div class="mb-4 flex items-center justify-between">
                                <label class="text-sm font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">ติดต่อหน่วยงานภายใน</label>
                            </div>

                            <!-- Item 1 -->
                            <div class="group bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-transparent hover:border-primary/20 transition-all flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">ตำรวจ</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 flex items-center">
                                            191
                                        </p>
                                    </div>
                                </div>
                                <button class="flex-shrink-0 h-10 w-10 rounded-full bg-primary/10 hover:bg-primary text-primary hover:text-white flex items-center justify-center transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[20px]">call</span>
                                </button>
                            </div>
                            <!-- Item 2 -->
                            <div class="group bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-transparent hover:border-primary/20 transition-all flex items-center justify-between">
                                <div class="flex items-center space-x-4">

                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">ดับเพลิง</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 flex items-center">
                                            191
                                        </p>
                                    </div>
                                </div>
                                <button class="flex-shrink-0 h-10 w-10 rounded-full bg-primary/10 hover:bg-primary text-primary hover:text-white flex items-center justify-center transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[20px]">call</span>
                                </button>
                            </div>

                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0d59a5",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem"},
                },
            },
        }
    </script>
    <!-- Modal -->
    <div id="confirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all duration-300">
        <div class="relative w-full max-w-md h-screen overflow-auto bg-white dark:bg-[#1a2632] rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">

          <header class="px-8 py-6 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between bg-white dark:bg-[#1a2632]">
<div>
<h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Report Incident</h1>
<p class="text-xs text-slate-400 mt-1 uppercase tracking-wide">Emergency Assistance Request</p>
</div>
<div class="h-10 w-10 rounded-full bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 flex items-center justify-center shadow-sm">
<span class="material-symbols-outlined text-xl">assignment_late</span>
</div>
</header>
            <form class="flex flex-col bg-white dark:bg-[#1a2632]" onsubmit="event.preventDefault();">
                <section class="px-8 py-6 border-b border-slate-100 dark:border-slate-700/50">
                    <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-4">Reporter Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-200" for="reporter-name">Reporter Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-[18px]">person</span>
                                </div>
                                <input class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 pr-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white dark:placeholder:text-slate-500 transition-shadow" id="reporter-name" placeholder="Full Name" type="text" />
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-200" for="phone-number">Phone Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-[18px]">call</span>
                                </div>
                                <input class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 pr-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white dark:placeholder:text-slate-500 transition-shadow" id="phone-number" placeholder="+1 (555) 000-0000" type="tel" />
                            </div>
                        </div>
                    </div>
                </section>
                <section class="px-8 py-6 border-b border-slate-100 dark:border-slate-700/50">
                    <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-4">Assistance Topic</h2>
                    <div class="space-y-1.5">
                        <label class="sr-only" for="topic">Topic</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">category</span>
                            </div>
                            <select class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 pr-10 py-2.5 text-sm text-slate-900 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white transition-shadow appearance-none" id="topic">
                                <option disabled="" selected="" value="">Select Incident Type...</option>
                                <option value="medical">Medical Emergency</option>
                                <option value="fire">Fire / Smoke</option>
                                <option value="accident">Traffic Accident</option>
                                <option value="security">Security Threat</option>
                                <option value="other">Other Assistance</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">expand_more</span>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="px-8 py-6 border-b border-slate-100 dark:border-slate-700/50">
                    <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-4">Detailed Description</h2>
                    <div class="space-y-1.5">
                        <textarea class="w-full rounded-lg border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white dark:placeholder:text-slate-500 transition-shadow resize-none" id="description" placeholder="Please describe the incident in detail, including location markers and current status..." rows="4"></textarea>
                    </div>
                </section>
                <section class="px-8 py-6">
                    <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-4">Evidence &amp; Photos</h2>
                    <div class="group relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-300 rounded-lg bg-slate-50 hover:bg-slate-100 hover:border-primary/50 dark:bg-slate-800/30 dark:border-slate-700 dark:hover:bg-slate-800 dark:hover:border-primary/50 transition-all cursor-pointer">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <div class="h-10 w-10 mb-3 rounded-full bg-white shadow-sm flex items-center justify-center text-primary dark:bg-slate-700 dark:text-primary-400 ring-1 ring-slate-900/5 group-hover:scale-110 transition-transform duration-300">
                                <span class="material-symbols-outlined text-xl">cloud_upload</span>
                            </div>
                            <p class="mb-1 text-sm text-slate-600 dark:text-slate-300"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">SVG, PNG, JPG (MAX. 5MB)</p>
                        </div>
                        <input class="hidden" id="dropzone-file" type="file" />
                    </div>
                </section>
                <footer class="px-8 py-5 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <p class="text-xs text-slate-400 hidden sm:block">All fields are required unless marked optional.</p>
                    <button class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-primary hover:bg-blue-700 active:bg-blue-800 rounded-lg shadow-sm shadow-blue-900/20 transition-all flex items-center justify-center gap-2">
                        <span>Submit Incident</span>
                        <span class="material-symbols-outlined text-[18px]">send</span>
                    </button>
                </footer>
            </form>
        </div>
    </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('confirmModal');
            const modalContent = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('confirmModal');
            const modalContent = document.getElementById('modalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function confirmSend() {
            // Add your form submission logic here
            console.log('Incident report confirmed and sent');

            // Close modal after submission
            closeModal();

            // Optional: Show success message
            alert('รายงานถูกส่งเรียบร้อยแล้ว');
        }

        // Close modal when clicking outside
        document.getElementById('confirmModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
@endsection