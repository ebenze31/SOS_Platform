@extends('layouts.theme')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="bg-background-light text-text-main font-display antialiased min-h-screen flex flex-col overflow-x-hidden mt-[61px]">
    
    <div class="flex-grow flex flex-col max-w-[1440px] mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 gap-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-text-main mb-2">พื้นที่ทั้งหมด</h2>
            </div>
            <div class="flex gap-3">
                @if($isSupervisor)
                <a href="{{ route('area.create_polygon') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-sm transition-all">
                    <span class="material-symbols-outlined mr-2 text-lg">add_location_alt</span>
                    เพิ่มพื้นที่ใหม่
                </a>
                @endif
            </div>
        </div>

        <div class="bg-surface-light rounded-xl border border-border-color shadow-sm flex flex-col">
            <div class="flex-wrap px-6 py-5 border-b border-border-color flex justify-between items-center bg-gray-50/30 gap-4">
                <div class="flex items-center gap-3">
                    <h3 class="font-bold text-lg text-text-main">ทั้งหมด {{ $areas->total() ?? 0 }}</h3>
                </div>
                
                <div class="flex items-center gap-3">
                    <form id="searchForm" action="{{ url()->current() }}" method="GET" class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                        <input id="searchInput" name="search" value="{{ request('search') }}" class="pl-9 pr-4 py-2 text-sm border-border-color rounded-lg focus:ring-primary focus:border-primary w-full sm:w-64 bg-white" placeholder="ค้นหาพื้นที่..." type="text" autocomplete="off" />
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-border-color text-xs uppercase tracking-wider text-text-sub font-semibold">
                            <th class="px-6 py-4 w-[25%] min-w-[200px]">ชื่อพื้นที่รับผิดชอบ</th>
                            <th class="px-6 py-4 w-[25%] min-w-[200px]">รูปแบบการรับเคส</th>
                            <th class="px-6 py-4 w-[15%] min-w-[150px]">เจ้าหน้าที่</th>
                            <th class="px-6 py-4 w-[15%] min-w-[150px]">สถิติรับแจ้งเหตุ</th>
                            <th class="px-6 py-4 w-[10%] min-w-[120px]">สถานะ</th>
                            <th class="px-6 py-4 w-[10%] min-w-[130px]">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-color bg-white">
                        
                        @forelse($areas as $area)
                        <tr class="group hover:bg-primary-light/30 transition-colors">
                            {{-- 1. ชื่อพื้นที่ --}}
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                        <span class="material-symbols-outlined">share_location</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-text-main text-md">{{ $area->name_area }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. รูปแบบการรับเคส (เวลา/กลุ่มไลน์) --}}
                            <td class="px-6 py-4 align-top">
                                @if($area->auto_assign === 'Yes')
                                    @php
                                        // แกะ JSON วันที่ออกมา เช่น ["จ", "อ"]
                                        $days = json_decode($area->day_command, true) ?? [];
                                        $timeStart = substr($area->time_start_command, 0, 5); // ตัดวิออก 08:00
                                        $timeEnd = substr($area->time_end_command, 0, 5);
                                    @endphp
                                    <div class="text-[11px] font-bold text-primary mb-1">
                                        <span class="material-symbols-outlined text-[14px] align-middle mr-0.5">schedule</span>
                                        เปิดตามเวลาทำการ
                                    </div>
                                    <div class="text-xs text-slate-600 mb-1.5 leading-relaxed">
                                        <span class="font-bold">วัน:</span> {{ implode(', ', $days) }}<br>
                                        <span class="font-bold">เวลา:</span> {{ $timeStart }} - {{ $timeEnd }} น.
                                    </div>
                                    <div class="text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded truncate max-w-[180px] inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[12px]">forum</span>
                                        {{ $area->group_name ?? 'ไม่พบข้อมูลกลุ่มไลน์' }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        ตลอด 24 ชม.
                                    </span>
                                @endif
                            </td>

                            {{-- 3. จำนวนเจ้าหน้าที่ (ทั้งหมด / ออโต้) --}}
                            <td class="px-6 py-4 align-top">
                                @php
                                    // แกะ JSON officer_priority และนับเฉพาะคนที่ auto_assign == 'Yes'
                                    $officers = json_decode($area->officer_priority, true) ?? [];
                                    $autoCount = collect($officers)->where('auto_assign', 'Yes')->count();
                                    $totalCount = $area->total_officers_count ?? 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div>
                                        {{-- แสดงผลแบบ ทั้งหมด / ออโต้ --}}
                                        <div class="text-sm font-bold text-text-main">
                                            {{ $totalCount }} <span class="text-slate-400 font-normal">/</span> <span class="text-indigo-600">{{ $autoCount }}</span>
                                        </div>
                                        <div class="text-[10px] text-text-sub">ทั้งหมด / เปิดออโต้</div>
                                    </div>
                                </div>
                            </td>

                            {{-- 4. สถิติ --}}
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-2">
                                    <div>
                                        <div class="text-sm font-bold text-text-main">
                                            {{ number_format($area->operations_count ?? 0) }} ครั้ง
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 5. สถานะ Toggle --}}
                            <td class="px-6 py-4 align-top">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           class="sr-only peer status-toggle" 
                                           data-id="{{ $area->id }}" 
                                           {{ strtolower($area->status) === 'active' ? 'checked' : '' }}>
                                           
                                    <div class="relative shrink-0 w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-primary/50 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                    
                                    <span class="ml-3 text-xs font-bold text-gray-600 status-label whitespace-nowrap" id="status-label-{{ $area->id }}">
                                        @if(strtolower($area->status) === 'active')
                                            <span class="text-green-600">เปิดใช้งาน</span>
                                        @else
                                            <span class="text-red-500">ปิดใช้งาน</span>
                                        @endif
                                    </span>
                                </label>
                            </td>

                            {{-- 6. จัดการ --}}
                            <td class="px-6 py-4 align-top text-right">
                                <a href="{{ route('area.manage_area', ['id' => $area->id]) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-primary text-primary hover:bg-primary hover:text-white rounded-md text-xs font-medium transition-colors gap-1 whitespace-nowrap">
                                    <span class="material-symbols-outlined text-sm">edit_location_alt</span>
                                    จัดการพื้นที่
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <span class="material-symbols-outlined text-4xl mb-2 text-gray-300">location_off</span>
                                <p>ยังไม่มีข้อมูลพื้นที่ในระบบ หรือคุณยังไม่ได้รับสิทธิ์ให้ดูแลพื้นที่ใดๆ</p>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            @if(isset($areas) && $areas->hasPages())
            <div class="px-6 py-4 border-t border-border-color bg-gray-50/30">
                {{ $areas->appends(request()->query())->links() }}
            </div>
            @else
            <div class="px-6 py-4 border-t border-border-color flex flex-wrap gap-4 items-center justify-between bg-gray-50/30">
                <div class="text-sm text-text-sub">
                    แสดง <span class="font-medium text-text-main">{{ count($areas ?? []) > 0 ? 1 : 0 }}</span> ถึง <span class="font-medium text-text-main">{{ count($areas ?? []) }}</span> รายการ
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- ระบบค้นหาอัตโนมัติ ---
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        let typingTimer;
        const doneTypingInterval = 500; 

        if (searchInput && searchForm) {
            searchInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    searchForm.submit();
                }, doneTypingInterval);
            });

            if (searchInput.value.length > 0) {
                searchInput.focus();
                let val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }
        }

        // --- ระบบ Toggle อัปเดตสถานะ ---
        const toggles = document.querySelectorAll('.status-toggle');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        toggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const areaId = this.dataset.id;
                const isChecked = this.checked;
                const newStatus = isChecked ? 'active' : 'inactive';
                const labelElement = document.getElementById(`status-label-${areaId}`);

                labelElement.innerHTML = isChecked 
                    ? '<span class="text-green-600">เปิดใช้งาน</span>' 
                    : '<span class="text-red-500">ปิดใช้งาน</span>';

                fetch("{{ url('/area') }}"+"/"+areaId+"/toggle-status", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if(!data.success) {
                        alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถอัปเดตสถานะได้'));
                        this.checked = !isChecked;
                        labelElement.innerHTML = !isChecked 
                            ? '<span class="text-green-600">เปิดใช้งาน</span>' 
                            : '<span class="text-red-500">ปิดใช้งาน</span>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                    this.checked = !isChecked;
                    labelElement.innerHTML = !isChecked 
                        ? '<span class="text-green-600">เปิดใช้งาน</span>' 
                        : '<span class="text-red-500">ปิดใช้งาน</span>';
                });
            });
        });
    });
</script>
@endsection