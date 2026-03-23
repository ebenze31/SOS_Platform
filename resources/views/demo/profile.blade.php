@extends('layouts.theme_user')

@section('content')

<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>
<div class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display mt-[50px]">
    <div class="relative flex h-auto w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">

            <div class="flex flex-1 justify-center py-8 px-4 md:px-0">
                <div class="layout-content-container flex flex-col max-w-[720px] flex-1 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 md:p-10">
                    <!-- Profile Picture Section -->
                    <div class="flex flex-col items-center mb-10">
                        <div class="relative group">
                            <div class="bg-slate-200 dark:bg-slate-800 bg-center bg-no-repeat aspect-square bg-cover rounded-full h-32 w-32 border-4 border-white dark:border-slate-800 shadow-md" data-alt="Professional portrait of an emergency responder in uniform" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuATGNn7iEzdh78GdCNSVlb1S3joXXzPgz2QU1HM4UVApV4tLe_WKkES9ZLLasKfd6UroygEdaoBvJ_FsTJ59iPpbhFU8IuZ5W55ezb8yQQ1ceqwRRXq-El5q9ufrRXV5fUXKGWLLcjSnMyXKQ-7sQMwuf-q4CrJ-jpvkuu20j_brY5LJjQPYh4Rg4dSiadbTAUUdy62ZRJmho64dle_QlwOLDOTyj70g-CeU5BTLlv-om7VCNrQbxxH_xMeMOl334HQjumIKsdW3AJl");'>
                            </div>
                            <button class="absolute bottom-0 right-0 bg-primary text-white p-2 w-[30px] h-[30px] flex justify-center items-center rounded-full shadow-lg hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-sm">photo_camera</span>
                            </button>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-slate-900 dark:text-slate-100 text-xl font-bold">สมชาย เข็มกลัด</p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">รหัสเจ้าหน้าที่: EMS-9924</p>
                        </div>
                    </div>
                    <!-- Personal Information Section -->
                    <section class="mb-10">
                        <div class="flex items-center gap-2 mb-6 border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="material-symbols-outlined text-primary">person</span>
                            <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold">ข้อมูลส่วนตัว</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <label class="flex flex-col gap-2">
                                <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">ชื่อ-นามสกุล</span>
                                <input class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" type="text" value="{{Auth::user()->name}}" />
                            </label>
                            <!-- <label class="flex flex-col gap-2">
                                <span class="text-slate-500 dark:text-slate-400 text-sm font-semibold">รหัสพนักงาน (อ่านอย่างเดียว)</span>
                                <input class="rounded-lg border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-slate-400 cursor-not-allowed h-12 px-4" disabled="" type="text" value="EMS-9924" />
                            </label> -->
                            <label class="flex flex-col gap-2">
                                <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">อีเมล</span>
                                <input class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" type="email" value="{{Auth::user()->email}}" />
                            </label>
                            <label class="flex flex-col gap-2">
                                <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">เบอร์โทรศัพท์ด่วน</span>
                                <input class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" type="tel" value="{{Auth::user()->phone}}" />
                            </label>

                            <label class="flex flex-col gap-2">
                                <div class="flex justify-between">
                                    <div> <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">เบอร์โทรศัพท์ด่วน</span>
                                        <input  type="tel" value="{{Auth::user()->phone}}" />
                                    </div>
                                    <div> <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">เบอร์โทรศัพท์ด่วน</span>
                                        <input class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" type="tel" value="{{Auth::user()->phone}}" />
                                    </div>
                                </div>

                            </label>
                        </div>
                    </section>
                    <!-- Department Section -->
                    @if(Auth::user()->role == "officer")
                    <section class="mb-10">
                        <div class="flex items-center gap-2 mb-6 border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="material-symbols-outlined text-primary">corporate_fare</span>
                            <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold">สังกัดและตำแหน่ง</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <label class="flex flex-col gap-2">
                                <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">ประเภท</span>
                                <input class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" type="text" value="" />
                            </label>
                            <label class="flex flex-col gap-2">
                                <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">พื้นที่ที่สังกัด</span>
                                <select class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4">
                                    <option>พื้นที่ A</option>
                                    <option>พื้นที่ B</option>
                                </select>
                            </label>
                            <label class="flex flex-col gap-2">
                                <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">ยานภาหนะ</span>
                                <input class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" type="text" value="" />
                            </label>
                        </div>

                    </section>
                    @endif
                    <!-- Security Section -->
                    <section class="mb-6">
                        <div class="flex items-center gap-2 mb-6 border-b border-slate-100 dark:border-slate-800 pb-2">
                            <span class="material-symbols-outlined text-primary">security</span>
                            <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold">การตั้งค่าความปลอดภัย</h3>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-xl border border-dashed border-slate-200 dark:border-slate-700">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <p class="text-slate-900 dark:text-slate-100 font-semibold">รหัสผ่าน</p>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm">เปลี่ยนรหัสผ่านเพื่อความปลอดภัยของระบบ</p>
                                </div>
                                <button class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 px-4 py-2 rounded-lg text-sm font-bold shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    เปลี่ยนรหัสผ่าน
                                </button>
                            </div>
                        </div>
                    </section>
                    <!-- Action Buttons (Mobile View Only / Bottom) -->
                    <div class="mt-10 flex  flex-col md:flex-row md:justify-end gap-3">
                        <button class="md:order-2 px-5 bg-primary text-white py-4 rounded-lg font-bold shadow-md">
                            บันทึกข้อมูลทั้งหมด
                        </button>
                        <button class="md:order-1 px-5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 py-4 rounded-lg font-bold">
                            ยกเลิก
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection