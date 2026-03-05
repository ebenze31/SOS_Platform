@extends('layouts.theme')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

<style>
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

@php
    $currentStatus = $operation->status;
@endphp

<div class="bg-background-light dark:bg-background-dark font-sans h-screen w-full overflow-hidden flex flex-col items-center justify-center relative">
    <div class="w-full bg-white dark:bg-surface-dark relative shadow-2xl overflow-hidden flex flex-col" style="height: calc(100% - 71px); margin-top: 71px;">

        <div class="flex-grow w-full relative bg-slate-200 overflow-hidden">
            <div id="officer-map" class="absolute inset-0 w-full h-full"></div>
            
            <button onclick="openPhotoModal()" class="absolute top-4 right-4 z-20 bg-white dark:bg-slate-800 text-primary p-3 rounded-full shadow-lg border border-slate-200 dark:border-slate-700 hover:scale-105 transition-transform flex items-center justify-center">
                <span class="material-icons text-2xl">photo_camera</span>
            </button>
        </div>

        <div class="absolute bottom-[60px] left-0 right-0 z-10 px-3 pb-3 flex flex-col" style="max-width:500px; margin:0 auto;">

            <div id="panel-info" class="tab-panel active">
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark overflow-hidden flex flex-col max-h-[60vh]">
                    <div class="flex items-center px-5 py-4 border-b border-border-light dark:border-border-dark flex items-center justify-between bg-white dark:bg-surface-dark sticky top-0 z-20">
                        <div class="flex items-center space-x-3">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white border-l-4 border-primary pl-2 uppercase tracking-tight">ข้อมูลการขอความช่วยเหลือ</h3>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-surface-dark shadow-sm p-4 space-y-3 overflow-y-auto custom-scrollbar">
                        <div>
                            <div class="text-xs text-slate-400 dark:text-slate-500 mb-0.5">ประเภทเหตุ</div>
                            <div class="font-bold text-slate-900 dark:text-white text-base">{{ $emergency->emergency_type }}</div>
                        </div>
                        <div class="h-px bg-slate-100 dark:bg-slate-700"></div>
                        <div>
                            <div class="text-xs text-slate-400 dark:text-slate-500 mb-0.5">สถานที่</div>
                            <div class="flex items-start gap-2 mt-1">
                                <span class="material-symbols-outlined text-red-500 text-[18px] mt-0.5 shrink-0">location_on</span>
                                <div class="text-sm text-slate-700 dark:text-slate-300 leading-snug">
                                    <span class="font-bold">{{ $emergency->emergency_location ?: 'ไม่ได้ระบุสถานที่ (ดูจุดปักหมุดในแผนที่)' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-px bg-slate-100 dark:bg-slate-700"></div>
                        <div>
                            <div class="text-xs text-slate-400 dark:text-slate-500 mb-0.5">ผู้ป่วย/ผู้แจ้ง</div>
                            <div class="flex justify-between items-center mt-1">
                                <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $emergency->name_reporter }}</div>
                                <a href="tel:{{ str_replace('-', '', $emergency->phone_reporter) }}" class="w-[30px] h-[30px] text-white bg-green-500 hover:bg-green-600 flex items-center justify-center rounded-full transition-colors shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">call</span>
                                </a>
                            </div>
                            @if($emergency->emergency_detail)
                            <div class="bg-blue-50/50 dark:bg-slate-800 p-3 rounded-lg border border-blue-100 dark:border-slate-700 mt-2">
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                                    {{ $emergency->emergency_detail }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div id="panel-action" class="tab-panel">
                <div class="bg-white dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark overflow-hidden flex flex-col max-h-[65vh]">
                    <div class="flex items-center justify-between p-4 border-b border-border-light dark:border-border-dark bg-white dark:bg-surface-dark">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white border-l-4 border-primary pl-2 uppercase tracking-tight">ดำเนินการ</h3>
                        <span id="status-badge" class="text-[10px] font-bold px-2 py-0.5 rounded border 
                            {{ $currentStatus == 'เสร็จสิ้น' ? 'text-green-600 bg-green-50 border-green-200' : 'text-blue-600 bg-blue-50 border-blue-200' }}">
                            {{ $currentStatus }}
                        </span>
                    </div>
                    
                    <div class="overflow-y-auto custom-scrollbar bg-white dark:bg-surface-dark">
                        
                        <div id="step1-section" class="px-5 py-5 {{ in_array($currentStatus, ['ถึงที่เกิดเหตุ', 'เสร็จสิ้น']) ? 'hidden' : '' }}">
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
                                <span class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors" id="btn-arrived-icon">chevron_right</span>
                            </button>
                        </div>

                        <div id="step2-section" class="px-5 pb-5 space-y-4 pt-4 {{ $currentStatus == 'ถึงที่เกิดเหตุ' ? '' : 'hidden' }}">
                            <div class="mt-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1 block">
                                    หมายเหตุผลการดำเนินการ <span class="text-red-400">*</span>
                                </label>
                                <textarea id="action-note" rows="3" oninput="syncNote('action')"
                                    class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                                    placeholder="ระบุหมายเหตุ..."></textarea>
                            </div>

                            <button id="btn-finish" onclick="markFinish()" disabled
                                class="w-full flex items-center justify-between p-4 space-x-2 py-3.5 rounded-xl font-bold text-sm transition-all bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed border-2 border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center" id="finish-icon-bg">
                                        <span class="material-icons text-gray-500 text-xl" id="finish-icon">task_alt</span>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-bold text-gray-900 dark:text-white text-sm" id="finish-text">เสร็จสิ้นภารกิจ</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" id="finish-subtext">ต้องกรอกหมายเหตุก่อน</p>
                                    </div>
                                </div>
                                <span class="material-icons text-gray-500" id="finish-arrow">chevron_right</span>
                            </button>
                        </div>

                        <div id="step-done" class="{{ $currentStatus == 'เสร็จสิ้น' ? 'flex' : 'hidden' }} w-full p-6 bg-white dark:bg-surface-dark items-center justify-center flex-col text-center space-y-3">
                            <span class="material-icons text-green-500 text-[60px]">check_circle</span>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-lg">ภารกิจเสร็จสิ้นสมบูรณ์</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">ระบบได้บันทึกข้อมูลและหมายเหตุเรียบร้อยแล้ว</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div id="panel-route" class="tab-panel">
                <div class="p-4 bg-white dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark overflow-hidden flex flex-col">
                    <div class="border-b border-border-light pb-2">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white border-l-4 border-primary pl-2 uppercase tracking-tight mb-1">ระยะทาง/เวลาถึง (โดยประมาณ)</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center shadow-sm">
                            <div class="text-[10px] uppercase tracking-wider text-slate-400 mb-1">เวลาเดินทาง</div>
                            <div class="text-3xl font-bold font-mono text-slate-900 dark:text-white">
                                <span id="route-time-val">--</span><span class="text-sm font-sans font-normal text-slate-500 ml-1">นาที</span>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center shadow-sm">
                            <div class="text-[10px] uppercase tracking-wider text-slate-400 mb-1">ระยะทาง</div>
                            <div class="text-3xl font-bold font-mono text-slate-900 dark:text-white">
                                <span id="route-dist-val">--</span><span class="text-sm font-sans font-normal text-slate-500 ml-1">กม.</span>
                            </div>
                        </div>
                    </div>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $emergency->emergency_lat }},{{ $emergency->emergency_lng }}" target="_blank" class="mt-4 w-full py-3.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl shadow-lg flex items-center justify-center gap-3 transition-all transform hover:-translate-y-0.5 group">
                        <span class="material-symbols-outlined text-[24px] group-hover:animate-pulse">explore</span>
                        <span class="font-bold text-sm">เปิด Google Maps นำทาง</span>
                    </a>
                </div>
            </div>

        </div>

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
                <span class="text-xs font-medium">นำทาง</span>
            </button>
        </nav>
    </div>
</div>

<div id="photo-modal" class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md overflow-hidden flex flex-col shadow-2xl transform transition-transform scale-95" id="photo-modal-content">
        <div class="flex border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            <button onclick="switchModalTab('view')" id="mtab-view" class="flex-1 py-3.5 text-sm font-bold border-b-2 border-primary text-primary transition-colors">ภาพผู้แจ้งเหตุ</button>
            <button onclick="switchModalTab('upload')" id="mtab-upload" class="flex-1 py-3.5 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">อัปโหลด (เจ้าหน้าที่)</button>
            <button onclick="closePhotoModal()" class="px-4 text-slate-400 hover:text-slate-600 dark:hover:text-white"><span class="material-icons">close</span></button>
        </div>
        
        <div class="p-5 max-h-[70vh] overflow-y-auto">
            <div id="mcontent-view" class="block">
                @if($emergency->emergency_photo)
                    <img src="{{ asset($emergency->emergency_photo) }}" class="w-full rounded-xl object-contain bg-black/5" alt="Emergency Photo">
                @else
                    <div class="py-12 text-center text-slate-400 dark:text-slate-500 flex flex-col items-center justify-center">
                        <span class="material-icons text-5xl mb-2 opacity-50">hide_image</span>
                        <p>ผู้แจ้งไม่ได้แนบรูปภาพมาด้วย</p>
                    </div>
                @endif
            </div>

            <div id="mcontent-upload" class="hidden space-y-4">
                <div class="bg-blue-50 dark:bg-slate-700 p-3 rounded-lg text-xs text-slate-600 dark:text-slate-300 flex items-start gap-2">
                    <span class="material-icons text-primary text-[16px] mt-0.5 shrink-0">info</span>
                    <span>คุณสามารถเพิ่ม/แก้ไขภาพถ่ายการดำเนินการได้ตลอดเวลา แม้จะปิดภารกิจไปแล้ว</span>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">อัปโหลด/ถ่ายภาพหลังการช่วยเหลือ</label>
                    <input type="file" id="photo-upload" accept="image/*" capture="environment" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors cursor-pointer border border-slate-200 dark:border-slate-600 rounded-lg p-2 bg-slate-50 dark:bg-slate-900/50">
                    
                    <div id="current-photo-preview" class="mt-3 {{ empty($operation->photo_succeed) ? 'hidden' : '' }}">
                        <p class="text-xs text-slate-500 mb-1">ภาพที่บันทึกไว้ล่าสุด:</p>
                        <img id="img-preview-tag" src="{{ asset($operation->photo_succeed) }}" class="w-full max-h-32 object-contain rounded-lg border border-slate-200" alt="Success Photo">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">หมายเหตุการดำเนินการ</label>
                    <textarea id="modal-note" rows="3" oninput="syncNote('modal')"
                        class="w-full text-sm border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-primary resize-none"
                        placeholder="กรอกหมายเหตุ (เชื่อมโยงกับหน้าดำเนินการ)...">{{ $operation->remark_by_helper ?? '' }}</textarea>
                </div>
                
                <button onclick="saveModalPhoto()" id="btn-save-modal" class="w-full py-3 bg-primary hover:bg-blue-700 text-white rounded-xl font-bold shadow-md shadow-primary/20 transition-colors mt-4 flex items-center justify-center gap-2">
                    <span class="material-icons text-[18px]" id="modal-btn-icon">save</span>
                    <span id="modal-btn-text">บันทึกรูปและหมายเหตุ</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initOfficerMap" async defer></script>
<script>
    const emergencyId = {{ $emergency->id }};
    const updateApiUrl = `{{ url('/') }}/officer/action/update/${emergencyId}`;
    const uploadPhotoApiUrl = `{{ route('officer.action.upload_photo', $emergency->id) }}`;
    
    // Tab Navigation Logic
    let activeTab = 'info';
    const tabs = ['info', 'action', 'route'];

    function closePanel(tab, callback) {
        const panel = document.getElementById('panel-' + tab);
        const nav = document.getElementById('nav-' + tab);
        const indicator = nav.querySelector('.tab-indicator');

        panel.classList.add('closing');
        nav.classList.remove('text-primary');
        nav.classList.add('text-gray-400', 'dark:text-gray-500');
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
        nav.classList.remove('text-gray-400', 'dark:text-gray-500');
        nav.classList.add('text-primary');
        indicator.classList.remove('hidden');
    }

    function switchTab(tab) {
        // กดแท็บเดิมที่กำลังเปิดอยู่ -> ปิด Card
        if (activeTab === tab) {
            activeTab = null;
            closePanel(tab);
            return;
        }

        const prev = activeTab;
        activeTab = tab;

        // ถ้ามีแท็บอื่นเปิดอยู่ ให้ปิดแท็บเก่าก่อนแล้วค่อยเปิดแท็บใหม่
        if (prev) {
            closePanel(prev, () => openPanel(tab));
        } else {
            // ถ้าไม่มีแท็บไหนเปิดอยู่เลย เปิดแท็บใหม่ขึ้นมา
            openPanel(tab);
        }
    }

    // ===== Photo Modal Logic =====
    function openPhotoModal() {
        const modal = document.getElementById('photo-modal');
        const content = document.getElementById('photo-modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closePhotoModal() {
        const modal = document.getElementById('photo-modal');
        const content = document.getElementById('photo-modal-content');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function switchModalTab(tabId) {
        const viewTab = document.getElementById('mtab-view');
        const uploadTab = document.getElementById('mtab-upload');
        const viewContent = document.getElementById('mcontent-view');
        const uploadContent = document.getElementById('mcontent-upload');

        if(tabId === 'view') {
            viewTab.className = "flex-1 py-3.5 text-sm font-bold border-b-2 border-primary text-primary transition-colors";
            uploadTab.className = "flex-1 py-3.5 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors";
            viewContent.classList.remove('hidden');
            uploadContent.classList.add('hidden');
        } else {
            uploadTab.className = "flex-1 py-3.5 text-sm font-bold border-b-2 border-primary text-primary transition-colors";
            viewTab.className = "flex-1 py-3.5 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors";
            uploadContent.classList.remove('hidden');
            viewContent.classList.add('hidden');
        }
    }

    // บันทึกรูปและหมายเหตุแยกต่างหาก
    async function saveModalPhoto() {
        const btnText = document.getElementById('modal-btn-text');
        const btnIcon = document.getElementById('modal-btn-icon');
        const btn = document.getElementById('btn-save-modal');
        
        btn.disabled = true;
        btnText.innerText = 'กำลังบันทึก...';
        btnIcon.innerText = 'sync';
        btnIcon.classList.add('animate-spin');

        try {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            
            const note = document.getElementById('modal-note').value.trim();
            formData.append('remark', note);
            
            const fileInput = document.getElementById('photo-upload');
            if(fileInput.files.length > 0) {
                formData.append('photo_succeed', fileInput.files[0]);
            }

            const res = await fetch(uploadPhotoApiUrl, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            
            if(data.success) {
                alert('บันทึกข้อมูลเรียบร้อยแล้ว');
                // โชว์รูปที่เพิ่งอัปโหลด
                if(data.photo_url) {
                    document.getElementById('current-photo-preview').classList.remove('hidden');
                    document.getElementById('img-preview-tag').src = data.photo_url;
                }
            } else {
                alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            }
        } catch (e) {
            console.error(e);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        } finally {
            btn.disabled = false;
            btnText.innerText = 'บันทึกรูปและหมายเหตุ';
            btnIcon.innerText = 'save';
            btnIcon.classList.remove('animate-spin');
        }
    }

    // ===== Note Syncing & Action API Call =====
    function syncNote(source) {
        const actionNote = document.getElementById('action-note');
        const modalNote = document.getElementById('modal-note');
        
        if(actionNote) {
            if(source === 'action') {
                modalNote.value = actionNote.value;
            } else {
                actionNote.value = modalNote.value;
            }
            checkFinishButton();
        }
    }

    function checkFinishButton() {
        const note = document.getElementById('action-note').value.trim();
        const btn = document.getElementById('btn-finish');
        if(!btn) return;

        const iconBg = document.getElementById('finish-icon-bg');
        const icon = document.getElementById('finish-icon');
        const text = document.getElementById('finish-text');
        const subtext = document.getElementById('finish-subtext');
        const arrow = document.getElementById('finish-arrow');

        if (note.length > 0) {
            btn.disabled = false;
            btn.className = 'w-full flex items-center justify-between p-4 space-x-2 py-3.5 rounded-xl font-bold text-sm transition-all bg-emerald-50 text-white cursor-pointer border-2 border-emerald-500 group';
            iconBg.className = 'w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center';
            icon.className = 'material-icons text-white text-xl';
            text.className = 'font-bold text-emerald-700 text-sm';
            subtext.innerText = 'กดเพื่อยืนยันการจบภารกิจ';
            subtext.className = 'text-xs text-emerald-600/70';
            arrow.className = 'material-icons text-emerald-500 group-hover:translate-x-1 transition-transform';
        } else {
            btn.disabled = true;
            btn.className = 'w-full flex items-center justify-between p-4 space-x-2 py-3.5 rounded-xl font-bold text-sm transition-all bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed border-2 border-gray-200 dark:border-gray-700';
            iconBg.className = 'w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center';
            icon.className = 'material-icons text-gray-500 text-xl';
            text.className = 'font-bold text-gray-900 dark:text-white text-sm';
            subtext.innerText = 'กรุณากรอกหมายเหตุ';
            subtext.className = 'text-xs text-gray-500 dark:text-gray-400';
            arrow.className = 'material-icons text-gray-500';
        }
    }

    async function updateStatusAPI(statusText) {
        try {
            const formData = new FormData();
            formData.append('status', statusText);
            formData.append('_token', '{{ csrf_token() }}');
            
            if (statusText === 'เสร็จสิ้น') {
                const note = document.getElementById('action-note').value.trim();
                formData.append('remark', note);
            }

            const res = await fetch(updateApiUrl, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            return data.success;
        } catch (e) {
            console.error(e);
            return false;
        }
    }

    // ===== Action steps =====
    async function markArrived() {
        const btnIcon = document.getElementById('btn-arrived-icon');
        btnIcon.innerText = 'sync';
        btnIcon.classList.add('animate-spin');

        const success = await updateStatusAPI('ถึงที่เกิดเหตุ');
        
        if(success) {
            document.getElementById('step1-section').classList.add('hidden');
            document.getElementById('step2-section').classList.remove('hidden');

            const badge = document.getElementById('status-badge');
            badge.textContent = 'ถึงที่เกิดเหตุ';
            badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded border text-orange-600 bg-orange-50 border-orange-200';
        } else {
            alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
            btnIcon.innerText = 'chevron_right';
            btnIcon.classList.remove('animate-spin');
        }
    }

    async function markFinish() {
        const text = document.getElementById('finish-text');
        const icon = document.getElementById('finish-icon');
        text.innerText = 'กำลังบันทึก...';
        icon.innerText = 'sync';
        icon.classList.add('animate-spin');

        const success = await updateStatusAPI('เสร็จสิ้น');

        if(success) {
            document.getElementById('step2-section').classList.add('hidden');
            document.getElementById('step-done').classList.remove('hidden');
            document.getElementById('step-done').classList.add('flex');

            const badge = document.getElementById('status-badge');
            badge.textContent = 'เสร็จสิ้น';
            badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded border text-green-600 bg-green-50 border-green-200';

        } else {
            alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
            text.innerText = 'เสร็จสิ้นภารกิจ';
            icon.innerText = 'task_alt';
            icon.classList.remove('animate-spin');
        }
    }

    // ===== Routes API Distance Calculator =====
    async function getRoutesDistance(originLat, originLng, destLat, destLng) {
        const API_KEY = '{{ env('MAP_API_KEY') }}';
        const url = 'https://routes.googleapis.com/directions/v2:computeRoutes';
        
        const requestBody = {
            "origin": {
                "location": {
                    "latLng": { "latitude": originLat, "longitude": originLng }
                }
            },
            "destination": {
                "location": {
                    "latLng": { "latitude": destLat, "longitude": destLng }
                }
            },
            "travelMode": "DRIVE",
            "routingPreference": "TRAFFIC_AWARE"
        };

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Goog-Api-Key': API_KEY,
                    'X-Goog-FieldMask': 'routes.duration,routes.distanceMeters'
                },
                body: JSON.stringify(requestBody)
            });

            const data = await response.json();
            
            if(data.routes && data.routes.length > 0) {
                const route = data.routes[0];
                const distMeters = route.distanceMeters;
                const durationSeconds = parseInt(route.duration.replace('s', ''));
                
                // แปลงหน่วย
                const distKm = (distMeters / 1000).toFixed(1);
                const durMins = Math.ceil(durationSeconds / 60);

                document.getElementById('route-time-val').innerText = durMins;
                document.getElementById('route-dist-val').innerText = distKm;
            } else {
                console.warn("No routes found.");
            }
        } catch (error) {
            console.error('Error fetching route data:', error);
        }
    }

    // ===== Google Maps Init & Directions Service =====
    function initOfficerMap() {
        const emergencyLat = {{ $emergency->emergency_lat ?? 13.7563 }};
        const emergencyLng = {{ $emergency->emergency_lng ?? 100.5018 }};
        const incidentLoc = { lat: emergencyLat, lng: emergencyLng };

        const map = new google.maps.Map(document.getElementById("officer-map"), {
            zoom: 16,
            center: incidentLoc,
            disableDefaultUI: true,
            zoomControl: true,
            mapTypeId: 'roadmap',
        });

        // สร้าง Custom Marker
        class CustomMarker extends google.maps.OverlayView {
            constructor(position, map, htmlContent) {
                super();
                this.position = position;
                this.htmlContent = htmlContent;
                this.div = null;
                this.setMap(map);
            }
            onAdd() {
                this.div = document.createElement('div');
                this.div.style.position = 'absolute';
                this.div.innerHTML = this.htmlContent;
                const panes = this.getPanes();
                panes.overlayMouseTarget.appendChild(this.div);
            }
            draw() {
                const overlayProjection = this.getProjection();
                const position = overlayProjection.fromLatLngToDivPixel(this.position);
                if (this.div) {
                    this.div.style.left = position.x + 'px';
                    this.div.style.top = position.y + 'px';
                }
            }
            onRemove() {
                if (this.div) {
                    this.div.parentNode.removeChild(this.div);
                    this.div = null;
                }
            }
        }

        // หมุดจุดเกิดเหตุ
        const incidentHtml = `
            <div class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 z-50">
                <div class="relative flex h-8 w-8">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-8 w-8 bg-red-600 border-2 border-white shadow-md items-center justify-center text-white">
                        <span class="material-symbols-outlined text-[16px]">person</span>
                    </span>
                </div>
            </div>
        `;
        new CustomMarker(incidentLoc, map, incidentHtml);

        // เช็คพิกัดปัจจุบันของเจ้าหน้าที่
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const officerLoc = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                // หมุดเจ้าหน้าที่
                const officerHtml = `
                    <div class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 z-40">
                        <div class="relative flex h-8 w-8">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-8 w-8 bg-blue-600 border-2 border-white shadow-md items-center justify-center text-white">
                                <span class="material-symbols-outlined text-[16px]">directions_car</span>
                            </span>
                        </div>
                    </div>
                `;
                new CustomMarker(officerLoc, map, officerHtml);

                // เรียกใช้ DirectionsService
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer({
                    map: map,
                    suppressMarkers: true, // ซ่อนหมุด A, B ดั้งเดิมของ Google
                    polylineOptions: {
                        strokeColor: "#3b82f6", // สีเส้นทาง
                        strokeWeight: 5
                    }
                });

                // คำนวณเส้นทาง
                directionsService.route({
                    origin: officerLoc,
                    destination: incidentLoc,
                    travelMode: 'DRIVING',
                }, function(response, status) {
                    if (status === 'OK') {
                        // วาดเส้นทาง
                        directionsRenderer.setDirections(response);

                        // ดึงระยะทางและเวลา
                        let text_distance = response.routes[0].legs[0].distance.text;
                        let text_duration = response.routes[0].legs[0].duration.text; 

                        let distValue = text_distance.replace(/[^0-9.]/g, ''); 
                        let durValue = text_duration.replace(/[^0-9]/g, '');

                        document.getElementById('route-time-val').innerText = durValue;
                        document.getElementById('route-dist-val').innerText = distValue;
                    } else {
                        console.error('ไม่สามารถคำนวณเส้นทางได้: ' + status);
                    }
                });

                // จัดหน้าจอแผนที่ให้อยู่ตรงกลางครอบคลุมทั้ง 2 หมุด
                const bounds = new google.maps.LatLngBounds();
                bounds.extend(incidentLoc);
                bounds.extend(officerLoc);
                map.fitBounds(bounds, { top: 60, bottom: 60, left: 60, right: 60 });

            }, () => {
                console.warn("ไม่สามารถเข้าถึงพิกัดปัจจุบันได้");
            });
        }
    }

    if(document.getElementById('action-note')) {
        checkFinishButton();
    }
</script>

@endsection