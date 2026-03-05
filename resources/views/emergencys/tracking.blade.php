@extends('layouts.theme')

@section('content')

<style>
    .route-line {
        stroke-dasharray: 1000;
        stroke-dashoffset: 1000;
        animation: drawLine 2s ease-in-out forwards;
    }
    @keyframes drawLine {
        to { stroke-dashoffset: 0; }
    }
    /* แอนิเมชันสำหรับสถานะปัจจุบัน */
    @keyframes floatMove {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }
    .active-icon-move {
        animation: floatMove 2s ease-in-out infinite;
        box-shadow: 0 4px 12px rgba(13, 89, 165, 0.3);
    }
    body, html {
        width: 100% !important;
        height: 100% !important;
    }
</style>

@php
    $dbStatus = $operation->status ?? 'รับแจ้งเหตุ';
    $currentState = 1;
    
    if ($dbStatus == 'รับแจ้งเหตุ') {
        $currentState = 1; 
    } elseif ($dbStatus == 'สั่งการ') {
        $currentState = 2; 
    } elseif ($dbStatus == 'กำลังไปช่วยเหลือ') {
        $currentState = 3; 
    } elseif ($dbStatus == 'ถึงที่เกิดเหตุ') {
        $currentState = 4; 
    } elseif ($dbStatus == 'เสร็จสิ้น') {
        $currentState = 5; 
    }

    // ดึงข้อมูลเจ้าหน้าที่ที่รับงานนี้จาก user_officers_id เท่านั้น
    $assignedOfficer = null;
    if(!empty($operation->user_officers_id)) {
        $assignedOfficer = \App\Models\User_officer::find($operation->user_officers_id);
    }

    // เตรียมเวลาของแต่ละสถานะ (แปลงเป็น xx:xx น.)
    $time_create = $operation->time_create_sos ? \Carbon\Carbon::parse($operation->time_create_sos)->format('H:i') . ' น.' : '';
    $time_go = $operation->time_go_to_help ? \Carbon\Carbon::parse($operation->time_go_to_help)->format('H:i') . ' น.' : '';
    $time_arrive = $operation->time_to_the_scene ? \Carbon\Carbon::parse($operation->time_to_the_scene)->format('H:i') . ' น.' : '';
    $time_success = $operation->time_sos_success ? \Carbon\Carbon::parse($operation->time_sos_success)->format('H:i') . ' น.' : '';
@endphp

<div class="w-full md:h-[calc(100%-71.75px)] relative flex justify-center sm:items-center p-3 bg-slate-50 dark:bg-slate-900" style="height: calc(100vh - 71.75px); margin-top:71.75px; overflow: auto;">

    <div class="relative w-full max-w-[500px] bg-white dark:bg-[#1a2632] rounded-xl shadow-xl flex flex-col overflow-auto ring-1 ring-black/5 dark:ring-white/10 h-full max-h-[850px]">
        
        <header class="px-8 py-5 border-b border-slate-100 dark:border-slate-700/50 flex flex-col justify-center bg-white dark:bg-[#1a2632] shrink-0">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">สถานะการช่วยเหลือ</h1>
            
            {{-- แสดงเวลารวมเมื่อสถานะเสร็จสิ้น --}}
            @if($currentState == 5 && !empty($operation->time_sum_sos))
                <p id="sumTimeDisplay" class="text-[13px] text-emerald-600 dark:text-emerald-400 mt-1 font-bold">
                    การช่วยเหลือเสร็จสิ้น ใช้เวลารวม {{ $operation->time_sum_sos }}
                </p>
            @endif
        </header>

        <div class="flex flex-col bg-white dark:bg-[#1a2632] flex-1 overflow-y-auto">
            
            <section class="px-8 py-6" id="statusContainer">
                </section>

            <section id="actionSection" class="px-8 pb-2 bg-white dark:bg-[#1a2632] hidden">
                
                <div id="activeOfficerView" class="flex items-center justify-between bg-blue-50/50 dark:bg-slate-800/50 rounded-lg p-3 border border-blue-100 dark:border-slate-700 hidden mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 text-[20px]">support_agent</span>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="compactOfficerName">
                            {{ $assignedOfficer->name_officer ?? '' }}
                        </h4>
                    </div>
                    <a href="tel:{{ ($assignedOfficer && $assignedOfficer->user) ? str_replace('-', '', $assignedOfficer->user->phone) : '#' }}" id="compactCallBtn" class="flex items-center gap-1.5 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-[11px] font-bold rounded-md shadow-sm transition-colors">
                        <span class="material-symbols-outlined text-[14px]">call</span>
                        โทร
                    </a>
                </div>

                <div id="finishedActionView" class="hidden mb-4">
                    <a href="{{ url('/sos/rate/'.$emergency->id) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-lg shadow-sm shadow-amber-500/20 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">star_rate</span>
                        ประเมินการบริการ
                    </a>
                </div>

            </section>

            <section class="px-8 pb-6">
                <div class="relative w-full h-48 sm:h-64 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden bg-slate-100">
                    <div id="tracking-map" class="absolute inset-0 w-full h-full"></div>
                </div>
            </section>

            <section class="px-8 pb-8">
                <div class="border-t border-slate-100 dark:border-slate-700/50 pt-6">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[22px]">description</span>
                        ข้อมูลการแจ้งเหตุ
                    </h3>

                    <div class="bg-slate-50 dark:bg-slate-800/30 rounded-xl p-4 sm:p-5 border border-slate-100 dark:border-slate-700/50 space-y-4 shadow-sm">
                        
                        <div>
                            <div class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">ประเภทเหตุ</div>
                            <div class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $emergency->emergency_type }}</div>
                        </div>

                        <div>
                            <div class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">รายละเอียด</div>
                            <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $emergency->emergency_detail ?: '-' }}</div>
                        </div>

                        <div>
                            <div class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">สถานที่เกิดเหตุ</div>
                            <div class="text-sm text-slate-700 dark:text-slate-300 flex items-start gap-1.5 mt-1">
                                <span class="material-symbols-outlined text-[16px] text-red-500 shrink-0 mt-0.5">location_on</span>
                                <span>{{ $emergency->emergency_location ?: 'ไม่ระบุสถานที่' }}</span>
                            </div>
                        </div>

                        @if(!empty($emergency->emergency_photo))
                        <div>
                            <div class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-2">ภาพถ่ายที่แนบมา</div>
                            <div class="relative w-full h-48 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-200 dark:bg-slate-800">
                                <img src="{{ asset($emergency->emergency_photo) }}" alt="Emergency Photo" class="w-full h-full object-cover">
                            </div>
                        </div>
                        @endif

                    </div>

                    @if(!empty($operation->remark_by_helper) || !empty($operation->photo_succeed))
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mt-8 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-500 text-[22px]">verified</span>
                        บันทึกการช่วยเหลือ
                    </h3>
                    
                    <div class="bg-emerald-50/50 dark:bg-emerald-900/10 rounded-xl p-4 sm:p-5 border border-emerald-100 dark:border-emerald-800/30 space-y-4 shadow-sm">
                        @if(!empty($operation->remark_by_helper))
                        <div>
                            <div class="text-[11px] text-emerald-600 dark:text-emerald-500 font-semibold uppercase tracking-wider mb-1">ผลการดำเนินงาน</div>
                            <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $operation->remark_by_helper }}</div>
                        </div>
                        @endif

                        @if(!empty($operation->photo_succeed))
                        <div>
                            <div class="text-[11px] text-emerald-600 dark:text-emerald-500 font-semibold uppercase tracking-wider mb-2">ภาพถ่ายหลังช่วยเหลือ</div>
                            <div class="relative w-full h-48 rounded-lg overflow-hidden border border-emerald-200 dark:border-emerald-800/50 bg-slate-200 dark:bg-slate-800">
                                <img src="{{ asset($operation->photo_succeed) }}" alt="Success Photo" class="w-full h-full object-cover">
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                </div>
            </section>

        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initTrackingMap" async defer></script>
<script>
    let currentStatus = {{ $currentState }}; 
    const emergencyId = {{ $emergency->id }};
    let officerMarker = null; // ตัวแปรเก็บหมุดเจ้าหน้าที่เพื่อจัดการลบทีหลัง

    // กำหนด Config ของการแสดงผล Action Section
    const statusConfigs = {
        1: { active: 1, showActionSection: false, isFinished: false },
        2: { active: 2, showActionSection: false, isFinished: false },
        3: { active: 3, showActionSection: true,  isFinished: false },
        4: { active: 4, showActionSection: true,  isFinished: false },
        5: { active: 5, showActionSection: true,  isFinished: true  }
    };

    const statusSteps = [
        {
            id: 1,
            title: 'ส่งคำขอเรียบร้อย',
            description: 'ระบบได้รับข้อมูลขอความช่วยเหลือของคุณแล้ว',
            icon: 'check',
            time: '{{ $time_create }}',
            completedStates: [1, 2, 3, 4, 5]
        },
        {
            id: 2,
            title: 'ค้นหาเจ้าหน้าที่',
            description: 'ระบบกำลังค้นหาและมอบหมายงานให้เจ้าหน้าที่',
            icon: 'sync',
            time: '',
            activeStates: [1, 2],
            completedStates: [3, 4, 5]
        },
        {
            id: 3,
            title: 'เจ้าหน้าที่กำลังไปช่วยเหลือ',
            description: 'เจ้าหน้าที่รับเรื่องและกำลังเดินทางไปช่วยเหลือ',
            icon: 'badge',
            time: '{{ $time_go }}',
            activeStates: [3],
            completedStates: [4, 5]
        },
        {
            id: 4,
            title: 'เจ้าหน้าที่มาถึงแล้ว',
            description: 'เจ้าหน้าที่เดินทางมาถึงจุดเกิดเหตุ',
            icon: 'shield_person',
            time: '{{ $time_arrive }}',
            activeStates: [4],
            completedStates: [5]
        },
        {
            id: 5,
            title: 'การช่วยเหลือเสร็จสิ้น',
            description: 'ภารกิจช่วยเหลือเสร็จสมบูรณ์',
            icon: 'flag',
            time: '{{ $time_success }}',
            activeStates: [5],
            completedStates: []
        }
    ];

    function renderStatus() {
        const config = statusConfigs[currentStatus];
        const container = document.getElementById('statusContainer');
        container.innerHTML = '';

        statusSteps.forEach((step, index) => {
            const isCompleted = step.completedStates?.includes(currentStatus);
            const isActive = step.activeStates?.includes(currentStatus);
            const isLast = index === statusSteps.length - 1;

            let bgColor = 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-600';
            let iconAnimation = '';
            let extraClass = '';

            if (isCompleted) {
                bgColor = 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400';
            } else if (isActive) {
                bgColor = 'bg-primary text-white';
                extraClass = 'active-icon-move ring-4 ring-primary/20';
                if (step.icon === 'sync') iconAnimation = 'animate-spin';
            }

            const titleColor = isCompleted ? 'text-slate-900 dark:text-white' : isActive ? 'text-primary dark:text-blue-400' : 'text-slate-400 dark:text-slate-500';
            const descColor = isCompleted || isActive ? 'text-slate-500 dark:text-slate-400' : 'text-slate-400 dark:text-slate-600';
            const lineColor = isCompleted ? 'bg-green-200 dark:bg-green-900/40' : 'bg-slate-200 dark:bg-slate-700';

            const stepHTML = `
                <div class="flex gap-4 group mb-1.5">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-md ${bgColor} ${extraClass} flex items-center justify-center shrink-0 z-10 transition-all duration-300">
                            <span class="material-symbols-outlined text-xl ${iconAnimation}" style="animation-duration: 3s;">${step.icon}</span>
                        </div>
                        ${!isLast ? `<div class="w-0.5 ${lineColor} h-full min-h-[40px] -my-2 transition-colors duration-500"></div>` : ''}
                    </div>
                    <div class="${!isLast ? 'pb-5' : ''} ">
                        <h3 class="text-sm font-bold ${titleColor} transition-colors duration-300">${step.title}</h3>
                        <p class="text-[11px] ${descColor} mt-0.5 leading-tight transition-colors duration-300">${step.description}</p>
                        ${step.time && (isCompleted || isActive) ? `
                        <span class="inline-block mt-1.5 text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-500">${step.time}</span>
                        ` : ''}
                    </div>
                </div>
            `;
            container.innerHTML += stepHTML;
        });

        // ควบคุมการแสดงผลส่วนของ Action Section
        const actionSection = document.getElementById('actionSection');
        const activeOfficerView = document.getElementById('activeOfficerView');
        const finishedActionView = document.getElementById('finishedActionView');
        const officerNameEl = document.getElementById('compactOfficerName');

        if (config.showActionSection && officerNameEl.innerText.trim() !== '') {
            actionSection.classList.remove('hidden');
            
            if (config.isFinished) {
                activeOfficerView.classList.add('hidden');
                activeOfficerView.classList.remove('flex');
                finishedActionView.classList.remove('hidden');
            } else {
                activeOfficerView.classList.remove('hidden');
                activeOfficerView.classList.add('flex');
                finishedActionView.classList.add('hidden');
            }
        } else {
            actionSection.classList.add('hidden');
        }
    }

    // ฟังก์ชันยิง API ดึงข้อมูลทุก 10 วินาที
    async function fetchTrackingStatus() {
        try {
            const response = await fetch(`{{ url('/') }}/emergency/tracking/api/${emergencyId}`);
            if (response.ok) {
                const data = await response.json();
                
                if (data.state !== currentStatus || data.officer || data.times) {
                    currentStatus = data.state;
                    
                    // อัปเดตข้อมูลเจ้าหน้าที่
                    if (data.officer) {
                        document.getElementById('compactOfficerName').innerText = data.officer.name;
                        document.getElementById('compactCallBtn').href = 'tel:' + data.officer.phone;
                        statusConfigs[3].showActionSection = true; 
                        statusConfigs[4].showActionSection = true; 
                    }

                    // อัปเดตข้อมูลเวลาใน statusSteps
                    if (data.times) {
                        if (data.times.time_create) statusSteps[0].time = data.times.time_create;
                        if (data.times.time_go) statusSteps[2].time = data.times.time_go;
                        if (data.times.time_arrive) statusSteps[3].time = data.times.time_arrive;
                        if (data.times.time_success) statusSteps[4].time = data.times.time_success;
                    }

                    // ลบหมุดเจ้าหน้าที่เมื่อสถานะเสร็จสิ้น
                    if (currentStatus === 5 && officerMarker) {
                        officerMarker.setMap(null);
                        officerMarker = null;
                        
                        // ถ้าระบบส่งเวลาผลรวมมา ให้โหลดหน้าใหม่เพื่อแสดงด้านบน
                        if (!document.getElementById('sumTimeDisplay')) {
                            window.location.reload();
                        }
                    }
                    
                    renderStatus();
                }
            }
        } catch (error) {
            console.error('Error fetching status:', error);
        }
    }

    // วนลูปทำงานทุก 10 วินาที
    setInterval(fetchTrackingStatus, 10000);

    renderStatus();

    // ดึงแผนที่
    function initTrackingMap() {
        const incidentLat = {{ $emergency->emergency_lat ?? 13.7563 }};
        const incidentLng = {{ $emergency->emergency_lng ?? 100.5018 }};
        const incidentLocation = { lat: incidentLat, lng: incidentLng };

        const map = new google.maps.Map(document.getElementById("tracking-map"), {
            zoom: 15,
            center: incidentLocation,
            disableDefaultUI: true,
            zoomControl: true,
            mapTypeId: 'roadmap',
        });

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
        new CustomMarker(incidentLocation, map, incidentHtml);

        // แสดงหมุดเจ้าหน้าที่เฉพาะสถานะที่ยังไม่เสร็จสิ้น
        @if($assignedOfficer && $assignedOfficer->lat && $assignedOfficer->lng)
            if (currentStatus !== 5) {
                const officerLoc = { lat: {{ $assignedOfficer->lat }}, lng: {{ $assignedOfficer->lng }} };
                const officerHtml = `
                    <div class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 z-40">
                        <div class="relative flex h-8 w-8">
                            <span class="relative inline-flex rounded-full h-8 w-8 bg-blue-600 border-2 border-white shadow-md items-center justify-center text-white">
                                <span class="material-symbols-outlined text-[16px]">directions_car</span>
                            </span>
                        </div>
                    </div>
                `;
                officerMarker = new CustomMarker(officerLoc, map, officerHtml);
                
                const bounds = new google.maps.LatLngBounds();
                bounds.extend(incidentLocation);
                bounds.extend(officerLoc);
                map.fitBounds(bounds, { top: 40, bottom: 40, left: 40, right: 40 });
            }
        @endif
    }
</script>

@endsection