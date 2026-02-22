@extends('layouts.theme')

@section('content')
<body class="bg-slate-50 dark:bg-[#101922] font-display text-slate-900 dark:text-white">

    <nav class="fixed top-0 left-0 right-0 z-30 bg-white/90 dark:bg-[#1a2632]/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-700 px-4 py-3 shadow-sm">
        <div class="max-w-lg mx-auto flex items-center justify-between">
            <h1 class="font-bold text-lg flex items-center gap-2 text-red-600 dark:text-red-400">
                <span class="material-symbols-outlined animate-pulse">emergency_share</span>
                กำลังขอความช่วยเหลือ
            </h1>
            <span class="text-xs font-mono bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded text-slate-500">
                ID: {{ $emergency->ID }}
            </span>
        </div>
    </nav>

    <div class="fixed inset-0 z-0 bg-slate-200">
        {{-- ใส่ Google Map ของจริงตรงนี้ --}}
        <div class="w-full h-full bg-cover bg-center relative" 
             style="background-image: url('https://maps.googleapis.com/maps/api/staticmap?center={{ $emergency->emergency_lat }},{{ $emergency->emergency_lng }}&zoom=15&size=800x800&maptype=roadmap&markers=color:red%7C{{ $emergency->emergency_lat }},{{ $emergency->emergency_lng }}&key=YOUR_API_KEY');">
            
            <div class="absolute inset-0 bg-gradient-to-b from-white/80 via-transparent to-white/90 dark:from-[#101922]/80 dark:to-[#101922]/90 pointer-events-none md:hidden"></div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 z-20 p-4 md:p-8">
        <div class="max-w-[1200px] mx-auto">
            <div class="bg-white dark:bg-[#1a2632] rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
                
                <div class="p-6 text-center border-b border-slate-100 dark:border-slate-700/50">
                    <div class="inline-flex items-center justify-center p-3 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 mb-4 animate-bounce">
                        <span class="material-symbols-outlined text-3xl">search</span>
                    </div>
                    <h2 class="text-xl font-bold mb-1">กำลังค้นหาเจ้าหน้าที่...</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">ระบบได้ส่งข้อมูลของคุณไปยังหน่วยงานที่ใกล้ที่สุดแล้ว</p>
                </div>

                <div class="bg-slate-50 dark:bg-[#131d27] p-4 md:p-6">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">my_location</span>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase text-slate-400 tracking-wider">ตำแหน่งของคุณ</p>
                                <p class="text-sm font-medium mt-0.5 line-clamp-1">{{ $emergency->emergency_location }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Lat: {{ number_format($emergency->emergency_lat, 5) }}, Lng: {{ number_format($emergency->emergency_lng, 5) }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">info</span>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase text-slate-400 tracking-wider">รายละเอียด</p>
                                <p class="text-sm font-medium mt-0.5 text-red-500">{{ ucfirst($emergency->emergency_type) }}</p>
                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $emergency->emergency_detail }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button onclick="window.location.reload()" class="w-full py-3 rounded-xl bg-white border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">refresh</span>
                            อัปเดตสถานะ
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
@endsection