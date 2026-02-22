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
                                {{-- ตัดรหัส Operating Code ออกตามคำขอ --}}
                                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $emergency->emergency_type }}</h1>
                                <h5 class="text-slate-600">{{ $emergency->emergency_detail }}</h5>
                            </div>
                            <div class="text-right bg-slate-50 px-4 py-2 rounded-lg border border-slate-100">
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">เวลาที่ผ่านไป</div>
                                {{-- ตัวนับเวลาและจุดกระพริบ (ID timer-wrapper สำหรับเปลี่ยนสีด้วย JS) --}}
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
                    
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-2 gap-6">
                        {{-- ข้อมูลผู้แจ้ง --}}
                        <div class="bg-slate-50/50 rounded-xl border border-slate-200 p-5">
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

                        {{-- ข้อมูลสถานที่ --}}
                        <div class="bg-slate-50/50 rounded-xl border border-slate-200 p-5">
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

                {{-- แผนที่ และ รูปภาพ --}}
                <div class="flex-1 relative w-full bg-slate-200 overflow-hidden group min-h-[300px]">
                    <div id="assign-map" class="absolute inset-0 w-full h-full"></div>
                    
                    {{-- แสดงรูปภาพแนบ พร้อมปุ่มกดดูภาพเต็ม --}}
                    @if($emergency->emergency_photo)
                    <div class="absolute bottom-6 left-6 z-30 group/thumb">
                        <div class="relative w-36 h-24 bg-slate-900 rounded-lg border-2 border-white shadow-2xl overflow-hidden transition-all duration-300 group-hover/thumb:w-[320px] group-hover/thumb:h-[200px] origin-bottom-left ease-out">
                            <img alt="ภาพถ่ายที่เกิดเหตุ" class="w-full h-full object-cover opacity-90 group-hover/thumb:opacity-100 transition-opacity" src="{{ asset($emergency->emergency_photo) }}" />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent pointer-events-none"></div>
                            
                            {{-- แถบข้อความด้านล่าง (ซ่อนเมื่อ Hover) --}}
                            <div class="absolute bottom-2 left-2 right-2 flex items-end justify-between transition-opacity duration-200 group-hover/thumb:opacity-0 pointer-events-none">
                                <div class="flex items-center gap-1.5 text-white/90">
                                    <span class="material-symbols-outlined text-[16px]">image</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wide">รูปล่าสุด</span>
                                </div>
                            </div>

                            {{-- ปุ่มดูภาพเต็ม (แสดงเมื่อ Hover) --}}
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
                    {{-- สัดส่วนสถานะ 70% และปุ่มเสร็จสิ้น 30% --}}
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
                            <p class="text-xs text-slate-400 mt-0.5">เลือกเพื่อสั่งการให้เข้าช่วยเหลือ</p>
                        </div>
                        
                        @if($isOutOfArea)
                        <div class="bg-red-50 border border-red-200 text-red-600 px-3 py-1.5 rounded-lg flex items-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">error</span>
                            <span class="text-[10px] font-bold">ไม่มีจนท.ในพื้นที่</span>
                        </div>
                        @else
                        <button type="button" class="text-primary hover:bg-primary/5 p-2 rounded-lg transition-colors">
                            <span class="material-symbols-outlined">tune</span>
                        </button>
                        @endif
                    </div>

                    {{-- Form สำหรับบันทึกการสั่งการ (ถ้ามี) --}}
                    <form action="#" method="POST" class="flex flex-col flex-1 overflow-hidden">
                        @csrf
                        
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-3 bg-slate-50/30">
                            @forelse($officers as $officer)
                            <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group has-[:checked]:border-primary has-[:checked]:ring-1 has-[:checked]:ring-primary has-[:checked]:bg-blue-50/20">
                                <input name="officer_id" value="{{ $officer->id }}" class="absolute right-4 top-4 rounded-full border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer peer" type="radio" required />
                                <div class="flex items-center gap-4 w-full">
                                    {{-- เปลี่ยนเป็นไอคอนมาตรฐานกลางๆ (รถยนต์) --}}
                                    <div class="size-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0 peer-checked:bg-primary peer-checked:text-white transition-colors">
                                        <span class="material-symbols-outlined text-[24px]">directions_car</span>
                                    </div>
                                    <div class="flex-1 pr-8">
                                        <div class="flex justify-between items-start mb-1">
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-bold text-slate-900">{{ $officer->name_officer }}</h4>
                                                <span class="bg-slate-100 text-slate-600 text-[9px] px-1.5 py-0.5 rounded border border-slate-200 uppercase">{{ $officer->level }}</span>
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
                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                            <span class="font-medium text-slate-700">{{ $officer->type }}</span>
                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                            <span class="font-medium text-slate-500 text-[10px]">ทีมละ {{ $officer->amount_help }} คน</span>
                                        </div>
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
                            <button type="submit" class="w-full py-4 bg-primary hover:bg-blue-600 text-white font-bold text-sm uppercase tracking-wide rounded-xl shadow-lg shadow-blue-500/25 flex items-center justify-center gap-3 transition-all transform hover:-translate-y-0.5">
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

            {{-- Action ส่งไปที่ Route สำหรับปิดเคส --}}
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

{{-- Script สำหรับเวลา และ Google Maps --}}
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initAssignMap" async defer></script>
<script>
    // ----------------------------------------------------------------
    // 1. ระบบ Popup ดูภาพเต็ม (1080x1080)
    // ----------------------------------------------------------------
    function openFullImage(url) {
        window.open(url, '_blank', 'width=1080,height=1080,menubar=no,toolbar=no,location=no,status=no,resizable=yes');
    }

    // ----------------------------------------------------------------
    // 2. ระบบจัดการ Modal ปิดงาน
    // ----------------------------------------------------------------
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

    // ----------------------------------------------------------------
    // 3. จับเวลาที่ผ่านไป + อัปเดตสีตามระดับความนาน
    // ----------------------------------------------------------------
    const sosTime = "{{ $emergency->operation->time_create_sos ? \Carbon\Carbon::parse($emergency->operation->time_create_sos)->toISOString() : $emergency->created_at->toISOString() }}";
    const startTime = new Date(sosTime).getTime();
    
    function updateTimer() {
        const now = new Date().getTime();
        const distance = now - startTime;
        
        if (distance < 0) return;
        
        const totalMinutes = Math.floor(distance / (1000 * 60)); // สำหรับใช้เช็คสี
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        
        let timeString = "";
        
        if (days > 0) timeString += days + " วัน ";
        if (hours > 0 || days > 0) timeString += hours + " ชม. ";
        if (minutes > 0 || hours > 0 || days > 0) timeString += minutes + " นาที";
        
        if (days === 0 && hours === 0 && minutes === 0) timeString = "เพิ่งแจ้งเหตุ";
        
        document.getElementById("elapsed-time").innerHTML = timeString.trim();

        // เปลี่ยนสีตามเวลา
        const wrapper = document.getElementById("timer-wrapper");
        const ping = document.getElementById("timer-ping");
        const dot = document.getElementById("timer-dot");

        // ลบสีเดิมออกก่อน
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

    // ----------------------------------------------------------------
    // 4. แผนที่และหมุดแจ้งเหตุ (Custom HTML Marker)
    // ----------------------------------------------------------------
    function initAssignMap() {
        const lat = {{ $emergency->emergency_lat ?? 13.7563 }};
        const lng = {{ $emergency->emergency_lng ?? 100.5018 }};
        const incidentLocation = { lat: lat, lng: lng };

        const map = new google.maps.Map(document.getElementById("assign-map"), {
            zoom: 16,
            center: incidentLocation,
            disableDefaultUI: true, 
            zoomControl: true,
            mapTypeId: 'roadmap',
        });

        // สร้าง Custom Overlay View สำหรับหมุดกระพริบ
        class CustomMarker extends google.maps.OverlayView {
            constructor(position, map) {
                super();
                this.position = position;
                this.div = null;
                this.setMap(map);
            }
            onAdd() {
                this.div = document.createElement('div');
                this.div.style.position = 'absolute';
                // HTML หมุดกระพริบ + ข้อความ
                this.div.innerHTML = `
                    <div class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 cursor-pointer">
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
                const panes = this.getPanes();
                panes.overlayMouseTarget.appendChild(this.div); // ให้คลิกและวางเหนือแผนที่
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

        // วาง Custom Marker ลงบนแผนที่
        new CustomMarker(incidentLocation, map);
    }
</script>

@endsection