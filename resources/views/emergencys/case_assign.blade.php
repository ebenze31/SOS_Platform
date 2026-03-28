@extends('layouts.theme')

@section('content')

@php
    // ประกาศไว้บนสุดเพื่อให้โค้ดด้านล่างทั้งหมดมองเห็นตัวแปรเหล่านี้
    $currentOpStatus = $emergency->operation->status ?? 'รับแจ้งเหตุ';
    $isActiveOperation = in_array($currentOpStatus, ['กำลังไปช่วยเหลือ', 'ถึงที่เกิดเหตุ', 'เสร็จสิ้น']);
    $acceptedOfficerId = $emergency->operation->user_officers_id ?? null;
    
    // ค้นหาข้อมูลเจ้าหน้าที่ที่รับงาน
    $acceptedOfficer = null;
    if ($acceptedOfficerId) {
        $acceptedOfficer = collect($officers)->firstWhere('id', $acceptedOfficerId);
    }
@endphp

{{-- Toast แจ้งเตือน --}}
<div id="statusToast" class="fixed top-20 left-1/2 z-[100] transform transition-all duration-300 -translate-x-1/2 -translate-y-[150%] opacity-0 flex items-center gap-3 bg-white border-b-4 border-primary px-5 py-3 rounded-xl shadow-2xl min-w-[320px]">
    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0" id="toastIconContainer">
        <span class="material-symbols-outlined text-[20px]" id="toastIcon">notifications_active</span>
    </div>
    <div>
        <h4 class="text-sm font-bold text-slate-900" id="toastTitle">อัปเดตสถานะการทำงาน</h4>
        <p class="text-xs text-slate-500 font-medium mt-0.5" id="toastMessage">ระบบกำลังทำงาน...</p>
    </div>
</div>

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
                                <div id="timer-title" class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">เวลาที่ผ่านไป</div>
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
                                    <span class="text-[10px] font-bold uppercase tracking-wide">รูปภาพ</span>
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

            {{-- สั่งการและมอบหมายงาน / ติดตามสถานะ --}}
            <div class="lg:col-span-5 xl:col-span-4 flex flex-col h-full gap-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 shrink-0">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">สถานะเหตุการณ์ปัจจุบัน</h3>
                        <h5 class="text-slate-600"># {{ $emergency->operation->operating_code }}</h5>
                    </div>
                    <div class="flex bg-slate-100 p-1.5 rounded-lg border border-slate-200 gap-1.5">
                        <div class="w-[70%] py-3 px-2 rounded-md bg-white text-slate-900 shadow-sm border border-slate-200 text-[12px] font-bold transition-all flex items-center justify-center relative overflow-hidden text-center leading-tight">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-slate-400 rounded-l-md transition-colors duration-300" id="status-color-bar"></div>
                            <span id="current-status-text">{{ $emergency->operation->status ?? 'รับแจ้งเหตุ' }}</span>
                        </div>
                        <button type="button" {{ $currentOpStatus == 'เสร็จสิ้น' ? 'disabled' : 'onclick=openCompleteModal()' }} class="w-[30%] py-3 px-1 rounded-md bg-emerald-100 hover:bg-emerald-200 text-emerald-700 shadow-sm border border-emerald-200 text-[12px] font-bold transition-all flex items-center justify-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed">
                            เสร็จสิ้น
                        </button>
                    </div>
                </div>

                <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col overflow-hidden relative min-h-[400px]">
                    
                    {{-- ================= โหมด 1: รอการมอบหมายงาน (Assign Form) ================= --}}
                    @php
                        // แยกกลุ่มเจ้าหน้าที่
                        $inAreaList = isset($officersInArea) ? $officersInArea : (isset($isOutOfArea) && $isOutOfArea ? [] : ($officers ?? []));
                        $otherList = isset($officersOther) ? $officersOther : (isset($isOutOfArea) && $isOutOfArea ? ($officers ?? []) : []);

                        $sections = [
                            [
                                'title' => 'ในพื้นที่',
                                'icon' => 'location_on',
                                'iconColor' => 'text-primary',
                                'list' => $inAreaList,
                                'emptyText' => 'ไม่มีเจ้าหน้าที่ในพื้นที่',
                                'emptyIcon' => 'location_off'
                            ],
                            [
                                'title' => 'อื่นๆ',
                                'icon' => 'explore',
                                'iconColor' => 'text-slate-400',
                                'list' => $otherList,
                                'emptyText' => 'ไม่พบเจ้าหน้าที่สแตนด์บาย',
                                'emptyIcon' => 'person_off'
                            ]
                        ];

                        // ดึง Log Command มาเตรียมไว้
                        $logCommands = json_decode($emergency->operation->log_command ?? '[]', true) ?? [];
                    @endphp

                    <div id="assign-officer-section" class="flex flex-col h-full {{ $isActiveOperation ? 'hidden' : '' }}">
                        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white z-10 shrink-0">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">เจ้าหน้าที่</h3>
                                <p class="text-[10px] text-red-400 font-medium mt-0.5">* อ้างอิงจากพิกัดล่าสุดที่ระบบบันทึกไว้</p>
                            </div>
                            <div class="text-[11px] font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                                เลือกเจ้าหน้าที่เข้าช่วยเหลือ
                            </div>
                        </div>

                        <form action="{{ route('emergency.assign', $emergency->id ?? 0) }}" method="POST" class="flex flex-col flex-1 overflow-hidden" id="assignForm">
                            @csrf
                            <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-6 bg-slate-50/30">
                                
                                @foreach($sections as $index => $section)
                                    @if($index > 0)
                                        <div class="border-t border-slate-200/60 -mx-4"></div>
                                    @endif

                                    <div>
                                        <h4 class="text-xs font-bold text-slate-700 mb-3 flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[16px] {{ $section['iconColor'] }}">{{ $section['icon'] }}</span>
                                            {{ $section['title'] }}
                                        </h4>
                                        
                                        <div class="space-y-3">
                                            @forelse($section['list'] as $officer)
                                                @php
                                                    // --- 1. เตรียมข้อมูลพื้นฐาน ---
                                                    $officerGlobalStatus = $officer->status ?? 'Standby';
                                                    $myLogs = array_filter($logCommands, function($l) use ($officer) {
                                                        return ($l['sendTo'] ?? null) == $officer->id;
                                                    });
                                                    
                                                    $sendCount = count($myLogs); 
                                                    $latestLog = !empty($myLogs) ? end($myLogs) : null;
                                                    $logStatus = $latestLog['status'] ?? '';
                                                    $logDt = $latestLog['datetime'] ?? '';

                                                    // --- หาเวลาที่รอของคนที่ไม่ตอบสนอง ---
                                                    $waitMin = 0; $waitSec = 0;
                                                    $noRespondLog = array_filter($myLogs, function($l) { return ($l['status'] ?? '') === 'no_respond'; });
                                                    if (!empty($noRespondLog)) {
                                                        $lastNoRespond = end($noRespondLog);
                                                        $sumTime = (int)($lastNoRespond['sum_time'] ?? 0);
                                                        $waitMin = floor($sumTime / 60);
                                                        $waitSec = $sumTime % 60;
                                                    }
                                                    $timeText = ($waitMin > 0 ? "{$waitMin} นาที " : "") . "{$waitSec} วิ";

                                                    // --- 2. Logic การห้ามกด (Disabled) ---
                                                    $isGlobalBusy = in_array($officerGlobalStatus, ['Helping', 'None']);
                                                    $isRejected = ($logStatus === 'reject');
                                                    $isPending = ($logStatus === 'pending');
                                                    $isNoRespond = ($logStatus === 'no_respond'); // เช็คว่าล่าสุดคือไม่ตอบสนองใช่ไหม

                                                    // *** กฎเหล็ก: ห้ามกดเฉพาะตอน ไม่ว่าง, ปฏิเสธมา, หรือกำลังรอสายอยู่เท่านั้น (ไม่ตอบสนอง = กดได้) ***
                                                    $shouldDisable = ($isGlobalBusy || $isRejected || $isPending);
                                                    
                                                    $fadedClass = $shouldDisable ? 'opacity-50 pointer-events-none cursor-not-allowed' : '';
                                                    $inputDisabled = $shouldDisable ? 'disabled' : '';
                                                @endphp

                                                <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm transition-all group has-[:checked]:border-primary has-[:checked]:ring-1 has-[:checked]:ring-primary has-[:checked]:bg-blue-50/20 {{ $fadedClass }} {{ $isPending ? 'ring-2 ring-blue-300' : 'cursor-pointer' }}">
                                                    
                                                    <input name="officer_id" value="{{ $officer->id }}" 
                                                        data-lat="{{ $officer->lat }}" 
                                                        data-lng="{{ $officer->lng }}" 
                                                        data-officer-status="{{ $officerGlobalStatus }}"
                                                        class="absolute right-4 top-4 rounded-full border-slate-300 text-primary focus:ring-primary h-5 w-5 assign-radio" 
                                                        type="radio" required {{ $inputDisabled }} />
                                                    
                                                    <div class="flex items-center gap-4 w-full">
                                                        <div class="size-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                                            <span class="material-symbols-outlined text-[24px]">directions_car</span>
                                                        </div>
                                                        <div class="flex-1 pr-8">
                                                            <div class="flex justify-between items-start mb-1">
                                                                <div class="flex items-center gap-2">
                                                                    <h4 class="font-bold text-slate-900">{{ $officer->name_officer }}</h4>
                                                                    @if($sendCount > 0)
                                                                        <span class="bg-slate-200 text-slate-600 text-[9px] px-1.5 py-0.5 rounded font-bold">ส่งแล้ว {{ $sendCount }} ครั้ง</span>
                                                                    @endif
                                                                </div>
                                                                <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">
                                                                    {{ $officer->distance_km ?? 0 }} กม.
                                                                </span>
                                                            </div>

                                                            {{-- ส่วนป้ายสถานะ --}}
                                                            <div class="mt-2 flex flex-wrap gap-1 status-wrapper">
                                                                @if($isPending)
                                                                    <div class="text-[11px] font-bold text-blue-700 bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-300 flex items-center gap-2">
                                                                        <span class="material-symbols-outlined text-[14px] animate-spin">sync</span>
                                                                        รอตอบรับ... <span class="waiting-timer" data-time="{{ $logDt }}"></span>
                                                                    </div>
                                                                @elseif($officerGlobalStatus === 'Helping')
                                                                    <div class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded-lg border border-purple-200 flex items-center gap-1">
                                                                        <span class="material-symbols-outlined text-[14px]">sync_problem</span> กำลังช่วยเหลือเคสอื่น
                                                                    </div>
                                                                @elseif($officerGlobalStatus === 'None')
                                                                    <div class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg border border-slate-200 flex items-center gap-1">
                                                                        <span class="material-symbols-outlined text-[14px]">person_off</span> ออฟไลน์
                                                                    </div>
                                                                @elseif($isRejected)
                                                                    <div class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg border border-red-200 flex items-center gap-1">
                                                                        <span class="material-symbols-outlined text-[14px]">cancel</span> ปฏิเสธเคสล่าสุด
                                                                    </div>
                                                                @elseif($isNoRespond)
                                                                    <div class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg border border-amber-200 flex items-center gap-1">
                                                                        <span class="material-symbols-outlined text-[14px]">timer_off</span> ไม่ตอบสนอง (รอ {{ $timeText }})
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            @empty
                                                <p>ไม่พบรายชื่อ</p>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <div class="p-5 border-t border-slate-100 bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] shrink-0 z-20">
                                <button type="submit" id="submitAssignBtn" class="w-full py-4 bg-primary hover:bg-blue-600 text-white font-bold text-sm uppercase tracking-wide rounded-xl shadow-lg shadow-blue-500/25 flex items-center justify-center gap-3 transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:hover:translate-y-0 disabled:cursor-not-allowed" disabled>
                                    <span>สั่งการและมอบหมายงาน</span>
                                    <span class="material-symbols-outlined">send</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- ================= โหมด 2: กำลังปฏิบัติหน้าที่ (Real-time Tracking) ================= --}}
                    <div id="active-officer-section" class="flex flex-col h-full {{ !$isActiveOperation ? 'hidden' : '' }} bg-slate-50/50 p-5">
                        
                        <div class="flex items-start gap-4">
                            @php
                                $officerPhoto = null;
                                $officerPhone = null;
                                
                                // ดึง user_officers_id จาก emergency_operations
                                $opOfficerId = $emergency->operation->user_officers_id ?? null;

                                if ($opOfficerId) {
                                    // ดึงข้อมูลจากตาราง user_officers
                                    $officerRecord = \App\Models\User_officer::find($opOfficerId);
                                    
                                    // ถ้าเจอข้อมูล ให้เอา user_id ไปหาในตาราง users ต่อ
                                    if ($officerRecord && $officerRecord->user_id) {
                                        $userRecord = \App\User::find($officerRecord->user_id);
                                        
                                        if ($userRecord) {
                                            $officerPhoto = $userRecord->photo;
                                            // ดึงเบอร์โทรจากตาราง users มาเก็บไว้ตรงนี้เลย
                                            $officerPhone = $userRecord->phone; 
                                        }
                                    }
                                }
                            @endphp

                            <div class="flex items-center justify-between w-full gap-4 relative z-20">
                                    
                                {{-- ฝั่งซ้าย: รูปโปรไฟล์ และ ข้อมูล --}}
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="size-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 border-2 border-white shadow-sm overflow-hidden">
                                        @if($officerPhoto)
                                            <img src="{{ url('/') }}/{{ $officerPhoto }}" alt="Officer" class="w-full h-full object-cover" onerror="this.outerHTML='<span class=\'material-symbols-outlined text-[28px] text-blue-600\'>support_agent</span>'">
                                        @else
                                            <span class="material-symbols-outlined text-[28px]">support_agent</span>
                                        @endif
                                    </div>
                                    
                                    <div class="min-w-0 pr-2">
                                        <h4 class="font-bold text-lg text-slate-900 truncate" id="active-officer-name">
                                            {{ $acceptedOfficer->name_officer ?? 'ไม่ระบุชื่อ' }}
                                        </h4>
                                        <div class="mt-1">
                                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded border border-slate-200">
                                                {{ $acceptedOfficer->vehicle_type ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- ฝั่งขวา: ปุ่มโทรพร้อมเบอร์ --}}
                                @if($officerPhone)
                                <a href="tel:0999991234" class="ml-auto flex items-center gap-1.5 px-3 py-2 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white hover:shadow-md transition-all shrink-0 group" title="โทรติดต่อเจ้าหน้าที่">
                                    <span class="material-symbols-outlined text-[18px] group-hover:animate-pulse">call</span>
                                    <span class="text-xs font-bold font-mono">0999991234</span>
                                </a>
                                @endif

                            </div>
                        </div>

                        <hr class="my-4 border-slate-100">

                        {{-- ส่วนแสดงระยะทางและเวลา โชว์เมื่อ "กำลังไปช่วยเหลือ" --}}
                        <div id="routing-info-container" class="{{ $currentOpStatus == 'ถึงที่เกิดเหตุ' || $currentOpStatus == 'เสร็จสิ้น' ? 'hidden' : '' }}">
                            
                            <div class="mb-2 text-xs font-medium text-slate-500 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">history</span>
                                รับพิกัดเจ้าหน้าที่ล่าสุดเมื่อ: <span id="last-location-time" class="text-slate-800 font-bold tracking-wide">-</span>
                            </div>

                            <div class="mb-3 p-2.5 bg-amber-50 rounded-lg border border-amber-200 text-[11px] text-amber-700 flex items-start gap-2 shadow-sm">
                                <span class="material-symbols-outlined text-[16px] text-amber-500 shrink-0">info</span>
                                <p class="leading-relaxed">ระยะทางและเวลาคำนวณจาก <strong>จุดเริ่มต้น</strong><br>เมื่อเวลาออกเดินทาง: <span id="start-help-time" class="font-bold text-amber-800">-</span></p>
                            </div>

                            <div id="routing-info" class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 rounded-lg p-3 border border-blue-100/50">
                                    <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wide mb-1">ระยะทางทั้งหมด</p>
                                    <p class="text-lg font-bold text-slate-800" id="distance-text">คำนวณ...</p>
                                </div>
                                <div class="bg-emerald-50 rounded-lg p-3 border border-emerald-100/50">
                                    <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-wide mb-1">คาดว่าจะไปถึง</p>
                                    <p class="text-lg font-bold text-slate-800" id="duration-text">คำนวณ...</p>
                                </div>
                            </div>
                        </div>

                        {{-- ส่วนแสดงเวลาที่ถึง โชว์เมื่อ "ถึงที่เกิดเหตุ" --}}
                        <div id="arrived-info" class="bg-emerald-50 rounded-lg p-4 border border-emerald-200 {{ $currentOpStatus != 'ถึงที่เกิดเหตุ' ? 'hidden' : '' }}">
                            <div class="flex flex-col items-center text-center mb-4">
                                <h4 class="font-bold text-emerald-700 text-sm">เจ้าหน้าที่ถึงที่เกิดเหตุแล้ว</h4>
                                
                                <div class="grid grid-cols-2 gap-2 w-full mt-3">
                                    <div class="bg-white/60 p-2 rounded border border-emerald-100">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">เวลาที่ถึงจริง</p>
                                        <p class="text-md font-bold text-slate-700" id="arrived-time-text">
                                            {{ $emergency->operation->time_to_the_scene ? \Carbon\Carbon::parse($emergency->operation->time_to_the_scene)->format('H:i น.') : '-' }}
                                        </p>
                                    </div>
                                    <div class="bg-white/60 p-2 rounded border border-emerald-100">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">ระยะเวลาเดินทาง</p>
                                        <p class="text-md font-bold text-slate-700" id="travel-duration-text">-</p>
                                    </div>
                                </div>
                            </div>

                            {{-- ส่วนแสดงรูปภาพที่เจ้าหน้าที่ถ่ายส่งมา --}}
                            <div id="officer-report-photo" class="hidden mt-3 pt-3 border-t border-emerald-200/50">
                                <p class="text-[10px] font-bold text-emerald-600 mb-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">photo_camera</span> ภาพถ่ายจากที่เกิดเหตุ
                                </p>
                                <div class="relative aspect-video bg-slate-200 rounded-lg overflow-hidden group/off">
                                    <img id="img-from-officer" src="" class="w-full h-full object-cover">
                                    <button onclick="openFullImage(this.previousElementSibling.src)" class="absolute inset-0 bg-black/40 opacity-0 group-hover/off:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-2">
                                        <span class="material-symbols-outlined">zoom_in</span> ดูภาพขยาย
                                    </button>
                                </div>
                                <div class="bg-white/60 mt-1 p-3 rounded-lg border border-emerald-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">หมายเหตุจากเจ้าหน้าที่</p>
                                    <p id="remark-from-officer" class="text-xs text-slate-600 leading-relaxed"></p>
                                </div>
                            </div>
                        </div>

                        {{-- ส่วนแสดงผลสถานะเสร็จสิ้น (Timeline & Comparison) --}}
                        <div id="success-info" class="bg-slate-50 rounded-xl p-5 border border-slate-200 flex flex-col gap-6 overflow-y-auto custom-scrollbar max-h-[600px] {{ $currentOpStatus != 'เสร็จสิ้น' ? 'hidden' : '' }}">
                            
                            <!-- ส่วนสรุปข้อมูลหลักและระยะทาง -->
                            <div class="flex items-center justify-between border-b border-slate-200 pb-3 shrink-0">
                                <h4 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-500">task_alt</span> สรุปภารกิจ
                                </h4>
                                <div class="bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-lg flex items-center gap-1.5 shadow-sm">
                                    <span class="material-symbols-outlined text-[16px] text-indigo-500">route</span>
                                    <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wide">ระยะทางรวม</span>
                                    <span class="text-sm font-bold text-indigo-700" id="tm-distance">{{ $emergency->operation->distance ?? '-' }}</span>
                                </div>
                            </div>

                            <!-- ไทม์ไลน์การช่วยเหลือ (แบ่ง 2 คอลัมน์) -->
                            <div class="shrink-0">
                                <div class="grid grid-cols-2 gap-x-4 gap-y-6 relative before:absolute before:left-[15px] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                                    <div class="relative pl-10">
                                        <div class="absolute left-0 top-0 size-8 rounded-full bg-blue-100 border-2 border-white shadow-sm flex items-center justify-center z-10">
                                            <span class="material-symbols-outlined text-[16px] text-blue-600">notifications</span>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">รับแจ้งเหตุ</p>
                                        <div class="flex flex-col">
                                            <span class="text-base font-bold text-blue-700 leading-none" id="tm-create-sos-time">{{ $emergency->operation->time_create_sos ? \Carbon\Carbon::parse($emergency->operation->time_create_sos)->format('H:i น.') : '-' }}</span>
                                            <span class="text-[10px] font-medium text-slate-500 mt-0.5" id="tm-create-sos-date">{{ $emergency->operation->time_create_sos ? \Carbon\Carbon::parse($emergency->operation->time_create_sos)->format('d M Y') : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="relative pl-10">
                                        <div class="absolute left-0 top-0 size-8 rounded-full bg-amber-100 border-2 border-white shadow-sm flex items-center justify-center z-10">
                                            <span class="material-symbols-outlined text-[16px] text-amber-600">send</span>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">สั่งการมอบหมาย</p>
                                        <div class="flex flex-col">
                                            <span class="text-base font-bold text-amber-700 leading-none" id="tm-command-time">{{ $emergency->operation->time_command ? \Carbon\Carbon::parse($emergency->operation->time_command)->format('H:i น.') : '-' }}</span>
                                            <span class="text-[10px] font-medium text-slate-500 mt-0.5" id="tm-command-date">{{ $emergency->operation->time_command ? \Carbon\Carbon::parse($emergency->operation->time_command)->format('d M Y') : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="relative pl-10">
                                        <div class="absolute left-0 top-0 size-8 rounded-full bg-indigo-100 border-2 border-white shadow-sm flex items-center justify-center z-10">
                                            <span class="material-symbols-outlined text-[16px] text-indigo-600">directions_car</span>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">ออกเดินทาง</p>
                                        <div class="flex flex-col">
                                            <span class="text-base font-bold text-indigo-700 leading-none" id="tm-go-help-time">{{ $emergency->operation->time_go_to_help ? \Carbon\Carbon::parse($emergency->operation->time_go_to_help)->format('H:i น.') : '-' }}</span>
                                            <span class="text-[10px] font-medium text-slate-500 mt-0.5" id="tm-go-help-date">{{ $emergency->operation->time_go_to_help ? \Carbon\Carbon::parse($emergency->operation->time_go_to_help)->format('d M Y') : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="relative pl-10">
                                        <div class="absolute left-0 top-0 size-8 rounded-full bg-purple-100 border-2 border-white shadow-sm flex items-center justify-center z-10">
                                            <span class="material-symbols-outlined text-[16px] text-purple-600">location_on</span>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">ถึงที่เกิดเหตุ</p>
                                        <div class="flex flex-col">
                                            <span class="text-base font-bold text-purple-700 leading-none" id="tm-at-scene-time">{{ $emergency->operation->time_to_the_scene ? \Carbon\Carbon::parse($emergency->operation->time_to_the_scene)->format('H:i น.') : '-' }}</span>
                                            <span class="text-[10px] font-medium text-slate-500 mt-0.5" id="tm-at-scene-date">{{ $emergency->operation->time_to_the_scene ? \Carbon\Carbon::parse($emergency->operation->time_to_the_scene)->format('d M Y') : '-' }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- ปิดเคสสำเร็จ -->
                                    <div class="relative pl-12 col-span-2 bg-emerald-50 p-4 rounded-xl border border-emerald-200 mt-2 shadow-sm">
                                        <div class="absolute left-3 top-5 size-9 rounded-full bg-emerald-500 border-2 border-white shadow-md flex items-center justify-center z-10">
                                            <span class="material-symbols-outlined text-[18px] text-white">check_circle</span>
                                        </div>
                                        <div class="flex justify-between items-center ml-2">
                                            <div>
                                                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide mb-1">ปิดเคสสำเร็จ</p>
                                                <div class="flex items-baseline gap-2">
                                                    <span class="text-xl font-bold text-emerald-800" id="tm-success-time">{{ $emergency->operation->time_sos_success ? \Carbon\Carbon::parse($emergency->operation->time_sos_success)->format('H:i น.') : '-' }}</span>
                                                    <span class="text-xs font-medium text-slate-500" id="tm-success-date">{{ $emergency->operation->time_sos_success ? \Carbon\Carbon::parse($emergency->operation->time_sos_success)->format('d M Y') : '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right bg-white px-3 py-2 rounded-lg border border-emerald-100 shadow-sm">
                                                <span class="text-[9px] font-bold text-slate-400 uppercase block mb-1">ใช้เวลาสุทธิ</span>
                                                <span class="text-sm font-bold text-emerald-700" id="tm-sum">{{ $emergency->operation->time_sum_sos ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200 shrink-0">
                                <!-- ภาพขณะถึงที่เกิดเหตุ -->
                                <div class="space-y-2">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">add_a_photo</span> ภาพขณะถึงที่เกิดเหตุ</p>
                                    <div class="aspect-square rounded-xl bg-slate-100 overflow-hidden border border-slate-200 relative group/photo flex items-center justify-center shadow-inner">
                                        <span id="no-img-officer" class="text-[11px] font-bold text-slate-400 uppercase tracking-wider {{ $emergency->operation->photo_by_officer ? 'hidden' : '' }}">ไม่มีภาพถ่าย</span>
                                        <img id="img-officer" src="{{ $emergency->operation->photo_by_officer ? url('/storage/'.$emergency->operation->photo_by_officer) : '' }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover/photo:scale-105 {{ $emergency->operation->photo_by_officer ? '' : 'hidden' }}">
                                        <div id="zoom-img-officer" class="absolute inset-0 bg-black/40 opacity-0 group-hover/photo:opacity-100 transition-all flex items-center justify-center backdrop-blur-[2px] {{ $emergency->operation->photo_by_officer ? '' : 'hidden' }}">
                                            <button onclick="openFullImage(this.parentElement.previousElementSibling.src)" class="text-slate-800 bg-white/90 hover:bg-white text-[11px] font-bold flex items-center gap-1.5 px-4 py-2 rounded-full shadow-lg transform translate-y-2 group-hover/photo:translate-y-0 transition-all">
                                                <span class="material-symbols-outlined text-[16px]">zoom_in</span> ดูภาพเต็ม
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bg-white p-2.5 rounded-lg border border-slate-200 min-h-[44px]">
                                        <p id="rm-officer" class="text-[11px] text-slate-600 line-clamp-2 leading-relaxed">{{ $emergency->operation->remark_photo_by_officer ?? 'ไม่มีหมายเหตุ' }}</p>
                                    </div>
                                </div>
                                
                                <!-- ภาพงานสำเร็จ -->
                                <div class="space-y-2">
                                    <p class="text-[10px] font-bold text-emerald-600 uppercase flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">verified</span> ภาพงานสำเร็จ</p>
                                    <div class="aspect-square rounded-xl bg-slate-100 overflow-hidden border border-emerald-200 relative group/photo flex items-center justify-center shadow-inner">
                                        <span id="no-img-success" class="text-[11px] font-bold text-slate-400 uppercase tracking-wider {{ $emergency->operation->photo_succeed ? 'hidden' : '' }}">ไม่มีภาพถ่าย</span>
                                        <img id="img-success" src="{{ $emergency->operation->photo_succeed ? url('/storage/'.$emergency->operation->photo_succeed) : '' }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover/photo:scale-105 {{ $emergency->operation->photo_succeed ? '' : 'hidden' }}">
                                        <div id="zoom-img-success" class="absolute inset-0 bg-black/40 opacity-0 group-hover/photo:opacity-100 transition-all flex items-center justify-center backdrop-blur-[2px] {{ $emergency->operation->photo_succeed ? '' : 'hidden' }}">
                                            <button onclick="openFullImage(this.parentElement.previousElementSibling.src)" class="text-slate-800 bg-white/90 hover:bg-white text-[11px] font-bold flex items-center gap-1.5 px-4 py-2 rounded-full shadow-lg transform translate-y-2 group-hover/photo:translate-y-0 transition-all">
                                                <span class="material-symbols-outlined text-[16px]">zoom_in</span> ดูภาพเต็ม
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bg-emerald-50/50 p-2.5 rounded-lg border border-emerald-100 min-h-[44px]">
                                        <p id="rm-success" class="text-[11px] text-emerald-800 line-clamp-2 leading-relaxed">{{ $emergency->operation->remark_by_helper ?? 'ไม่มีหมายเหตุ' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= Modal ยืนยันเสร็จสิ้นภารกิจ ================= --}}
<div id="completeModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
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

{{-- ================= Modal แจ้งเตือนเจ้าหน้าที่ปฏิเสธงาน ================= --}}
<div id="refusedModal" class="hidden fixed inset-0 z-[110] overflow-y-auto bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1a2632] rounded-2xl w-full max-w-sm shadow-2xl relative border border-red-200 dark:border-red-900/50 transform scale-95 transition-all duration-300" id="refusedModalContent">
            <div class="p-6 text-center space-y-4">
                <div class="mx-auto size-16 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center mb-4 shadow-inner">
                    <span class="material-symbols-outlined text-4xl animate-bounce">warning</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">เจ้าหน้าที่ปฏิเสธเคส!</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">เจ้าหน้าที่ที่คุณมอบหมายได้ปฏิเสธการรับงาน กรุณาสั่งการและมอบหมายงานให้เจ้าหน้าที่ท่านอื่นโดยด่วน</p>
                <button type="button" onclick="closeRefusedModal()" class="mt-4 w-full px-5 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-500/30 rounded-xl transition-all active:scale-95">
                    รับทราบและสั่งการใหม่
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ================= JavaScript Core ================= --}}
<script>
    const emergencyId = "{{ $emergency->id }}";
    const operationId = "{{ $emergency->operation->id ?? '' }}";
    let currentOpStatus = "{{ $emergency->operation->status ?? 'รับแจ้งเหตุ' }}";
    let pollingInterval = null;

    let initialOfficerLat = null;
    let initialOfficerLng = null;
    let startMarkerObj = null;

    let notifiedRefusedOfficers = @json(json_decode($emergency->operation->officer_refuse ?? '[]', true) ?? []).map(Number);

    // ================== ส่วนของ UI พื้นฐาน ==================
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

    function openRefusedModal() {
        const modal = document.getElementById('refusedModal');
        const content = document.getElementById('refusedModalContent');
        modal.classList.remove('hidden');
        const audio = new Audio("{{ url('/sounds/Update_Status.mp3') }}");
        audio.play().catch(e => console.log("Browser block autoplay:", e));

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closeRefusedModal() {
        const modal = document.getElementById('refusedModal');
        const content = document.getElementById('refusedModalContent');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function showToast(statusText, type = 'info') {
        if (statusText === 'สั่งการ') return; 

        const toast = document.getElementById('statusToast');
        const toastTitle = document.getElementById('toastTitle');
        toastTitle.innerText = `${statusText}`;
        document.getElementById('toastMessage').innerText = `อัปเดตสถานะการทำงาน`;
        const iconContainer = document.getElementById('toastIconContainer');
        const icon = document.getElementById('toastIcon');

        toast.className = 'fixed top-20 left-1/2 z-[100] transform transition-all duration-300 -translate-x-1/2 flex items-center gap-3 bg-white px-5 py-3 rounded-xl shadow-2xl min-w-[320px]';

        if(type === 'success') {
            iconContainer.className = 'h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0';
            icon.innerText = 'check_circle';
            toast.classList.add('border-b-4', 'border-emerald-500');
        } else if(type === 'warning') {
            iconContainer.className = 'h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0';
            icon.innerText = 'assignment_ind';
            toast.classList.add('border-b-4', 'border-amber-500');
        } else {
            iconContainer.className = 'h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0';
            icon.innerText = 'notifications_active';
            toast.classList.add('border-b-4', 'border-blue-500');
        }

        toast.classList.remove('-translate-y-[150%]', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        const audio = new Audio("{{ url('/sounds/Update_Status.mp3') }}");
        audio.play().catch(error => console.log("Browser block autoplay:", error));

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('-translate-y-[150%]', 'opacity-0');
        }, 4000);
    }

    function updateStatusUI(status) {
        document.getElementById('current-status-text').innerText = status;
        const colorBar = document.getElementById('status-color-bar');
        colorBar.className = 'absolute left-0 top-0 bottom-0 w-1 rounded-l-md transition-colors duration-300';

        switch(status) {
            case 'รับแจ้งเหตุ': colorBar.classList.add('bg-slate-400'); break;
            case 'สั่งการ': colorBar.classList.add('bg-amber-500'); break;
            case 'กำลังไปช่วยเหลือ': colorBar.classList.add('bg-blue-500'); break;
            case 'ถึงที่เกิดเหตุ': colorBar.classList.add('bg-purple-500'); break;
            case 'เสร็จสิ้น': colorBar.classList.add('bg-emerald-500'); break;
            default: colorBar.classList.add('bg-slate-400');
        }
    }

    function getStatusColorType(status) {
        if(status === 'เสร็จสิ้น') return 'success';
        if(status === 'สั่งการ') return 'warning';
        return 'info';
    }

    // ================== ระบบเวลา Elapsed Timer ==================
    const sosTime = "{{ $emergency->operation->time_create_sos ? \Carbon\Carbon::parse($emergency->operation->time_create_sos)->toISOString() : $emergency->created_at->toISOString() }}";
    const startTime = new Date(sosTime).getTime();
    
    function updateTimer() {
        // หากสถานะเสร็จสิ้นแล้ว ให้หยุดนับเวลาและดึงข้อมูลสรุปมาแสดงแทน
        if (currentOpStatus === 'เสร็จสิ้น') {
            const titleEl = document.getElementById('timer-title');
            if (titleEl) titleEl.innerText = 'ใช้เวลาสุทธิ';
            
            const pingEl = document.getElementById('timer-ping');
            if (pingEl) pingEl.classList.add('hidden'); // ซ่อนจุดกระพริบ
            
            const sumText = document.getElementById('tm-sum')?.innerText;
            if (sumText && sumText !== '-') {
                document.getElementById("elapsed-time").innerText = sumText;
            }
            return; 
        }

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
        
        if (ping) ping.classList.remove("hidden");

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

    // ================== ระบบนับเวลาสำหรับคนที่กำลังรอการตอบรับ ==================
    function updateWaitingTimers() {
        const timers = document.querySelectorAll('.waiting-timer');
        
        timers.forEach(timer => {
            const startTimeStr = timer.getAttribute('data-time');
            if (!startTimeStr) return;

            const startTime = new Date(startTimeStr).getTime();
            const now = new Date().getTime();
            const diffSeconds = Math.floor((now - startTime) / 1000);

            if (diffSeconds >= 0) {
                if (diffSeconds < 60) {
                    timer.innerText = diffSeconds + ' วิ';
                } else {
                    const mins = Math.floor(diffSeconds / 60);
                    const secs = diffSeconds % 60;
                    timer.innerText = `${mins} นาที ${secs} วิ`;
                }
            }
        });
    }

    // อัปเดตทุกๆ 1 วินาที
    setInterval(updateWaitingTimers, 1000);
    // เรียกใช้ทันทีเมื่อโหลดหน้าจอ
    document.addEventListener('DOMContentLoaded', updateWaitingTimers);

    // ================== ระบบ Map & หมุด ==================
    let mapInstance;
    let incidentLatLng;
    let officerMarkerMap = null; 
    let CustomMarker; 
    
    let directionsService;
    let directionsRenderer;
    let isRouteDrawn = false;
    let hasRecoveredRoute = false; // เอาไว้เช็คว่าดึงข้อมูลเส้นทางเดิมมาวาดแล้วหรือยัง

    function initAssignMap() {
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
            updatePosition(newLatLng) {
                this.position = newLatLng;
                this.draw();
            }
        };

        const incidentLat = {{ $emergency->emergency_lat ?? 13.7563 }};
        const incidentLng = {{ $emergency->emergency_lng ?? 100.5018 }};
        incidentLatLng = new google.maps.LatLng(incidentLat, incidentLng);

        mapInstance = new google.maps.Map(document.getElementById("assign-map"), {
            zoom: 14,
            center: incidentLatLng,
            disableDefaultUI: true, 
            zoomControl: true,
            mapTypeId: 'roadmap',
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: mapInstance,
            suppressMarkers: true,
            polylineOptions: { strokeColor: "#3b82f6", strokeWeight: 5, strokeOpacity: 0.8 }
        });

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
            </div>`;
        new CustomMarker(incidentLatLng, mapInstance, incidentHtml);

        if (['กำลังไปช่วยเหลือ', 'ถึงที่เกิดเหตุ'].includes(currentOpStatus)) {
            startOperationPolling(); 
        } else {
            const officersData = @json($officers);
            officersData.forEach(officer => {
                if (officer.lat && officer.lng) {
                    let displayName = officer.name_officer;
                    if (displayName.length > 8) displayName = displayName.substring(0, 8) + '..';
                    
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
                        </div>`;
                    new CustomMarker(new google.maps.LatLng(officer.lat, officer.lng), mapInstance, officerHtml);
                }
            });
            initAssignClickEvents();
            startOperationPolling();
        }
    }

    function initAssignClickEvents() {
        let currentSelectedRadio = null;
        const submitBtn = document.getElementById('submitAssignBtn');
        
        document.querySelectorAll('.assign-radio').forEach(radio => {
            radio.addEventListener('click', function(e) {

                if (this.disabled) {
                    e.preventDefault();
                    return;
                }

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
                        bounds.extend(new google.maps.LatLng(lat, lng));
                        mapInstance.fitBounds(bounds, { top: 60, bottom: 60, left: 60, right: 60 });
                    }
                }
            });
        });
    }

    // ================== ระบบ Polling (ติดตามงาน/กู้คืนเส้นทาง) ==================
    let pollingTimer = null;

    function startOperationPolling() {
        if(pollingTimer) clearTimeout(pollingTimer);
        fetchOperationData(); 
    }

    async function fetchOperationData() {
        try {
            const response = await fetch("{{ url('/api/emergency') }}"+`/${emergencyId}/operation`);
            if(!response.ok) {
                // ถ้ายิงไม่ผ่าน ให้ลองใหม่ใน 10 วินาที
                pollingTimer = setTimeout(fetchOperationData, 10000);
                return;
            }
            const data = await response.json();
            // console.log(data);

            // 1. ตรวจสอบการเปลี่ยน Status
            if (data.status && data.status !== currentOpStatus) {
                const previousStatus = currentOpStatus;
                currentOpStatus = data.status;
                
                updateStatusUI(currentOpStatus);
                showToast(currentOpStatus, getStatusColorType(currentOpStatus));

                if (previousStatus === 'สั่งการ' && currentOpStatus === 'กำลังไปช่วยเหลือ') {
                    setTimeout(() => window.location.reload(), 4000);
                    return;
                }

                if (currentOpStatus === 'ถึงที่เกิดเหตุ' || currentOpStatus === 'เสร็จสิ้น') {
                    const routingInfo = document.getElementById('routing-info-container');
                    const arrivedInfo = document.getElementById('arrived-info');
                    const successInfo = document.getElementById('success-info');
                    
                    // ซ่อนข้อมูลเส้นทางเสมอ เมื่อถึงที่เกิดเหตุหรือเสร็จสิ้นแล้ว
                    if(routingInfo) routingInfo.classList.add('hidden');

                    if (currentOpStatus === 'ถึงที่เกิดเหตุ') {
                        // UI: โชว์ข้อมูลถึงที่เกิดเหตุ ซ่อนข้อมูลเสร็จสิ้น
                        if(arrivedInfo) arrivedInfo.classList.remove('hidden'); 
                        if(successInfo) successInfo.classList.add('hidden');    
                        
                        // Map: เคลียร์แผนที่ทั้งหมด
                        if (officerMarkerMap) officerMarkerMap.onRemove();
                        if (directionsRenderer) directionsRenderer.setMap(null);
                        if (startMarkerObj) startMarkerObj.onRemove();
                        isRouteDrawn = false; // รีเซ็ตเพื่อรอให้สถานะเสร็จสิ้นสามารถวาดเส้นประวัติศาสตร์ได้
                        
                        // อัปเดตเวลาถึงที่เกิดเหตุ
                        if(data.time_to_the_scene) {
                            const time = new Date(data.time_to_the_scene).toLocaleTimeString('th-TH', {hour: '2-digit', minute:'2-digit'});
                            document.getElementById('arrived-time-text').innerText = 'เวลา: ' + time + ' น.';
                        }
                    } else if (currentOpStatus === 'เสร็จสิ้น') {
                        // UI: โชว์ข้อมูลเสร็จสิ้น ซ่อนข้อมูลถึงที่เกิดเหตุ
                        if(successInfo) successInfo.classList.remove('hidden'); 
                        if(arrivedInfo) arrivedInfo.classList.add('hidden');    
                    }
                }
            }

            // 2. จัดการข้อมูลคนปฏิเสธเคส
            if (data.officer_refuse && Array.isArray(data.officer_refuse)) {
                let hasNewRefusal = false;
                data.officer_refuse.forEach(refusedId => {
                    const parsedId = parseInt(refusedId);
                    if (!notifiedRefusedOfficers.includes(parsedId)) {
                        hasNewRefusal = true;
                        notifiedRefusedOfficers.push(parsedId);
                    }
                });
                if (hasNewRefusal) openRefusedModal();
            }

            // 3. จัดการสถานะและกู้คืนเส้นทาง เฉพาะกำลังไปช่วยเหลือ
            if (currentOpStatus === 'กำลังไปช่วยเหลือ') {
                            
                if (data.officer_last_update) {
                    const lastUpdate = new Date(data.officer_last_update);
                    const now = new Date();
                    const diffMs = now - lastUpdate;
                    const diffMins = Math.floor(diffMs / 60000); 

                    let relativeTime = diffMins > 0 ? `(${diffMins} นาทีที่แล้ว)` : `(เมื่อครู่)`;
                    const timeStr = lastUpdate.toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    
                    document.getElementById('last-location-time').innerHTML = `${timeStr} น. <span class="text-blue-500 font-medium ml-1">${relativeTime}</span>`;
                }

                const currentLat = parseFloat(data.officer_lat);
                const currentLng = parseFloat(data.officer_lng);
                // รับค่าอายุพิกัดจาก PHP
                const diffMins = data.location_diff_minutes; 

                let activeLog = null;
                if (data.log_command && Array.isArray(data.log_command)) {
                    const logs = [...data.log_command].reverse();
                    activeLog = logs.find(l => l.status === 'go_to_help');
                }

                // ถ้ามีพิกัด และยังไม่เคยวาดเส้น และยังไม่มี Polyline ในระบบ และพิกัดต้องอัปเดตล่าสุดไม่เกิน 5 นาที ถึงจะยอมวาดเส้นใหม่
                if (currentLat && currentLng && !isRouteDrawn && (!activeLog || !activeLog.polyline) && (diffMins == null || diffMins <= 5)) {
                    drawRouteToIncident(currentLat, currentLng);
                }

                if (activeLog && activeLog.polyline) {
                    if (activeLog.time_go_to_help) {
                        const startTime = new Date(activeLog.time_go_to_help);
                        document.getElementById('start-help-time').innerText = startTime.toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' }) + ' น.';
                        
                        const durationValue = activeLog.duration_value ? parseInt(activeLog.duration_value) : 0;
                        const arrivalTime = new Date(startTime.getTime() + (durationValue * 1000));
                        const arrivalStr = arrivalTime.toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
                        
                        document.getElementById('duration-text').innerText = `${arrivalStr} น. (${activeLog.duration_text || '-'})`;
                    }

                    document.getElementById('distance-text').innerText = activeLog.distance_text || '-';

                    if (!isRouteDrawn) {
                        const decodedPath = google.maps.geometry.encoding.decodePath(activeLog.polyline);
                        directionsRenderer.setMap(mapInstance);
                        
                        const recoveredPolyline = new google.maps.Polyline({
                            path: decodedPath,
                            strokeColor: "#3b82f6",
                            strokeWeight: 5,
                            strokeOpacity: 0.8
                        });
                        recoveredPolyline.setMap(mapInstance);
                        
                        const bounds = new google.maps.LatLngBounds();
                        decodedPath.forEach(latLng => bounds.extend(latLng));
                        mapInstance.fitBounds(bounds, { top: 60, bottom: 60, left: 60, right: 60 });

                        if (activeLog.start_lat && activeLog.start_lng) {
                            createStartFlag(activeLog.start_lat, activeLog.start_lng);
                        }
                        
                        isRouteDrawn = true;
                        hasRecoveredRoute = true;
                    }
                }

                if (currentLat && currentLng) {
                    updateOfficerLocationOnMap(currentLat, currentLng); 
                } else if (!isRouteDrawn && activeLog && activeLog.start_lat && activeLog.start_lng) {
                    updateOfficerLocationOnMap(activeLog.start_lat, activeLog.start_lng);
                }
            }

            // 4. จัดการ UI หน้าสั่งการ (ล้างสถานะ ปิดปุ่ม ฯลฯ)
            if (['รับแจ้งเหตุ', 'สั่งการ'].includes(currentOpStatus)) {
                document.querySelectorAll('.assign-radio').forEach(radio => {
                    const officerId = parseInt(radio.value);
                    const labelContainer = radio.closest('label');
                    const infoContainer = labelContainer.querySelector('.flex-1.pr-8');

                    // ฟังก์ชันช่วยเช็คข้อมูลใน Array
                    const checkInArray = (arr, val) => arr && Array.isArray(arr) && arr.some(item => String(item) === String(val));
                    const isRefused = checkInArray(data.officer_refuse, officerId);
                    const isNoRespond = checkInArray(data.officer_no_respond, officerId);
                    const isWaiting = (String(data.waiting_reply) === String(officerId));

                    // ดึงสถานะของเจ้าหน้าที่
                    const officerGlobalStatus = radio.dataset.officerStatus;
                    const isGlobalBusy = ['Helping', 'None'].includes(officerGlobalStatus);

                    // --- คำนวณเวลาที่รอของคนที่ไม่ตอบสนอง ---
                    let waitMin = 0, waitSec = 0;
                    if (data.log_command && Array.isArray(data.log_command)) {
                        const logs = [...data.log_command].reverse();
                        const officerLog = logs.find(l => String(l.sendTo) === String(officerId) && l.status === 'no_respond');
                        if (officerLog && officerLog.sum_time) {
                            const sumTime = parseInt(officerLog.sum_time);
                            waitMin = Math.floor(sumTime / 60);
                            waitSec = sumTime % 60;
                        }
                    }
                    let timeText = waitMin > 0 ? `${waitMin} นาที ` : '';
                    timeText += `${waitSec} วิ`;

                    // ล้าง class เดิมที่ล็อกปุ่มไว้ก่อน
                    labelContainer.classList.remove('opacity-50', 'pointer-events-none', 'cursor-not-allowed', 'ring-2', 'ring-blue-300');

                    // *** จัดการล็อกปุ่มใหม่ (สังเกตว่าผมเอา isNoRespond ออกแล้ว เพื่อให้กดได้!) ***
                    if (isWaiting) {
                        radio.disabled = true;
                        labelContainer.classList.add('pointer-events-none', 'cursor-not-allowed', 'ring-2', 'ring-blue-300');
                    } else if (isRefused || isGlobalBusy) {
                        radio.disabled = true;
                        labelContainer.classList.add('opacity-50', 'pointer-events-none', 'cursor-not-allowed');
                        const targetMarker = document.getElementById(`officer-marker-${officerId}`);
                        if (targetMarker && isRefused) {
                            const pulse = targetMarker.querySelector('.officer-pulse');
                            if (pulse) pulse.classList.add('hidden');
                        }
                    } else {
                        // ว่าง หรือ ไม่ตอบสนอง = ให้กดส่งซ้ำได้
                        radio.disabled = false;
                    }

                    // จัดการแสดงผลป้าย Badge
                    let statusDiv = infoContainer.querySelector('.status-wrapper');
                    
                    if (!statusDiv) {
                        const bladeStatus = infoContainer.querySelector('.mt-2');
                        let preserveTime = new Date().toISOString(); 
                        if (bladeStatus) {
                            const timerSpan = bladeStatus.querySelector('.waiting-timer');
                            if (timerSpan) preserveTime = timerSpan.getAttribute('data-time');
                            bladeStatus.remove();
                        }
                        statusDiv = document.createElement('div');
                        statusDiv.className = 'mt-2 flex flex-wrap gap-1 status-wrapper';
                        statusDiv.dataset.commandTime = preserveTime;
                        infoContainer.appendChild(statusDiv);
                    }

                    // แทรก HTML ให้คลาสและไอคอนเหมือน Blade
                    if (isWaiting) {
                        // เพื่อให้เวลาดึงค่า datetime ของ pending ล่าสุดมาแสดงได้ตรงเป๊ะแม้จะไม่ได้รีเฟรชหน้า
                        let pendingTime = statusDiv.dataset.commandTime;
                        if (data.log_command && Array.isArray(data.log_command)) {
                            const logs = [...data.log_command].reverse();
                            const pendingLog = logs.find(l => String(l.sendTo) === String(officerId) && l.status === 'pending');
                            if (pendingLog && pendingLog.datetime) pendingTime = pendingLog.datetime;
                        }

                        if (!statusDiv.querySelector('.waiting-timer')) {
                            statusDiv.innerHTML = `
                                <div class="text-[11px] font-bold text-blue-700 bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-300 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[14px] animate-spin">sync</span>
                                    รอตอบรับ... <span class="waiting-timer" data-time="${pendingTime}">0 วิ</span>
                                </div>`;
                        }
                    } else if (officerGlobalStatus === 'Helping') {
                        statusDiv.innerHTML = `
                            <div class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded-lg border border-purple-200 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">sync_problem</span> กำลังช่วยเหลือเคสอื่น
                            </div>`;
                    } else if (officerGlobalStatus === 'None') {
                        statusDiv.innerHTML = `
                            <div class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg border border-slate-200 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">person_off</span> ออฟไลน์
                            </div>`;
                    } else if (isRefused) {
                        statusDiv.innerHTML = `
                            <div class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg border border-red-200 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">cancel</span> ปฏิเสธเคสล่าสุด
                            </div>`;
                    } else if (isNoRespond) {
                        statusDiv.innerHTML = `
                            <div class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg border border-amber-200 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">timer_off</span> ไม่ตอบสนอง (รอ ${timeText})
                            </div>`;
                    } else {
                        statusDiv.innerHTML = ''; 
                    }
                });
            }

            // 5. "ถึงที่เกิดเหตุ" และข้อมูลภาพถ่าย
            if (currentOpStatus === 'ถึงที่เกิดเหตุ' || data.status === 'ถึงที่เกิดเหตุ') {
                
                // เอาเส้นทางและหมุดเจ้าหน้าที่ / หมุดจุดเริ่มต้นออก
                if (directionsRenderer) {
                    directionsRenderer.setMap(null);
                    isRouteDrawn = false; // คลีนสถานะการวาดเส้น
                }
                if (officerMarkerMap) {
                    officerMarkerMap.onRemove();
                    officerMarkerMap = null;
                }
                if (startMarkerObj) {
                    startMarkerObj.onRemove();
                    startMarkerObj = null;
                }

                // ขยับให้จุดเกิดเหตุอยู่ตรงกลาง
                if (mapInstance && incidentLatLng) {
                    mapInstance.panTo(incidentLatLng);
                    mapInstance.setZoom(16);
                }

                // แสดงข้อมูลเวลาจริงที่ถึง
                if (data.time_to_the_scene) {
                    const arrival = new Date(data.time_to_the_scene);
                    document.getElementById('arrived-time-text').innerText = arrival.toLocaleTimeString('th-TH', {hour: '2-digit', minute:'2-digit'}) + ' น.';
                    
                    // คำนวณเวลาที่ใช้เดินทาง (Duration)
                    let activeLog = [...(data.log_command || [])].reverse().find(l => l.time_go_to_help);
                    if (activeLog && activeLog.time_go_to_help) {
                        const start = new Date(activeLog.time_go_to_help);
                        const diffMs = arrival - start;

                        // คำนวณหน่วยเวลาจากผลต่างมิลลิวินาที
                        const hours = Math.floor(diffMs / 3600000);
                        const minutes = Math.floor((diffMs % 3600000) / 60000);
                        const seconds = Math.floor((diffMs % 60000) / 1000);

                        // สร้างข้อความแสดงผลตามเงื่อนไขที่มีค่าของหน่วยเวลานั้นๆ
                        let durationParts = [];
                        if (hours > 0) durationParts.push(hours + " ชม.");
                        if (minutes > 0) durationParts.push(minutes + " นาที");
                        if (seconds > 0 || durationParts.length === 0) durationParts.push(seconds + " วิ");

                        document.getElementById('travel-duration-text').innerText = durationParts.join(" ");
                    }
                }

                // แสดงรูปภาพและ Remark จากเจ้าหน้าที่ทันทีที่มีข้อมูลส่งมา
                if (data.photo_by_officer) {
                    const photoBox = document.getElementById('officer-report-photo');
                    const imgTag = document.getElementById('img-from-officer');
                    const remarkTag = document.getElementById('remark-from-officer');
                    
                    if (photoBox) photoBox.classList.remove('hidden');
                    if (imgTag) imgTag.src = "{{ url('/storage') }}/" + data.photo_by_officer;
                    if (remarkTag) remarkTag.innerText = data.remark_photo_by_officer || 'ไม่มีหมายเหตุ';
                }
            }

            // 6. จัดการสถานะ "เสร็จสิ้น" (วาดเส้นประวัติศาสตร์ + โชว์ข้อมูล)
            if (currentOpStatus === 'เสร็จสิ้น' || data.status === 'เสร็จสิ้น') {

                // 1. เอาหมุดเจ้าหน้าที่ออก
                if (officerMarkerMap) {
                    officerMarkerMap.onRemove();
                    officerMarkerMap = null;
                }

                // 2. ปิดเส้นนำทางเดิมแบบ Realtime
                if (directionsRenderer) directionsRenderer.setMap(null);

                // 3. หยุดกระพริบหมุดจุดเกิดเหตุ
                const pings = document.querySelectorAll('.animate-ping');
                pings.forEach(p => p.remove());

                // 4. วาดเส้นทางประวัติศาสตร์จาก log_command
                let activeLog = null;
                if (data.log_command && Array.isArray(data.log_command)) {
                    const logs = [...data.log_command].reverse();
                    activeLog = logs.find(l => l.polyline && (l.status === 'go_to_help' || l.status === 'accept'));
                    
                    const logWithDistance = logs.find(l => l.distance_text);
                    if (logWithDistance && logWithDistance.distance_text) {
                        if (document.getElementById('tm-distance')) {
                            document.getElementById('tm-distance').innerText = logWithDistance.distance_text;
                        }
                    }
                }

                if (activeLog && !isRouteDrawn) {
                    const decodedPath = google.maps.geometry.encoding.decodePath(activeLog.polyline);
                    new google.maps.Polyline({
                        path: decodedPath,
                        strokeColor: "#3b82f6",
                        strokeWeight: 4,
                        strokeOpacity: 0.6,
                        map: mapInstance
                    });

                    if (activeLog.start_lat && activeLog.start_lng) {
                        createStartFlag(activeLog.start_lat, activeLog.start_lng);
                        const bounds = new google.maps.LatLngBounds();
                        bounds.extend(new google.maps.LatLng(activeLog.start_lat, activeLog.start_lng));
                        bounds.extend(incidentLatLng);
                        mapInstance.fitBounds(bounds, { top: 80, bottom: 80, left: 80, right: 80 });
                    }
                    isRouteDrawn = true;
                }

                // 5. อัปเดตข้อมูล Text ในหน้าสรุป
                const successInfo = document.getElementById('success-info');
                if (successInfo) successInfo.classList.remove('hidden');

                const tmSum = document.getElementById('tm-sum');
                if (tmSum && data.time_sum_sos) {
                    tmSum.innerText = data.time_sum_sos.replace(/0\s?ชม\.?\s?/g, '').trim() || '-';
                }

                function updateTimeAndDate(elementIdPrefix, dateString) {
                    const timeEl = document.getElementById(`${elementIdPrefix}-time`);
                    const dateEl = document.getElementById(`${elementIdPrefix}-date`);
                    if (!timeEl || !dateEl) return;

                    if (!dateString) {
                        timeEl.innerText = '-';
                        dateEl.innerText = '-';
                        return;
                    }
                    const d = new Date(dateString);
                    if (isNaN(d)) return;
                    
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    timeEl.innerText = `${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')} น.`;
                    dateEl.innerText = `${d.getDate().toString().padStart(2, '0')} ${months[d.getMonth()]} ${d.getFullYear()}`;
                }

                updateTimeAndDate('tm-create-sos', data.time_create_sos);
                updateTimeAndDate('tm-command', data.time_command);
                updateTimeAndDate('tm-go-help', data.time_go_to_help);
                updateTimeAndDate('tm-at-scene', data.time_to_the_scene);
                updateTimeAndDate('tm-success', data.time_sos_success);

                const tmSum = document.getElementById('tm-sum');
                if (tmSum) tmSum.innerText = data.time_sum_sos || '-';

                // อัปเดตรูปภาพ (ถ้ามี)
                if (data.photo_by_officer) {
                    document.getElementById('no-img-officer').classList.add('hidden');
                    document.getElementById('img-officer').classList.remove('hidden');
                    document.getElementById('zoom-img-officer').classList.remove('hidden');
                    document.getElementById('img-officer').src = "{{ url('/storage') }}/" + data.photo_by_officer;
                    document.getElementById('rm-officer').innerText = data.remark_photo_by_officer || 'ไม่มีหมายเหตุ';
                }

                if (data.photo_succeed) {
                    document.getElementById('no-img-success').classList.add('hidden');
                    document.getElementById('img-success').classList.remove('hidden');
                    document.getElementById('zoom-img-success').classList.remove('hidden');
                    document.getElementById('img-success').src = "{{ url('/storage') }}/" + data.photo_succeed;
                    document.getElementById('rm-success').innerText = data.remark_by_helper || 'ไม่มีหมายเหตุ';
                }
            }

            // =========================================================
            // Smart Polling: กำหนดเวลาหน่วงสำหรับรอบถัดไป
            // =========================================================
            let nextPollTime = 5000; // ตอนสั่งการ/รับแจ้งเหตุ ยิงทุก 5 วินาที

            if (currentOpStatus === 'กำลังไปช่วยเหลือ') {
                nextPollTime = 10000; // ตอนกำลังเดินทาง ยิงเช็คทุก 10 วินาที
            }
            else if (currentOpStatus === 'ถึงที่เกิดเหตุ') {
                nextPollTime = 7000; // ถึงที่เกิดเหตุ ยิงเช็คทุก 7 วินาที
            }
            else if (currentOpStatus === 'เสร็จสิ้น') {
                nextPollTime = 60000; // เสร็จสิ้น ยิงเช็คทุก 60 วินาที
            }

            pollingTimer = setTimeout(fetchOperationData, nextPollTime);

        } catch (error) {
            console.error('Polling Error:', error);
            pollingTimer = setTimeout(fetchOperationData, 10000); // ถ้า Error ยิงใหม่ใน 10 วิ
        }
    }

    // ================== ฟังก์ชันวาดแผนที่/หมุดย่อย ==================
    function createStartFlag(lat, lng) {
        if (!CustomMarker || !mapInstance || startMarkerObj) return;
        const startLatLng = new google.maps.LatLng(lat, lng);
        const startHtml = `
            <div class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 z-30">
                <span class="relative inline-flex rounded-full h-8 w-8 bg-slate-800 border-[2px] border-white shadow-md items-center justify-center text-white">
                    <span class="material-symbols-outlined text-[16px]">flag</span>
                </span>
                <div class="mt-1 bg-slate-800 text-white text-[9px] font-bold px-2 py-0.5 rounded shadow-sm whitespace-nowrap">
                    จุดเริ่มต้น
                </div>
            </div>`;
        startMarkerObj = new CustomMarker(startLatLng, mapInstance, startHtml);
    }

    function updateOfficerLocationOnMap(lat, lng) {
        if (!CustomMarker || !mapInstance) return;
        const newLatLng = new google.maps.LatLng(lat, lng);
        
        if (!officerMarkerMap) {
            const officerHtml = `
                <div class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 z-40 transition-all duration-1000">
                    <span class="relative inline-flex rounded-full h-10 w-10 bg-blue-600 border-[3px] border-white shadow-xl items-center justify-center text-white">
                        <span class="material-symbols-outlined text-[20px]">directions_car</span>
                    </span>
                </div>`;
            officerMarkerMap = new CustomMarker(newLatLng, mapInstance, officerHtml);
        } else {
            officerMarkerMap.updatePosition(newLatLng);
        }
    }

    function drawRouteToIncident(officerLat, officerLng) {
        if (!mapInstance || isRouteDrawn) return;

        const startLatLng = new google.maps.LatLng(officerLat, officerLng);
        
        directionsService.route({
            origin: startLatLng,
            destination: incidentLatLng,
            travelMode: google.maps.TravelMode.DRIVING
        }, (response, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
                isRouteDrawn = true;

                const leg = response.routes[0].legs[0];
                const polyline = response.routes[0].overview_polyline;

                // อัปเดตการแสดงผลระยะทางและเวลาบน UI
                if (document.getElementById('distance-text')) {
                    document.getElementById('distance-text').innerText = leg.distance.text;
                }
                if (document.getElementById('duration-text')) {
                    document.getElementById('duration-text').innerText = leg.duration.text;
                }

                // ส่งข้อมูลพิกัดและรายละเอียดเส้นทางไปบันทึกใน log_command
                fetch(`{{ url('/api/emergency') }}/${emergencyId}/update-route-log`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        start_lat: officerLat,
                        start_lng: officerLng,
                        incident_lat: incidentLatLng.lat(),
                        incident_lng: incidentLatLng.lng(),
                        distance_text: leg.distance.text,
                        distance_value: leg.distance.value,
                        duration_text: leg.duration.text,
                        duration_value: leg.duration.value,
                        polyline: polyline
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) console.error(data.message);
                })
                .catch(err => console.error('Fetch error:', err));
            }
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initAssignMap&libraries=marker,geometry" async defer></script>
@endsection