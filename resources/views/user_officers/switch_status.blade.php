@extends('layouts.theme_user')

@section('content')
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#137fec",
                    "background-light": "#F7F8F9",
                    "background-dark": "#13191f",
                    "safety-green": "#2EB854",
                    "warning-orange": "#F59E0B",
                },
                fontFamily: {
                    "display": ["Public Sans", "sans-serif"]
                },
                borderRadius: {
                    "DEFAULT": "4px",
                    "sm": "4px",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
                },
            },
        },
    }
</script>

<div class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display antialiased mt-[61px]">
    <div class="relative flex h-[calc(100vh-61px)] w-full flex-col overflow-hidden">
        
        <div id="map" class="absolute inset-0 z-0"></div>
                
        <div id="gps-loading" class="absolute inset-0 z-10 hidden flex-col items-center justify-center bg-slate-900/30 backdrop-blur-sm transition-all duration-300">
            <div class="h-14 w-14 animate-spin rounded-full border-4 border-white border-t-primary mb-4 shadow-lg"></div>
            <div class="bg-white px-6 py-3 rounded-full shadow-lg flex items-center gap-3 animate-pulse">
                <span class="material-symbols-outlined text-primary">my_location</span>
                <span class="text-slate-700 font-bold">กำลังค้นหาตำแหน่ง GPS...</span>
            </div>
        </div>
        
        <div class="absolute inset-0 z-10 pointer-events-none"></div>
        
        <main class="relative z-20 flex flex-1 flex-col items-center justify-end px-4 pb-12 pointer-events-none">
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl pointer-events-auto">
                
                <div class="mb-6 flex items-center gap-4 border-b border-slate-100 pb-4">
                    <div class="h-14 w-14 overflow-hidden rounded-full border-2 border-primary/20 bg-slate-100 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-3xl">account_circle</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 leading-tight">{{ $officer->name_officer }}</h3>
                        <p class="text-sm font-medium text-slate-500">
                            {{ $officer->type }} • พาหนะ: {{ $officer->vehicle_type ?? 'ไม่ระบุ' }}
                        </p>
                        <p class="text-xs text-primary mt-0.5">ช่วยเหลือแล้ว: {{ $officer->amount_help ?? 0 }} ครั้ง</p>
                    </div>
                </div>

                <div class="flex flex-col items-center gap-4">
                    <div class="flex w-full items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-5 py-4">
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-bold uppercase tracking-wider text-slate-500">สถานะปัจจุบัน</span>
                            <div class="flex items-center gap-2">
                                @php
                                    $dotClass = 'bg-red-500';
                                    $statusText = 'ไม่พร้อมปฏิบัติงาน';
                                    $isChecked = '';
                                    $isDisabled = '';
                                    $labelBg = 'bg-slate-400';

                                    if($officer->status == 'Standby') {
                                        $dotClass = 'bg-safety-green';
                                        $statusText = 'พร้อมปฏิบัติงาน';
                                        $isChecked = 'checked';
                                        $labelBg = 'bg-primary';
                                    } elseif($officer->status == 'Helping') {
                                        $dotClass = 'bg-warning-orange animate-pulse';
                                        $statusText = 'กำลังช่วยเหลือ';
                                        $isChecked = 'checked';
                                        $isDisabled = 'disabled';
                                        $labelBg = 'bg-warning-orange';
                                    }
                                @endphp

                                <div class="h-2.5 w-2.5 rounded-full {{ $dotClass }}" id="status-dot"></div>
                                <span class="text-base font-bold text-slate-900" id="status-text">{{ $statusText }}</span>
                            </div>
                        </div>

                        <label id="toggle-label" class="relative flex h-10 w-16 {{ $isDisabled ? 'cursor-not-allowed opacity-80' : 'cursor-pointer' }} items-center rounded-full border-none {{ $labelBg }} p-1 shadow-inner transition-colors">
                            <input {{ $isChecked }} {{ $isDisabled }} class="peer sr-only" type="checkbox" id="status-toggle" />
                            <div class="h-8 w-8 rounded-full bg-white shadow-sm transition-transform duration-200 ease-in-out peer-checked:translate-x-6"></div>
                        </label>
                    </div>
                    
                    @if($officer->status == 'Helping')
                    <p class="text-xs text-red-500 font-medium text-center">
                        * ไม่สามารถปิดสถานะได้ในขณะนี้
                    </p>
                    @endif

                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initMap&libraries=marker" async defer></script>
<script>
    const toggle = document.getElementById('status-toggle');
    const toggleLabel = document.getElementById('toggle-label');
    const statusDot = document.getElementById('status-dot');
    const statusText = document.getElementById('status-text');
    const gpsLoading = document.getElementById('gps-loading');

    const currentStatus = "{{ $officer->status }}";
    
    // ดึงพิกัดตั้งต้นจาก DB (ถ้ามี)
    let initialLat = parseFloat("{{ $officer->lat ?? 'null' }}");
    let initialLng = parseFloat("{{ $officer->lng ?? 'null' }}");
    
    let map, officerMarker, markerElement;
    let locationInterval = null;

    // --- ฟังก์ชันสร้างและวาดแผนที่ ---
    function renderMap(lat, lng) {
        const position = { lat: lat, lng: lng };
        
        // ถ้าแผนที่ยังไม่เคยสร้าง ให้สร้างใหม่
        if (!map) {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: position,
                disableDefaultUI: true,
                mapId: "DEMO_MAP_ID" 
            });

            const officerHtml = `
                <div id="officer-marker-{{ $officer->id }}" class="relative flex flex-col items-center cursor-pointer transition-transform hover:scale-110 z-40">
                    <div class="relative flex h-9 w-9">
                        <span class="officer-pulse hidden animate-[ping_1.5s_cubic-bezier(0,0,0.2,1)_infinite] absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-60"></span>
                        <span class="relative inline-flex rounded-full h-9 w-9 bg-blue-600 border-2 border-white shadow-lg items-center justify-center text-white">
                            <span class="material-symbols-outlined text-[18px]">directions_car</span>
                        </span>
                    </div>
                    <div class="mt-1 bg-white text-slate-900 text-[11px] font-bold px-2.5 py-1 rounded shadow-md border border-slate-200 whitespace-nowrap">
                        {{ $officer->name_officer }}
                    </div>
                </div>
            `;

            const div = document.createElement('div');
            div.innerHTML = officerHtml.trim();
            markerElement = div.firstChild;

            officerMarker = new google.maps.marker.AdvancedMarkerElement({
                map: map,
                position: position,
                content: markerElement,
            });

            if (currentStatus === 'Standby' || currentStatus === 'Helping') {
                markerElement.querySelector('.officer-pulse').classList.remove('hidden');
            }
        } else {
            // ถ้ามีแผนที่อยู่แล้ว ให้เลื่อนจุดศูนย์กลางและย้ายหมุดแทน
            map.panTo(position);
            officerMarker.position = position;
        }
    }

    // --- ฟังก์ชันหลักที่ทำงานตอนโหลดหน้าเว็บ ---
    function initMap() {
        // ถ้ายอมรับว่าให้ดึง GPS ได้ทันทีที่โหลดหน้าเว็บ (เฉพาะคนที่สถานะเปิดอยู่ หรือยังไม่มีพิกัดเลย)
        if (currentStatus === 'Standby' || currentStatus === 'Helping' || isNaN(initialLat)) {
            
            // แสดงหน้าจอโหลด GPS ก่อนเลย
            if(gpsLoading) {
                gpsLoading.classList.remove('hidden');
                gpsLoading.classList.add('flex');
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const currentLat = position.coords.latitude;
                        const currentLng = position.coords.longitude;
                        
                        // ได้พิกัดปัจจุบันแล้ว ปิดหน้าจอโหลด
                        if(gpsLoading) {
                            gpsLoading.classList.add('hidden');
                            gpsLoading.classList.remove('flex');
                        }

                        // วาดแผนที่ที่จุดปัจจุบัน
                        renderMap(currentLat, currentLng);
                        
                        // ถ้าเขากำลัง Standby/Helping อยู่ ให้ยิง API ไปเซฟพิกัดใหม่ลง DB ด้วยเลย (แก้ปัญหาเข้าแล้วออกไว)
                        if (currentStatus === 'Standby' || currentStatus === 'Helping') {
                            syncLocationToServer(currentLat, currentLng);
                            startLocationTracking(); // เริ่มลูป 1 นาที
                        }
                    },
                    function(error) {
                        // ถ้าหา GPS ไม่เจอ ให้ใช้พิกัดเดิมจาก DB (ถ้ามี) ในการวาดแผนที่แทนหน้าจอขาวๆ
                        if(gpsLoading) {
                            gpsLoading.classList.add('hidden');
                            gpsLoading.classList.remove('flex');
                        }
                        console.warn("ไม่สามารถดึงตำแหน่งตอนโหลดเว็บได้: ", error);
                        
                        // วาดแผนที่ที่พิกัดเดิม (ถ้าใน DB มีค่า)
                        if (!isNaN(initialLat) && !isNaN(initialLng)) {
                            renderMap(initialLat, initialLng);
                        } else {
                            // ถ้าใน DB ก็ไม่มีค่าด้วย ให้แสดงพิกัด Default (กรุงเทพ)
                            renderMap(13.7563, 100.5018);
                            alert('ไม่สามารถหาพิกัดได้ กรุณาเปิด GPS');
                        }
                        
                        if (currentStatus === 'Standby' || currentStatus === 'Helping') {
                            startLocationTracking(); // ยังคงพยายามลูปหา GPS ต่อไป
                        }
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                // เบราว์เซอร์ไม่รองรับ
                if(gpsLoading) {
                    gpsLoading.classList.add('hidden');
                    gpsLoading.classList.remove('flex');
                }
                if (!isNaN(initialLat)) {
                    renderMap(initialLat, initialLng);
                } else {
                    renderMap(13.7563, 100.5018);
                }
            }
        } else {
            // ถ้าสถานะเป็น ปิด (None) ก็ใช้พิกัดจาก DB วาดแผนที่ได้เลย ไม่ต้องบังคับหา GPS 
            if (!isNaN(initialLat) && !isNaN(initialLng)) {
                renderMap(initialLat, initialLng);
            } else {
                renderMap(13.7563, 100.5018);
            }
        }
    }

    // --- การทำงานของปุ่มเปิด/ปิด ---
    if(toggle && currentStatus !== 'Helping') {
        toggle.addEventListener('change', function () {
            
            const isReady = this.checked;
            toggle.disabled = true; 

            if (isReady) {
                if(gpsLoading) {
                    gpsLoading.classList.remove('hidden');
                    gpsLoading.classList.add('flex');
                }

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            
                            if(gpsLoading) {
                                gpsLoading.classList.add('hidden');
                                gpsLoading.classList.remove('flex');
                            }

                            const currentLat = position.coords.latitude;
                            const currentLng = position.coords.longitude;
                            
                            renderMap(currentLat, currentLng);
                            sendUpdateToServer(isReady, currentLat, currentLng);
                        },
                        function(error) {
                            if(gpsLoading) {
                                gpsLoading.classList.add('hidden');
                                gpsLoading.classList.remove('flex');
                            }

                            console.warn("ไม่สามารถดึงตำแหน่งได้: ", error);
                            alert('กรุณาอนุญาตให้เบราว์เซอร์เข้าถึงตำแหน่ง (Location) ของคุณก่อนเปิดสถานะ');
                            
                            toggle.checked = false; 
                            toggle.disabled = false;
                            updateUI(false);
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                } else {
                    if(gpsLoading) {
                        gpsLoading.classList.add('hidden');
                        gpsLoading.classList.remove('flex');
                    }

                    alert('อุปกรณ์ของคุณไม่รองรับการระบุตำแหน่ง GPS');
                    toggle.checked = false;
                    toggle.disabled = false;
                    updateUI(false);
                }
            } else {
                sendUpdateToServer(isReady, null, null);
            }
        });
    }

    // --- API ยิงเปิด/ปิดสถานะ ---
    function sendUpdateToServer(isReady, lat, lng) {
        const newStatus = isReady ? 'Standby' : 'None';
            
        fetch("{{ url('/officer/update-status') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                status: newStatus,
                lat: lat,
                lng: lng
            })
        })
        .then(response => response.json())
        .then(data => {
            toggle.disabled = false; 
            
            if(data.success) {
                updateUI(isReady);
            } else {
                alert('ผิดพลาด: ' + data.message);
                toggle.checked = !isReady;
                updateUI(!isReady);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('ขาดการเชื่อมต่อกับเซิร์ฟเวอร์');
            
            toggle.disabled = false;
            toggle.checked = !isReady;
            updateUI(!isReady);
        });
    }

    // --- API ยิงอัปเดตเฉพาะพิกัด (พื้นหลัง) ---
    function syncLocationToServer(lat, lng) {
        fetch("{{ url('/officer/update-location') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ lat: lat, lng: lng })
        }).catch(e => console.log("Background tracking sync failed"));
    }


    // ================= ระบบ Background Tracking =================
    function startLocationTracking() {
        if (locationInterval) clearInterval(locationInterval);

        locationInterval = setInterval(() => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const currentLat = position.coords.latitude;
                        const currentLng = position.coords.longitude;
                        
                        if (map && officerMarker) {
                            officerMarker.position = { lat: currentLat, lng: currentLng };
                        }
                        
                        syncLocationToServer(currentLat, currentLng);
                    },
                    function(error) {
                        console.warn("Background tracking error: ", error);
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            }
        }, 60000); 
    }

    function stopLocationTracking() {
        if (locationInterval) {
            clearInterval(locationInterval);
            locationInterval = null;
        }
    }
    // =========================================================

    // --- อัปเดตหน้าตาปุ่ม ---
    function updateUI(isReady) {
        if (isReady) {
            toggleLabel.classList.remove('bg-slate-400');
            toggleLabel.classList.add('bg-primary');
            statusDot.classList.remove('bg-red-500');
            statusDot.classList.add('bg-safety-green');
            statusText.textContent = 'พร้อมปฏิบัติงาน';
            if(markerElement) markerElement.querySelector('.officer-pulse').classList.remove('hidden');
            
            startLocationTracking();
        } else {
            toggleLabel.classList.remove('bg-primary');
            toggleLabel.classList.add('bg-slate-400');
            statusDot.classList.remove('bg-safety-green');
            statusDot.classList.add('bg-red-500');
            statusText.textContent = 'ไม่พร้อมปฏิบัติงาน';
            if(markerElement) markerElement.querySelector('.officer-pulse').classList.add('hidden');
            
            stopLocationTracking();
        }
    }
</script>
@endsection