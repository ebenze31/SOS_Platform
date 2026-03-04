@extends('layouts.theme')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

<style>
    .bg-map-pattern {
        background-color: #e5e7eb;
        background-image: url("data:image/svg+xml,%3Csvg width='100%25' height='100%25' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h100v100H0z' fill='%23f3f4f6'/%3E%3Cpath d='M20 0v100M40 0v100M60 0v100M80 0v100M0 20h100M0 40h100M0 60h100M0 80h100' stroke='%23e5e7eb' stroke-width='1'/%3E%3Cpath d='M30 0c10 20 5 40 20 60s40 10 50 40' stroke='%2393c5fd' stroke-width='8' fill='none'/%3E%3Ccircle cx='65' cy='35' r='3' fill='%236b7280'/%3E%3C/svg%3E");
        background-size: cover;
        background-position: center;
    }

    .dark .bg-map-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='100%25' height='100%25' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h100v100H0z' fill='%231f2937'/%3E%3Cpath d='M20 0v100M40 0v100M60 0v100M80 0v100M0 20h100M0 40h100M0 60h100M0 80h100' stroke='%23374151' stroke-width='1'/%3E%3Cpath d='M30 0c10 20 5 40 20 60s40 10 50 40' stroke='%233b82f6' stroke-width='8' fill='none' opacity='0.4'/%3E%3Ccircle cx='65' cy='35' r='3' fill='%239ca3af'/%3E%3C/svg%3E");
    }

   .tab-panel {
    display: none;
    transform: translateY(20px);
    opacity: 0;
}

.tab-panel.active {
    display: flex;
    flex-direction: column;
    flex: 1;
    animation: slideUp 0.28s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

.tab-panel.closing {
    display: flex;
    flex-direction: column;
    flex: 1;
    animation: slideDown 0.22s cubic-bezier(0.4, 0, 1, 1) forwards;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes slideDown {
    from { opacity: 1; transform: translateY(0); }
    to   { opacity: 0; transform: translateY(24px); }
}
</style>

<div class="bg-background-light dark:bg-background-dark font-sans h-screen w-full overflow-hidden flex flex-col items-center justify-center relative">
    <div class="w-full bg-white dark:bg-surface-dark relative shadow-2xl overflow-hidden flex flex-col" style="height: calc(100% - 71px); margin-top: 71px;">

        <!-- MAP BACKGROUND (always visible behind panels) -->
        <div class="flex-grow w-full relative bg-black overflow-hidden">


            <!-- ==================== BOTTOM SHEET PANELS ==================== -->
            <div class="absolute bottom-0 left-0 right-0 z-10 px-3 pb-3 flex flex-col" style="max-width:500px; margin:0 auto;">

                <!-- TAB PANEL: ข้อมูล -->
                <div id="panel-info" class="tab-panel active">
                    <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark overflow-hidden flex flex-col max-h-[60vh]">
                        <div class="flex items-center px-5 py-4 border-b border-border-light dark:border-border-dark flex items-center justify-between bg-white dark:bg-surface-dark sticky top-0 z-20">
                            <div class="flex items-center space-x-3">
                                <h3 class="text-sm font-bold text-slate-800 border-l-4 border-primary pl-2 uppercase tracking-tight">ข้อมูลการขอความช่วยเหลือ</h3>
                            </div>
                        </div>
                        <div class="bg-white shadow-sm p-4 space-y-3 overflow-y-auto custom-scrollbar ">
                            <div>
                                <div class="text-xs text-slate-400 mb-0.5">ประเภทเหตุ</div>
                                <div class="font-bold text-slate-900 text-base">ภาวะหัวใจหยุดเต้น (Cardiac Arrest)</div>
                            </div>
                            <div class="h-px bg-slate-100"></div>
                            <div>
                                <div class="text-xs text-slate-400 mb-0.5">สถานที่</div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-slate-400 text-[16px] mt-0.5">location_on</span>
                                    <div class="text-sm text-slate-700 leading-snug">
                                        <span class="font-bold">8421 ถนนบรอด, ห้อง 4B</span><br>
                                        <span class="text-slate-500">เขตเวสต์ไซด์ • ใกล้แยกเมน</span>
                                    </div>
                                </div>
                            </div>
                            <div class="h-px bg-slate-100"></div>
                            <div>
                                <div class="text-xs text-slate-400 mb-0.5">ผู้ป่วย/ผู้แจ้ง</div>
                                <div class="flex justify-between items-center">
                                    <div class="text-sm font-medium text-slate-700">คุณซาร่า เจนกินส์</div>
                                    <a class="w-[30px] h-[30px] text-primary hover:bg-primary/5 p-1.5 rounded-full transition-colors" href="#"><span class="material-symbols-outlined text-[18px]">call</span></a>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700 mt-1">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                        ผู้ป่วยชาย อายุ 65 ปี หมดสติ ไม่หายใจ ญาติกำลังทำ CPR เบื้องต้น อยู่บริเวณชั้น 2 ของบ้านพัก
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB PANEL: ดำเนินการ -->
                <div id="panel-action" class="tab-panel">
                    <div class="bg-white dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark overflow-hidden flex flex-col max-h-[65vh]">


                        <div class="flex items-center justify-between p-4 border-b border-border-light">
                            <h3 class="text-sm font-bold text-slate-800 border-l-4 border-primary pl-2 uppercase tracking-tight">ดำเนินการ</h3>
                            <span id="status-badge" class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-red-100">ออกจากฐาน</span>
                        </div>
                        <div class="overflow-y-auto custom-scrollbar bg-white dark:bg-surface-dark">

                            <!-- ===== STEP 1: ถึงที่เกิดเหตุ ===== -->
                            <div id="step1-section" class="px-5 py-5">
                                <button id="step-arrived" onclick="markArrived()" class="w-full relative group overflow-hidden rounded-xl bg-white border-2 border-primary/20 hover:border-primary shadow-sm hover:shadow-md transition-all p-4 flex items-center gap-4 text-left">
                                    <div class="absolute inset-0 bg-primary/5 group-hover:bg-primary/10 transition-colors"></div>
                                    <div class="relative flex h-12 w-12 shrink-0">
                                        <span class="relative inline-flex rounded-full h-12 w-12 bg-emerald-500 items-center justify-center text-white shadow-sm border-2 border-white">
                                            <span class="material-symbols-outlined text-[24px]">flag</span>
                                        </span>
                                    </div>
                                    <div class="flex-1 z-10">
                                        <div class="text-lg font-bold text-slate-900 leading-tight">ถึงที่เกิดเหตุ</div>
                                        <div class="text-xs text-primary font-medium mt-0.5 group-hover:underline">กดเพื่อยืนยันสถานะ</div>
                                    </div>
                                    <span class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors">chevron_right</span>
                                </button>
                            </div>

                            <!-- ===== STEP 2: หมายเหตุ + เสร็จสิ้น (hidden initially) ===== -->
                            <div id="step2-section" class="hidden px-5 pb-5 space-y-4">



                                <!-- หมายเหตุ -->
                                <div class="mt-5">
                                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1 block">
                                        หมายเหตุ <span class="text-red-400">*</span>
                                    </label>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-2">ระบุหมายเหตุเพิ่มเติม เช่น นำส่งโรงพยาบาลเอเชียเหนือ</p>
                                    <textarea id="action-note" rows="3" oninput="onNoteInput()"
                                        class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                                        placeholder="ระบุหมายเหตุ..."></textarea>
                                </div>

                                <!-- เสร็จสิ้น button (disabled until note is filled) -->
                                <!-- <button id="btn-finish" onclick="markFinish()" disabled
                                    class="w-full flex items-center justify-center space-x-2 py-3.5 rounded-xl font-bold text-sm transition-all
                                           bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed border-2 border-gray-200 dark:border-gray-700">
                                    <span class="material-icons text-xl">task_alt</span>
                                    <span>เสร็จสิ้นภารกิจ</span>
                                </button> -->
                                <button id="btn-finish" onclick="markFinish()" disabled
                                    class="w-full flex items-center justify-between p-4 space-x-2 py-3.5 rounded-xl font-bold text-sm transition-all
                                           bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed border-2 border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-text-green-500 dark:bg-green/20 flex items-center justify-center">
                                            <span class="material-icons text-gray-500 text-xl">task_alt</span>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-bold text-gray-900 dark:text-white text-sm">ถึงที่เกิดเหตุ</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">กดเพื่อยืนยัน</p>
                                        </div>
                                    </div>
                                    <span class="material-icons text-gray-500">chevron_right</span>
                                </button>


                                <!-- Completed message (hidden until finish) -->
                                <div id="step-done" class="hidden w-full p-4 rounded-xl border-2 border-green-400 bg-green-50 dark:bg-green-900/20 flex items-center space-x-3">
                                    <span class="material-icons text-green-500 text-2xl">check_circle</span>
                                    <div>
                                        <p class="font-bold text-green-700 dark:text-green-400 text-sm">ส่งมอบผู้ป่วยเรียบร้อย</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">ภารกิจเสร็จสิ้น</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- TAB PANEL: เส้นทาง -->
                <div id="panel-route" class="tab-panel">
                    <div class="p-4 bg-white dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark overflow-hidden flex flex-col max-h-[65vh]">
                        <div class="border-b border-border-light">

                            <h3 class="text-sm font-bold text-slate-800 border-l-4 border-primary pl-2 uppercase tracking-tight mb-3">ระยะทาง/เวลาถึง</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <div class="bg-white border border-slate-200 rounded-xl p-4 text-center shadow-sm">
                                <div class="text-[10px] uppercase tracking-wider text-slate-400 mb-1">เวลาเดินทาง</div>
                                <div class="text-3xl font-bold font-mono text-slate-900">12<span class="text-sm font-sans font-normal text-slate-500">นาที</span></div>
                            </div>
                            <div class="bg-white border border-slate-200 rounded-xl p-4 text-center shadow-sm">
                                <div class="text-[10px] uppercase tracking-wider text-slate-400 mb-1">ระยะทาง</div>
                                <div class="text-3xl font-bold font-mono text-slate-900">1.2<span class="text-sm font-sans font-normal text-slate-500">กม.</span></div>
                            </div>
                        </div>
                        <button class="mt-4 w-full py-3.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl shadow-lg flex items-center justify-center gap-3 transition-all transform hover:-translate-y-0.5 group">
                            <span class="material-symbols-outlined text-[24px] group-hover:animate-pulse">map</span>
                            <span class="font-bold text-sm">เปิดแผนที่</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- BOTTOM NAVIGATION -->
        <nav class="bg-surface-light dark:bg-surface-dark border-t border-border-light dark:border-border-dark h-[60px] flex items-center justify-around w-full z-30 pb-safe shadow-up">
            <button onclick="switchTab('info')" id="nav-info"
                class="nav-btn flex flex-col items-center justify-center w-full h-full space-y-1 relative group text-primary">
                <span class="absolute top-0 w-full h-0.5 bg-primary tab-indicator"></span>
                <span class="material-icons text-2xl group-hover:scale-110 transition-transform">info</span>
                <span class="text-xs font-semibold">ข้อมูล</span>
            </button>
            <button onclick="switchTab('action')" id="nav-action"
                class="nav-btn flex flex-col items-center justify-center w-full h-full space-y-1 relative group text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <span class="absolute top-0 w-full h-0.5 bg-primary tab-indicator hidden"></span>
                <span class="material-icons text-2xl group-hover:scale-110 transition-transform">assignment_turned_in</span>
                <span class="text-xs font-medium">ดำเนินการ</span>
            </button>
            <button onclick="switchTab('route')" id="nav-route"
                class="nav-btn flex flex-col items-center justify-center w-full h-full space-y-1 relative group text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <span class="absolute top-0 w-full h-0.5 bg-primary tab-indicator hidden"></span>
                <span class="material-icons text-2xl group-hover:scale-110 transition-transform">alt_route</span>
                <span class="text-xs font-medium">เส้นทาง</span>
            </button>
        </nav>
    </div>
    <div class="absolute inset-0 -z-10 bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
        <p class="text-gray-400 dark:text-gray-600 text-sm hidden md:block absolute bottom-4">Responsive Mobile Interface Preview</p>
    </div>
</div>

<script>
    const tabs = ['info', 'action', 'route'];

    function switchTab(tab) {
        tabs.forEach(t => {
            const panel = document.getElementById('panel-' + t);
            const nav = document.getElementById('nav-' + t);
            const indicator = nav.querySelector('.tab-indicator');

            if (t === tab) {
                panel.classList.add('active');
                nav.classList.remove('text-gray-400', 'dark:text-gray-500', 'hover:text-gray-600', 'dark:hover:text-gray-300', 'transition-colors');
                nav.classList.add('text-primary');
                indicator.classList.remove('hidden');
            } else {
                panel.classList.remove('active');
                nav.classList.remove('text-primary');
                nav.classList.add('text-gray-400', 'dark:text-gray-500', 'hover:text-gray-600', 'dark:hover:text-gray-300', 'transition-colors');
                indicator.classList.add('hidden');
            }
        });
    }

    // ===== Action step logic =====

    function markArrived() {
        // Hide step 1, show step 2
        document.getElementById('step1-section').classList.add('hidden');
        document.getElementById('step2-section').classList.remove('hidden');

        // Update status badge → ออกจากที่เกิดเหตุ
        const badge = document.getElementById('status-badge');
        badge.textContent = 'ออกจากที่เกิดเหตุ';
        badge.className = 'text-xs font-semibold px-3 py-1 rounded-full border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400';

        // Show arrived timestamp
        const now = new Date();
        const timeStr = now.toLocaleTimeString('th-TH', {
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('arrived-time').textContent = 'ยืนยันเวลา ' + timeStr + ' น.';
    }

    function onNoteInput() {
        const note = document.getElementById('action-note').value.trim();
        const btn = document.getElementById('btn-finish');

        if (note.length > 0) {
            btn.disabled = false;
            btn.className = 'w-full flex items-center justify-between p-4 items-center justify-center space-x-2 py-3.5 rounded-xl font-bold text-sm transition-all bg-green-100 text-white cursor-pointer border-2 border-green-500';
        } else {
            btn.disabled = true;
            btn.className = 'w-full flex items-center justify-between p-4 items-center justify-center space-x-2 py-3.5 rounded-xl font-bold text-sm transition-all bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed border-2 border-gray-200 dark:border-gray-700';
        }
    }

    function markFinish() {
        // Hide finish button, show done banner
        document.getElementById('btn-finish').classList.add('hidden');
        const done = document.getElementById('step-done');
        done.classList.remove('hidden');
        done.classList.add('flex');

        // Update status badge → เสร็จสิ้น
        const badge = document.getElementById('status-badge');
        badge.textContent = 'เสร็จสิ้น';
        badge.className = 'text-xs font-semibold px-3 py-1 rounded-full border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400';

        // Disable note textarea
        document.getElementById('action-note').disabled = true;
        document.getElementById('action-note').classList.add('opacity-60');
    }
let activeTab = 'info';

function closePanel(tab, callback) {
    const panel = document.getElementById('panel-' + tab);
    const nav = document.getElementById('nav-' + tab);
    const indicator = nav.querySelector('.tab-indicator');

    panel.classList.add('closing');
    nav.classList.remove('text-primary');
    nav.classList.add('text-gray-400', 'dark:text-gray-500', 'hover:text-gray-600', 'dark:hover:text-gray-300', 'transition-colors');
    indicator.classList.add('hidden');

    panel.addEventListener('animationend', function handler() {
        panel.removeEventListener('animationend', handler);
        panel.classList.remove('active', 'closing');
        if (callback) callback();
    });
}

function openPanel(tab) {
    const panel = document.getElementById('panel-' + tab);
    const nav = document.getElementById('nav-' + tab);
    const indicator = nav.querySelector('.tab-indicator');

    panel.classList.add('active');
    nav.classList.remove('text-gray-400', 'dark:text-gray-500', 'hover:text-gray-600', 'dark:hover:text-gray-300', 'transition-colors');
    nav.classList.add('text-primary');
    indicator.classList.remove('hidden');
}

function switchTab(tab) {
    if (activeTab === tab) {
        // Toggle off
        activeTab = null;
        closePanel(tab);
        return;
    }

    const prev = activeTab;
    activeTab = tab;

    if (prev) {
        closePanel(prev, () => openPanel(tab));
    } else {
        openPanel(tab);
    }
}

</script>

@endsection