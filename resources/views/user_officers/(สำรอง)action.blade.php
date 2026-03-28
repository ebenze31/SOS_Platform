@extends('layouts.theme_user')

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
                                <textarea id="action-note" rows="3" oninput="checkFinishButton();"
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
                            
                            <button type="button" onclick="openPhotoModal('upload', 'success')" class="mt-4 w-full py-3 bg-blue-50 hover:bg-blue-100 text-primary border border-blue-200 font-bold rounded-xl flex items-center justify-center gap-2 transition-colors">
                                <span class="material-icons">add_a_photo</span> เพิ่มภาพถ่ายเสร็จสิ้น
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div id="panel-route" class="tab-panel">
                <div class="p-4 bg-white dark:bg-surface-dark rounded-xl shadow-xl border border-border-light dark:border-border-dark overflow-hidden flex flex-col">
                    
                    <!-- ข้อความ Loading สำหรับรอ Command (ถ้ามี) -->
                    <div id="route-loading-status" class="flex items-center gap-2 text-orange-500 bg-orange-50 p-2 rounded-lg mb-3">
                        <span class="material-icons animate-spin text-sm">sync</span>
                        <span class="text-xs font-medium">กำลังรอการจัดเส้นทางจากศูนย์สั่งการ...</span>
                    </div>

                    <div class="border-b border-border-light pb-2">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white border-l-4 border-primary pl-2 uppercase tracking-tight mb-1">ระยะทาง/เวลาถึง (โดยประมาณ)</h3>
                        <!-- แสดงเวลาออกเดินทาง -->
                        <p id="route-calc-info" class="text-[12px] text-slate-500 pl-3">คำนวณจากจุดเริ่มต้นเมื่อเวลาออกเดินทาง: --:-- น.</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center shadow-sm">
                            <!-- "เวลาถึงที่หมาย" -->
                            <div class="text-[10px] uppercase tracking-wider text-slate-400 mb-1">เวลาถึงที่หมาย</div>
                            <div class="text-3xl font-bold font-mono text-slate-900 dark:text-white">
                                <span id="route-time-val">--:--</span><span class="text-sm font-sans font-normal text-slate-500 ml-1">น.</span>
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

<!-- Photo Modal -->
<div id="photo-modal" class="hidden fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <!-- กำหนดความสูง 75vh และเป็น flex-col เพื่อให้หัวคงที่และส่วนกลาง scroll ได้ -->
    <div class="bg-slate-50 dark:bg-slate-800 rounded-2xl w-full max-w-md h-[75vh] flex flex-col shadow-2xl transform transition-transform scale-95 overflow-hidden" id="photo-modal-content">
        
        <!-- Header & Tabs (คงที่) -->
        <div class="flex shrink-0 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900">
            <button onclick="switchModalTab('view')" id="mtab-view" class="flex-1 py-4 text-sm font-bold border-b-2 border-primary text-primary transition-colors">ภาพผู้แจ้งเหตุ</button>
            <button onclick="switchModalTab('upload')" id="mtab-upload" class="flex-1 py-4 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">ภาพเจ้าหน้าที่</button>
            <button onclick="closePhotoModal()" class="px-4 text-slate-400 hover:text-slate-600 dark:hover:text-white flex items-center justify-center bg-slate-50 hover:bg-slate-100 dark:bg-slate-800">
                <span class="material-icons">close</span>
            </button>
        </div>
        
        <!-- Content Area (เลื่อนขึ้นลงได้) -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-4">
            
            <!-- Tab 1: รูปผู้แจ้ง -->
            <div id="mcontent-view" class="block h-full">
                @if($emergency->emergency_photo)
                    <img src="{{ asset($emergency->emergency_photo) }}" class="w-full h-auto rounded-xl object-contain bg-black/5" alt="Emergency Photo">
                @else
                    <div class="h-full flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 pb-10">
                        <span class="material-icons text-5xl mb-2 opacity-50">hide_image</span>
                        <p>ผู้แจ้งไม่ได้แนบรูปภาพมาด้วย</p>
                    </div>
                @endif
            </div>

            <!-- Tab 2: รูปเจ้าหน้าที่ (แบ่งเป็น 2 ส่วน) -->
            <div id="mcontent-upload" class="hidden space-y-6 pb-6">
                
                <!-- ส่วนที่ 1: ภาพที่เกิดเหตุ -->
                <div id="section-scene" class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-all duration-300">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-icons text-orange-500">warning</span>
                        <h4 class="font-bold text-slate-800 dark:text-white">1. ภาพที่เกิดเหตุ</h4>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <!-- ปุ่มถ่ายภาพ (เรียกกล้อง) -->
                        <button type="button" onclick="document.getElementById('input-scene-camera').click()" class="py-2.5 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center gap-2 text-sm text-slate-700 font-medium hover:bg-slate-100 shadow-sm">
                            <span class="material-icons text-[18px]">photo_camera</span> ถ่ายภาพ
                        </button>
                        <!-- ปุ่มเลือกภาพ (เรียก Gallery) -->
                        <button type="button" onclick="document.getElementById('input-scene-gallery').click()" class="py-2.5 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center gap-2 text-sm text-slate-700 font-medium hover:bg-slate-100 shadow-sm">
                            <span class="material-icons text-[18px]">image</span> เลือกรูป
                        </button>
                    </div>

                    <!-- Input ซ่อน สำหรับแยก 2 รูปแบบการทำงาน -->
                    <input type="file" id="input-scene-camera" accept="image/*" capture="environment" class="hidden" onchange="handleModalFileSelect(this, 'scene')">
                    <input type="file" id="input-scene-gallery" accept="image/*" class="hidden" onchange="handleModalFileSelect(this, 'scene')">

                    <!-- Preview ภาพที่เกิดเหตุ -->
                    <div id="preview-box-scene" class="{{ empty($operation->photo_by_officer) ? 'hidden' : 'block' }} mb-3">
                        <img id="img-scene" src="{{ $operation->photo_by_officer ? url('/storage/' . $operation->photo_by_officer) : '' }}" class="w-full max-h-48 object-cover rounded-lg border border-slate-200" alt="Scene Photo">
                    </div>

                    <label class="text-xs font-semibold text-slate-500 mb-1 block">หมายเหตุภาพที่เกิดเหตุ:</label>
                    <textarea id="remark-scene" rows="2" class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-slate-50 focus:ring-1 focus:ring-primary resize-none mb-3" placeholder="ระบุหมายเหตุ...">{{ $operation->remark_photo_by_officer ?? '' }}</textarea>

                    <button type="button" id="btn-save-scene" onclick="saveSpecificPhoto('scene')" class="w-full py-2.5 bg-slate-800 text-white rounded-lg text-sm font-bold flex items-center justify-center gap-2 hover:bg-slate-700 transition-colors shadow-sm">
                        <span class="material-icons text-[18px]" id="icon-save-scene">save</span> บันทึกภาพที่เกิดเหตุ
                    </button>
                </div>

                <!-- ส่วนที่ 2: ภาพเสร็จสิ้นภารกิจ -->
                <div id="section-success" class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-all duration-300">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-icons text-green-500">task_alt</span>
                        <h4 class="font-bold text-slate-800 dark:text-white">2. ภาพเสร็จสิ้นภารกิจ</h4>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <!-- ปุ่มถ่ายภาพ (เรียกกล้อง) -->
                        <button type="button" onclick="document.getElementById('input-success-camera').click()" class="py-2.5 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-center gap-2 text-sm text-blue-700 font-medium hover:bg-blue-100 shadow-sm">
                            <span class="material-icons text-[18px]">photo_camera</span> ถ่ายภาพ
                        </button>
                        <!-- ปุ่มเลือกภาพ (เรียก Gallery) -->
                        <button type="button" onclick="document.getElementById('input-success-gallery').click()" class="py-2.5 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-center gap-2 text-sm text-blue-700 font-medium hover:bg-blue-100 shadow-sm">
                            <span class="material-icons text-[18px]">image</span> เลือกรูป
                        </button>
                    </div>

                    <!-- Input ซ่อน สำหรับแยก 2 รูปแบบการทำงาน -->
                    <input type="file" id="input-success-camera" accept="image/*" capture="environment" class="hidden" onchange="handleModalFileSelect(this, 'success')">
                    <input type="file" id="input-success-gallery" accept="image/*" class="hidden" onchange="handleModalFileSelect(this, 'success')">

                    <!-- Preview ภาพเสร็จสิ้น -->
                    <div id="preview-box-success" class="{{ empty($operation->photo_succeed) ? 'hidden' : 'block' }} mb-3">
                        <img id="img-success" src="{{ $operation->photo_succeed ? url('/storage/' . $operation->photo_succeed) : '' }}" class="w-full max-h-48 object-cover rounded-lg border border-slate-200" alt="Success Photo">
                    </div>

                    <label class="text-xs font-semibold text-slate-500 mb-1 block">หมายเหตุภาพเสร็จสิ้น:</label>
                    <textarea id="remark-success" rows="2" class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-slate-50 focus:ring-1 focus:ring-primary resize-none mb-3" placeholder="ระบุหมายเหตุ...">{{ $operation->remark_by_helper ?? '' }}</textarea>

                    <button type="button" id="btn-save-success" onclick="saveSpecificPhoto('success')" class="w-full py-2.5 bg-primary text-white rounded-lg text-sm font-bold flex items-center justify-center gap-2 hover:bg-blue-600 transition-colors shadow-sm">
                        <span class="material-icons text-[18px]" id="icon-save-success">save</span> บันทึกภาพเสร็จสิ้น
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- เพิ่ม libraries=geometry สำหรับการ Decode Polyline -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initOfficerMap&libraries=geometry" async defer></script>
<script>
    const emergencyId = {{ $emergency->id }};
    const updateApiUrl = `{{ url('/') }}/officer/action/update/${emergencyId}`;
    const updateApiUrl_success = `{{ url('/') }}/officer/update-status-case-success/${emergencyId}`;
    const uploadPhotoApiUrl = `{{ route('officer.action.upload_photo', $emergency->id) }}`;
    const syncLocationApiUrl = `{{ url('/') }}/officer/sync-operation`;
    
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
        if (activeTab === tab) {
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

    // ==========================================
    // ===== ระบบจัดการ Photo Modal แบบใหม่ =====
    // ==========================================
    
    // เก็บสถานะไฟล์ที่ถูกเลือก
    let selectedFiles = { scene: null, success: null };

    // เปิด Modal พร้อมรองรับการเด้งไปที่แท็บและหัวข้อที่ต้องการ
    function openPhotoModal(tabId = 'view', focusSection = null) {
        const modal = document.getElementById('photo-modal');
        const content = document.getElementById('photo-modal-content');
        
        // ล็อกไม่ให้หน้าจอหลัก Scroll ได้ตอนเปิด Modal
        document.body.style.overflow = 'hidden';
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);

        switchModalTab(tabId);

        // ถ้ามีการระบุให้เลื่อนไปที่หัวข้อใดหัวข้อหนึ่ง (เช่น ตอนกดปุ่มเพิ่มภาพเสร็จสิ้น)
        if (tabId === 'upload' && focusSection) {
            setTimeout(() => {
                const sectionEl = document.getElementById('section-' + focusSection);
                if (sectionEl) {
                    sectionEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // สร้าง Effect กระพริบขอบเพื่อให้รู้ว่าอยู่ตรงนี้
                    sectionEl.classList.add('ring-2', 'ring-primary', 'shadow-lg');
                    setTimeout(() => {
                        sectionEl.classList.remove('ring-2', 'ring-primary', 'shadow-lg');
                    }, 1500);
                }
            }, 300); // รอให้ Modal เด้งเสร็จก่อนค่อยเลื่อน
        }
    }

    function closePhotoModal() {
        const modal = document.getElementById('photo-modal');
        const content = document.getElementById('photo-modal-content');
        
        // คืนค่าให้หน้าจอหลักกลับมา Scroll ได้
        document.body.style.overflow = '';
        
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
            viewTab.className = "flex-1 py-4 text-sm font-bold border-b-2 border-primary text-primary transition-colors";
            uploadTab.className = "flex-1 py-4 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors";
            viewContent.classList.remove('hidden');
            uploadContent.classList.add('hidden');
        } else {
            uploadTab.className = "flex-1 py-4 text-sm font-bold border-b-2 border-primary text-primary transition-colors";
            viewTab.className = "flex-1 py-4 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors";
            uploadContent.classList.remove('hidden');
            viewContent.classList.add('hidden');
        }
    }

    // ฟังก์ชันจัดการตอนเลือกไฟล์ (พรีวิวรูปทันที)
    function handleModalFileSelect(inputElement, type) {
        if (!inputElement.files || inputElement.files.length === 0) return;
        
        const file = inputElement.files[0];
        selectedFiles[type] = file;

        // แสดงภาพพรีวิว
        const imgTag = document.getElementById('img-' + type);
        const previewBox = document.getElementById('preview-box-' + type);
        
        imgTag.src = URL.createObjectURL(file);
        previewBox.classList.remove('hidden');
        previewBox.classList.add('block');
        
        // เคลียร์ค่า input เพื่อให้สามารถเลือกรูปเดิมซ้ำได้ (กรณีเผลอกดยกเลิก)
        inputElement.value = '';
    }

    // ฟังก์ชันบันทึกรูปเข้าฐานข้อมูล
    async function saveSpecificPhoto(type) {
        const btn = document.getElementById('btn-save-' + type);
        const icon = document.getElementById('icon-save-' + type);
        const remarkInput = document.getElementById('remark-' + type);
        
        btn.disabled = true;
        const originalText = btn.innerText;
        btn.innerHTML = `<span class="material-icons animate-spin text-[18px]">sync</span> กำลังบันทึก...`;

        try {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            
            // แนบไฟล์ (ถ้ามีอัปโหลดใหม่)
            if (selectedFiles[type]) {
                const fileFieldName = type === 'scene' ? 'photo_by_officer' : 'photo_succeed';
                formData.append(fileFieldName, selectedFiles[type]);
            }

            // แนบหมายเหตุ
            const remarkFieldName = type === 'scene' ? 'remark_photo_by_officer' : 'remark_by_helper';
            formData.append(remarkFieldName, remarkInput.value.trim());

            // ยิง API
            const res = await fetch(uploadPhotoApiUrl, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            
            if(data.success) {
                // เคลียร์ไฟล์ที่เลือกไว้ เพราะอัปโหลดเสร็จแล้ว (จะกลายเป็นรูปใน DB แทน)
                selectedFiles[type] = null;
                
                // อัปเดตรูปจาก Server (ถ้า Server ส่ง URL กลับมา)
                if (type === 'scene' && data.photo_by_officer_url) {
                    document.getElementById('img-scene').src = "{{ url('/storage') }}/" + data.photo_by_officer_url;
                } else if (type === 'success' && data.photo_succeed_url) {
                    document.getElementById('img-success').src = "{{ url('/storage') }}/" + data.photo_succeed_url;
                }

                alert(`บันทึกข้อมูล${type === 'scene' ? 'ภาพที่เกิดเหตุ' : 'ภาพเสร็จสิ้น'} เรียบร้อยแล้ว`);
            } else {
                alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' + (data.message || ''));
            }
        } catch (e) {
            console.error(e);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<span class="material-icons text-[18px]">${type === 'scene' ? 'save' : 'save'}</span> ${type === 'scene' ? 'บันทึกภาพที่เกิดเหตุ' : 'บันทึกภาพเสร็จสิ้น'}`;
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

        let url ;
        try {
            const formData = new FormData();
            formData.append('status', statusText);
            formData.append('_token', '{{ csrf_token() }}');
            
            if (statusText === 'เสร็จสิ้น') {
                const note = document.getElementById('action-note').value.trim();
                formData.append('remark', note);
                url = updateApiUrl_success ;
            }
            else{
                url = updateApiUrl ;
            }

            const res = await fetch(url, {
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

    // ====================
    // ===== ระบบแผนที่ =====
    // ====================
    let map = null;
    let officerMarker = null;
    let startMarker = null;
    let currentPolylineStr = null;
    let polylinePath = null;
    let locationInterval = null;
    let CustomMarker; 
    
    const incidentLoc = { 
        lat: {{ $emergency->emergency_lat ?? 13.7563 }}, 
        lng: {{ $emergency->emergency_lng ?? 100.5018 }} 
    };

    // ฟังก์ชันวาดเส้นทางจาก Polyline
    function drawRouteFromPolyline(encodedString) {
        if (polylinePath) {
            polylinePath.setMap(null); 
        }

        const decodedPath = google.maps.geometry.encoding.decodePath(encodedString);
        polylinePath = new google.maps.Polyline({
            path: decodedPath,
            geodesic: true,
            strokeColor: "#3b82f6",
            strokeOpacity: 0.7,
            strokeWeight: 5,
            map: map
        });

        const bounds = new google.maps.LatLngBounds();
        decodedPath.forEach(point => bounds.extend(point));
        map.fitBounds(bounds, { top: 60, bottom: 60, left: 60, right: 60 });
    }

    // ฟังก์ชันยิงพิกัด + รับค่า Log Command
    async function trackAndSync() {
        if (!navigator.geolocation) return;

        navigator.geolocation.getCurrentPosition(async (position) => {
            const officerLoc = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            // สร้างหรือขยับหมุดรถเจ้าหน้าที่
            if (!officerMarker && CustomMarker) {
                const officerHtml = `
                    <div class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 z-40 transition-all duration-300">
                        <div class="relative flex h-8 w-8">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-8 w-8 bg-blue-600 border-2 border-white shadow-md items-center justify-center text-white">
                                <span class="material-symbols-outlined text-[16px]">directions_car</span>
                            </span>
                        </div>
                    </div>
                `;
                officerMarker = new CustomMarker(officerLoc, map, officerHtml);
            } else if (officerMarker) {
                officerMarker.setPosition(officerLoc);
            }

            // อัปเดตตำแหน่ง + ดึง log_command
            try {
                const response = await fetch(syncLocationApiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        lat: officerLoc.lat, 
                        lng: officerLoc.lng,
                        emergency_id: emergencyId
                    })
                });

                const data = await response.json();

                if (data.success && data.log_command) {
                    let logs = data.log_command;
                    if (typeof logs === 'string') {
                        logs = JSON.parse(logs);
                    }

                    const routeLog = [...logs].reverse().find(log => log.status === 'go_to_help' && log.polyline);

                    if (routeLog) {
                        document.getElementById('route-loading-status')?.classList.add('hidden');
                        
                        // คำนวณเวลาถึงที่หมาย (ETA)
                        if (routeLog.time_go_to_help && routeLog.duration_value !== undefined) {
                            // แปลงเวลาออกเดินทางให้อยู่ในรูปแบบ Date Object
                            const startDate = new Date(routeLog.time_go_to_help);
                            
                            // จัดฟอร์แมตเวลาออกเดินทาง (HH:mm)
                            const startH = String(startDate.getHours()).padStart(2, '0');
                            const startM = String(startDate.getMinutes()).padStart(2, '0');
                            const startTimeStr = `${startH}:${startM}`;
                            
                            // นำเวลาออกเดินทาง + ระยะเวลาขับรถ (วินาที -> มิลลิวินาที)
                            const etaDate = new Date(startDate.getTime() + (routeLog.duration_value * 1000));
                            
                            // จัดฟอร์แมตเวลาถึงเป้าหมาย (HH:mm)
                            const etaH = String(etaDate.getHours()).padStart(2, '0');
                            const etaM = String(etaDate.getMinutes()).padStart(2, '0');
                            const etaTimeStr = `${etaH}:${etaM}`;

                            // แสดงผลบนหน้าจอ
                            document.getElementById('route-calc-info').innerHTML = `คำนวณจากจุดเริ่มต้นเมื่อ <span class="text-primary font-bold">เวลาออกเดินทาง: ${startTimeStr} น.</span>`;
                            document.getElementById('route-time-val').innerText = etaTimeStr;
                        }

                        // แสดงระยะทาง
                        document.getElementById('route-dist-val').innerText = routeLog.distance_text.replace(/[^0-9.]/g, '');

                        // สร้างหมุดจุดเริ่มต้น (ถ้ายังไม่มีและมีพิกัดมาให้)
                        if (!startMarker && routeLog.start_lat && routeLog.start_lng) {
                            const startLoc = {
                                lat: parseFloat(routeLog.start_lat),
                                lng: parseFloat(routeLog.start_lng)
                            };
                            const startHtml = `
                                <div class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 z-30">
                                    <span class="relative inline-flex rounded-full h-8 w-8 bg-slate-800 border-[2px] border-white shadow-md items-center justify-center text-white">
                                        <span class="material-symbols-outlined text-[16px]">flag</span>
                                    </span>
                                    <div class="mt-1 bg-slate-800 text-white text-[9px] font-bold px-2 py-0.5 rounded shadow-sm whitespace-nowrap">
                                        จุดเริ่มต้น
                                    </div>
                                </div>`;
                            startMarker = new CustomMarker(startLoc, map, startHtml);
                        }

                        // วาดเส้นทางใหม่ถ้ามีการอัปเดต
                        if (currentPolylineStr !== routeLog.polyline) {
                            currentPolylineStr = routeLog.polyline;
                            drawRouteFromPolyline(routeLog.polyline);
                        }
                    } else {
                        // กรณียังไม่มี log_command หรือศูนย์สั่งการยังไม่จัดการให้
                        document.getElementById('route-calc-info').innerHTML = 'คำนวณจากจุดเริ่มต้นเมื่อ <span class="text-primary font-bold">เวลาออกเดินทาง: --:-- น.</span>';
                        document.getElementById('route-time-val').innerText = '--:--';
                        document.getElementById('route-dist-val').innerText = '--';
                    }
                }
            } catch (e) {
                console.warn("ไม่สามารถอัปเดตและดึงข้อมูลเส้นทางได้", e);
            }

        }, (err) => console.warn(err), { enableHighAccuracy: true });
    }

    function initOfficerMap() {
        CustomMarker = class extends google.maps.OverlayView {
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
                if(!overlayProjection) return;
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
            setPosition(newLoc) {
                this.position = new google.maps.LatLng(newLoc.lat, newLoc.lng);
                this.draw();
            }
        };

        map = new google.maps.Map(document.getElementById("officer-map"), {
            zoom: 15,
            center: incidentLoc,
            disableDefaultUI: true,
            zoomControl: true,
            mapTypeId: 'roadmap',
        });

        // สร้างหมุดจุดเกิดเหตุ (สีแดง)
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

        trackAndSync();
        locationInterval = setInterval(trackAndSync, 8000);
    }

    if(document.getElementById('action-note')) {
        checkFinishButton();
    }
</script>

@endsection