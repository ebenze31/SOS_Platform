@extends('layouts.theme_user')

@section('content')
<div class="w-full min-h-[calc(100vh-71.75px)] bg-slate-50 dark:bg-slate-900 mt-[71.75px] py-8 px-4 sm:px-6 lg:px-8 overflow-auto">
    <div class="max-w-3xl mx-auto pb-10">
        
        <header class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">ประวัติการขอความช่วยเหลือ</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">รายการแจ้งเหตุฉุกเฉินทั้งหมดของคุณ</p>
            </div>
        </header>

        <div class="space-y-4">
            @forelse($emergencies as $emergency)
                @php
                    $op = $emergency->operation;
                    $status = $op->status ?? 'รับแจ้งเหตุ';
                    
                    // กำหนดสีของ Badge ตามสถานะ
                    $statusColor = 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700';
                    if($status == 'เสร็จสิ้น') {
                        $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/30';
                    } elseif(in_array($status, ['สั่งการ', 'กำลังไปช่วยเหลือ', 'กำลังเดินทาง', 'ถึงที่เกิดเหตุ'])) {
                        $statusColor = 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800/30';
                    }
                @endphp

                <a href="{{ route('emergency.tracking', $emergency->id) }}" class="block bg-white dark:bg-[#1a2632] rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 sm:p-6 hover:shadow-md hover:border-primary/40 dark:hover:border-blue-500/40 transition-all group">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-primary dark:group-hover:text-blue-400 transition-colors">
                            {{ $emergency->emergency_type }}
                        </h3>
                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full border {{ $statusColor }} whitespace-nowrap ml-3 shrink-0">
                            {{ $status }}
                        </span>
                    </div>
                    
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-5 line-clamp-2 leading-relaxed">
                        {{ $emergency->emergency_detail }}
                    </p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100 dark:border-slate-700/50">
                        <div>
                            <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-1 font-semibold">เวลาแจ้งเหตุ</div>
                            <div class="text-[13px] font-bold text-slate-700 dark:text-slate-300">
                                {{ $op && $op->time_create_sos ? \Carbon\Carbon::parse($op->time_create_sos)->format('d/m/Y H:i') : '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-1 font-semibold">เวลาเสร็จสิ้น</div>
                            <div class="text-[13px] font-bold text-slate-700 dark:text-slate-300">
                                {{ $op && $op->time_sos_success ? \Carbon\Carbon::parse($op->time_sos_success)->format('d/m/Y H:i') : '-' }}
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-1 font-semibold">เวลารวมที่ใช้</div>
                            <div class="text-[13px] font-bold text-primary dark:text-blue-400">
                                {{ $op && $op->time_sum_sos ? $op->time_sum_sos : '-' }}
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white dark:bg-[#1a2632] rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-10 text-center flex flex-col items-center justify-center min-h-[300px]">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400 mb-4 ring-1 ring-slate-100 dark:ring-slate-700">
                        <span class="material-symbols-outlined text-3xl">inbox</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">ยังไม่มีประวัติการแจ้งเหตุ</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">เมื่อคุณส่งคำขอความช่วยเหลือ ประวัติจะแสดงที่นี่</p>
                </div>
            @endforelse
        </div>
        
    </div>
</div>
@endsection