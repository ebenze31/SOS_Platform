@extends('layouts.theme_user')

@section('content')
<div class="w-full min-h-[calc(100vh-71.75px)] bg-slate-50 dark:bg-slate-900 mt-[71.75px] py-8 px-4 sm:px-6 lg:px-8 overflow-auto">
    <div class="max-w-4xl mx-auto pb-10">
        
        <header class="mb-8 space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">ประวัติการปฏิบัติงาน</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">รายการช่วยเหลือเหตุฉุกเฉินที่คุณได้รับมอบหมาย</p>
            </div>

            {{-- ส่วนแสดงสถิติ --}}
            <div class="grid grid-cols-2 gap-4 w-full">
                <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col items-center justify-center text-center">
                    <span class="block text-[10px] uppercase text-slate-400 font-bold tracking-wider mb-1">ช่วยสำเร็จ</span>
                    <span class="text-2xl font-black text-emerald-500">{{ $operations->where('status', 'เสร็จสิ้น')->count() }}</span>
                    <span class="text-[10px] text-slate-400">เคสทั้งหมด</span>
                </div>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col items-center justify-center text-center">
                    <span class="block text-[10px] uppercase text-slate-400 font-bold tracking-wider mb-1">กำลังดำเนินการ</span>
                    <span class="text-2xl font-black text-blue-500">{{ $operations->whereIn('status', ['สั่งการ', 'กำลังไปช่วยเหลือ', 'กำลังเดินทาง', 'ถึงที่เกิดเหตุ'])->count() }}</span>
                    <span class="text-[10px] text-slate-400">รอการปิดเคส</span>
                </div>
            </div>
        </header>

        <div class="space-y-6">
            @forelse($operations as $op)
                @php
                    $emergency = $op->emergency;
                    $status = $op->status ?? 'รอดำเนินการ';
                    
                    $statusColor = 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700';
                    if($status == 'เสร็จสิ้น') {
                        $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/30';
                    } elseif(in_array($status, ['สั่งการ', 'กำลังไปช่วยเหลือ', 'กำลังเดินทาง', 'ถึงที่เกิดเหตุ'])) {
                        $statusColor = 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800/30';
                    } elseif($status == 'ปฏิเสธ' || $status == 'ยกเลิก') {
                        $statusColor = 'bg-red-50 text-red-600 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800/30';
                    }
                @endphp

                <div class="bg-white dark:bg-[#1a2632] rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-md transition-all border-l-4 {{ $status == 'เสร็จสิ้น' ? 'border-l-emerald-500' : (in_array($status, ['สั่งการ', 'กำลังไปช่วยเหลือ', 'กำลังเดินทาง', 'ถึงที่เกิดเหตุ']) ? 'border-l-blue-500' : 'border-l-slate-400') }}">
                    <div class="p-5 sm:p-6">
                        {{-- ข้อมูลผู้แจ้ง --}}
                        <div class="flex justify-between items-start mb-4">
                            <div class="space-y-1">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $status == 'เสร็จสิ้น' ? 'bg-emerald-500' : 'bg-blue-500 animate-pulse' }}"></span>
                                    {{ $emergency->emergency_type ?? 'ไม่ระบุประเภท' }}
                                </h3>
                                <div class="flex flex-wrap gap-y-1 gap-x-4">
                                    <div class="flex items-center gap-1.5 text-sm font-medium text-slate-700 dark:text-slate-300">
                                        <span class="material-symbols-outlined text-base text-primary">person</span>
                                        <span>{{ $emergency->name_reporter ?? 'ไม่ทราบชื่อ' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md">
                                        <span class="material-symbols-outlined text-sm">badge</span>
                                        <span>{{ $emergency->type_reporter ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-[10px] sm:text-[11px] font-bold px-3 py-1.5 rounded-xl border {{ $statusColor }} uppercase tracking-wider">
                                {{ $status }}
                            </span>
                        </div>

                        {{-- เวลารับแจ้งเหตุ --}}
                        <div class="flex items-center gap-2 text-sm text-slate-500 mb-3 px-1">
                            <span class="material-symbols-outlined text-base text-emerald-500">check_circle</span>
                            <span class="font-semibold">รับแจ้งเมื่อ:</span>
                            <span>{{ $op->time_create_sos ? \Carbon\Carbon::parse($op->time_create_sos)->format('d/m/Y H:i') : '-' }}</span>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 mb-6 ring-1 ring-slate-100 dark:ring-slate-700">
                            <div class="flex items-start gap-3 text-sm text-slate-600 dark:text-slate-300">
                                <div class="w-8 h-8 rounded-full bg-white dark:bg-slate-700 flex items-center justify-center shadow-sm shrink-0">
                                    <span class="material-symbols-outlined text-red-500 text-lg">location_on</span>
                                </div>
                                <span class="leading-relaxed">{{ $emergency->emergency_location }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            {{-- เวลาออกเดินทาง --}}
                            <div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-1.5 font-bold">เวลาออกเดินทาง</div>
                                <div class="text-[13px] font-bold text-slate-700 dark:text-slate-200">
                                    {{ $op->time_go_to_help ? \Carbon\Carbon::parse($op->time_go_to_help)->format('H:i น.') : '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-1.5 font-bold">เวลาเสร็จสิ้น</div>
                                <div class="text-[13px] font-bold text-slate-700 dark:text-slate-200">
                                    {{ $op->time_sos_success ? \Carbon\Carbon::parse($op->time_sos_success)->format('H:i น.') : '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-1.5 font-bold">รวมเวลาปฏิบัติงาน</div>
                                <div class="text-[13px] font-bold text-blue-600 dark:text-blue-400">
                                    {{ $op->time_sum_sos ?? '-' }}
                                </div>
                            </div>
                            {{-- คะแนน impression / period --}}
                            <div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-1.5 font-bold whitespace-nowrap">ความพึงพอใจ (บริการ/เวลา)</div>
                                <div class="flex items-center gap-1.5">
                                    <div class="flex items-center bg-amber-50 dark:bg-amber-900/20 px-2 py-0.5 rounded-md">
                                        <span class="text-[13px] font-bold text-amber-600 dark:text-amber-500">
                                            {{ $emergency->score_impression ?? '0' }}/{{ $emergency->score_period ?? '0' }}
                                        </span>
                                        <span class="material-symbols-outlined text-sm text-amber-500 ml-0.5">star</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50/80 dark:bg-slate-800/40 px-6 py-4 border-t border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
                        <span class="text-xs text-slate-400"></span>
                        <a href="{{ url('/officer/action/'.$op->emergency_id) }}" class="inline-flex items-center gap-2 bg-white dark:bg-slate-700 px-4 py-2 rounded-xl text-sm font-bold text-slate-700 dark:text-white shadow-sm border border-slate-200 dark:border-slate-600 hover:bg-slate-50 transition-colors">
                            รายละเอียดเคส
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-[#1a2632] rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 p-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4">
                        <span class="material-symbols-outlined text-4xl">inventory_2</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">ไม่พบประวัติการปฏิบัติงาน</h3>
                    <p class="text-sm text-slate-500">รายการที่คุณได้รับมอบหมายจะปรากฏที่นี่</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection