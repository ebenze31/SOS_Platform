@extends('layouts.theme')

@section('content')

<div class="bg-background-light h-[calc(100vh-71.75px)] dark:bg-background-dark text-slate-900 flex flex-col relative mt-[71.75px]">
    <div class="flex-1 bg-slate-50/50 p-4 sm:p-6 z-0 h-full">
        <div class="h-full max-w-[1800px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- ข้อมูลเหตุการณ์ และ แผนที่ --}}
            <div class="lg:col-span-7 xl:col-span-8 flex flex-col bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden h-full">
                <div class="shrink-0 bg-white relative z-20 shadow-sm border-b border-slate-200">
                    <div class="p-6 border-b border-slate-100">
                        <div class="flex flex-wrap justify-between items-start">
                            <div class="space-y-1">
                                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $emergency->emergency_type }}</h1>
                                <h5 class="text-slate-600">{{ $emergency->emergency_detail }}</h5>
                            </div>
                            <div class="text-right bg-slate-50 px-4 py-2 rounded-lg border border-slate-100">
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">เวลาที่ผ่านไป</div>
                                <div id="timer-wrapper" class="text-xl font-bold text-emerald-600 flex items-center gap-2 justify-end transition-colors duration-500">
                                    <span class="relative flex h-3 w-3">
                                        <span id="timer-ping" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span id="timer-dot" class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                    </span>
                                    <span id="elapsed-time">กำลังคำนวณ...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-2 grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <div class="bg-slate-50/50 rounded-xl border border-slate-200 p-2">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">person</span>
                                    ผู้แจ้งเหตุ
                                </h3>
                                <span class="text-[10px] font-medium text-blue-600 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded">{{ $emergency->type_reporter }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4 flex-wrap">
                                <div>
                                    <div class="text-xl font-bold text-slate-900">{{ $emergency->name_reporter }}</div>
                                    <div class="text-sm text-slate-500">{{ $emergency->phone_reporter }}</div>
                                </div>
                                <a href="tel:{{ str_replace('-', '', $emergency->phone_reporter) }}" class="flex items-center gap-2 px-4 py-2.5 max-sm:w-full max-sm:justify-center bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-lg hover:border-blue-500 hover:text-blue-600 hover:shadow-md transition-all group">
                                    <span class="material-symbols-outlined text-[20px] group-hover:animate-pulse">call</span>
                                    โทรติดต่อ
                                </a>
                            </div>
                        </div>

                        <div class="bg-slate-50/50 rounded-xl border border-slate-200 p-2">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">location_on</span>
                                    สถานที่เกิดเหตุ
                                </h3>
                            </div>
                            <div class="space-y-1">
                                <div class="text-sm font-bold text-slate-900 leading-tight">{{ $emergency->emergency_location }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-1">
                                    พิกัด: {{ number_format($emergency->emergency_lat, 5) }}, {{ number_format($emergency->emergency_lng, 5) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1 relative w-full bg-slate-200 overflow-hidden group min-h-[300px]">
                    <div id="assign-map" class="absolute inset-0 w-full h-full"></div>
                    
                    @if($emergency->emergency_photo)
                    <div class="absolute bottom-6 left-6 z-30 group/thumb">
                        <div class="relative w-36 h-24 bg-slate-900 rounded-lg border-2 border-white shadow-2xl overflow-hidden transition-all duration-300 group-hover/thumb:w-[320px] group-hover/thumb:h-[200px] origin-bottom-left ease-out">
                            <img alt="ภาพถ่ายที่เกิดเหตุ" class="w-full h-full object-cover opacity-90 group-hover/thumb:opacity-100 transition-opacity" src="{{ asset($emergency->emergency_photo) }}" />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent pointer-events-none"></div>
                            
                            <div class="absolute bottom-2 left-2 right-2 flex items-end justify-between transition-opacity duration-200 group-hover/thumb:opacity-0 pointer-events-none">
                                <div class="flex items-center gap-1.5 text-white/90">
                                    <span class="material-symbols-outlined text-[16px]">image</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wide">รูปล่าสุด</span>
                                </div>
                            </div>

                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover/thumb:opacity-100 transition-opacity duration-300 bg-black/20 backdrop-blur-[1px]">
                                <button onclick="openFullImage('{{ asset($emergency->emergency_photo) }}')" class="bg-white/90 hover:bg-white text-slate-800 flex items-center gap-2 px-4 py-2 rounded-full shadow-lg transform hover:scale-105 transition-all font-bold text-xs">
                                    <span class="material-symbols-outlined text-[18px]">open_in_new</span> ดูภาพเต็ม
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- สั่งการและมอบหมายงาน --}}
            <div class="lg:col-span-5 xl:col-span-4 flex flex-col h-full gap-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 shrink-0">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">สถานะเหตุการณ์ปัจจุบัน</h3>
                    </div>
                    <div class="flex bg-slate-100 p-1.5 rounded-lg border border-slate-200 gap-1.5">
                        <div class="w-[70%] py-3 px-2 rounded-md bg-white text-slate-900 shadow-sm border border-slate-200 text-[12px] font-bold transition-all flex items-center justify-center relative overflow-hidden text-center leading-tight">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 rounded-l-md"></div>
                            {{ $emergency->operation->status ?? 'รอรับเรื่อง' }}
                        </div>
                        <button type="button" onclick="openCompleteModal()" class="w-[30%] py-3 px-1 rounded-md bg-emerald-100 hover:bg-emerald-200 text-emerald-700 shadow-sm border border-emerald-200 text-[12px] font-bold transition-all flex items-center justify-center gap-1">
                            เสร็จสิ้น
                        </button>
                    </div>
                </div>

                <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col overflow-hidden relative min-h-[400px]">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white z-10 shrink-0">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">เจ้าหน้าที่</h3>
                            <p class="text-[10px] text-red-400 font-medium mt-0.5">* อ้างอิงจากพิกัดล่าสุดที่ระบบบันทึกไว้</p>
                        </div>
                        
                        @if($isOutOfArea)
                        <div class="bg-red-50 border border-red-200 text-red-600 px-3 py-1.5 rounded-lg flex items-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">error</span>
                            <span class="text-[10px] font-bold">ไม่มีจนท.ในพื้นที่</span>
                        </div>
                        @else
                        <div class="text-[11px] font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                            เลือกเจ้าหน้าที่เข้าช่วยเหลือ
                        </div>
                        @endif
                    </div>

                    <form action="{{ route('emergency.assign', $emergency->id ?? 0) }}" method="POST" class="flex flex-col flex-1 overflow-hidden" id="assignForm">
                        @csrf
                        
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-3 bg-slate-50/30">
                            @forelse($officers as $officer)
                            @php
                                $refusedList = json_decode($emergency->operation->officer_refuse ?? '[]', true) ?? [];
                                $isRefused = in_array($officer->id, $refusedList);
                                $isWaiting = ($emergency->operation->waiting_reply ?? null) == $officer->id;

                                // ตรวจสอบและดึงข้อมูลเวลารอถ้าเจ้าหน้าที่มีสถานะ no_respond ในรอบก่อนหน้า
                                $logCommands = json_decode($emergency->operation->log_command ?? '[]', true) ?? [];
                                $isNoRespond = false;
                                $noRespondTimeMin = 0;
                                $noRespondTimeSec = 0;

                                // วนลูปจาก log ล่าสุดเพื่อหาสถานะล่าสุดของเจ้าหน้าที่คนนี้
                                foreach(array_reverse($logCommands) as $log) {
                                    if(($log['sendTo'] ?? null) == $officer->id) {
                                        if(($log['status'] ?? '') === 'no_respond') {
                                            $isNoRespond = true;
                                            $sumTime = $log['sum_time'] ?? 0;
                                            $noRespondTimeMin = floor($sumTime / 60);
                                            $noRespondTimeSec = $sumTime % 60;
                                        }
                                        break;
                                    }
                                }
                            @endphp

                            <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group has-[:checked]:border-primary has-[:checked]:ring-1 has-[:checked]:ring-primary has-[:checked]:bg-blue-50/20 {{ $isRefused || $isWaiting ? 'opacity-50 pointer-events-none cursor-not-allowed' : '' }}">
                                <input name="officer_id" value="{{ $officer->id }}" data-lat="{{ $officer->lat }}" data-lng="{{ $officer->lng }}" class="absolute right-4 top-4 rounded-full border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer peer assign-radio" type="radio" required {{ $isRefused || $isWaiting ? 'disabled' : '' }} />
                                <div class="flex items-center gap-4 w-full">
                                    <div class="size-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0 peer-checked:bg-primary peer-checked:text-white transition-colors">
                                        <span class="material-symbols-outlined text-[24px]">directions_car</span>
                                    </div>
                                    <div class="flex-1 pr-8">
                                        <div class="flex justify-between items-start mb-1">
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-bold text-slate-900">{{ $officer->name_officer }}</h4>
                                                @if($officer->level)
                                                    <span class="bg-slate-100 text-slate-600 text-[9px] px-1.5 py-0.5 rounded border border-slate-200 uppercase">{{ $officer->level }}</span>
                                                @endif
                                            </div>
                                            <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">
                                                {{ $officer->distance_km }} กม.
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3 text-xs text-slate-500">
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">schedule</span> 
                                                ~{{ max(1, round($officer->distance_km * 1.5)) }} นาที
                                            </span>
                                            
                                            @if($officer->type)
                                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                <span class="font-medium text-slate-700">{{ $officer->type }}</span>
                                            @endif
                                            
                                            @if($officer->amount_help !== null)
                                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                <span class="font-medium text-slate-500 text-[10px]">ช่วยเหลือแล้ว {{ $officer->amount_help }} ครั้ง</span>
                                            @endif
                                        </div>

                                        @if($isWaiting)
                                            <div class="mt-2 text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg border border-amber-200 inline-block">
                                                กำลังรอการตอบรับ...
                                            </div>
                                        @elseif($isRefused)
                                            <div class="mt-2 text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg border border-red-200 inline-block">
                                                ปฏิเสธการรับงาน
                                            </div>
                                        @elseif($isNoRespond)
                                            <div class="mt-2 text-[10px] font-bold text-[#f87171] bg-[#f87171]/10 px-2 py-1 rounded-lg border border-[#f87171]/30 inline-block">
                                                ไม่มีการตอบสนอง เวลารอ {{ $noRespondTimeMin > 0 ? $noRespondTimeMin . ' นาที ' : '' }}{{ $noRespondTimeSec }} วินาที
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </label>
                            @empty
                            <div class="flex flex-col items-center justify-center py-10 text-slate-400 h-full">
                                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">person_off</span>
                                <p class="text-sm font-bold text-slate-600 mb-1">ไม่พบเจ้าหน้าที่แสตนด์บาย</p>
                            </div>
                            @endforelse
                        </div>

                        <div class="p-5 border-t border-slate-100 bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] shrink-0 z-20">
                            <button type="submit" id="submitAssignBtn" class="w-full py-4 bg-primary hover:bg-blue-600 text-white font-bold text-sm uppercase tracking-wide rounded-xl shadow-lg shadow-blue-500/25 flex items-center justify-center gap-3 transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:hover:translate-y-0 disabled:cursor-not-allowed" disabled>
                                <span>สั่งการและมอบหมายงาน</span>
                                <span class="material-symbols-outlined">send</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= Modal ยืนยันเสร็จสิ้นภารกิจ ================= --}}
<div id="completeModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a2632] rounded-2xl w-full max-w-md shadow-2xl relative border border-slate-200 dark:border-slate-700 transform scale-95 transition-all duration-300" id="completeModalContent">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700/50 flex items-start gap-4">
                <div class="size-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">ยืนยันการเสร็จสิ้นภารกิจ</h3>
                    <p class="text-sm text-slate-500 mt-1">กรุณาระบุรายละเอียดการดำเนินการ</p>
                </div>
            </div>
            <form action="{{ route('emergency.complete', $emergency->id) }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">รายละเอียด / หมายเหตุ <span class="text-red-500">*</span></label>
                        <textarea name="remark_status" required rows="4" class="w-full rounded-lg border-slate-200 bg-slate-50 p-3 text-sm text-slate-900 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-slate-800/50 dark:border-slate-700 dark:text-white placeholder:text-slate-400" placeholder="ระบุรายละเอียด"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 justify-end mt-6">
                    <button type="button" onclick="closeCompleteModal()" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        ยกเลิก
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-500/25 rounded-lg transition-colors">
                        บันทึกและปิดเคส
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initAssignMap" async defer></script>
<script>
    function openFullImage(url) {
        window.open(url, '_blank', 'width=1080,height=1080,menubar=no,toolbar=no,location=no,status=no,resizable=yes');
    }

    function openCompleteModal() {
        const modal = document.getElementById('completeModal');
        const content = document.getElementById('completeModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closeCompleteModal() {
        const modal = document.getElementById('completeModal');
        const content = document.getElementById('completeModalContent');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    const sosTime = "{{ $emergency->operation->time_create_sos ? \Carbon\Carbon::parse($emergency->operation->time_create_sos)->toISOString() : $emergency->created_at->toISOString() }}";
    const startTime = new Date(sosTime).getTime();
    
    function updateTimer() {
        const now = new Date().getTime();
        const distance = now - startTime;
        if (distance < 0) return;
        
        const totalMinutes = Math.floor(distance / (1000 * 60));
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        
        let timeString = "";
        if (days > 0) timeString += days + " วัน ";
        if (hours > 0 || days > 0) timeString += hours + " ชม. ";
        if (minutes > 0 || hours > 0 || days > 0) timeString += minutes + " นาที";
        if (days === 0 && hours === 0 && minutes === 0) timeString = "เพิ่งแจ้งเหตุ";
        
        document.getElementById("elapsed-time").innerHTML = timeString.trim();

        const wrapper = document.getElementById("timer-wrapper");
        const ping = document.getElementById("timer-ping");
        const dot = document.getElementById("timer-dot");

        wrapper.classList.remove("text-emerald-600", "text-orange-500", "text-red-600");
        ping.classList.remove("bg-emerald-400", "bg-orange-400", "bg-red-400");
        dot.classList.remove("bg-emerald-500", "bg-orange-500", "bg-red-500");

        if (totalMinutes < 8) {
            wrapper.classList.add("text-emerald-600");
            ping.classList.add("bg-emerald-400");
            dot.classList.add("bg-emerald-500");
        } else if (totalMinutes < 12) {
            wrapper.classList.add("text-orange-500");
            ping.classList.add("bg-orange-400");
            dot.classList.add("bg-orange-500");
        } else {
            wrapper.classList.add("text-red-600");
            ping.classList.add("bg-red-400");
            dot.classList.add("bg-red-500");
        }
    }
    
    setInterval(updateTimer, 60000);
    updateTimer();

    let mapInstance;
    let incidentLatLng;

    let currentSelectedRadio = null;
    const submitBtn = document.getElementById('submitAssignBtn');
    
    document.querySelectorAll('.assign-radio').forEach(radio => {
        radio.addEventListener('click', function(e) {
            document.querySelectorAll('.officer-pulse').forEach(el => el.classList.add('hidden'));

            if (currentSelectedRadio === this) {
                this.checked = false;
                currentSelectedRadio = null;
                submitBtn.disabled = true;
                
                if(mapInstance && incidentLatLng) {
                    mapInstance.panTo(incidentLatLng);
                    mapInstance.setZoom(14);
                }
            } else {
                currentSelectedRadio = this;
                submitBtn.disabled = false;

                const officerId = this.value;
                const lat = parseFloat(this.dataset.lat);
                const lng = parseFloat(this.dataset.lng);

                const targetMarker = document.getElementById(`officer-marker-${officerId}`);
                if (targetMarker) {
                    targetMarker.querySelector('.officer-pulse').classList.remove('hidden');
                }

                if(mapInstance && lat && lng) {
                    const bounds = new google.maps.LatLngBounds();
                    bounds.extend(incidentLatLng);
                    bounds.extend({ lat: lat, lng: lng });
                    mapInstance.fitBounds(bounds, { top: 60, bottom: 60, left: 60, right: 60 });
                }
            }
        });
    });

    function initAssignMap() {
        const incidentLat = {{ $emergency->emergency_lat ?? 13.7563 }};
        const incidentLng = {{ $emergency->emergency_lng ?? 100.5018 }};
        incidentLatLng = { lat: incidentLat, lng: incidentLng };

        mapInstance = new google.maps.Map(document.getElementById("assign-map"), {
            zoom: 14,
            center: incidentLatLng,
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
            <div class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 cursor-pointer z-50">
                <div class="relative flex h-10 w-10">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-10 w-10 bg-red-600 border-[3px] border-white shadow-xl items-center justify-center text-white">
                        <span class="material-symbols-outlined text-[20px]">warning</span>
                    </span>
                </div>
                <div class="mt-2 bg-slate-900/90 backdrop-blur text-white text-[11px] font-bold px-3 py-1.5 rounded-full shadow-lg border border-slate-700 whitespace-nowrap">
                    จุดเกิดเหตุ
                </div>
            </div>
        `;
        new CustomMarker(incidentLatLng, mapInstance, incidentHtml);

        const officersData = @json($officers);
        
        officersData.forEach(officer => {
            if (officer.lat && officer.lng) {
                let displayName = officer.name_officer;
                if (displayName.length > 8) {
                    displayName = displayName.substring(0, 8) + '..';
                }
                
                const officerHtml = `
                    <div id="officer-marker-${officer.id}" class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 cursor-pointer transition-transform hover:scale-110 z-40">
                        <div class="relative flex h-9 w-9">
                            <span class="officer-pulse hidden animate-[ping_1.5s_cubic-bezier(0,0,0.2,1)_infinite] absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-60"></span>
                            <span class="relative inline-flex rounded-full h-9 w-9 bg-blue-600 border-2 border-white shadow-lg items-center justify-center text-white">
                                <span class="material-symbols-outlined text-[18px]">directions_car</span>
                            </span>
                        </div>
                        <div class="mt-1 bg-white text-slate-900 text-[11px] font-bold px-2.5 py-1 rounded shadow-md border border-slate-200 whitespace-nowrap">
                            ${displayName}
                        </div>
                    </div>
                `;
                new CustomMarker({ lat: parseFloat(officer.lat), lng: parseFloat(officer.lng) }, mapInstance, officerHtml);
            }
        });
    }
</script>

@endsection