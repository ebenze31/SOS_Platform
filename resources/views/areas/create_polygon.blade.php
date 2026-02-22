@extends('layouts.theme')

@section('content')

<div class="bg-background-light h-[calc(100vh-71.75px)] dark:bg-background-dark text-slate-900 flex flex-col relative mt-[71.75px] overflow-hidden">
    <div class="flex-1 bg-slate-50/50 p-4 sm:p-6 pb-4 sm:pb-6 z-0 flex flex-col">
        <div class="max-w-[1600px] w-full mx-auto flex flex-col h-full flex-1">
            
            <div class="flex items-center justify-between mb-4 shrink-0">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-3xl">add_location_alt</span>
                        สร้างพื้นที่
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">กำหนดข้อมูลและวาดขอบเขตพื้นที่บนแผนที่</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 flex-1 overflow-hidden">
                
                {{-- ฝั่งซ้าย: แบบฟอร์ม --}}
                <div class="lg:col-span-4 flex flex-col h-full">
                    <form action="{{ route('area.store_polygon') }}" method="POST" id="areaForm" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col h-full relative overflow-hidden">
                        @csrf
                        <div class="absolute top-0 left-0 right-0 h-1 bg-primary"></div>

                        <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3 shrink-0">ข้อมูลพื้นที่</h3>

                        <div class="space-y-5 flex-1 overflow-y-auto custom-scrollbar pr-2">
                            <div>
                                <label for="name_area" class="block text-sm font-bold text-slate-700 mb-1.5">ชื่อพื้นที่ <span class="text-red-500">*</span></label>
                                <input type="text" name="name_area" id="name_area" required class="w-full rounded-lg border-slate-200 bg-slate-50 p-3 text-sm focus:ring-primary focus:border-primary placeholder:text-slate-400" placeholder="เช่น เขตเทศบาลเมือง, โซนนิคมอุตสาหกรรม...">
                            </div>

                            <input type="hidden" name="type" value="ทั่วไป">

                            <div>
                                <label for="status" class="block text-sm font-bold text-slate-700 mb-1.5">สถานะ <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required class="w-full rounded-lg border-slate-200 bg-slate-50 p-3 text-sm focus:ring-primary focus:border-primary">
                                    <option value="active" selected>เปิดใช้งาน (Active)</option>
                                    <option value="inactive">ปิดใช้งานชั่วคราว (Inactive)</option>
                                </select>
                            </div>

                            <input type="hidden" name="polygon" id="polygon_data" required>

                            <div id="polygon-status" class="hidden mt-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-3">
                                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                <div class="text-xs font-bold">วาดขอบเขตพื้นที่เรียบร้อยแล้ว</div>
                            </div>
                        </div>

                        <div class="pt-6 mt-4 border-t border-slate-100 shrink-0">
                            <button type="button" onclick="validateAndSubmit()" class="w-full py-3.5 bg-primary hover:bg-blue-600 text-white font-bold text-sm uppercase tracking-wide rounded-xl shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2 transition-transform transform hover:-translate-y-0.5">
                                <span class="material-symbols-outlined text-[20px]">save</span>
                                ยืนยันการสร้างพื้นที่
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ฝั่งขวา: แผนที่ --}}
                <div class="lg:col-span-8 flex flex-col h-full gap-4">
                    
                    {{-- แถบเครื่องมือ --}}
                    <div class="flex flex-wrap items-center justify-between bg-white p-3 rounded-xl shadow-sm border border-slate-200 gap-4 shrink-0">
                        {{-- ช่องค้นหา & ปุ่มตำแหน่งปัจจุบัน --}}
                        <div class="flex items-center gap-2 flex-1 min-w-[250px]">
                            <div class="relative flex-1">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400">search</span>
                                <input type="text" id="pac-input" class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 p-2.5 text-sm focus:ring-primary focus:border-primary placeholder:text-slate-400" placeholder="ค้นหาสถานที่...">
                            </div>
                            <button type="button" onclick="goToCurrentLocation()" class="bg-blue-50 hover:bg-blue-100 text-blue-600 p-2.5 rounded-lg border border-blue-200 transition-colors flex items-center justify-center shrink-0" title="ไปยังตำแหน่งปัจจุบัน">
                                <span class="material-symbols-outlined text-[20px]">my_location</span>
                            </button>
                        </div>
                        
                        {{-- คำแนะนำการวาด --}}
                        <div class="flex items-center gap-2 text-amber-700 bg-amber-50 px-4 py-2 rounded-lg border border-amber-200 shrink-0">
                            <span class="material-symbols-outlined animate-bounce text-[20px]">pan_tool_alt</span>
                            <span class="text-sm font-bold tracking-wide">คลิกบนแผนที่เพื่อวาดขอบเขต</span>
                        </div>
                    </div>

                    {{-- คอนเทนเนอร์แผนที่ --}}
                    <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative group">
                        {{-- ปุ่มล้างการวาด --}}
                        <button type="button" onclick="clearPolygon()" class="absolute top-4 right-4 z-10 bg-white hover:bg-red-50 text-slate-700 hover:text-red-600 px-4 py-2 rounded-lg shadow-md border border-slate-200 text-xs font-bold flex items-center gap-1.5 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">delete</span>
                            ล้างการวาดใหม่
                        </button>

                        {{-- แผนที่ --}}
                        <div id="drawing-map" class="w-full h-full"></div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- library places สำหรับ SearchBox --}}
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&libraries=drawing,places&callback=initDrawingMap" async defer></script>

<script>
    let map;
    let drawingManager;
    let currentPolygon = null;
    let searchMarkers = [];

    function initDrawingMap() {
        // ตำแหน่งเริ่มต้นประเทศไทย
        const startLocation = { lat: 13.19928, lng: 102.128662 };

        map = new google.maps.Map(document.getElementById("drawing-map"), {
            zoom: 6,
            center: startLocation,
            mapTypeId: 'roadmap',
            disableDefaultUI: true,
            zoomControl: true,
            mapTypeControl: true,
        });

        // เปิดใช้งาน Drawing Manager ของ Google Maps
        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_LEFT,
                drawingModes: [google.maps.drawing.OverlayType.POLYGON],
            },
            polygonOptions: {
                fillColor: '#3b82f6',
                fillOpacity: 0.3,
                strokeWeight: 2,
                strokeColor: '#2563eb',
                clickable: true,
                editable: true,
                zIndex: 1,
            },
        });
        drawingManager.setMap(map);

        // Event: เมื่อวาดเสร็จสิ้น
        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
            if (currentPolygon) {
                currentPolygon.setMap(null);
            }
            
            currentPolygon = polygon;
            drawingManager.setDrawingMode(null);
            updatePolygonInput();

            // Event: อัปเดตพิกัดเมื่อมีการดึง/แก้ไขจุด
            const path = polygon.getPath();
            google.maps.event.addListener(path, 'insert_at', updatePolygonInput);
            google.maps.event.addListener(path, 'set_at', updatePolygonInput);
            google.maps.event.addListener(path, 'remove_at', updatePolygonInput);
        });

        // ระบบค้นหาสถานที่ (Search Box)
        const input = document.getElementById("pac-input");
        const searchBox = new google.maps.places.SearchBox(input);

        // ให้ Search Box ค้นหาอิงจากพิกัดหน้าจอแผนที่ปัจจุบัน
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();
            if (places.length == 0) return;

            // ล้างหมุดค้นหาเก่า
            searchMarkers.forEach((marker) => marker.setMap(null));
            searchMarkers = [];

            const bounds = new google.maps.LatLngBounds();
            places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) return;

                // สร้างหมุดชี้เป้าสถานที่ที่ค้นหา
                searchMarkers.push(
                    new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    })
                );

                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    }

    // ฟังก์ชันไปตำแหน่งปัจจุบัน
    function goToCurrentLocation() {
        if (navigator.geolocation) {
            // โชว์ Loading หรือหมุนๆ ตรงปุ่มได้ถ้าต้องการ
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    map.setCenter(pos);
                    map.setZoom(15);
                },
                () => {
                    alert("ไม่สามารถเข้าถึงตำแหน่งปัจจุบันของคุณได้ กรุณาเปิดสิทธิ์ Location ในเบราว์เซอร์");
                }
            );
        } else {
            alert("เบราว์เซอร์ของคุณไม่รองรับการระบุตำแหน่ง (Geolocation)");
        }
    }

    // ฟังก์ชันอัปเดตค่าพิกัดลงใน Hidden Input แบบ JSON
    function updatePolygonInput() {
        if (!currentPolygon) return;

        const path = currentPolygon.getPath();
        const coords = [];
        
        for (let i = 0; i < path.getLength(); i++) {
            const xy = path.getAt(i);
            coords.push({ lat: xy.lat(), lng: xy.lng() });
        }

        document.getElementById('polygon_data').value = JSON.stringify(coords);
        document.getElementById('polygon-status').classList.remove('hidden');
    }

    // ฟังก์ชันล้างพื้นที่ที่วาด
    function clearPolygon() {
        if (currentPolygon) {
            currentPolygon.setMap(null);
            currentPolygon = null;
            document.getElementById('polygon_data').value = "";
            document.getElementById('polygon-status').classList.add('hidden');
            drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
        }
    }

    // ฟังก์ชันเช็คก่อน Submit
    function validateAndSubmit() {
        const form = document.getElementById('areaForm');
        const polygonData = document.getElementById('polygon_data').value;

        if(!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if(polygonData === "" || polygonData === "[]") {
            alert("กรุณาวาดขอบเขตพื้นที่บนแผนที่ก่อนบันทึก");
            return;
        }

        form.submit();
    }
</script>

@endsection