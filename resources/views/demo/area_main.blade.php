@extends('layouts.theme')

@section('content')

<div class="bg-background-light text-text-main font-display antialiased min-h-screen flex flex-col overflow-x-hidden mt-[61px]">
  
    <div class="flex-grow flex flex-col max-w-[1440px] mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 gap-8">
        <div class="flex  flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-text-main mb-2">ภาพรวมหน่วยงาน (แบบที่ 2)</h2>
                <p class="text-text-sub text-sm">ตารางแสดงรายละเอียดหน่วยงานทั้งหมดในระบบ พร้อมสถานะและการจัดการพื้นที่</p>
            </div>
            <div class="flex gap-3">
                <button class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-sm transition-all">
                    <span class="material-symbols-outlined mr-2 text-lg">add</span>
                    เพิ่มหน่วยงานใหม่
                </button>
            </div>
        </div>
        <div class="bg-surface-light rounded-xl border border-border-color shadow-sm flex flex-col">
            <div class="flex-wrap px-6 py-5 border-b border-border-color flex justify-between items-center bg-gray-50/30">
                <div class="flex items-center gap-3">
                    <h3 class="font-bold text-lg text-text-main">รายชื่อหน่วยงาน</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-text-sub border border-gray-200">ทั้งหมด 142</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                        <input class="pl-9 pr-4 py-2 text-sm border-border-color rounded-lg focus:ring-primary focus:border-primary w-64 bg-white" placeholder="ค้นหาหน่วยงาน..." type="text" />
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-border-color text-xs uppercase tracking-wider text-text-sub font-semibold">
                            <th class="px-6 py-4 w-[30%] min-w-[200px]">ชื่อหน่วยงาน</th>
                            <th class="px-6 py-4 w-[25%]">เขต/โซนรับผิดชอบ</th>
                            <th class="px-6 py-4 w-[20%] min-w-[150px]">ผู้ติดต่อหลัก</th>
                            <th class="px-6 py-4 w-[15%] min-w-[140px]">สถานะ</th>
                            <th class="px-6 py-4 w-[10%] text-right min-w-[130px]">การกระทำ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-color bg-white">
                        <tr class="group hover:bg-primary-light/30 transition-colors">
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 mt-0.5">
                                        <span class="material-symbols-outlined">medical_services</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-text-main text-sm">ตำรวจพื้นที่หลักสี่</div>
                                        <div class="text-xs text-text-sub mt-0.5">รหัสหน่วย: RES-001</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="text-sm font-medium text-text-main mb-1">เขตหลักสี่</div>
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">ทุ่งสองห้อง</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">ตลาดบางเขน</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-5 h-5 rounded-full bg-gray-200 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAlKiXG7HUhmG8TOCVWJnPrU7zlamCokzfFQOF8mo9Y2N20VC1nfyOdrAhO_C3y1Gw-nRfuaVKOFiLNTOeTkbNSYL38l_jBlxks6ZxhC9qAutLpcRFKpmOB9z7n3bCOzfRkIsPViHyCOHaRA30BqNuceO8FGeRHT2R0JCqQAuAP2wnt8O-OtHvF9eu2hGDtSTRPsfG3FUm_ro4AT5QwbS4Irh9_nfObnrFJFsB42qLcexDIyn4WWmq4BgeiWQMLhY_DDSewXQ46lz9b');"></div>
                                    <span class="text-sm text-text-main font-medium">สมชาย ใจดี</span>
                                </div>
                                <div class="text-xs text-text-sub flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">call</span> 081-234-5678
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                    เปิดใช้งาน
                                </span>
                            </td>
                            <td class="px-6 py-4 align-top text-right">
                                <button class="inline-flex items-center justify-center px-3 py-1.5 border border-primary text-primary hover:bg-primary hover:text-white rounded-md text-xs font-medium transition-colors gap-1">
                                    <span class="material-symbols-outlined text-sm">map</span>
                                    ดูพื้นที่
                                </button>
                            </td>
                        </tr>
                        <tr class="group hover:bg-primary-light/30 transition-colors">
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0 mt-0.5">
                                        <span class="material-symbols-outlined">local_hospital</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-text-main text-sm">ตำรวจส่วนกลาง</div>
                                        <div class="text-xs text-text-sub mt-0.5">รหัสหน่วย: EMS-HQ</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="text-sm font-medium text-text-main mb-1">ทั่วกรุงเทพมหานคร</div>
                                <div class="text-xs text-text-sub">ศูนย์สั่งการและประสานงานหลัก</div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-5 h-5 rounded-full bg-blue-200 flex items-center justify-center text-[10px] text-blue-700 font-bold">W</div>
                                    <span class="text-sm text-text-main font-medium">วิชัย มั่นคง</span>
                                </div>
                                <div class="text-xs text-text-sub flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">call</span> 1669 (ภายใน 101)
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                    เปิดใช้งาน
                                </span>
                            </td>
                            <td class="px-6 py-4 align-top text-right">
                                <button class="inline-flex items-center justify-center px-3 py-1.5 border border-primary text-primary hover:bg-primary hover:text-white rounded-md text-xs font-medium transition-colors gap-1">
                                    <span class="material-symbols-outlined text-sm">map</span>
                                    ดูพื้นที่
                                </button>
                            </td>
                        </tr>
                        <tr class="group hover:bg-primary-light/30 transition-colors">
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0 mt-0.5">
                                        <span class="material-symbols-outlined">emergency_share</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-text-main text-sm">ตำรวจดอนเมือง</div>
                                        <div class="text-xs text-text-sub mt-0.5">รหัสหน่วย: VOL-088</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="text-sm font-medium text-text-main mb-1">เขตดอนเมือง</div>
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">ดอนเมือง</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">สีกัน</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">สนามบิน</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-5 h-5 rounded-full bg-orange-200 flex items-center justify-center text-[10px] text-orange-700 font-bold">S</div>
                                    <span class="text-sm text-text-main font-medium">สมศักดิ์ กล้าหาญ</span>
                                </div>
                                <div class="text-xs text-text-sub flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">call</span> 089-999-8888
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                    ปิดใช้งาน
                                </span>
                            </td>
                            <td class="px-6 py-4 align-top text-right">
                                <button class="inline-flex items-center justify-center px-3 py-1.5 border border-primary text-primary hover:bg-primary hover:text-white rounded-md text-xs font-medium transition-colors gap-1">
                                    <span class="material-symbols-outlined text-sm">map</span>
                                    ดูพื้นที่
                                </button>
                            </td>
                        </tr>
                        <tr class="group hover:bg-primary-light/30 transition-colors">
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center text-teal-600 flex-shrink-0 mt-0.5">
                                        <span class="material-symbols-outlined">ambulance</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-text-main text-sm">หน่วยกู้ชีพวชิระ</div>
                                        <div class="text-xs text-text-sub mt-0.5">รหัสหน่วย: HOS-005</div>
                                        <div class="text-xs text-text-sub mt-0.5">ประเภท: โรงพยาบาล</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="text-sm font-medium text-text-main mb-1">เขตดุสิต</div>
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">วชิรพยาบาล</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">ดุสิต</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">ถนนนครไชยศรี</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-5 h-5 rounded-full bg-teal-200 flex items-center justify-center text-[10px] text-teal-700 font-bold">P</div>
                                    <span class="text-sm text-text-main font-medium">พญ.ปราณี รักษา</span>
                                </div>
                                <div class="text-xs text-text-sub flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">call</span> 02-244-3000
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                    เปิดใช้งาน
                                </span>
                            </td>
                            <td class="px-6 py-4 align-top text-right">
                                <button class="inline-flex items-center justify-center px-3 py-1.5 border border-primary text-primary hover:bg-primary hover:text-white rounded-md text-xs font-medium transition-colors gap-1">
                                    <span class="material-symbols-outlined text-sm">map</span>
                                    ดูพื้นที่
                                </button>
                            </td>
                        </tr>
                        <tr class="group hover:bg-primary-light/30 transition-colors">
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-700 flex-shrink-0 mt-0.5">
                                        <span class="material-symbols-outlined">volunteer_activism</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-text-main text-sm">อาสาสมัครป่อเต็กตึ๊ง (จุดบางซื่อ)</div>
                                        <div class="text-xs text-text-sub mt-0.5">รหัสหน่วย: VOL-102</div>
                                        <div class="text-xs text-text-sub mt-0.5">ประเภท: อาสาสมัคร</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="text-sm font-medium text-text-main mb-1">เขตบางซื่อ</div>
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">บางซื่อ</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">วงศ์สว่าง</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-5 h-5 rounded-full bg-yellow-200 flex items-center justify-center text-[10px] text-yellow-800 font-bold">K</div>
                                    <span class="text-sm text-text-main font-medium">กิตติ ช่วยเหลือ</span>
                                </div>
                                <div class="text-xs text-text-sub flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">call</span> 085-555-4444
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                    พักเบรก
                                </span>
                            </td>
                            <td class="px-6 py-4 align-top text-right">
                                <button class="inline-flex items-center justify-center px-3 py-1.5 border border-primary text-primary hover:bg-primary hover:text-white rounded-md text-xs font-medium transition-colors gap-1">
                                    <span class="material-symbols-outlined text-sm">map</span>
                                    ดูพื้นที่
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-border-color flex items-center justify-between bg-gray-50/30">
                <div class="text-sm text-text-sub">
                    แสดง <span class="font-medium text-text-main">1</span> ถึง <span class="font-medium text-text-main">5</span> จากทั้งหมด <span class="font-medium text-text-main">142</span> รายการ
                </div>
                <div class="flex gap-2">
                    <button class="px-3 py-1.5 border border-border-color rounded-lg text-sm text-text-sub hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed bg-white shadow-sm" disabled="">ก่อนหน้า</button>
                    <div class="hidden sm:flex gap-1">
                        <button class="px-3 py-1.5 border border-primary bg-primary text-white rounded-lg text-sm shadow-sm">1</button>
                        <button class="px-3 py-1.5 border border-border-color bg-white text-text-main rounded-lg text-sm hover:bg-gray-50 shadow-sm">2</button>
                        <button class="px-3 py-1.5 border border-border-color bg-white text-text-main rounded-lg text-sm hover:bg-gray-50 shadow-sm">3</button>
                        <span class="px-2 py-1.5 text-text-sub">...</span>
                        <button class="px-3 py-1.5 border border-border-color bg-white text-text-main rounded-lg text-sm hover:bg-gray-50 shadow-sm">29</button>
                    </div>
                    <button class="px-3 py-1.5 border border-border-color rounded-lg text-sm text-text-main hover:bg-white bg-white shadow-sm">ถัดไป</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection