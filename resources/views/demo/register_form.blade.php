@extends('layouts.theme')

@section('content')

<div class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 antialiased selection:bg-primary/20 selection:text-primary">
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
      
        <div class="flex flex-1 flex-col items-center justify-center p-4 md:p-8 relative">
            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/10 via-transparent to-transparent opacity-60"></div>
                <div class="absolute bottom-0 right-0 w-full h-96 bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-slate-200/50 dark:from-slate-800/50 via-transparent to-transparent opacity-40"></div>
            </div>
            <div class="relative z-10 w-full max-w-lg">
                <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-8 pt-8 pb-6 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex flex-col gap-2 text-center">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">ลงทะเบียนเจ้าหน้าที่</h1>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">กรอกข้อมูลเพื่อลงทะเบียนเข้าสู่หน่วยงาน</p>
                        </div>
                        <div class="mt-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 flex items-center gap-4 shadow-sm">
                            <div class="w-12 h-12 shrink-0 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-500">
                                <span class="material-symbols-outlined text-2xl">local_hospital</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-primary uppercase tracking-wider">หน่วยงานที่เลือก</span>
                                <span class="font-bold text-slate-900 dark:text-white text-base">โรงพยาบาลศูนย์การแพทย์ฉุกเฉิน</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">เขตปฏิบัติการที่ 1 (กทม.)</span>
                            </div>
                            <div class="ml-auto text-primary">
                                <span class="material-symbols-outlined">verified</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 md:p-8 space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="name">ชื่อ-นามสกุล</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-3.5 text-slate-400 dark:text-slate-500 material-symbols-outlined text-[20px]">person</span>
                                <input class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600 text-slate-900 dark:text-white text-sm" id="name" name="name" placeholder="ระบุชื่อจริงและนามสกุล" type="text" />
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="type">ประเภทเจ้าหน้าที่</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-3.5 text-slate-400 dark:text-slate-500 material-symbols-outlined text-[20px]">badge</span>
                                <select class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none text-slate-900 dark:text-white cursor-pointer text-sm" id="type" name="type">
                                    <option disabled="" selected="" value="">เลือกประเภทเจ้าหน้าที่</option>
                                    <option value="emt-b">เวชกิจฉุกเฉินระดับต้น (EMT-B)</option>
                                    <option value="emt-i">เวชกิจฉุกเฉินระดับกลาง (EMT-I)</option>
                                    <option value="emt-p">เวชกิจฉุกเฉินระดับสูง (EMT-P)</option>
                                    <option value="driver">พนักงานขับรถฉุกเฉิน</option>
                                    <option value="volunteer">อาสาสมัครกู้ภัย</option>
                                </select>
                                
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="vehicle">รหัสรถ</label>
                                <div class="relative flex items-center">
                                    <span class="absolute left-3.5 text-slate-400 dark:text-slate-500 material-symbols-outlined text-[20px]">ambulance</span>
                                    <input class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600 text-slate-900 dark:text-white uppercase font-medium tracking-wide text-sm" id="vehicle" name="vehicle" placeholder="AMB-0199" type="text" />
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="level">ระดับ</label>
                                <div class="relative flex items-center">
                                    <span class="absolute left-3.5 text-slate-400 dark:text-slate-500 material-symbols-outlined text-[20px]">stars</span>
                                    <select class="w-full pl-10 pr-8 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none text-slate-900 dark:text-white cursor-pointer text-sm" id="level" name="level">
                                        <option disabled="" selected="" value="">เลือกระดับ</option>
                                        <option value="basic">BLS</option>
                                        <option value="intermediate">ALS</option>
                                        <option value="special">Specialized</option>
                                    </select>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 pb-8 pt-2">
                        <button class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white font-bold py-3 px-6 rounded-lg shadow-lg shadow-primary/20 transition-all hover:shadow-primary/30 active:scale-[0.98] text-sm">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            <span>ลงทะเบียนเจ้าหน้าที่</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsectiob