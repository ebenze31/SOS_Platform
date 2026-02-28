@extends('layouts.theme')

@section('content')

<div class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 antialiased selection:bg-primary/20 selection:text-primary mt-[71.75px]">
    <div class="relative flex min-h-[calc(100vh-71.75px)] w-full flex-col overflow-x-hidden">
      
        <div class="flex flex-1 flex-col items-center justify-center p-4 md:p-8 relative">
            {{-- Background Effects --}}
            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/10 via-transparent to-transparent opacity-60"></div>
                <div class="absolute bottom-0 right-0 w-full h-96 bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-slate-200/50 dark:from-slate-800/50 via-transparent to-transparent opacity-40"></div>
            </div>

            <div class="relative z-10 w-full max-w-lg">

                <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
                    
                    {{-- ส่วนหัวการ์ด (โชว์ข้อมูลพื้นที่) --}}
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-8 pt-8 pb-6 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex flex-col gap-2 text-center">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">ลงทะเบียนเจ้าหน้าที่</h1>
                        </div>
                        
                        {{-- การ์ดแสดงพื้นที่ที่เลือก --}}
                        <div class="mt-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 flex items-center gap-4 shadow-sm relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary"></div>
                            <div class="flex flex-col flex-1 overflow-hidden">
                                <span class="text-[10px] font-semibold text-primary uppercase tracking-wider">พื้นที่เป้าหมาย</span>
                                <span class="font-bold text-slate-900 dark:text-white text-base truncate">{{ $selectedArea->name_area }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">ประเภท: {{ $selectedArea->type }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- เช็คสถานะ: จากตัวแปร $currentStatus ที่แกะมาจาก JSON --}}
                    @if($currentStatus)
                        
                        <div class="p-8 text-center space-y-4">
                            @if($currentStatus == 'Pending')
                                {{-- สถานะ: รออนุมัติ --}}
                                <div class="w-20 h-20 mx-auto bg-amber-50 dark:bg-amber-500/10 text-amber-500 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-4xl">hourglass_empty</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">รอการอนุมัติ</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">คุณได้ส่งคำขอลงทะเบียนพื้นที่นี้แล้ว กรุณารอศูนย์สั่งการตรวจสอบและยืนยันข้อมูล</p>
                                
                            @elseif($currentStatus == 'Approve')
                                {{-- สถานะ: อนุมัติแล้ว --}}
                                <div class="w-20 h-20 mx-auto bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-4xl">check_circle</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">อนุมัติเรียบร้อย</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">คุณสามารถเริ่มงานในพื้นที่นี้ได้ทันที</p>
                                
                                {{-- ปุ่มไปหน้าเปิดสถานะ --}}
                                <a href="{{ route('user_officers.scan') }}" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-emerald-500/20 transition-all hover:shadow-emerald-500/30 active:scale-[0.98] text-sm">
                                    <span class="material-symbols-outlined text-[18px]">power_settings_new</span>
                                    <span>ไปหน้าเปิดสถานะการทำงาน</span>
                                </a>

                            @elseif($currentStatus == 'Reject')
                                {{-- สถานะ: ถูกปฏิเสธ (Rejected) --}}
                                <div class="w-20 h-20 mx-auto bg-red-50 dark:bg-red-500/10 text-red-500 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-4xl">cancel</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">ถูกปฏิเสธ</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">คำขอลงทะเบียนของคุณไม่ได้รับการอนุมัติ</p>

                                @if($remark)
                                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-lg p-3 text-left mb-2">
                                        <p class="text-xs font-bold text-red-600 dark:text-red-400 mb-1">เหตุผลจากศูนย์สั่งการ:</p>
                                        <p class="text-sm text-red-700 dark:text-red-300">{{ $remark }}</p>
                                    </div>
                                @endif

                                {{-- ข้อความแนะนำบางๆ --}}
                                <div class="mt-6 flex items-center justify-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                                    <span>กรุณาติดต่อศูนย์ควบคุมเพื่อสอบถามรายละเอียด</span>
                                </div>
                            @endif
                        </div>

                    @else
                        {{-- ถ้ายังไม่เคยลงทะเบียนพื้นที่นี้ โชว์แบบฟอร์มให้กรอกปกติ (ดึงข้อมูลเก่าถ้ามี) --}}
                        <form action="{{ route('user_officers.register_store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="area_id" value="{{ $selectedArea->id }}">

                            <div class="p-6 md:p-8 space-y-5">
                                {{-- ชื่อ-นามสกุล / นามเรียกขาน --}}
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="name_officer">ชื่อ (แสดงผลในระบบ)<span class="text-red-500">*</span></label>
                                    <div class="relative flex items-center">
                                        <span class="absolute left-3.5 text-slate-400 dark:text-slate-500 material-symbols-outlined text-[20px]">person</span>
                                        <input required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600 text-slate-900 dark:text-white text-sm" id="name_officer" name="name_officer" value="{{ old('name_officer', $userProfile->name_officer ?? '') }}" placeholder="กรุณากรอกชื่อ" type="text" />
                                    </div>
                                    @error('name_officer') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- ประเภทยานพาหนะ --}}
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="vehicle_type">ประเภทยานพาหนะ <span class="text-red-500">*</span></label>
                                    <div class="relative flex items-center">
                                        <span class="absolute left-3.5 text-slate-400 dark:text-slate-500 material-symbols-outlined text-[20px]">directions_car</span>
                                        <select required class="w-full pl-10 pr-8 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none text-slate-900 dark:text-white cursor-pointer text-sm" id="vehicle_type" name="vehicle_type">
                                            <option disabled {{ empty($userProfile) ? 'selected' : '' }} value="">เลือกพาหนะ</option>
                                            <option value="รถพยาบาล" {{ old('vehicle_type', $userProfile->vehicle_type ?? '') == 'รถพยาบาล' ? 'selected' : '' }}>รถพยาบาล</option>
                                            <option value="รถอุปกรณ์กู้ภัย" {{ old('vehicle_type', $userProfile->vehicle_type ?? '') == 'รถอุปกรณ์กู้ภัย' ? 'selected' : '' }}>รถอุปกรณ์กู้ภัย</option>
                                            <option value="รถดับเพลิง" {{ old('vehicle_type', $userProfile->vehicle_type ?? '') == 'รถดับเพลิง' ? 'selected' : '' }}>รถดับเพลิง</option>
                                            <option value="รถยนต์" {{ old('vehicle_type', $userProfile->vehicle_type ?? '') == 'รถยนต์' ? 'selected' : '' }}>รถยนต์</option>
                                            <option value="รถจักรยานยนต์" {{ old('vehicle_type', $userProfile->vehicle_type ?? '') == 'รถจักรยานยนต์' ? 'selected' : '' }}>รถจักรยานยนต์</option>
                                        </select>
                                    </div>
                                    @error('vehicle_type') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="px-8 pb-8 pt-2">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-primary/20 transition-all hover:shadow-primary/30 active:scale-[0.98] text-sm">
                                    <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                                    <span>ส่งคำขอลงทะเบียน</span>
                                </button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection