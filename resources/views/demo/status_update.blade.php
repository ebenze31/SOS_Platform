@extends('layouts.theme')

@section('content')

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
                borderRadius: {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
                },
                animation: {
                    'shimmer': 'shimmer 2s linear infinite',
                },
                keyframes: {
                    shimmer: {
                        '0%': {
                            backgroundPosition: '-200% 0'
                        },
                        '100%': {
                            backgroundPosition: '200% 0'
                        },
                    }
                }
            },
        },
    }
</script>
<style>
    .map-pattern {
        background-color: #f1f5f9;
        background-image:
            linear-gradient(rgba(203, 213, 225, 0.4) 1px, transparent 1px),
            linear-gradient(90deg, rgba(203, 213, 225, 0.4) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    .dark .map-pattern {
        background-color: #1e293b;
        background-image:
            linear-gradient(rgba(51, 65, 85, 0.4) 1px, transparent 1px),
            linear-gradient(90deg, rgba(51, 65, 85, 0.4) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    .route-line {
        stroke-dasharray: 1000;
        stroke-dashoffset: 1000;
        animation: drawLine 2s ease-in-out forwards;
    }

    @keyframes drawLine {
        to {
            stroke-dashoffset: 0;
        }
    }body ,html{
        width: 100% !important;
        height: 100% !important;
    }
</style>
</head>
<div class="w-full md:h-[calc(100% - 71px)] relative flex justify-center sm:items-center p-3 bg-slate-50 dark:bg-slate-900 " style="height: calc(100% - 71.75px);margin-top:71.75px;overflow: auto;">

    <div class="relative w-full  max-w-[500px] bg-white dark:bg-[#1a2632] rounded-xl shadow-xl flex flex-col overflow-auto ring-1 ring-black/5 dark:ring-white/10">
        <header class="px-8 py-6 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between bg-white dark:bg-[#1a2632]">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Status Update</h1>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide">ID: #REQ-2023-891</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-primary flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-2xl text-white">emergency</span>
            </div>
        </header>
        <div class="flex flex-col bg-white dark:bg-[#1a2632]">
            <section class="px-8 py-8" id="statusContainer">
                <!-- Status steps will be rendered here by JavaScript -->
            </section>

            <!-- Map Section -->
            <section class="px-8 pb-8">
                <div class="relative w-full h-64 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden bg-white">
                    <!-- Real map image placeholder -->
                    <img id="mapImage" src=""
                        alt="Map"
                        class="w-full h-full object-cover"
                        style="filter: brightness(0.95);">

                    <!-- Route Line Overlay (shown in states 3 & 4) -->
                    <svg id="routeLine" class="absolute inset-0 w-full h-full hidden" style="pointer-events: none;">
                        <path d="M 120 80 Q 200 120, 280 180 T 400 280"
                            stroke="#7c3aed"
                            stroke-width="4"
                            fill="none"
                            stroke-linecap="round"
                            class="route-line" />
                        <circle cx="120" cy="80" r="8" fill="#7c3aed" />
                        <circle cx="400" cy="280" r="8" fill="#7c3aed" />
                    </svg>

                    <!-- Your Location Marker -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center z-10 cursor-pointer">
                        <div class="relative flex items-center justify-center mb-1">
                            <div class="absolute w-12 h-12 bg-primary/20 dark:bg-blue-500/20 rounded-full animate-ping"></div>
                            <div class="absolute w-24 h-24 bg-primary/5 dark:bg-blue-500/5 rounded-full animate-pulse delay-75"></div>
                            <div class="relative w-4 h-4 bg-primary rounded-full border-2 border-white dark:border-slate-800 shadow-lg z-20"></div>
                        </div>
                    </div>

                    <div class="absolute bottom-3 right-3 bg-white/90 dark:bg-slate-800/90 p-1 rounded shadow-sm border border-slate-100 dark:border-slate-700">
                        <span class="material-symbols-outlined text-lg text-slate-500">my_location</span>
                    </div>
                </div>
            </section>

            <section id="officerInfo" class="px-8 py-6 bg-white dark:bg-[#1a2632] border-t border-slate-100 dark:border-slate-700 hidden">
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-100 dark:border-slate-700 shadow-sm">
                    <div class="flex items-center">

                        <div class="w-12 h-12 mr-3 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl text-slate-600 dark:text-slate-400">person</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">นายสมชาย ยิ้มดี</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">หน่วย A </p>
                        </div>
                    </div>
                    <button id="callOfficerBtn" class="mt-3  w-full px-4 py-1 text-sm font-bold text-black bg-white hover:bg-primary  hover:text-white rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">call</span>
                        <span>ติดต่อ</span>
                    </button>
                    <div id="caseClosed" class="flex flex-col mt-3 gap-3 hidden">
                        <button class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-lg shadow-sm shadow-amber-500/20 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">star_rate</span>
                            Rate &amp; Review Service
                        </button>
                        <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                            Return to Home
                        </button>
                    </div>
                </div>
            </section>
            <!-- Officer Info (shown in states 3, 4, 5) -->
            <!-- <section id="officerInfo" class="px-8 pb-6 hidden">
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4  gap-4">
                    <div class="flex items-center">

                        <div class="w-12 h-12 mr-3 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl text-slate-600 dark:text-slate-400">person</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">นายสมชาย ยิ้มดี</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">หน่วย A </p>
                        </div>
                    </div>
                    <button id="callOfficerBtn" class="mt-3 text-center  w-full px-4 py-1 text-sm font-bold text-black bg-white hover:bg-primary  hover:text-white rounded-lg shadow-sm transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">call</span>
                        <span>ติดต่อ</span>
                    </button>
                </div>
            </section> -->

            <!-- Case Closed (shown in state 5) -->
            <section id="caseClosed" class="px-8 pb-6 hidden">
                <div class="bg-slate-100 dark:bg-slate-800 rounded-xl p-6 text-center">
                    <div class="text-sm font-bold text-slate-600 dark:text-slate-400 mb-4">CASE CLOSED</div>
                    <button id="reviewBtn" class="w-full px-6 py-3 text-base font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-xl">star</span>
                        <span>ประเมินการบริการของเรา</span>
                    </button>
                    <button class="mt-3 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                        กลับหน้าหลัก
                    </button>
                </div>
            </section>
        </div>

        <footer class="px-8 py-5 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <button id="backBtn" class="text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 transition-colors uppercase tracking-wide">
                ตำรวจจังหวัดสมุทรปราการ
            </button>
            <button id="nextBtn" onclick="nextStatus()" class="text-xs font-semibold text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors uppercase tracking-wide">
                (หน้าต่อไป)
            </button>
        </footer>
    </div>
</div>

<script>
    let currentStatus = 1; // Start at status 1 (ค้นหาเจ้าหน้าที่)

    const statusConfigs = {
        1: { // ค้นหาเจ้าหน้าที่
            active: 1,
            showRoute: false,
            showOfficer: false,
            showCaseClosed: false,
            showNextBtn: true
        },
        2: { // เจ้าหน้าที่รับเคส
            active: 2,
            showRoute: false,
            showOfficer: true,
            showCaseClosed: false,
            showNextBtn: true
        },
        3: { // เจ้าหน้าที่มากันแล้ว
            active: 3,
            showRoute: true,
            showOfficer: true,
            showCaseClosed: false,
            showNextBtn: true
        },
        4: { // เจ้าหน้าที่กำลังดำเนินการ (implied between 3 and 5)
            active: 4,
            showRoute: true,
            showOfficer: true,
            showCaseClosed: false,
            showNextBtn: true
        },
        5: { // การช่วยเหลือเสร็จสิ้น
            active: 5,
            showRoute: false,
            showOfficer: true,
            showCaseClosed: true,
            showNextBtn: false
        }
    };

    const statusSteps = [{
            id: 1,
            title: 'ส่งคำขอเรียบร้อย',
            description: 'คุณได้ส่งคำขอความช่วยเหลือไปยังเจ้าหน้าที่เรียบร้อยแล้ว',
            icon: 'check',
            time: '12.00 น.',
            completedStates: [1, 2, 3, 4, 5]
        },
        {
            id: 2,
            title: 'ค้นหาเจ้าหน้าที่',
            description: 'ระบบกำลังค้นหาเจ้าหน้าที่จากเจ้าหน้าที่ของความช่วยเหลือของคุณ',
            icon: 'sync',
            activeStates: [1],
            completedStates: [2, 3, 4, 5]
        },
        {
            id: 3,
            title: 'เจ้าหน้าที่กำลังเดินทาง',
            description: 'เจ้าหน้าที่กำลังเดินทางมาหาคุณ',
            icon: 'badge',
            activeStates: [2],
            completedStates: [3, 4, 5]
        },
        {
            id: 4,
            title: 'เจ้าหน้าที่มาถึงแล้ว',
            description: 'เจ้าหน้าที่เดินทางมาถึงแล้ว กรุณาอยู่ในจุดที่สังเกตได้ง่าย',
            icon: 'shield_person',
            activeStates: [3],
            completedStates: [4, 5]
        },
        {
            id: 5,
            title: 'การช่วยเหลือเสร็จสิ้น',
            description: 'กรุณาให้คะแนนความพึงพอใจเพื่อปรับปรุงการให้บริการ',
            icon: 'flag',
            activeStates: [4],
            completedStates: [5]
        }
    ];

    function renderStatus() {
        const config = statusConfigs[currentStatus];
        const container = document.getElementById('statusContainer');
        container.innerHTML = '';

        statusSteps.forEach((step, index) => {
            const isCompleted = step.completedStates?.includes(currentStatus);
            const isActive = step.activeStates?.includes(currentStatus);
            const isPending = !isCompleted && !isActive;
            const isLast = index === statusSteps.length - 1;

            let bgColor = 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-600';
            let iconAnimation = '';

            if (isCompleted) {
                bgColor = 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400';
            } else if (isActive) {
                bgColor = 'bg-primary text-white';
                if (step.icon === 'sync') {
                    iconAnimation = 'animate-spin';
                }
            }

            const titleColor = isCompleted ? 'text-slate-900 dark:text-white' :
                isActive ? 'text-primary dark:text-blue-400' :
                'text-slate-400 dark:text-slate-500';

            const descColor = isCompleted ? 'text-slate-500 dark:text-slate-400' :
                isActive ? 'text-slate-500 dark:text-slate-400' :
                'text-slate-400 dark:text-slate-600';

            const lineColor = isCompleted ? 'bg-green-200 dark:bg-green-900/40' :
                'bg-slate-200 dark:bg-slate-700';

            const stepHTML = `
                <div class="flex gap-4 group mb-1.5">
                    <div class="flex flex-col items-center">
                        ${isActive && step.icon === 'sync' ? `
                        <div class="relative flex items-center justify-center w-10 h-10 shrink-0 z-10">
                            <div class="absolute w-full h-full rounded-md bg-primary/20 animate-pulse"></div>
                            <div class="relative w-10 h-10 rounded-md ${bgColor} flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl ${iconAnimation}" style="animation-duration: 3s;">${step.icon}</span>
                            </div>
                        </div>
                        ` : `
                        <div class="w-10 h-10 rounded-md ${bgColor} flex items-center justify-center shrink-0 z-10">
                            <span class="material-symbols-outlined text-xl">${step.icon}</span>
                        </div>
                        `}
                        ${!isLast ? `<div class="w-0.5 ${lineColor} h-full min-h-[50px] -my-2"></div>` : ''}
                    </div>
                    <div class="${!isLast ? 'pb-6' : ''} ">
                        <h3 class="text-base font-bold ${titleColor}">${step.title}</h3>
                        <p class="text-sm ${descColor} mt-1">${step.description}</p>
                        ${step.time && isCompleted ? `
                        <span class="inline-block mt-2 text-xs font-semibold px-2 py-1 rounded bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400">${step.time}</span>
                        ` : ''}
                    </div>
                </div>
            `;

            container.innerHTML += stepHTML;
        });

        // Update UI based on current status
        const routeLine = document.getElementById('routeLine');
        const officerInfo = document.getElementById('officerInfo');
        const caseClosed = document.getElementById('caseClosed');
        const nextBtn = document.getElementById('nextBtn');
        const callOfficerBtn = document.getElementById('callOfficerBtn');

        if (config.showRoute) {
            routeLine.classList.remove('hidden');
        } else {
            routeLine.classList.add('hidden');
        }

        if (config.showOfficer) {
            officerInfo.classList.remove('hidden');
        } else {
            officerInfo.classList.add('hidden');
        }

        if (config.showCaseClosed) {
            caseClosed.classList.remove('hidden');
            callOfficerBtn.classList.add('hidden');
        } else {
            caseClosed.classList.add('hidden');
            callOfficerBtn.classList.remove('hidden');

        }

        if (config.showNextBtn) {
            nextBtn.classList.remove('hidden');
        } else {
            nextBtn.classList.add('hidden');
        }
    }

    function nextStatus() {
        if (currentStatus < 5) {
            currentStatus++;
            renderStatus();
        }
    }

    // Initial render
    renderStatus();
</script>

@endsection