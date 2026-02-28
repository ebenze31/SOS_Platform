@extends('layouts.theme')

@section('content')

<div class="bg-background-light dark:bg-background-dark font-display min-h-[calc(100vh-71.75px)] mt-[71.75px] flex flex-col text-text-primary-light dark:text-text-primary-dark antialiased selection:bg-primary/20 selection:text-primary">
  
    <div class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md bg-surface-light dark:bg-surface-dark rounded-2xl shadow-xl border border-border-light dark:border-border-dark overflow-hidden">

            <div class="px-6 pt-8 pb-4 text-center">
                <h2 class="text-2xl font-bold text-text-primary-light dark:text-text-primary-dark mb-1">ลงทะเบียนพื้นที่</h2>
            </div>

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
                        <span>เลือกรายชื่อพื้นที่</span>
                    </button>
                </div>
            </div>

            <div id="view-scan" class="px-6 pb-8">
                <div class="relative w-full aspect-square bg-black rounded-xl overflow-hidden shadow-inner ring-1 ring-border-light dark:ring-border-dark mb-4">
                    
                    <div id="qr-reader" class="absolute inset-0 w-full h-full object-cover"></div>

                    <div class="absolute inset-0 flex items-center justify-center p-12 pointer-events-none z-10">
                        <div class="relative w-full h-full border-2 border-primary/50 rounded-lg">
                            <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-primary rounded-tl-lg"></div>
                            <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-primary rounded-tr-lg"></div>
                            <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-primary rounded-bl-lg"></div>
                            <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-primary rounded-br-lg"></div>
                            <div class="scan-line top-0 opacity-50"></div>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-4 left-0 w-full flex justify-center z-10 pointer-events-none">
                        <div id="camera-status" class="bg-black/60 backdrop-blur-sm px-3 py-1 rounded-full flex items-center gap-2 transition-colors">
                            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                            <span class="text-white text-xs font-medium tracking-wide">กำลังค้นหา QR...</span>
                        </div>
                    </div>
                </div>

                <div class="text-center space-y-4">
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">
                        นำกล้องส่องไปที่ QR Code ของพื้นที่
                    </p>
                    
                    <input type="file" id="qr-upload" accept="image/*" class="hidden" onchange="handleImageScan(event)">
                    <button onclick="document.getElementById('qr-upload').click()" class="w-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium py-3 px-4 rounded-xl shadow-sm transition-all duration-200 flex items-center justify-center gap-2 group border border-slate-200 dark:border-slate-700">
                        <span class="material-symbols-outlined text-lg">image</span>
                        <span>เลือกจากรูปภาพอัลบั้ม</span>
                    </button>
                </div>
            </div>

            <div id="view-manual" class="hidden px-6 pb-8">
                <form action="{{ route('user_officers.register') }}" method="GET" class="space-y-4">

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-text-primary-light dark:text-text-primary-dark" for="area-search">
                            ค้นหาพื้นที่รับผิดชอบ
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-secondary-light dark:text-text-secondary-dark">
                                <span class="material-symbols-outlined text-[20px]">search</span>
                            </div>
                            <input
                                id="area-search"
                                class="block w-full pl-10 pr-3 py-2.5 border border-border-light dark:border-border-dark rounded-xl bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark placeholder-text-secondary-light/50 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm"
                                placeholder="ชื่อพื้นที่ หรือ ประเภท..."
                                type="text"
                                autocomplete="off"
                            />
                        </div>
                    </div>

                    <div id="area-list" class="space-y-2 max-h-64 overflow-y-auto pr-0.5 custom-scrollbar">
                        
                        @forelse($areas as $area)
                            @php
                                $icon = 'share_location';
                                $colorClass = 'text-blue-600 dark:text-blue-400 bg-blue-500/10 group-hover:bg-blue-500/20';
                                
                                if($area->type == 'เขตเมือง') {
                                    $icon = 'location_city';
                                    $colorClass = 'text-indigo-600 dark:text-indigo-400 bg-indigo-500/10 group-hover:bg-indigo-500/20';
                                } elseif($area->type == 'เขตนอกเมือง') {
                                    $icon = 'landscape';
                                    $colorClass = 'text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 group-hover:bg-emerald-500/20';
                                } elseif($area->type == 'เขตอุตสาหกรรม') {
                                    $icon = 'factory';
                                    $colorClass = 'text-amber-600 dark:text-amber-400 bg-amber-500/10 group-hover:bg-amber-500/20';
                                } elseif($area->type == 'ทางหลวง/มอเตอร์เวย์') {
                                    $icon = 'add_road';
                                    $colorClass = 'text-slate-600 dark:text-slate-400 bg-slate-500/10 group-hover:bg-slate-500/20';
                                }
                            @endphp

                            <div class="area-item" data-type="{{ $area->type }}" data-id="{{ $area->id }}" data-name="{{ $area->name_area }}" data-icon="{{ $icon }}">
                                <button type="button" onclick="selectArea(this)" class="area-btn w-full flex items-center gap-3 p-3 rounded-xl border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark hover:border-primary hover:bg-primary/5 transition-all duration-150 group text-left">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors {{ $colorClass }}">
                                        <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-text-primary-light dark:text-text-primary-dark truncate">{{ $area->name_area }}</p>
                                        <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">ประเภท: {{ $area->type }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-[18px] text-border-light dark:text-border-dark group-hover:text-primary transition-colors flex-shrink-0">chevron_right</span>
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-8 text-text-secondary-light dark:text-text-secondary-dark">
                                <span class="material-symbols-outlined text-[40px] opacity-30 block mb-2">location_off</span>
                                <p class="text-sm">ยังไม่มีพื้นที่เปิดรับลงทะเบียน</p>
                            </div>
                        @endforelse

                        <div id="empty-state" class="hidden text-center py-8 text-text-secondary-light dark:text-text-secondary-dark">
                            <span class="material-symbols-outlined text-[40px] opacity-30 block mb-2">search_off</span>
                            <p class="text-sm">ไม่พบพื้นที่ที่ค้นหา</p>
                        </div>
                    </div>

                    <div id="selected-area" class="hidden">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-primary/10 border border-primary/30">
                            <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center flex-shrink-0 text-white">
                                <span id="selected-area-icon" class="material-symbols-outlined text-[20px]">share_location</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-primary font-semibold uppercase tracking-wide">พื้นที่ที่เลือก</p>
                                <p id="selected-area-name" class="text-sm font-bold text-text-primary-light dark:text-text-primary-dark truncate"></p>
                                <p id="selected-area-type-display" class="text-xs text-text-secondary-light dark:text-text-secondary-dark"></p>
                            </div>
                            <button type="button" onclick="clearSelection()" class="text-text-secondary-light dark:text-text-secondary-dark hover:text-red-500 transition-colors flex-shrink-0">
                                <span class="material-symbols-outlined text-[20px]">close</span>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" id="selected-area-value" name="area_id" />

                    <button
                        id="submit-btn"
                        class="w-full bg-primary hover:bg-primary-hover text-white font-medium py-3 px-4 rounded-xl shadow-lg shadow-primary/20 transition-all duration-200 flex items-center justify-center gap-2 group disabled:opacity-40 disabled:cursor-not-allowed"
                        type="submit"
                        disabled
                    >
                        <span class="material-symbols-outlined text-lg">how_to_reg</span>
                        <span>ดำเนินการต่อ</span>
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
    /* CSS จัดการวิดีโอจาก html5-qrcode ให้เต็มกรอบสวยงาม */
    #qr-reader { border: none !important; }
    #qr-reader video { 
        object-fit: cover; 
        width: 100% !important; 
        height: 100% !important; 
    }
    #qr-reader__scan_region { background: transparent !important; }
    #qr-reader__dashboard { display: none !important; /* ซ่อนปุ่มเดิมของระบบ */ }

    .scan-line {
        position: absolute;
        width: 100%;
        height: 2px;
        background: #3b82f6;
        box-shadow: 0 0 8px #3b82f6;
        animation: scan 2.5s linear infinite;
    }
    @keyframes scan {
        0% { top: 0; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
</style>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    /* ── ตัวแปรสำหรับกล้อง ── */
    let html5QrCode;
    let isCameraRunning = false;
    let isProcessing = false; // ป้องกันการเด้งแจ้งเตือนซ้ำรัวๆ

    /* ── เริ่มต้นระบบตอนโหลดหน้าเว็บ ── */
    document.addEventListener("DOMContentLoaded", function() {
        html5QrCode = new Html5Qrcode("qr-reader");
        startCamera();
    });

    /* ── ฟังก์ชันเปิดกล้อง ── */
    function startCamera() {
        if (isCameraRunning) return;
        
        // กำหนดให้ใช้กล้องหลัง (environment)
        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            onScanFailure
        ).then(() => {
            isCameraRunning = true;
            document.getElementById('camera-status').innerHTML = '<div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div><span class="text-white text-xs font-medium tracking-wide">กำลังสแกน...</span>';
        }).catch((err) => {
            console.error("Camera start failed", err);
            document.getElementById('camera-status').innerHTML = '<div class="w-2 h-2 bg-red-500 rounded-full"></div><span class="text-white text-xs font-medium tracking-wide">ไม่สามารถเปิดกล้องได้</span>';
        });
    }

    /* ── ฟังก์ชันปิดกล้อง ── */
    function stopCamera() {
        if (isCameraRunning) {
            // คืนค่า (return) ตัว Promise ออกไป เพื่อให้ฟังก์ชันอื่นใช้ .then() รอได้
            return html5QrCode.stop().then(() => {
                isCameraRunning = false;
            }).catch(err => console.error("Error stopping camera", err));
        }
        // ถ้ากล้องปิดอยู่แล้ว ให้ส่งสถานะสำเร็จกลับไปเลยทันที
        return Promise.resolve(); 
    }

    /* ── เมื่อสแกนสำเร็จ ── */
    function onScanSuccess(decodedText, decodedResult) {
        if(isProcessing) return;
        isProcessing = true; // ล็อกไว้ไม่ให้รันซ้ำ

        // ตรวจสอบความถูกต้องของ URL (ต้องมีคำว่า user_officers/register และ area_id)
        if (decodedText.includes("user_officers/register") && decodedText.includes("area_id=")) {
            stopCamera(); // ปิดกล้องก่อน Redirect
            window.location.href = decodedText;
        } else {
            alert("QR-Code ไม่ถูกต้อง โปรดตรวจสอบอีกครั้ง");
            
            // หน่วงเวลา 2 วินาที ก่อนให้สแกนใหม่ได้ (กัน Alert เด้งรัวๆ)
            setTimeout(() => {
                isProcessing = false;
            }, 2000);
        }
    }

    function onScanFailure(error) {
        // ไม่ต้องทำอะไร ปล่อยให้มันหาต่อไปเงียบๆ
    }

    /* ── สแกนจากรูปภาพ ── */
    function handleImageScan(event) {
        if (event.target.files.length == 0) return;
        
        const imageFile = event.target.files[0];
        
        document.getElementById('camera-status').innerHTML = '<div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div><span class="text-white text-xs font-medium tracking-wide">กำลังอ่านรูปภาพ...</span>';

        // สั่งปิดกล้อง และ .then() รอจนกว่าจะปิดสนิทจริงๆ ถึงเริ่มสแกนรูป
        stopCamera().then(() => {
            html5QrCode.scanFile(imageFile, true)
                .then(decodedText => {
                    isProcessing = false; // ปลดล็อก
                    onScanSuccess(decodedText);
                })
                .catch(err => {
                    alert("ไม่พบ QR Code ในรูปภาพนี้ หรือรูปภาพไม่ชัดเจน โปรดลองใหม่อีกครั้ง");
                    // รีเซ็ตไฟล์เพื่อให้เลือกรูปเดิมซ้ำได้ 
                    event.target.value = "";
                    // เปิดกล้องกลับมาให้สแกนสดต่อ
                    startCamera(); 
                });
        });
    }

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
            
            // เปิดกล้องเมื่อกลับมาหน้า Scan
            startCamera();
        } else {
            manualView.classList.remove('hidden');
            scanView.classList.add('hidden');
            tabManual.classList.add(...on);
            tabManual.classList.remove(...off);
            tabScan.classList.remove(...on);
            tabScan.classList.add(...off);
            
            // ปิดกล้องเมื่อไปหน้า Manual
            stopCamera();
        }
    }

    /* ── Search ── */
    document.getElementById('area-search').addEventListener('input', applyFilters);

    function applyFilters() {
        const query   = document.getElementById('area-search').value.toLowerCase();
        const items   = document.querySelectorAll('.area-item');
        let visible   = 0;

        items.forEach(item => {
            const textOk = item.dataset.name.toLowerCase().includes(query)
                        || item.dataset.type.toLowerCase().includes(query);
            item.style.display = textOk ? '' : 'none';
            if (textOk) visible++;
        });

        document.getElementById('empty-state').classList.toggle('hidden', visible > 0 || items.length === 0);
    }

    /* ── Select Area ── */
    function selectArea(btn) {
        const item = btn.closest('.area-item');
        const name = item.dataset.name;
        const id   = item.dataset.id;
        const type = item.dataset.type;
        const icon = item.dataset.icon;

        document.querySelectorAll('.area-btn').forEach(b => {
            b.classList.remove('!border-primary', 'bg-primary/5');
        });
        btn.classList.add('!border-primary', 'bg-primary/5');

        document.getElementById('selected-area-value').value = id;

        document.getElementById('selected-area-name').textContent       = name;
        document.getElementById('selected-area-type-display').textContent = 'ประเภท: ' + type;
        document.getElementById('selected-area-icon').textContent       = icon;
        document.getElementById('selected-area').classList.remove('hidden');

        document.getElementById('submit-btn').disabled = false;

        document.getElementById('selected-area').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function clearSelection() {
        document.getElementById('selected-area').classList.add('hidden');
        document.getElementById('selected-area-value').value = '';
        document.getElementById('submit-btn').disabled = true;
        document.querySelectorAll('.area-btn').forEach(b => {
            b.classList.remove('!border-primary', 'bg-primary/5');
        });
    }
</script>

@endsection