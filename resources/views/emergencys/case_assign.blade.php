@extends('layouts.theme')

@section('content')

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

            {{-- สั่งการและมอบหมายงาน / ติดตามสถานะ --}}
            <div class="lg:col-span-5 xl:col-span-4 flex flex-col h-full gap-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 shrink-0">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">สถานะเหตุการณ์ปัจจุบัน</h3>
                    </div>
                    <div class="flex bg-slate-100 p-1.5 rounded-lg border border-slate-200 gap-1.5">
                        <div class="w-[70%] py-3 px-2 rounded-md bg-white text-slate-900 shadow-sm border border-slate-200 text-[12px] font-bold transition-all flex items-center justify-center relative overflow-hidden text-center leading-tight">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-slate-400 rounded-l-md transition-colors duration-300" id="status-color-bar"></div>
                            <span id="current-status-text">{{ $emergency->operation->status ?? 'รับแจ้งเหตุ' }}</span>
                        </div>
                        <button type="button" onclick="openCompleteModal()" class="w-[30%] py-3 px-1 rounded-md bg-emerald-100 hover:bg-emerald-200 text-emerald-700 shadow-sm border border-emerald-200 text-[12px] font-bold transition-all flex items-center justify-center gap-1">
                            เสร็จสิ้น
                        </button>
                    </div>
                </div>

                <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col overflow-hidden relative min-h-[400px]">
                    
                    @php
                        // ตัวแปรเช็คว่ามีคนรับงานและกำลังเดินทางอยู่หรือไม่
                        $currentOpStatus = $emergency->operation->status ?? '';
                        
                        $isActiveOperation = in_array($currentOpStatus, ['กำลังไปช่วยเหลือ', 'ถึงที่เกิดเหตุ', 'เสร็จสิ้น']);
                        $acceptedOfficerId = $emergency->operation->user_officers_id ?? null;
                        $acceptedOfficer = collect($officers)->firstWhere('id', $acceptedOfficerId);
                    @endphp

                    {{-- ================= โหมด 1: รอการมอบหมายงาน (Assign Form) ================= --}}
                    <div id="assign-officer-section" class="flex flex-col h-full {{ $isActiveOperation ? 'hidden' : '' }}">
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
                                    $noRespondList = json_decode($emergency->operation->officer_no_respond ?? '[]', true) ?? [];
                                    
                                    // รวมคนที่ไม่ตอบสนองและปฏิเสธเข้าด้วยกัน
                                    $isCannotAssign = in_array($officer->id, $refusedList) || in_array($officer->id, $noRespondList);
                                    
                                    $isWaiting = ($emergency->operation->waiting_reply ?? null) == $officer->id;

                                    $logCommands = json_decode($emergency->operation->log_command ?? '[]', true) ?? [];
                                    $isNoRespond = false;
                                    $noRespondTimeMin = 0;
                                    $noRespondTimeSec = 0;

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
                                    
                                    // หากกำลังรออยู่ เอาเวลาส่งคำสั่งมาเพื่อใช้นับ
                                    $commandTimeStr = '';
                                    if ($isWaiting && !empty($emergency->operation->time_command)) {
                                        $commandTimeStr = \Carbon\Carbon::parse($emergency->operation->time_command)->toISOString();
                                    }
                                @endphp

                                <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group has-[:checked]:border-primary has-[:checked]:ring-1 has-[:checked]:ring-primary has-[:checked]:bg-blue-50/20 {{ $isCannotAssign ? 'opacity-50 pointer-events-none cursor-not-allowed' : '' }}">
                                    <input name="officer_id" value="{{ $officer->id }}" data-lat="{{ $officer->lat }}" data-lng="{{ $officer->lng }}" class="absolute right-4 top-4 rounded-full border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer peer assign-radio" type="radio" required {{ $isCannotAssign ? 'disabled' : '' }} />
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
                                                <div class="mt-2 text-[11px] font-bold text-amber-700 bg-amber-100 px-3 py-1.5 rounded-lg border border-amber-300 flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-[14px] animate-spin">sync</span>
                                                    กำลังรอการตอบรับ... 
                                                    <span class="waiting-timer" data-time="{{ $commandTimeStr }}">0 วิ</span>
                                                </div>
                                            @elseif(in_array($officer->id, $refusedList))
                                                <div class="mt-2 text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg border border-red-200 inline-block">ปฏิเสธการรับงาน</div>
                                            @elseif($isNoRespond || in_array($officer->id, $noRespondList))
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

                    {{-- ================= โหมด 2: กำลังปฏิบัติหน้าที่ (Real-time Tracking) ================= --}}
                    <div id="active-officer-section" class="flex flex-col h-full {{ !$isActiveOperation ? 'hidden' : '' }} bg-slate-50/50 p-5">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-4">เจ้าหน้าที่ผู้รับผิดชอบ</h3>
                        
                        <div class="bg-white p-5 rounded-xl border border-blue-200 shadow-md relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500 rounded-bl-full opacity-10"></div>
                            
                            <div class="flex items-start gap-4">
                                <div class="size-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 border-2 border-white shadow-sm">
                                    <span class="material-symbols-outlined text-[28px]">support_agent</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg text-slate-900" id="active-officer-name">{{ $acceptedOfficer->name_officer ?? 'ไม่ระบุชื่อ' }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded border border-slate-200">{{ $acceptedOfficer->type ?? 'หน่วยกู้ภัย' }}</span>
                                    </div>
                                    @if($acceptedOfficer && isset($acceptedOfficer->phone))
                                    <a href="tel:{{ $acceptedOfficer->phone }}" class="inline-flex items-center gap-1.5 text-blue-600 text-xs font-bold mt-3 hover:underline">
                                        <span class="material-symbols-outlined text-[14px]">call</span> โทรติดต่อเจ้าหน้าที่
                                    </a>
                                    @endif
                                </div>
                            </div>

                            <hr class="my-4 border-slate-100">

                            {{-- ส่วนแสดงระยะทางและเวลา (ซ่อนเมื่อ "ถึงที่เกิดเหตุ") --}}
                            <div id="routing-info" class="grid grid-cols-2 gap-4 {{ $currentOpStatus == 'ถึงที่เกิดเหตุ' || $currentOpStatus == 'เสร็จสิ้น' ? 'hidden' : '' }}">
                                <div class="bg-blue-50 rounded-lg p-3 border border-blue-100/50">
                                    <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wide mb-1">ระยะทางที่เหลือ</p>
                                    <p class="text-lg font-bold text-slate-800" id="distance-text">คำนวณ...</p>
                                </div>
                                <div class="bg-emerald-50 rounded-lg p-3 border border-emerald-100/50">
                                    <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-wide mb-1">เวลาโดยประมาณ</p>
                                    <p class="text-lg font-bold text-slate-800" id="duration-text">คำนวณ...</p>
                                </div>
                            </div>

                            {{-- ส่วนแสดงเวลาที่ถึง (โชว์เมื่อ "ถึงที่เกิดเหตุ") --}}
                            <div id="arrived-info" class="bg-emerald-50 rounded-lg p-4 border border-emerald-200 text-center {{ $currentOpStatus != 'ถึงที่เกิดเหตุ' && $currentOpStatus != 'เสร็จสิ้น' ? 'hidden' : '' }}">
                                <span class="material-symbols-outlined text-emerald-500 text-3xl mb-1">location_on</span>
                                <h4 class="font-bold text-emerald-700">เจ้าหน้าที่ถึงที่เกิดเหตุแล้ว</h4>
                                <p class="text-xs text-emerald-600 mt-1" id="arrived-time-text">
                                    เวลา: {{ $emergency->operation->arrived_at ? \Carbon\Carbon::parse($emergency->operation->arrived_at)->format('H:i น.') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

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

<script>
    const emergencyId = "{{ $emergency->id }}";
    const operationId = "{{ $emergency->operation->id ?? '' }}";
    let currentOpStatus = "{{ $emergency->operation->status ?? 'รับแจ้งเหตุ' }}";
    let pollingInterval = null;

    let initialOfficerLat = null;
    let initialOfficerLng = null;
    let startMarkerObj = null;

    // เก็บ ID คนที่แจ้งเตือนไปแล้ว (ดึงค่าเริ่มต้นจากตอนโหลดหน้าเว็บ เผื่อรีเฟรชจะได้ไม่เด้งซ้ำ)
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

    // ================== ระบบเวลา Elapsed Timer ==================
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

    // ================== ระบบนับเวลาสำหรับคนที่กำลังรอการตอบรับ ==================
    function updateWaitingTimers() {
        const timers = document.querySelectorAll('.waiting-timer');
        if (timers.length === 0) return;

        const now = new Date().getTime();

        timers.forEach(timer => {
            const commandTimeStr = timer.getAttribute('data-time');
            if (!commandTimeStr) return;

            const commandTime = new Date(commandTimeStr).getTime();
            const distance = now - commandTime;
            
            if (distance < 0) return;

            const totalSeconds = Math.floor(distance / 1000);
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;

            let timeString = "";
            if (minutes > 0) {
                timeString += minutes + " นาที ";
            }
            timeString += seconds + " วิ";

            timer.innerHTML = timeString;
        });
    }
    
    // อัปเดตทุกๆ 1 วินาที
    setInterval(updateWaitingTimers, 1000);
    updateWaitingTimers(); // เรียกใช้ครั้งแรกทันที

    // ================== ระบบแจ้งเตือน Toast ==================
    function showToast(statusText, type = 'info') {

        if (statusText === 'สั่งการ') {
            return; 
        }

        const toast = document.getElementById('statusToast');
        const toastTitle = document.getElementById('toastTitle');
            toastTitle.innerText = `${statusText}`;
        document.getElementById('toastMessage').innerText = `อัปเดตสถานะการทำงาน`;
        const iconContainer = document.getElementById('toastIconContainer');
        const icon = document.getElementById('toastIcon');

        // รีเซ็ตคลาสสี
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

        // โชว์ Toast เลื่อนลงมา
        toast.classList.remove('-translate-y-[150%]', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        const audio = new Audio("{{ url('/sounds/Update_Status.mp3') }}");
        audio.play().catch(error => {
            console.log("Browser block autoplay:", error);
        });

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('-translate-y-[150%]', 'opacity-0');
        }, 4000);
    }

    // ฟังก์ชันจัดสีแถบ Status
    function updateStatusUI(status) {
        document.getElementById('current-status-text').innerText = status;
        const colorBar = document.getElementById('status-color-bar');
        
        // ล้างคลาสสีเดิม
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

    // ================== ระบบ Polling ตรวจสอบการดำเนินการ ==================
    document.addEventListener('DOMContentLoaded', () => {
        updateStatusUI(currentOpStatus); // เซ็ตสีเริ่มต้นตอนโหลดหน้า
        startOperationPolling();
    });

    function startOperationPolling() {
        if(pollingInterval) clearInterval(pollingInterval);
        fetchOperationData(); // ดึงรอบแรก
        pollingInterval = setInterval(fetchOperationData, 5000); // วนทุก 5 วิ
    }

    async function fetchOperationData() {
        try {
            const response = await fetch("{{ url('/api/emergency') }}"+`/${emergencyId}/operation`);
            if(!response.ok) return;
            const data = await response.json();

            // ================== ตรวจสอบ Status เปลี่ยนแปลง ==================
            if (data.status && data.status !== currentOpStatus) {
                const previousStatus = currentOpStatus;
                
                currentOpStatus = data.status;
                updateStatusUI(currentOpStatus);
                showToast(currentOpStatus, getStatusColorType(currentOpStatus));

                if (previousStatus === 'สั่งการ' && currentOpStatus === 'กำลังไปช่วยเหลือ') {
                    setTimeout(() => {
                        window.location.reload();
                    }, 4000);
                    return;
                }

                if (currentOpStatus === 'ถึงที่เกิดเหตุ') {
                    if (officerMarkerMap && typeof officerMarkerMap.onRemove === 'function') officerMarkerMap.onRemove();
                    if (directionsRenderer && typeof directionsRenderer.setMap === 'function') directionsRenderer.setMap(null);
                    if (startMarkerObj && typeof startMarkerObj.onRemove === 'function') startMarkerObj.onRemove();
                    
                    const routingInfo = document.getElementById('routing-info');
                    const arrivedInfo = document.getElementById('arrived-info');
                    if(routingInfo) routingInfo.classList.add('hidden');
                    if(arrivedInfo) arrivedInfo.classList.remove('hidden');
                    
                    if(data.time_to_the_scene) {
                        const time = new Date(data.time_to_the_scene).toLocaleTimeString('th-TH', {hour: '2-digit', minute:'2-digit'});
                        document.getElementById('arrived-time-text').innerText = 'เวลา: ' + time + ' น.';
                    }
                } else if (currentOpStatus === 'เสร็จสิ้น') {
                    clearInterval(pollingInterval); 
                }
            }

            // ================== กำลังไปช่วยเหลือ (จับพิกัด + วาดเส้นตามเวลาอัปเดต) ==================
            if (currentOpStatus === 'กำลังไปช่วยเหลือ') {
                const currentLat = parseFloat(data.officer_lat);
                const currentLng = parseFloat(data.officer_lng);
                const diffMinutes = data.location_diff_minutes;

                if (currentLat && currentLng) {
                    if (initialOfficerLat === null && initialOfficerLng === null) {
                        // รับค่าครั้งแรก เก็บเป็นจุด Start ไว้ก่อน (ยังไม่วาดเส้น)
                        initialOfficerLat = currentLat;
                        initialOfficerLng = currentLng;
                        updateOfficerLocationOnMap(currentLat, currentLng);
                        
                    } else {
                        // ถ้ายังไม่เคยตีเส้น และพิกัดอัปเดตล่าสุดไม่เกิน 5 นาที ให้ตีเส้นได้เลย
                        if (!isRouteDrawn && diffMinutes !== null && diffMinutes <= 5) {
                            drawRouteToIncident(initialOfficerLat, initialOfficerLng);
                        }
                        
                        // ปักหมุดรถใหม่ทุกรอบ เพื่ออัปเดตจุดเผื่อมีการขยับ
                        updateOfficerLocationOnMap(currentLat, currentLng);
                    }
                }
            }

            // ================== ตรวจสอบการปฏิเสธเพื่อเด้ง Modal ==================
            if (data.officer_refuse && Array.isArray(data.officer_refuse)) {
                let hasNewRefusal = false;
                data.officer_refuse.forEach(refusedId => {
                    const parsedId = parseInt(refusedId);
                    if (!notifiedRefusedOfficers.includes(parsedId)) {
                        hasNewRefusal = true;
                        notifiedRefusedOfficers.push(parsedId);
                    }
                });
                
                if (hasNewRefusal) {
                    openRefusedModal();
                }
            }

            // ================== จัดการ UI หน้าสั่งการ ==================
            if (['รับแจ้งเหตุ', 'สั่งการ'].includes(currentOpStatus)) {
                document.querySelectorAll('.assign-radio').forEach(radio => {
                    const officerId = parseInt(radio.value);
                    const labelContainer = radio.closest('label');
                    const infoContainer = labelContainer.querySelector('.flex-1.pr-8');

                    const checkInArray = (arr, val) => arr && Array.isArray(arr) && arr.some(item => String(item) === String(val));
                    
                    const isRefused = checkInArray(data.officer_refuse, officerId);
                    const isNoRespond = checkInArray(data.officer_no_respond, officerId);
                    const isWaiting = (String(data.waiting_reply) === String(officerId));

                    let waitMin = 0;
                    let waitSec = 0;
                    if (data.log_command && Array.isArray(data.log_command)) {
                        const logs = [...data.log_command].reverse();
                        const officerLog = logs.find(l => String(l.sendTo) === String(officerId));
                        if (officerLog && officerLog.sum_time) {
                            const sumTime = parseInt(officerLog.sum_time);
                            waitMin = Math.floor(sumTime / 60);
                            waitSec = sumTime % 60;
                        }
                    }

                    let timeText = waitMin > 0 ? `${waitMin} นาที ` : '';
                    timeText += `${waitSec} วินาที`;

                    labelContainer.classList.remove('opacity-50', 'pointer-events-none', 'cursor-not-allowed', 'ring-2', 'ring-blue-300');
                    radio.disabled = false;

                    if (isWaiting) {
                        radio.disabled = true;
                        labelContainer.classList.add('pointer-events-none', 'cursor-not-allowed', 'ring-2', 'ring-blue-300');
                    } else if (isRefused) {
                        radio.disabled = true;
                        labelContainer.classList.add('opacity-50', 'pointer-events-none', 'cursor-not-allowed');
                        const targetMarker = document.getElementById(`officer-marker-${officerId}`);
                        if (targetMarker) targetMarker.querySelector('.officer-pulse').classList.add('hidden');
                    }

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
                        statusDiv.className = 'status-wrapper';
                        statusDiv.dataset.commandTime = preserveTime;
                        infoContainer.appendChild(statusDiv);
                    }

                    if (isWaiting) {
                        if (!statusDiv.querySelector('.waiting-timer')) {
                            statusDiv.innerHTML = `
                                <div class="mt-2 text-[11px] font-bold text-blue-700 bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-300 flex items-center gap-2 shadow-sm">
                                    <span class="material-symbols-outlined text-[14px] animate-spin">sync</span>
                                    กำลังรอการตอบรับ... 
                                    <span class="waiting-timer" data-time="${statusDiv.dataset.commandTime}">0 วิ</span>
                                </div>`;
                        }
                    } else if (isRefused) {
                        statusDiv.innerHTML = `<div class="mt-2 text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg border border-red-200 inline-block shadow-sm">ปฏิเสธเคส เวลารอ ${timeText}</div>`;
                    } else if (isNoRespond) {
                        statusDiv.innerHTML = `<div class="mt-2 text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg border border-amber-200 inline-block shadow-sm">ไม่มีการตอบสนอง เวลารอ ${timeText}</div>`;
                    } else {
                        statusDiv.innerHTML = ''; 
                    }
                });
            }

        } catch (error) {
            console.error('Polling Error:', error);
        }
    }

    // ================== ฟังก์ชันย่อยสำหรับ Map ==================

    // เลื่อนตำแหน่งรถอย่างเดียว
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

    // ตีเส้น 1 ครั้ง + คำนวณเวลา + สร้างหมุดธงเริ่มต้น
    function drawRouteToIncident(startLat, startLng) {

        if (!CustomMarker || !mapInstance || isRouteDrawn) return;

        if (isRouteDrawn) return; 
        const startLatLng = new google.maps.LatLng(startLat, startLng);

        // ปักหมุดธงจุดเริ่มต้น (Start Point)
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

        // ร้องขอเส้นทางจาก Google Maps ทำแค่ครั้งเดียว
        directionsService.route({
            origin: startLatLng,
            destination: incidentLatLng,
            travelMode: google.maps.TravelMode.DRIVING
        }, (response, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
                isRouteDrawn = true;
                
                const leg = response.routes[0].legs[0];
                const polyline = response.routes[0].overview_polyline; // เส้นทาง
                
                // อัปเดตหน้าจอ
                const distanceText = document.getElementById('distance-text');
                const durationText = document.getElementById('duration-text');
                if(distanceText) distanceText.innerText = leg.distance.text;
                if(durationText) durationText.innerText = leg.duration.text;
                
                // ปรับ Zoom กล้อง
                if(mapInstance) {
                    const bounds = new google.maps.LatLngBounds();
                    bounds.extend(incidentLatLng);
                    bounds.extend(startLatLng);
                    mapInstance.fitBounds(bounds, { top: 60, bottom: 60, left: 60, right: 60 });
                }

                // อัปเดต Log_command
                fetch(`{{ url('/api/emergency') }}/${emergencyId}/update-route-log`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        start_lat: startLat,
                        start_lng: startLng,
                        incident_lat: incidentLatLng.lat(),
                        incident_lng: incidentLatLng.lng(),
                        distance_text: leg.distance.text,
                        distance_value: leg.distance.value,
                        duration_text: leg.duration.text,
                        duration_value: leg.duration.value,
                        polyline: polyline
                    })
                }).then(res => res.json())
                  .then(data => console.log('Log Updated:', data))
                  .catch(err => console.error('Error updating log:', err));
            }
        });
    }

    function openRefusedModal() {
        const modal = document.getElementById('refusedModal');
        const content = document.getElementById('refusedModalContent');
        modal.classList.remove('hidden');
        
        // เล่นเสียงแจ้งเตือน
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

    // ================== ระบบ Map & หมุด ==================
    let mapInstance;
    let incidentLatLng;
    let officerMarkerMap = null; 
    let CustomMarker; 
    
    let directionsService;
    let directionsRenderer;
    let isRouteDrawn = false;

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

        // หมุดจุดเกิดเหตุ
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

        // เช็คว่ามอบหมายงานไปแล้วหรือยัง
        if (['กำลังไปช่วยเหลือ', 'ถึงที่เกิดเหตุ'].includes(currentOpStatus)) {
            // ไม่ต้องแสดงรายชื่อคนอื่น ให้รอการอัปเดตจาก Polling จัดการหมุด
        } else {
            // วาดหมุดเจ้าหน้าที่ Standby สำหรับให้เลือก
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
        }
    }

    // ================== ฟังก์ชันย่อยสำหรับ Map ==================
    function initAssignClickEvents() {
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
                        bounds.extend(new google.maps.LatLng(lat, lng));
                        mapInstance.fitBounds(bounds, { top: 60, bottom: 60, left: 60, right: 60 });
                    }
                }
            });
        });
    }

    function drawRouteToIncident(startLat, startLng) {
        if (isRouteDrawn) return; 
        const startLatLng = new google.maps.LatLng(startLat, startLng);

        directionsService.route({
            origin: startLatLng,
            destination: incidentLatLng,
            travelMode: google.maps.TravelMode.DRIVING
        }, (response, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
                isRouteDrawn = true;
                const leg = response.routes[0].legs[0];
                const distanceText = document.getElementById('distance-text');
                const durationText = document.getElementById('duration-text');
                if(distanceText) distanceText.innerText = leg.distance.text;
                if(durationText) durationText.innerText = leg.duration.text;
            }
        });
    }

    // เลื่อนตำแหน่งรถและวาดเส้นทาง
    function updateOfficerLocationOnMap(lat, lng, startLat, startLng) {
        const newLatLng = new google.maps.LatLng(lat, lng);
        if (!officerMarkerMap) {
            const officerHtml = `
                <div class="relative flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2 z-40 transition-all duration-1000">
                    <span class="relative inline-flex rounded-full h-10 w-10 bg-blue-600 border-[3px] border-white shadow-xl items-center justify-center text-white">
                        <span class="material-symbols-outlined text-[20px]">directions_car</span>
                    </span>
                </div>`;
            officerMarkerMap = new CustomMarker(newLatLng, mapInstance, officerHtml);
            
            if(startLat && startLng) {
                drawRouteToIncident(startLat, startLng);
            }
        } else {
            officerMarkerMap.updatePosition(newLatLng);
        }
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initAssignMap" async defer></script>
@endsection