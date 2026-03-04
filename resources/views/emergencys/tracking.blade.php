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

    $assignedOfficer = null;
    if(!empty($operation->user_officers_id)) {
        $assignedOfficer = \App\Models\User_officer::find($operation->user_officers_id);
    }
@endphp

<div class="w-full md:h-[calc(100%-71.75px)] relative flex justify-center sm:items-center p-3 bg-slate-50 dark:bg-slate-900" style="height: calc(100vh - 71.75px); margin-top:71.75px; overflow: auto;">

    <div class="relative w-full max-w-[500px] bg-white dark:bg-[#1a2632] rounded-xl shadow-xl flex flex-col overflow-auto ring-1 ring-black/5 dark:ring-white/10 h-full max-h-[850px]">
        
        <header class="px-8 py-6 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between bg-white dark:bg-[#1a2632] shrink-0">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">สถานะการช่วยเหลือ</h1>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide">
                    รหัสเคส: #{{ $operation->operating_code ?? 'กำลังดำเนินการ' }}
                </p>
            </div>
            <div class="h-12 w-12 rounded-full bg-primary flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-2xl text-white">emergency</span>
            </div>
        </header>

        <div class="flex flex-col bg-white dark:bg-[#1a2632] flex-1 overflow-y-auto">
            
            <section class="px-8 py-6" id="statusContainer">
                </section>

            <section class="px-8 pb-6">
                <div class="relative w-full h-48 sm:h-64 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden bg-slate-100">
                    <div id="tracking-map" class="absolute inset-0 w-full h-full"></div>
                </div>
            </section>

            <section id="officerInfo" class="px-8 py-6 bg-white dark:bg-[#1a2632] border-t border-slate-100 dark:border-slate-700 hidden">
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-100 dark:border-slate-700 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-12 h-12 mr-3 rounded-full bg-blue-100 dark:bg-slate-700 flex items-center justify-center text-blue-600 shrink-0">
                            <span class="material-symbols-outlined text-2xl">support_agent</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white" id="officerName">
                                {{ $assignedOfficer->name_officer ?? 'กำลังค้นหาเจ้าหน้าที่...' }}
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400" id="officerTeam">
                                {{ $assignedOfficer->type ?? 'กรุณารอสักครู่' }}
                            </p>
                        </div>
                    </div>
                    
                    <a href="tel:{{ $assignedOfficer ? str_replace('-', '', $assignedOfficer->phone) : '#' }}" id="callOfficerBtn" class="mt-4 w-full px-4 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-200 hover:border-primary hover:text-primary rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 {{ $assignedOfficer ? '' : 'hidden' }}">
                        <span class="material-symbols-outlined text-lg">call</span>
                        <span>โทรติดต่อเจ้าหน้าที่</span>
                    </a>

                    <div id="caseClosed" class="flex flex-col mt-4 gap-3 hidden">
                        <button class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-lg shadow-sm shadow-amber-500/20 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">star_rate</span>
                            ประเมินการบริการ
                        </button>
                        <a href="{{ url('/') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                            กลับหน้าหลัก
                        </a>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initTrackingMap" async defer></script>
<script>
    let currentStatus = {{ $currentState }}; 
    const emergencyId = {{ $emergency->id }};

    const statusConfigs = {
        1: { active: 1, showOfficer: false, showCaseClosed: false },
        2: { active: 2, showOfficer: false, showCaseClosed: false }, // ยังไม่แสดงจนกว่าจะมี user_officers_id
        3: { active: 3, showOfficer: true,  showCaseClosed: false }, // เจ้าหน้าที่รับงานแล้ว
        4: { active: 4, showOfficer: true,  showCaseClosed: false },
        5: { active: 5, showOfficer: true,  showCaseClosed: true  }
    };

    const statusSteps = [
        {
            id: 1,
            title: 'ส่งคำขอเรียบร้อย',
            description: 'ระบบได้รับข้อมูลขอความช่วยเหลือของคุณแล้ว',
            icon: 'check',
            time: '{{ \Carbon\Carbon::parse($emergency->created_at)->format('H:i') }} น.',
            completedStates: [1, 2, 3, 4, 5]
        },
        {
            id: 2,
            title: 'ค้นหาเจ้าหน้าที่',
            description: 'ระบบกำลังค้นหาและมอบหมายงานให้เจ้าหน้าที่',
            icon: 'sync',
            activeStates: [1, 2],
            completedStates: [3, 4, 5]
        },
        {
            id: 3,
            title: 'เจ้าหน้าที่กำลังไปช่วยเหลือ',
            description: 'เจ้าหน้าที่รับเรื่องและกำลังไปช่วยเหลือ',
            icon: 'badge',
            activeStates: [3],
            completedStates: [4, 5]
        },
        {
            id: 4,
            title: 'เจ้าหน้าที่มาถึงแล้ว',
            description: 'เจ้าหน้าที่เดินทางมาถึงจุดเกิดเหตุ',
            icon: 'shield_person',
            activeStates: [4],
            completedStates: [5]
        },
        {
            id: 5,
            title: 'การช่วยเหลือเสร็จสิ้น',
            description: 'ภารกิจช่วยเหลือเสร็จสมบูรณ์',
            icon: 'flag',
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
                        ${step.time && isCompleted && index === 0 ? `
                        <span class="inline-block mt-1.5 text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-500">${step.time}</span>
                        ` : ''}
                    </div>
                </div>
            `;
            container.innerHTML += stepHTML;
        });

        const officerInfo = document.getElementById('officerInfo');
        const caseClosed = document.getElementById('caseClosed');
        const callOfficerBtn = document.getElementById('callOfficerBtn');

        if (config.showOfficer) officerInfo.classList.remove('hidden');
        else officerInfo.classList.add('hidden');

        if (config.showCaseClosed) {
            caseClosed.classList.remove('hidden');
            if(callOfficerBtn) callOfficerBtn.classList.add('hidden');
        } else {
            caseClosed.classList.add('hidden');
            if(callOfficerBtn && callOfficerBtn.getAttribute('href') !== '#') callOfficerBtn.classList.remove('hidden');
        }
    }

    // ฟังก์ชันยิง API ดึงข้อมูลทุก 10 วินาที
    async function fetchTrackingStatus() {
        try {
            const response = await fetch(`{{ url('/') }}/emergency/tracking/api/${emergencyId}`);
            if (response.ok) {
                const data = await response.json();
                // console.log("fetchTrackingStatus")
                
                // ถ้าระบบเจอเจ้าหน้าที่และเปลี่ยน State
                if (data.state !== currentStatus || data.officer) {
                    currentStatus = data.state;
                    
                    if (data.officer) {
                        document.getElementById('officerName').innerText = data.officer.name;
                        document.getElementById('officerTeam').innerText = data.officer.type;
                        const callBtn = document.getElementById('callOfficerBtn');
                        callBtn.href = 'tel:' + data.officer.phone;
                        callBtn.classList.remove('hidden');
                        statusConfigs[2].showOfficer = true; // แสดงผลเมื่อมีเจ้าหน้าที่รับเคส
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

        @if($assignedOfficer && $assignedOfficer->lat && $assignedOfficer->lng)
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
            new CustomMarker(officerLoc, map, officerHtml);
            
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(incidentLocation);
            bounds.extend(officerLoc);
            map.fitBounds(bounds, { top: 40, bottom: 40, left: 40, right: 40 });
        @endif
    }
</script>

@endsection