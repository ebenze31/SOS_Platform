@extends('layouts.theme')

@section('content')
<main class="flex-1 overflow-x-auto overflow-y-hidden bg-slate-50/50 p-6 pt-[85px]">
    <div id="monitor-container" class="h-full grid grid-cols-1 md:grid-cols-3 gap-6 min-w-[1024px] max-w-[1600px] mx-auto">
        
        {{-- ================================================================================== --}}
        {{-- COLUMN 1: แจ้งเหตุใหม่ / รอการดำเนินการ (สีแดง) --}}
        {{-- ================================================================================== --}}
        <div class="flex flex-col h-full bg-slate-100/70 rounded-xl border border-slate-200/60 shadow-inner">
            <div class="p-4 flex items-center justify-between border-b border-slate-200/60 bg-white/60 backdrop-blur-sm rounded-t-xl sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center size-6 rounded bg-red-100 text-red-600">
                        <span class="material-symbols-outlined text-[16px]">assignment_late</span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">แจ้งเหตุใหม่</h3>
                    <div class="relative flex h-5 w-5 items-center justify-center">
                        @if($totalPending > 0)
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        @endif
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-red-600 text-white text-[10px] font-bold items-center justify-center shadow-sm">
                            {{ $totalPending }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-3 flex-1 overflow-y-auto custom-scrollbar space-y-3">
                @forelse($pendingCases as $case)
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md hover:border-red-300 transition-all cursor-pointer relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-500"></div>
                    
                    <div class="flex justify-between items-start mb-2 pl-3">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-700 bg-red-50 px-2 py-0.5 rounded border border-red-100 uppercase tracking-wide">
                            {{ $case->emergency_type ?? 'ไม่ระบุประเภท' }}
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-red-100 text-red-700 border border-red-200">
                            {{ $case->operation->status ?? 'รับแจ้งเหตุ' }}
                        </span>
                    </div>
                    
                    <h4 class="font-bold text-slate-900 text-sm mb-1 pl-3 line-clamp-2" title="{{ $case->emergency_detail }}">{{ $case->emergency_detail }}</h4>
                    <div class="flex items-start gap-1.5 text-slate-500 text-[11px] mb-3 pl-3">
                        <span class="material-symbols-outlined text-[14px] shrink-0 text-slate-400">location_on</span>
                        <span class="line-clamp-2 leading-tight">{{ $case->emergency_location }}</span>
                    </div>

                    <div class="bg-slate-50/80 rounded border border-slate-100 p-2 ml-3 mb-3 flex flex-col gap-1">
                        <div class="flex items-center gap-1.5 text-slate-600 text-[11px]">
                             <span class="material-symbols-outlined text-[14px]">person</span>
                             <span class="font-semibold">{{ $case->name_reporter }}</span> 
                             <span class="text-slate-400">({{ $case->type_reporter ?? 'ผู้แจ้ง' }})</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px]">
                             <span class="material-symbols-outlined text-[14px] text-slate-400">call</span>
                             <a href="tel:{{ str_replace('-', '', $case->phone_reporter) }}" class="text-blue-600 font-bold hover:underline">{{ $case->phone_reporter }}</a>
                        </div>
                    </div>

                    {{-- ไม่มีข้อมูลเจ้าหน้าที่ในสถานะรอรับแจ้งเหตุ ซ่อนไว้ก่อน --}}

                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 pl-3">
                        <div class="flex items-center gap-1 text-slate-400 text-[10px] font-medium">
                            <span class="material-symbols-outlined text-[14px]">timer</span>
                            <span class="text-red-600 font-bold">ผ่านไป {{ $case->created_at->locale('th')->diffForHumans(null, true) }}</span>
                        </div>
                        {{-- ยังไม่เสร็จสิ้น ซ่อนเวลาใช้งานรวม --}}
                    </div>
                    
                    <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">
                        <a href="{{ url('/case_assign/') }}/{{ $case->id }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-1.5 rounded shadow-lg shadow-red-600/20">รับเรื่อง</a>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10 text-slate-400 opacity-60">
                    <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                    <span class="text-sm">ไม่มีการแจ้งเหตุใหม่</span>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ================================================================================== --}}
        {{-- COLUMN 2: กำลังดำเนินการ (สีน้ำเงิน) --}}
        {{-- ================================================================================== --}}
        <div class="flex flex-col h-full bg-slate-100/70 rounded-xl border border-slate-200/60 shadow-inner">
            <div class="p-4 flex items-center justify-between border-b border-slate-200/60 bg-white/60 backdrop-blur-sm rounded-t-xl sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center size-6 rounded bg-blue-100 text-blue-600">
                        <span class="material-symbols-outlined text-[16px]">timelapse</span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">กำลังดำเนินการ</h3>
                    <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $totalInProgress }}
                    </span>
                </div>
            </div>

            <div class="p-3 flex-1 overflow-y-auto custom-scrollbar space-y-3">
                @forelse($inProgressCases as $case)
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md transition-all cursor-pointer relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500"></div>

                    <div class="flex justify-between items-start mb-2 pl-3">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100 uppercase tracking-wide">
                            {{ $case->emergency_type ?? 'ไม่ระบุประเภท' }}
                        </span>
                        
                        @php
                            $status = $case->operation->status ?? '-';
                            $badgeClass = 'bg-blue-100 text-blue-700 border-blue-200';
                            if($status == 'สั่งการ') $badgeClass = 'bg-amber-100 text-amber-700 border-amber-200';
                            elseif($status == 'กำลังไปช่วยเหลือ') $badgeClass = 'bg-orange-100 text-orange-700 border-orange-200';
                            elseif($status == 'ถึงที่เกิดเหตุ') $badgeClass = 'bg-purple-100 text-purple-700 border-purple-200';
                        @endphp
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded border {{ $badgeClass }}">
                            {{ $status }}
                        </span>
                    </div>

                    <h4 class="font-bold text-slate-900 text-sm mb-1 pl-3 line-clamp-2" title="{{ $case->emergency_detail }}">{{ $case->emergency_detail }}</h4>
                    <div class="flex items-start gap-1.5 text-slate-500 text-[11px] mb-3 pl-3">
                        <span class="material-symbols-outlined text-[14px] shrink-0 text-slate-400">location_on</span>
                        <span class="line-clamp-2 leading-tight">{{ $case->emergency_location }}</span>
                    </div>

                    <div class="bg-slate-50/80 rounded border border-slate-100 p-2 ml-3 mb-3 flex flex-col gap-1">
                        <div class="flex items-center gap-1.5 text-slate-600 text-[11px]">
                             <span class="material-symbols-outlined text-[14px]">person</span>
                             <span class="font-semibold">{{ $case->name_reporter }}</span>
                             <span class="text-slate-400">({{ $case->type_reporter ?? 'ผู้แจ้ง' }})</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px]">
                             <span class="material-symbols-outlined text-[14px] text-slate-400">call</span>
                             <a href="tel:{{ str_replace('-', '', $case->phone_reporter) }}" class="text-blue-600 font-bold hover:underline">{{ $case->phone_reporter }}</a>
                        </div>
                    </div>

                    {{-- แสดงเจ้าหน้าที่ --}}
                    <div class="inline-flex items-center p-2 ml-3 mb-2 text-blue-600 bg-blue-50 px-2 py-1 rounded border border-blue-100">
                        <span class="material-symbols-outlined mr-1 text-[13px]">support_agent</span>
                        <span class="text-[10px] font-bold">{{ $case->operation->officer->name ?? 'รอรับเรื่อง...' }}</span>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 pl-3">
                        <div class="flex items-center gap-1 text-slate-400 text-[10px] font-medium">
                            <span class="material-symbols-outlined text-[14px]">timer</span>
                            <span class="text-blue-600 font-bold">ผ่านไป {{ $case->created_at->locale('th')->diffForHumans(null, true) }}</span>
                        </div>
                        {{-- ยังไม่เสร็จสิ้น ซ่อนเวลาใช้งานรวม --}}
                    </div>
                    
                    <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">
                         <a href="{{ url('/case_assign/') }}/{{ $case->id }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-1.5 rounded shadow-lg shadow-blue-600/20">จัดการ</a>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10 text-slate-400 opacity-60">
                    <span class="material-symbols-outlined text-4xl mb-2">pending_actions</span>
                    <span class="text-sm">ไม่มีรายการดำเนินการ</span>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ================================================================================== --}}
        {{-- COLUMN 3: เสร็จสิ้น (สีเขียว) --}}
        {{-- ================================================================================== --}}
        <div class="flex flex-col h-full bg-slate-100/70 rounded-xl border border-slate-200/60 shadow-inner opacity-90">
            <div class="p-4 flex items-center justify-between border-b border-slate-200/60 bg-white/60 backdrop-blur-sm rounded-t-xl sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center size-6 rounded bg-emerald-100 text-emerald-600">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">เสร็จสิ้นล่าสุด</h3>
                    <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $totalCompleted }}
                    </span>
                </div>
            </div>

            <div class="p-3 flex-1 overflow-y-auto custom-scrollbar space-y-3">
                @forelse($completedCases as $case)
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md hover:border-emerald-300 transition-all cursor-pointer relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>

                    <div class="flex justify-between items-start mb-2 pl-3">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 uppercase tracking-wide">
                            {{ $case->emergency_type ?? 'ไม่ระบุประเภท' }}
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 border border-emerald-200">
                            {{ $case->operation->status ?? 'เสร็จสิ้น' }}
                        </span>
                    </div>

                    <h4 class="font-bold text-slate-900 text-sm mb-1 pl-3 line-clamp-2" title="{{ $case->emergency_detail }}">{{ $case->emergency_detail }}</h4>
                    <div class="flex items-start gap-1.5 text-slate-500 text-[11px] mb-3 pl-3">
                        <span class="material-symbols-outlined text-[14px] shrink-0 text-slate-400">location_on</span>
                        <span class="line-clamp-2 leading-tight">{{ $case->emergency_location }}</span>
                    </div>

                    <div class="bg-slate-50/80 rounded border border-slate-100 p-2 ml-3 mb-3 flex flex-col gap-1">
                        <div class="flex items-center gap-1.5 text-slate-600 text-[11px]">
                             <span class="material-symbols-outlined text-[14px]">person</span>
                             <span class="font-semibold">{{ $case->name_reporter }}</span>
                             <span class="text-slate-400">({{ $case->type_reporter ?? 'ผู้แจ้ง' }})</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px]">
                             <span class="material-symbols-outlined text-[14px] text-slate-400">call</span>
                             <a href="tel:{{ str_replace('-', '', $case->phone_reporter) }}" class="text-blue-600 font-bold hover:underline">{{ $case->phone_reporter }}</a>
                        </div>
                    </div>

                    {{-- แสดงเจ้าหน้าที่ --}}
                    <div class="inline-flex items-center p-2 ml-3 mb-2 text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100">
                        <span class="material-symbols-outlined mr-1 text-[13px]">support_agent</span>
                        <span class="text-[10px] font-bold">{{ $case->operation->officer->name ?? 'ไม่ระบุจนท.' }}</span>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 pl-3">
                        <div class="flex items-center gap-1 text-slate-400 text-[10px] font-medium">
                            <span class="material-symbols-outlined text-[14px]">event_available</span>
                            <span>ปิดงาน: {{ $case->updated_at->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-end gap-1 text-slate-400 text-[10px]">
                            <span class="material-symbols-outlined text-[12px]">timelapse</span>
                            <span>ใช้เวลารวม: <span class="text-emerald-600 font-bold">{{ \Carbon\Carbon::parse($case->created_at)->locale('th')->diffForHumans($case->updated_at, true) }}</span></span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10 text-slate-400 opacity-60">
                    <span class="material-symbols-outlined text-4xl mb-2">history</span>
                    <span class="text-sm">ยังไม่มีประวัติการแจ้งเหตุ</span>
                </div>
                @endforelse

                @if($totalCompleted > 3)
                <a href="#" class="block group bg-emerald-50/50 hover:bg-emerald-50 p-4 rounded-lg border border-dashed border-emerald-200 hover:border-emerald-400 transition-all cursor-pointer text-center mt-2">
                    <div class="flex flex-col items-center justify-center gap-1">
                        <div class="size-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-1 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">read_more</span>
                        </div>
                        <span class="text-xs font-bold text-emerald-700">ดูประวัติเคสทั้งหมด</span>
                        <span class="text-[10px] text-emerald-500/70">คลิกเพื่อดูรายการย้อนหลังอีก {{ $totalCompleted - 3 }} รายการ</span>
                    </div>
                </a>
                @endif
            </div>
        </div>

    </div>
</main>

<script>
    function refreshDashboard() {
        const currentUrl = window.location.href;

        fetch(currentUrl)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('monitor-container').innerHTML;
                document.getElementById('monitor-container').innerHTML = newContent;
            })
            .catch(error => {
                console.error('Error refreshing dashboard:', error);
            });
    }
    
    setInterval(refreshDashboard, 10000);
</script>
@endsection