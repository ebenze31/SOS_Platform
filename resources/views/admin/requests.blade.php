@extends('layouts.theme')

@section('content')
<div class="bg-slate-50 dark:bg-slate-900 font-display min-h-[calc(100vh-71.75px)] mt-[71.75px] text-slate-900 dark:text-slate-100 pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight">ตรวจสอบคำขอลงทะเบียนพื้นที่</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">อนุมัติหรือปฏิเสธคำขอลงทะเบียนของเจ้าหน้าที่</p>
            </div>
            
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-4 py-2 rounded-xl shadow-sm flex items-center gap-3 shrink-0">
                <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                <span class="text-sm font-semibold">รออนุมัติ <span class="text-amber-600 dark:text-amber-400 ml-1">{{ count($pendingRequests) }}</span> รายการ</span>
            </div>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm transition-all">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Content List --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            @if(count($pendingRequests) > 0)
                <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @foreach($pendingRequests as $req)
                        <div class="p-4 sm:p-6 hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors flex flex-col lg:flex-row items-start lg:items-center gap-4 lg:gap-6">
                            
                            {{-- Officer Info --}}
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 shrink-0">
                                    <span class="material-symbols-outlined text-2xl">person</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ $req['name_officer'] }}</h3>
                                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">directions_car</span> {{ $req['vehicle_type'] }}</span>
                                        <span>•</span>
                                        <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> {{ \Carbon\Carbon::parse($req['requested_at'])->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Area Info --}}
                            <div class="flex-1 bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3 border border-slate-100 dark:border-slate-700 w-full lg:w-auto">
                                <div class="flex items-center gap-3">
                                    <div class="overflow-hidden">
                                        <p class="text-[10px] font-bold text-primary uppercase tracking-wider">ลงทะเบียนพื้นที่</p>
                                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate">{{ $req['area_name'] }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $req['area_type'] }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 w-full lg:w-auto pt-2 lg:pt-0 shrink-0">
                                {{-- ฟอร์มอนุมัติ --}}
                                <form action="{{ route('command.requests.manage') }}" method="POST" class="flex-1 lg:flex-none">
                                    @csrf
                                    <input type="hidden" name="officer_id" value="{{ $req['officer_id'] }}">
                                    <input type="hidden" name="area_id" value="{{ $req['area_id'] }}">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" onclick="return confirm('ยืนยันการอนุมัติเจ้าหน้าที่ท่านนี้?')" class="w-full lg:w-auto flex items-center justify-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-sm shadow-emerald-500/20 active:scale-95">
                                        <span class="material-symbols-outlined text-[18px]">check</span>
                                        อนุมัติ
                                    </button>
                                </form>

                                {{-- ปุ่มเปิด Modal ปฏิเสธ --}}
                                <button type="button" onclick="openRejectModal({{ $req['officer_id'] }}, {{ $req['area_id'] }}, '{{ $req['name_officer'] }}')" class="flex-1 lg:flex-none flex items-center justify-center gap-1.5 bg-white dark:bg-slate-800 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 border border-slate-200 dark:border-slate-700 hover:border-red-200 dark:hover:border-red-800 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all active:scale-95">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                    ปฏิเสธ
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="py-16 px-6 text-center flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-300 dark:text-slate-600 mb-4">
                        <span class="material-symbols-outlined text-5xl">task</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-1">ไม่มีคำขอรออนุมัติ</h3>
                    <p class="text-sm text-slate-500">เยี่ยมมาก! คุณจัดการคำขอทั้งหมดเรียบร้อยแล้ว</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ================= Modal ปฏิเสธคำขอ ================= --}}
<div id="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeRejectModal()"></div>
    <div class="relative bg-white dark:bg-slate-800 w-full max-w-md mx-4 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden transform scale-95 transition-transform duration-300" id="rejectModalContent">
        
        <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-red-50/50 dark:bg-red-900/10">
            <h3 class="text-lg font-bold text-red-600 dark:text-red-400 flex items-center gap-2">
                <span class="material-symbols-outlined">warning</span>
                ปฏิเสธคำขอลงทะเบียน
            </h3>
            <button onclick="closeRejectModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('command.requests.manage') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="action" value="reject">
            <input type="hidden" name="officer_id" id="reject_officer_id">
            <input type="hidden" name="area_id" id="reject_area_id">

            <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
                คุณกำลังปฏิเสธคำขอของ: <strong id="reject_officer_name" class="text-slate-900 dark:text-white"></strong>
            </p>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">เหตุผลที่ปฏิเสธ (Remark) <span class="text-red-500">*</span></label>
                <textarea required name="remark" rows="3" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all text-sm text-slate-900 dark:text-white placeholder:text-slate-400" placeholder="เช่น กรอกข้อมูลไม่ครบถ้วน, รถไม่ตรงประเภท..."></textarea>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2.5 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-sm">
                    ยกเลิก
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl shadow-sm shadow-red-500/20 transition-all active:scale-95 text-sm">
                    ยืนยันการปฏิเสธ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('rejectModal');
    const modalContent = document.getElementById('rejectModalContent');

    function openRejectModal(officerId, areaId, officerName) {
        // Set values
        document.getElementById('reject_officer_id').value = officerId;
        document.getElementById('reject_area_id').value = areaId;
        document.getElementById('reject_officer_name').textContent = officerName;

        // Show Modal
        modal.classList.remove('hidden');
        // Trigger animation
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
    }

    function closeRejectModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300); // Wait for transition
    }
</script>
@endsection