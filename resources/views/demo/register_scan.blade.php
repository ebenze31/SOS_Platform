@extends('layouts.theme')

@section('content')

<div class="bg-background-light dark:bg-background-dark font-display min-h-screen flex flex-col text-text-primary-light dark:text-text-primary-dark antialiased selection:bg-primary/20 selection:text-primary">
  
    <!-- Main Content Area -->
    <div class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md bg-surface-light dark:bg-surface-dark rounded-2xl shadow-xl border border-border-light dark:border-border-dark overflow-hidden">

            <!-- Header Section -->
            <div class="px-6 pt-8 pb-4 text-center">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[32px] text-primary">emergency_home</span>
                </div>
                <h2 class="text-2xl font-bold text-text-primary-light dark:text-text-primary-dark mb-1">ลงทะเบียนเข้าหน่วย</h2>
                <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">เลือกหน่วยที่ต้องการเข้าประจำการ</p>
            </div>

            <!-- Tab Switcher -->
            <div class="px-6 pb-6">
                <div class="flex p-1 bg-background-light dark:bg-background-dark rounded-xl border border-border-light dark:border-border-dark">
                    <button
                        id="tab-scan"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 text-sm font-semibold rounded-lg bg-surface-light dark:bg-surface-dark text-primary shadow-sm ring-1 ring-black/5 dark:ring-white/10 transition-all duration-200"
                        type="button"
                        onclick="switchTab('scan')"
                    >
                        <span class="material-symbols-outlined text-[20px]">qr_code_scanner</span>
                        <span>สแกน QR Code</span>
                    </button>
                    <button
                        id="tab-manual"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark transition-colors duration-200"
                        type="button"
                        onclick="switchTab('manual')"
                    >
                        <span class="material-symbols-outlined text-[20px]">list_alt</span>
                        <span>เลือกรายชื่อหน่วย</span>
                    </button>
                </div>
            </div>

            <!-- ── SCAN VIEW ── -->
            <div id="view-scan" class="px-6 pb-8">
                <!-- Camera Frame -->
                <div class="relative w-full aspect-square bg-black rounded-xl overflow-hidden shadow-inner ring-1 ring-border-light dark:ring-border-dark mb-4">
                    <div class="absolute inset-0 bg-cover bg-center opacity-60" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAtFP0xEAQqBf4x1z6v_380z8CjrIRclmsfMEIJyL0USBQioTY__ediLCwHVSqWPimxjMyhqel3ndwC54NSe58JniUofaYNNT-RkN_6D8jKnYYpw-0z15sg-WDj1cO-JpJfZdh4IPktu1xtj3_wbxiMH8LyBDIJ0g7nXuS3QUlDCDBM_aK-bCGjuWlznOwU8nXsDtuxZgrL27mAzLugiR76YgpdKD8Eu9sZFFFgPBFIb8fuaDXHBKxO35AAWKjtO5o2VfVpXW9GkqKf');"></div>
                    <!-- Viewfinder -->
                    <div class="absolute inset-0 flex items-center justify-center p-12">
                        <div class="relative w-full h-full border-2 border-primary/50 rounded-lg">
                            <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-primary rounded-tl-lg"></div>
                            <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-primary rounded-tr-lg"></div>
                            <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-primary rounded-bl-lg"></div>
                            <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-primary rounded-br-lg"></div>
                            <div class="scan-line top-0 opacity-50"></div>
                        </div>
                    </div>
                    <!-- Status -->
                    <div class="absolute bottom-4 left-0 w-full flex justify-center">
                        <div class="bg-black/60 backdrop-blur-sm px-3 py-1 rounded-full flex items-center gap-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                            <span class="text-white text-xs font-medium tracking-wide">LIVE CAMERA</span>
                        </div>
                    </div>
                </div>

                <div class="text-center space-y-4">
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">
                        สแกน QR Code ประจำหน่วยที่ต้องการลงทะเบียนเข้าประจำการ<br />
                        <span class="text-xs opacity-75">(Scan your unit's QR code to register)</span>
                    </p>
                    <button class="w-full bg-primary hover:bg-primary-hover text-white font-medium py-3 px-4 rounded-xl shadow-lg shadow-primary/20 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <span class="material-symbols-outlined text-lg">how_to_reg</span>
                        <span>ยืนยันลงทะเบียน</span>
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>
            </div>

            <!-- ── MANUAL SELECT VIEW ── -->
            <div id="view-manual" class="hidden px-6 pb-8">
                <form class="space-y-4" onsubmit="handleSubmit(event)">

                    <!-- Search Box -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-text-primary-light dark:text-text-primary-dark" for="unit-search">
                            ค้นหาหน่วย
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-secondary-light dark:text-text-secondary-dark">
                                <span class="material-symbols-outlined text-[20px]">search</span>
                            </div>
                            <input
                                id="unit-search"
                                class="block w-full pl-10 pr-3 py-2.5 border border-border-light dark:border-border-dark rounded-xl bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark placeholder-text-secondary-light/50 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm"
                                placeholder="ชื่อหน่วย หรือ รหัสหน่วย..."
                                type="text"
                                autocomplete="off"
                            />
                        </div>
                    </div>

                    <!-- Unit List -->
                    <div id="unit-list" class="space-y-2 max-h-64 overflow-y-auto pr-0.5">

                        <!-- EMS units -->
                        <div class="unit-item" data-type="ems" data-id="EMS-001" data-name="หน่วยกู้ชีพ สถานีกลาง">
                            <button type="button" onclick="selectUnit(this)" class="unit-btn w-full flex items-center gap-3 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark hover:border-primary hover:bg-primary/5 transition-all duration-150 group text-left">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center flex-shrink-0 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-500/20 transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">emergency</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-text-primary-light dark:text-text-primary-dark truncate">หน่วยกู้ชีพ สถานีกลาง</p>
                                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">EMS-001 · กู้ชีพ (EMS)</p>
                                </div>
                                <span class="material-symbols-outlined text-[18px] text-border-light dark:text-border-dark group-hover:text-primary transition-colors flex-shrink-0">chevron_right</span>
                            </button>
                        </div>

                        <div class="unit-item" data-type="ems" data-id="EMS-002" data-name="หน่วยกู้ชีพ ฝั่งเหนือ">
                            <button type="button" onclick="selectUnit(this)" class="unit-btn w-full flex items-center gap-3 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark hover:border-primary hover:bg-primary/5 transition-all duration-150 group text-left">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center flex-shrink-0 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-500/20 transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">emergency</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-text-primary-light dark:text-text-primary-dark truncate">หน่วยกู้ชีพ ฝั่งเหนือ</p>
                                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">EMS-002 · กู้ชีพ (EMS)</p>
                                </div>
                                <span class="material-symbols-outlined text-[18px] text-border-light dark:text-border-dark group-hover:text-primary transition-colors flex-shrink-0">chevron_right</span>
                            </button>
                        </div>

                        <!-- Police units -->
                        <div class="unit-item" data-type="police" data-id="POL-001" data-name="สถานีตำรวจนครบาล 1">
                            <button type="button" onclick="selectUnit(this)" class="unit-btn w-full flex items-center gap-3 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark hover:border-primary hover:bg-primary/5 transition-all duration-150 group text-left">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center flex-shrink-0 text-blue-600 dark:text-blue-400 group-hover:bg-blue-500/20 transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">local_police</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-text-primary-light dark:text-text-primary-dark truncate">สถานีตำรวจนครบาล 1</p>
                                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">POL-001 · ตำรวจ</p>
                                </div>
                                <span class="material-symbols-outlined text-[18px] text-border-light dark:text-border-dark group-hover:text-primary transition-colors flex-shrink-0">chevron_right</span>
                            </button>
                        </div>

                        <div class="unit-item" data-type="police" data-id="POL-002" data-name="สถานีตำรวจนครบาล 2">
                            <button type="button" onclick="selectUnit(this)" class="unit-btn w-full flex items-center gap-3 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark hover:border-primary hover:bg-primary/5 transition-all duration-150 group text-left">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center flex-shrink-0 text-blue-600 dark:text-blue-400 group-hover:bg-blue-500/20 transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">local_police</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-text-primary-light dark:text-text-primary-dark truncate">สถานีตำรวจนครบาล 2</p>
                                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">POL-002 · ตำรวจ</p>
                                </div>
                                <span class="material-symbols-outlined text-[18px] text-border-light dark:text-border-dark group-hover:text-primary transition-colors flex-shrink-0">chevron_right</span>
                            </button>
                        </div>

                        <!-- Fire units -->
                        <div class="unit-item" data-type="fire" data-id="FIRE-001" data-name="สถานีดับเพลิงกลาง">
                            <button type="button" onclick="selectUnit(this)" class="unit-btn w-full flex items-center gap-3 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark hover:border-primary hover:bg-primary/5 transition-all duration-150 group text-left">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center flex-shrink-0 text-orange-600 dark:text-orange-400 group-hover:bg-orange-500/20 transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">local_fire_department</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-text-primary-light dark:text-text-primary-dark truncate">สถานีดับเพลิงกลาง</p>
                                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">FIRE-001 · ดับเพลิง</p>
                                </div>
                                <span class="material-symbols-outlined text-[18px] text-border-light dark:text-border-dark group-hover:text-primary transition-colors flex-shrink-0">chevron_right</span>
                            </button>
                        </div>

                        <div class="unit-item" data-type="fire" data-id="FIRE-002" data-name="สถานีดับเพลิงฝั่งใต้">
                            <button type="button" onclick="selectUnit(this)" class="unit-btn w-full flex items-center gap-3 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark hover:border-primary hover:bg-primary/5 transition-all duration-150 group text-left">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center flex-shrink-0 text-orange-600 dark:text-orange-400 group-hover:bg-orange-500/20 transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">local_fire_department</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-text-primary-light dark:text-text-primary-dark truncate">สถานีดับเพลิงฝั่งใต้</p>
                                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">FIRE-002 · ดับเพลิง</p>
                                </div>
                                <span class="material-symbols-outlined text-[18px] text-border-light dark:text-border-dark group-hover:text-primary transition-colors flex-shrink-0">chevron_right</span>
                            </button>
                        </div>

                        <!-- Empty state -->
                        <div id="empty-state" class="hidden text-center py-8 text-text-secondary-light dark:text-text-secondary-dark">
                            <span class="material-symbols-outlined text-[40px] opacity-30 block mb-2">search_off</span>
                            <p class="text-sm">ไม่พบหน่วยที่ค้นหา</p>
                        </div>
                    </div>

                    <!-- Selected Unit Badge -->
                    <div id="selected-unit" class="hidden">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-primary/10 border border-primary/30">
                            <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center flex-shrink-0 text-white">
                                <span id="selected-unit-icon" class="material-symbols-outlined text-[20px]">emergency_home</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-primary font-semibold uppercase tracking-wide">หน่วยที่เลือก</p>
                                <p id="selected-unit-name" class="text-sm font-bold text-text-primary-light dark:text-text-primary-dark truncate"></p>
                                <p id="selected-unit-id-display" class="text-xs text-text-secondary-light dark:text-text-secondary-dark"></p>
                            </div>
                            <button type="button" onclick="clearSelection()" class="text-text-secondary-light dark:text-text-secondary-dark hover:text-red-500 transition-colors flex-shrink-0">
                                <span class="material-symbols-outlined text-[20px]">close</span>
                            </button>
                        </div>
                    </div>

                    <!-- Hidden input -->
                    <input type="hidden" id="selected-unit-value" name="unit_id" />

                    <!-- Submit -->
                    <button
                        id="submit-btn"
                        class="w-full bg-primary hover:bg-primary-hover text-white font-medium py-3 px-4 rounded-xl shadow-lg shadow-primary/20 transition-all duration-200 flex items-center justify-center gap-2 group disabled:opacity-40 disabled:cursor-not-allowed"
                        type="submit"
                        disabled
                    >
                        <span class="material-symbols-outlined text-lg">how_to_reg</span>
                        <span>ลงทะเบียนเข้าหน่วย</span>
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
</style>

<script>
    /* ── Tab switching ── */
    function switchTab(tab) {
        const scanView   = document.getElementById('view-scan');
        const manualView = document.getElementById('view-manual');
        const tabScan    = document.getElementById('tab-scan');
        const tabManual  = document.getElementById('tab-manual');

        const on  = ['bg-surface-light', 'dark:bg-surface-dark', 'text-primary', 'shadow-sm', 'ring-1', 'ring-black/5', 'dark:ring-white/10', 'font-semibold'];
        const off = ['text-text-secondary-light', 'dark:text-text-secondary-dark', 'font-medium'];

        if (tab === 'scan') {
            scanView.classList.remove('hidden');
            manualView.classList.add('hidden');
            tabScan.classList.add(...on);
            tabScan.classList.remove(...off);
            tabManual.classList.remove(...on);
            tabManual.classList.add(...off);
        } else {
            manualView.classList.remove('hidden');
            scanView.classList.add('hidden');
            tabManual.classList.add(...on);
            tabManual.classList.remove(...off);
            tabScan.classList.remove(...on);
            tabScan.classList.add(...off);
        }
    }

    /* ── Search ── */
    document.getElementById('unit-search').addEventListener('input', applyFilters);

    function applyFilters() {
        const query   = document.getElementById('unit-search').value.toLowerCase();
        const items   = document.querySelectorAll('.unit-item');
        let visible   = 0;

        items.forEach(item => {
            const textOk = item.dataset.name.toLowerCase().includes(query)
                        || item.dataset.id.toLowerCase().includes(query);
            item.style.display = textOk ? '' : 'none';
            if (textOk) visible++;
        });

        document.getElementById('empty-state').classList.toggle('hidden', visible > 0);
    }

    /* ── Select unit ── */
    const iconMap = {
        ems:    'emergency',
        police: 'local_police',
        fire:   'local_fire_department'
    };

    function selectUnit(btn) {
        const item = btn.closest('.unit-item');
        const name = item.dataset.name;
        const id   = item.dataset.id;
        const type = item.dataset.type;

        // Highlight row
        document.querySelectorAll('.unit-btn').forEach(b => {
            b.classList.remove('!border-primary', 'bg-primary/5');
        });
        btn.classList.add('!border-primary', 'bg-primary/5');

        // Hidden input
        document.getElementById('selected-unit-value').value = id;

        // Badge
        document.getElementById('selected-unit-name').textContent       = name;
        document.getElementById('selected-unit-id-display').textContent = id;
        document.getElementById('selected-unit-icon').textContent       = iconMap[type] || 'emergency_home';
        document.getElementById('selected-unit').classList.remove('hidden');

        // Enable submit
        document.getElementById('submit-btn').disabled = false;

        // Scroll badge into view smoothly
        document.getElementById('selected-unit').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function clearSelection() {
        document.getElementById('selected-unit').classList.add('hidden');
        document.getElementById('selected-unit-value').value = '';
        document.getElementById('submit-btn').disabled = true;
        document.querySelectorAll('.unit-btn').forEach(b => {
            b.classList.remove('!border-primary', 'bg-primary/5');
        });
    }

    /* ── Submit ── */
    function handleSubmit(e) {
        e.preventDefault();
        const unitId = document.getElementById('selected-unit-value').value;
        if (!unitId) return;
        // TODO: ส่งข้อมูลไปยัง backend route
        console.log('Registering into unit:', unitId);
    }
</script>

@endsection