@extends('layouts.theme')

@section('content')

<div class="bg-background-light h-[calc(100vh-71.75px)] dark:bg-background-dark text-slate-900 flex flex-col relative mt-[71.75px] overflow-hidden">
    <div class="flex-1 bg-slate-50/50 p-4 sm:p-6 pb-4 sm:pb-6 flex flex-col h-full z-0">
        <div class="max-w-[1800px] w-full mx-auto flex flex-col h-full flex-1">
            
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-3 shadow-sm animate-pulse-short shrink-0">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
            @endif

            {{-- Grid เลย์เอาต์หลัก --}}
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 flex-1 overflow-hidden">
                
                {{-- ================= ซ้าย: แก้ไขข้อมูล & แผนที่ ================= --}}
                <div class="xl:col-span-8 flex flex-col h-full">
                    <form action="{{ route('area.update_manage', $area->id) }}" method="POST" id="manageAreaForm" class="flex flex-col gap-4 h-full">
                        @csrf
                        
                        {{-- การ์ดข้อมูลพื้นที่ --}}
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 relative overflow-hidden shrink-0">
                            <div class="absolute top-0 left-0 bottom-0 w-1 bg-primary"></div>
                            <div class="flex flex-wrap gap-4 items-end">
                                <div class="flex-1 min-w-[200px]">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">ชื่อพื้นที่ <span class="text-red-500">*</span></label>
                                    <input type="text" name="name_area" value="{{ $area->name_area }}" required class="w-full rounded-lg border-slate-200 bg-slate-50 p-2.5 text-sm font-bold focus:ring-primary focus:border-primary">
                                </div>
                                <div class="w-48">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">สถานะ <span class="text-red-500">*</span></label>
                                    <select name="status" required class="w-full rounded-lg border-slate-200 bg-slate-50 p-2.5 text-sm font-bold focus:ring-primary focus:border-primary">
                                        <option value="active" {{ $area->status == 'active' ? 'selected' : '' }}>🟢 เปิดใช้งาน (Active)</option>
                                        <option value="inactive" {{ $area->status == 'inactive' ? 'selected' : '' }}>🔴 ปิดใช้งาน (Inactive)</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="button" onclick="validateAndSubmit()" class="h-[42px] px-6 bg-primary hover:bg-blue-600 text-white font-bold text-sm rounded-lg shadow-md shadow-blue-500/25 flex items-center gap-2 transition-transform transform hover:-translate-y-0.5">
                                        <span class="material-symbols-outlined text-[18px]">save</span>
                                        บันทึกการแก้ไข
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- การ์ดข้อมูล Auto Assign --}}
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 shrink-0">
                            <div class="flex items-center justify-between mb-4">
                                <label class="text-sm font-bold text-slate-700">การรับเคสอัตโนมัติ (Auto Assign)</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="auto_assign" id="auto_assign" value="Yes" class="sr-only peer" onchange="toggleAutoAssignFields()" {{ $area->auto_assign == 'Yes' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>

                            <div id="auto_assign_container" class="{{ $area->auto_assign == 'Yes' ? '' : 'hidden' }} space-y-4 border-t border-slate-100 pt-4">
                                {{-- วันทำการ --}}
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">วันทำการ</label>
                                    <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
                                        @php
                                            $selectedDays = json_decode($area->day_command) ?? [];
                                        @endphp
                                        @foreach(['จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส', 'อา'] as $day)
                                            <label class="flex items-center justify-center p-2 border border-slate-200 rounded-lg text-xs cursor-pointer hover:bg-slate-100 has-[:checked]:bg-primary has-[:checked]:text-white has-[:checked]:border-primary transition-all">
                                                <input type="checkbox" name="day_command[]" value="{{ $day }}" class="hidden" {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                                                {{ $day }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- เวลาเปิด/ปิด --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1.5">เวลาเริ่ม</label>
                                        <select name="time_start_command" id="time_start_command" onchange="handleTimeChange()" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 text-sm focus:ring-primary focus:border-primary">
                                            <option value="">-- เลือกเวลา --</option>
                                            @for($h = 0; $h < 24; $h++)
                                                @php 
                                                    $t1 = sprintf("%02d:00", $h); 
                                                    $t2 = sprintf("%02d:30", $h);
                                                    // ตัดวินาทีออกจากค่าใน DB มาเทียบ (08:00:00 -> 08:00)
                                                    $currentTime = substr($area->time_start_command, 0, 5);
                                                @endphp
                                                <option value="{{ $t1 }}" {{ $currentTime == $t1 ? 'selected' : '' }}>{{ $t1 }}</option>
                                                <option value="{{ $t2 }}" {{ $currentTime == $t2 ? 'selected' : '' }}>{{ $t2 }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1.5">เวลาสิ้นสุด</label>
                                        <select name="time_end_command" id="time_end_command" class="w-full rounded-lg border-slate-200 bg-slate-50 p-2 text-sm focus:ring-primary focus:border-primary">
                                            <option value="">-- เลือกเวลา --</option>
                                            @for($h = 0; $h < 24; $h++)
                                                @php 
                                                    $t1 = sprintf("%02d:00", $h); 
                                                    $t2 = sprintf("%02d:30", $h);
                                                    $currentTimeEnd = substr($area->time_end_command, 0, 5);
                                                @endphp
                                                <option value="{{ $t1 }}" {{ $currentTimeEnd == $t1 ? 'selected' : '' }}>{{ $t1 }}</option>
                                                <option value="{{ $t2 }}" {{ $currentTimeEnd == $t2 ? 'selected' : '' }}>{{ $t2 }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                {{-- กลุ่มไลน์ --}}
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1.5">กลุ่มไลน์แจ้งเตือน</label>
                                    <div class="flex gap-2">
                                        <select name="groupID" id="groupID" class="flex-1 rounded-lg border-slate-200 bg-slate-50 p-2 text-sm focus:ring-primary focus:border-primary">
                                            <option value="">-- เลือกกลุ่มไลน์ --</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" {{ $area->groupID == $group->id ? 'selected' : '' }}>{{ $group->groupName }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="refreshGroupLine()" class="p-2 border border-slate-200 rounded-lg hover:bg-slate-50">
                                            <span class="material-symbols-outlined text-slate-500 text-[20px]" id="refresh-icon">refresh</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- การ์ดแผนที่ --}}
                        <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
                            <input type="hidden" name="polygon" id="polygon_data" value="{{ $area->polygon }}" required>
                            {{-- แผนที่ --}}
                            <div id="manage-map" class="absolute inset-0 w-full h-full"></div>
                        </div>
                    </form>
                </div>

                {{-- ================= ขวา: QR Code & รายชื่อเจ้าหน้าที่ ================= --}}
                <div class="xl:col-span-4 flex flex-col gap-4 h-full">
                    
                    {{-- QR Code --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center shrink-0">
                        <h3 class="text-sm font-bold text-slate-800 mb-3">QR-Code ลงทะเบียน</h3>
                        
                        {{-- สร้าง div ครอบ QR Code ไว้สำหรับทำรูปภาพ --}}
                        <div id="qr-container" class="bg-white p-4 inline-block mb-3" style="background-color: #ffffff;">
                            
                            {!! QrCode::size(150)->margin(0)->generate($registerUrl) !!}
                            
                            {{-- ซ่อนชื่อพื้นที่ไว้ในรูปตอนกดโหลด จะได้รู้ว่า QR ของที่ไหน --}}
                            <div class="text-sm font-bold text-black mt-3 text-center hidden print-only">{{ $area->name_area }}</div>
                        </div>
                        
                        <button onclick="downloadQR()" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-lg transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                            ดาวน์โหลดรูปภาพ
                        </button>
                    </div>

                    {{-- การ์ดรายชื่อเจ้าหน้าที่ --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col overflow-hidden flex-1">
                        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between shrink-0">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <span class="material-symbols-outlined text-slate-400">group</span>
                                เจ้าหน้าที่ในพื้นที่นี้
                            </h3>
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $officers->count() }} คน</span>
                        </div>
                        
                        {{-- สกอร์บาร์ด้านในสำหรับรายชื่อ --}}
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-3 space-y-2">
                            @forelse($officers as $officer)
                                <div class="p-3 bg-white border border-slate-200 rounded-lg hover:border-blue-300 transition-colors flex items-center gap-3">
                                    <div class="size-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">directions_car</span>
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <h4 class="font-bold text-slate-800 text-sm truncate">{{ $officer->name_officer }}</h4>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $officer->vehicle_type }} • {{ $officer->level }}</p>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        @if($officer->status_register == 'Pending')
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-1 rounded">
                                                รออนุมัติ
                                            </span>
                                        @else
                                            @if($officer->status == 'Active' || $officer->status == 'Standby')
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded">
                                                    พร้อมทำงาน
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 border border-slate-200 px-2 py-1 rounded">
                                                    ออฟไลน์
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 text-slate-400 h-full flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50">person_off</span>
                                    <p class="text-xs">ยังไม่มีเจ้าหน้าที่ลงทะเบียนในพื้นที่นี้</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Google Maps --}}
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initManageMap" async defer></script>
{{-- html2canvas สำหรับแปลง HTML เป็นรูปภาพ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    let map;
    let editablePolygon;

    // ==========================================
    // แผนที่และการแก้ไข Polygon
    // ==========================================
    function initManageMap() {
        const rawPolygonData = `{!! addslashes($area->polygon) !!}`;
        let polygonCoords = [];
        
        try {
            polygonCoords = JSON.parse(rawPolygonData);
        } catch (e) {
            console.error("Invalid Polygon Data");
            return;
        }

        map = new google.maps.Map(document.getElementById("manage-map"), {
            mapTypeId: 'roadmap',
            disableDefaultUI: true,
            zoomControl: true,
            mapTypeControl: true,
        });

        editablePolygon = new google.maps.Polygon({
            paths: polygonCoords,
            fillColor: '#3b82f6',
            fillOpacity: 0.3,
            strokeWeight: 2,
            strokeColor: '#2563eb',
            editable: true, 
            draggable: false, 
            map: map
        });

        const bounds = new google.maps.LatLngBounds();
        polygonCoords.forEach(coord => bounds.extend(coord));
        map.fitBounds(bounds);

        const path = editablePolygon.getPath();
        google.maps.event.addListener(path, 'insert_at', updateHiddenInput);
        google.maps.event.addListener(path, 'set_at', updateHiddenInput);
        google.maps.event.addListener(path, 'remove_at', updateHiddenInput);
    }

    function updateHiddenInput() {
        const path = editablePolygon.getPath();
        const coords = [];
        for (let i = 0; i < path.getLength(); i++) {
            const xy = path.getAt(i);
            coords.push({ lat: xy.lat(), lng: xy.lng() });
        }
        document.getElementById('polygon_data').value = JSON.stringify(coords);
    }

    // ==========================================
    // ดาวน์โหลด QR Code เป็นรูปภาพ
    // ==========================================
    function downloadQR() {
        const qrContainer = document.getElementById('qr-container');
        const areaName = "{{ $area->name_area }}";
        
        // แสดงชื่อใต้ QR ชั่วคราวเพื่อให้ติดไปในรูป
        const textLabel = qrContainer.querySelector('.print-only');
        textLabel.classList.remove('hidden');

        // แปลง HTML เป็น Canvas แล้วโหลดลงเครื่อง
        html2canvas(qrContainer, {
            scale: 3,
            backgroundColor: "#ffffff"
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = `ลงทะเบียนพื้นที่(${areaName}).png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
            
            // ซ่อนชื่อกลับไปเหมือนเดิม
            textLabel.classList.add('hidden');
        });
    }

    function toggleAutoAssignFields() {
        const isChecked = document.getElementById('auto_assign').checked;
        const container = document.getElementById('auto_assign_container');
        if (isChecked) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function handleTimeChange() {
        const startSelect = document.getElementById('time_start_command');
        const endSelect = document.getElementById('time_end_command');
        const startValue = startSelect.value;

        Array.from(endSelect.options).forEach(option => {
            if (option.value === "") return;
            if (startValue && option.value <= startValue) {
                option.disabled = true;
                option.classList.add('text-slate-300');
            } else {
                option.disabled = false;
                option.classList.remove('text-slate-300');
            }
        });
    }

    // เรียกใช้ handleTimeChange ทันทีเมื่อโหลดหน้าเพื่อให้เงื่อนไขเวลาสิ้นสุดถูกต้องตามค่าเดิมใน DB
    document.addEventListener('DOMContentLoaded', function() {
        handleTimeChange();
    });

    async function refreshGroupLine() {
        const icon = document.getElementById('refresh-icon');
        const select = document.getElementById('groupID');
        const currentAreaId = "{{ $area->id }}";
        
        icon.classList.add('animate-spin');
        try {
            const response = await fetch("{{ route('groups.ajax') }}?area_id=" + currentAreaId);
            const groups = await response.json();

            const currentVal = select.value;
            select.innerHTML = '<option value="">-- เลือกกลุ่มไลน์ --</option>';

            groups.forEach(group => {
                const option = document.createElement('option');
                option.value = group.id;
                option.textContent = group.groupName;
                if(group.id == currentVal) option.selected = true;
                select.appendChild(option);
            });
        } catch (error) {
            console.error(error);
        } finally {
            setTimeout(() => icon.classList.remove('animate-spin'), 500);
        }
    }

    function validateAndSubmit() {
        const form = document.getElementById('manageAreaForm');
        const autoAssign = document.getElementById('auto_assign').checked;
        const polygonData = document.getElementById('polygon_data').value;

        if(!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if(polygonData === "" || polygonData === "[]") {
            alert("กรุณาวาดขอบเขตพื้นที่บนแผนที่");
            return;
        }

        if(autoAssign) {
            const dayCommands = document.querySelectorAll('input[name="day_command[]"]:checked');
            const startTime = document.getElementById('time_start_command').value;
            const endTime = document.getElementById('time_end_command').value;
            const groupID = document.getElementById('groupID').value;

            if(dayCommands.length === 0) { alert("กรุณาเลือกวันทำการ"); return; }
            if(!startTime) { alert("กรุณาเลือกเวลาเริ่ม"); return; }
            if(!endTime) { alert("กรุณาเลือกเวลาสิ้นสุด"); return; }
            if(!groupID) { alert("กรุณาเลือกกลุ่มไลน์"); return; }
        }

        form.submit();
    }
</script>

@endsection