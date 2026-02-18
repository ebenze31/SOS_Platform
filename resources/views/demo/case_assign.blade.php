@extends('layouts.theme')

@section('content')
<!DOCTYPE html>
<html class="light" lang="th">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ระบบบัญชาการเหตุการณ์ - Incident Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Sarabun", "sans-serif"],
                        "sans": ["Sarabun", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .bg-map-pattern {
            background-color: #e5e9ec;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
        }

        body {
            height: 100dvh !important;
        }
    </style>
</head>

<div class="bg-background-light h-full dark:bg-background-dark text-slate-900  flex flex-col  relative mt-[71.75px]">
    <div class="flex-1  bg-slate-50/50 p-4 sm:p-6 z-0">
        <div class="h-full max-w-[1800px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-7 xl:col-span-8 flex flex-col bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden h-full">
                <div class="shrink-0 bg-white relative z-20 shadow-sm border-b border-slate-200">
                    <div class="p-6 border-b border-slate-100">
                        <div class="flex flex-wrap justify-between items-start">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-700 bg-red-50 px-2.5 py-1 rounded-md border border-red-100 uppercase tracking-wide">
                                        <span class="material-symbols-outlined text-[14px]">warning</span> วิกฤต
                                    </span>
                                    <span class="text-xs font-mono text-slate-400">รหัส: INC-9205-A</span>
                                </div>
                                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">ภาวะหัวใจหยุดเต้น • ฉุกเฉินทางการแพทย์</h1>
                            </div>
                            <div class="text-right bg-slate-50 px-4 py-2 rounded-lg border border-slate-100">
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">เวลาที่ผ่านไป</div>
                                <div class="text-2xl font-mono font-bold text-red-600 flex items-center gap-2 justify-end">
                                    <span class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                    </span>
                                    00:04:12
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <div class="bg-slate-50/50 rounded-xl border border-slate-200 p-5">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">person</span>
                                    ผู้แจ้งเหตุ / ผู้ประสบภัย
                                </h3>
                                <span class="text-[10px] font-medium text-slate-400 bg-white border border-slate-200 px-2 py-0.5 rounded">ยืนยันแล้ว</span>
                            </div>
                            <div class="flex items-center justify-between gap-4 flex-wrap">
                                <div>
                                    <div class="text-xl font-bold text-slate-900">คุณซาร่า เจนกินส์</div>
                                    <div class="text-sm text-slate-500">ผู้แจ้งเหตุในสถานที่เกิดเหตุ</div>
                                </div>
                                <button class="flex items-center gap-2 px-4 py-2.5 max-sm:w-full  max-sm:justify-center bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-lg hover:border-primary hover:text-primary hover:shadow-md transition-all group">
                                    <span class="material-symbols-outlined text-[20px] group-hover:animate-pulse">call</span>
                                    โทรตอนนี้
                                </button>
                            </div>
                        </div>
                        <div class="bg-slate-50/50 rounded-xl border border-slate-200 p-5">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">location_on</span>
                                    สถานที่เกิดเหตุ
                                </h3>
                            </div>
                            <div class="space-y-1">
                                <div class="text-lg font-bold text-slate-900 leading-tight">8421 ถนนบรอด, ห้อง 4B</div>
                                <div class="text-sm text-slate-500">เขตเวสต์ไซด์ • จุดตัด: เมน และ 4</div>
                                <div class="flex items-center gap-2 mt-3">
                                    <code class="bg-slate-200/50 px-2 py-1 rounded text-xs text-slate-600 font-mono">34.0522, -118.2437</code>
                                    <button class="text-slate-400 hover:text-primary transition-colors" title="Copy"><span class="material-symbols-outlined text-[14px]">content_copy</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 relative w-full bg-slate-100 overflow-hidden group">
                    <div class="absolute inset-0 bg-map-pattern opacity-60"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 flex flex-col items-center">
                        <div class="relative flex h-10 w-10 cursor-pointer">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-10 w-10 bg-red-600 border-[3px] border-white shadow-xl items-center justify-center text-white">
                                <span class="material-symbols-outlined text-[20px]">warning</span>
                            </span>
                        </div>
                        <div class="mt-2 bg-slate-900/90 backdrop-blur text-white text-[11px] font-bold px-3 py-1.5 rounded-full shadow-lg border border-slate-700 whitespace-nowrap">
                            จุดเกิดเหตุวิกฤต
                        </div>
                    </div>
                    <div class="absolute bottom-6 left-6 z-30 group/thumb">
                        <div class="relative w-36 h-24 bg-slate-900 rounded-lg border-2 border-white shadow-2xl overflow-hidden transition-all duration-300 group-hover/thumb:w-[320px] group-hover/thumb:h-[200px] origin-bottom-left cursor-pointer ease-out">
                            <img alt="ภาพถ่ายที่เกิดเหตุ" class="w-full h-full object-cover opacity-90 group-hover/thumb:opacity-100 transition-opacity" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA31ZoJED9mous9oO2UAzpTSTMuL1mAIqxl7RQ5Hu4MsVFi3lDRXRbJm435hzDl8fkuA0tCZ8Gwmhas0H5EVyCzVFR_hvFcqkicrU8d0QxUXkL76g0ozT74IURD8lDSN-223R8U9EQ1HtFVPR0zwilhjacL1Rqtp9SWnMjYh_Wd8PWNa0JJhB-z-GACrnV-QC5As7-cMG1sVF1wpV66Ud-O9nGBpPe87yKWqUp7BAE40jLVXLN46LwFkluAWkXfrMDvo0nDrEPbR4vY" />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                            <div class="absolute bottom-2 left-2 right-2 flex items-end justify-between transition-opacity duration-200 group-hover/thumb:opacity-0">
                                <div class="flex items-center gap-1.5 text-white/90">
                                    <span class="material-symbols-outlined text-[16px]">image</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wide">รูปภาพที่เกิดเหตุ</span>
                                </div>
                                <span class="material-symbols-outlined text-white/70 text-[14px]">open_in_full</span>
                            </div>
                            <div class="absolute inset-0 p-4 flex flex-col justify-end opacity-0 group-hover/thumb:opacity-100 transition-opacity duration-300 delay-75">
                                <div class="flex justify-between items-start mb-2 absolute top-4 left-4 right-4">
                                    <span class="bg-red-500/90 backdrop-blur text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm uppercase tracking-wider">ไม่สามารถดูวิดีโอสดได้</span>
                                    <button class="bg-black/40 hover:bg-black/60 text-white rounded-full p-1.5 backdrop-blur transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">close_fullscreen</span>
                                    </button>
                                </div>
                                <div>
                                    <h4 class="text-white font-bold text-sm mb-0.5">ภาพบรรยากาศที่เกิดเหตุ</h4>
                                    <p class="text-white/70 text-xs">อัปโหลดโดยผู้แจ้งเหตุ • 2 นาทีที่แล้ว</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-6 right-6 flex flex-col gap-2 z-20">
                        <div class="bg-white rounded-lg shadow-lg border border-slate-200 overflow-hidden flex flex-col">
                            <button class="p-2 hover:bg-slate-50 text-slate-600 border-b border-slate-100"><span class="material-symbols-outlined text-[20px]">add</span></button>
                            <button class="p-2 hover:bg-slate-50 text-slate-600"><span class="material-symbols-outlined text-[20px]">remove</span></button>
                        </div>
                        <button class="bg-white p-2 rounded-lg shadow-lg border border-slate-200 text-slate-600 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">my_location</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-5 xl:col-span-4 flex flex-col h-full gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 shrink-0">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">สถานะเหตุการณ์</h3>
                        <span class="material-symbols-outlined text-slate-300">toggle_on</span>
                    </div>
                    <div class="flex bg-slate-100 p-1.5 rounded-lg border border-slate-200 gap-1">
                        <button class="flex-1 py-3 px-1 rounded-md bg-white text-slate-900 shadow-sm border border-slate-200 text-[12px] font-bold transition-all flex items-center justify-center gap-1 relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500 rounded-l-md"></div>
                            รอดำเนินการ
                        </button>
                        <button class="flex-1 py-3 px-1 rounded-md text-slate-500 hover:bg-slate-200/50 hover:text-slate-700 text-[12px] font-medium transition-all">กำลังดำเนินการ</button>
                        <button class="flex-1 py-3 px-1 rounded-md text-slate-500 hover:bg-slate-200/50 hover:text-slate-700 text-[12px] font-medium transition-all">เสร็จสิ้น</button>
                    </div>
                </div>
                <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col overflow-hidden relative">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white z-10">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">เจ้าหน้าที่ช่วยเหลือที่ใกล้ที่สุด</h3>
                            <p class="text-xs text-slate-400 mt-0.5">พิจารณาจากระยะทางและความสามารถ</p>
                        </div>
                        <button class="text-primary hover:bg-primary/5 p-2 rounded-lg transition-colors">
                            <span class="material-symbols-outlined">tune</span>
                        </button>
                    </div>
                    <div class="flex-1 overflow-auto max-h-[calc(100dvh - 900px)] custom-scrollbar p-4 space-y-3 bg-slate-50/30" style="max-height: calc(100dvh - 530px);">
                        <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group">
                            <input class="absolute right-4 top-4 rounded border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" type="checkbox" />
                            <div class="flex items-center gap-4 w-full">
                                <div class="size-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">ambulance</span>
                                </div>
                                <div class="flex-1 pr-8">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-slate-900">รถฉุกเฉิน 202</h4>
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">1.3 กม.</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> 2 นาที</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="font-medium text-slate-700">หน่วย ALS</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group">
                            <input class="absolute right-4 top-4 rounded border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" type="checkbox" />
                            <div class="flex items-center gap-4 w-full">
                                <div class="size-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">ambulance</span>
                                </div>
                                <div class="flex-1 pr-8">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-slate-900">รถฉุกเฉิน 202</h4>
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">1.3 กม.</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> 2 นาที</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="font-medium text-slate-700">หน่วย ALS</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group">
                            <input class="absolute right-4 top-4 rounded border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" type="checkbox" />
                            <div class="flex items-center gap-4 w-full">
                                <div class="size-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">ambulance</span>
                                </div>
                                <div class="flex-1 pr-8">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-slate-900">รถฉุกเฉิน 202</h4>
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">1.3 กม.</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> 2 นาที</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="font-medium text-slate-700">หน่วย ALS</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group">
                            <input class="absolute right-4 top-4 rounded border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" type="checkbox" />
                            <div class="flex items-center gap-4 w-full">
                                <div class="size-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">ambulance</span>
                                </div>
                                <div class="flex-1 pr-8">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-slate-900">รถฉุกเฉิน 202</h4>
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">1.3 กม.</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> 2 นาที</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="font-medium text-slate-700">หน่วย ALS</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group">
                            <input class="absolute right-4 top-4 rounded border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" type="checkbox" />
                            <div class="flex items-center gap-4 w-full">
                                <div class="size-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">ambulance</span>
                                </div>
                                <div class="flex-1 pr-8">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-slate-900">รถฉุกเฉิน 202</h4>
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">1.3 กม.</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> 2 นาที</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="font-medium text-slate-700">หน่วย ALS</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                         <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group">
                            <input class="absolute right-4 top-4 rounded border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" type="checkbox" />
                            <div class="flex items-center gap-4 w-full">
                                <div class="size-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">ambulance</span>
                                </div>
                                <div class="flex-1 pr-8">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-slate-900">รถฉุกเฉิน 202</h4>
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">1.3 กม.</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> 2 นาที</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="font-medium text-slate-700">หน่วย ALS</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group">
                            <input class="absolute right-4 top-4 rounded border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" type="checkbox" />
                            <div class="flex items-center gap-4 w-full">
                                <div class="size-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">ambulance</span>
                                </div>
                                <div class="flex-1 pr-8">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-slate-900">รถฉุกเฉิน 202</h4>
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">1.3 กม.</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> 2 นาที</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="font-medium text-slate-700">หน่วย ALS</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group">
                            <input class="absolute right-4 top-4 rounded border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" type="checkbox" />
                            <div class="flex items-center gap-4 w-full">
                                <div class="size-12 rounded-full bg-red-50 border border-red-100 flex items-center justify-center text-red-600 shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">fire_truck</span>
                                </div>
                                <div class="flex-1 pr-8">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-slate-900">รถดับเพลิง 41</h4>
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">1.9 กม.</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> 4 นาที</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="font-medium text-slate-700">ดับเพลิง/กู้ภัย</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-primary/40 hover:shadow-md transition-all group opacity-75">
                            <input class="absolute right-4 top-4 rounded border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer" type="checkbox" />
                            <div class="flex items-center gap-4 w-full">
                                <div class="size-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">local_police</span>
                                </div>
                                <div class="flex-1 pr-8">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-slate-900">สายตรวจ 104</h4>
                                        <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-yellow-200">5.6 กม.</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> 8 นาที</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="font-medium text-slate-700">ไม่ว่าง</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="p-5 border-t border-slate-100 bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
                        <button class="w-full py-4 bg-primary hover:bg-blue-600 text-white font-bold text-sm uppercase tracking-wide rounded-xl shadow-lg shadow-blue-500/25 flex items-center justify-center gap-3 transition-all transform hover:-translate-y-0.5">
                            <span>ส่งไปยังเจ้าหน้าที่</span>
                            <span class="material-symbols-outlined">send</span>
                        </button>
                        <button class="w-full mt-3 py-3 bg-white border border-slate-200 text-slate-500 font-bold text-sm rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                            ยกเลิกเหตุการณ์
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection