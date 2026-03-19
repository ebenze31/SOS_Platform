@extends('layouts.theme_user')

@section('content')

<body class="bg-background-light dark:bg-background-dark font-display text-[#0d141b] dark:text-white transition-colors duration-200 ">
    
    @if(session('error'))
    <div class="fixed top-20 right-5 z-50 bg-red-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center gap-3">
        <span class="material-symbols-outlined">error</span>
        <div>
            <h4 class="font-bold">ผิดพลาด!</h4>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden p-4 md:p-8 h-full">
        <div class="mx-auto w-full max-w-[1200px] mt-20">
            <div>
                <div class="md:col-span-8 flex flex-col gap-6">
                    <section class="h-full rounded-xl bg-white p-1 shadow-sm ring-1 ring-slate-900/5 dark:bg-[#1a2632] dark:ring-white/10">
                        <div class="flex items-center justify-between px-3 py-4">
                            <h3 class="text-sm font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">จุดเกิดเหตุ</h3>
                            <button onclick="getCurrentLocation()" type="button" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">my_location</span>
                                ตำแหน่งปัจจุบัน
                            </button>
                        </div>
                        <div class="relative overflow-hidden rounded-lg mx-1 mb-1">
                            {{-- Map Container --}}
                            <div id="map" class="aspect-[16/9] md:aspect-auto md:h-[400px] w-full bg-slate-200"></div>
                            
                            {{-- Loading Overlay --}}
                            <div id="map-loading" class="absolute inset-0 flex items-center justify-center bg-slate-100/80 z-30">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined animate-spin text-3xl text-primary mb-2">refresh</span>
                                    <span class="text-xs font-bold text-slate-500">กำลังโหลดแผนที่...</span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="block md:flex mt-4">
                    <div class="block w-full px-2 order-last mb-5">
                        <div class="relative bg-white/95 px-5 py-4 backdrop-blur-sm rounded-xl dark:bg-[#1a2632]/95 border border-slate-100 dark:border-slate-700 shadow-lg flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined">location_on</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold leading-none">ตำแหน่งที่ตรวจพบ</p>
                                    <p class="text-sm text-slate-500 mt-1" id="location-text">กำลังรอข้อมูล GPS...</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full md:w-auto flex-col sm:flex-row items-center gap-3 mt-3">
                            <button onclick="checkGPSAndOpenModal()" class="flex w-full min-w-[200px] cursor-pointer items-center justify-center gap-2 rounded-lg bg-primary px-8 py-3.5 text-base font-bold text-white shadow-lg transition-all hover:bg-blue-700 active:scale-[0.98] order-1 sm:order-2">
                                <span>ยืนยันและส่งข้อมูล</span>
                                <span class="material-symbols-outlined text-lg">send</span>
                            </button>
                        </div>
                    </div>

                    <div class="md:col-span-4 flex flex-col gap-6 w-full px-2 order-first">
                        {{-- ส่วนติดต่อหน่วยงาน (ดึงจาก DB) --}}
                        <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 dark:bg-[#1a2632] dark:ring-white/10">
                            <div class="mb-4 flex items-center justify-between">
                                <label class="text-sm font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">ติดต่อหน่วยงานฉุกเฉิน</label>
                            </div>
                            
                            @forelse($phoneEmergencies as $phone)
                            <div class="group bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-transparent hover:border-primary/20 transition-all flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">{{ $phone->name }}</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $phone->number }}</p>
                                    </div>
                                </div>
                                <a href="tel:{{ str_replace('-', '', $phone->number) }}" class="flex-shrink-0 h-10 w-10 rounded-full bg-primary/10 hover:bg-primary text-primary hover:text-white flex items-center justify-center transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[20px]">call</span>
                                </a>
                            </div>
                            @empty
                            <p class="text-sm text-slate-500">ไม่มีข้อมูลหน่วยงาน</p>
                            @endforelse
                            
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-all duration-300">
        
        <div class="relative w-full max-w-md bg-white dark:bg-[#1a2632] rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 flex flex-col max-h-[90vh] transform transition-all duration-300 scale-95 opacity-0" id="modalContent">

            <header class="shrink-0 px-3 py-3 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between rounded-t-2xl">
                <h1 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">รายงานเหตุการณ์</h1>
                <button onclick="closeModal()" type="button" class="h-6 w-6 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center shadow-sm transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </header>

            <form id="sosForm" action="{{ route('emergency.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden min-h-0">
                @csrf
                <input type="hidden" name="emergency_lat" id="lat_input" value="">
                <input type="hidden" name="emergency_lng" id="lng_input" value="">
                <input type="hidden" name="emergency_location" id="location_input" value="">

                <section class="flex-1 overflow-y-auto px-5 py-2 custom-scrollbar">
                    <div class="grid grid-cols-1 gap-3">
                        
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">ข้อมูลผู้แจ้ง</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-[18px]">person</span>
                                </div>
                                <input required name="name_reporter" value="{{ old('name_reporter', auth()->user()->name ?? '') }}" class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 pr-3 py-2.5 text-sm text-slate-900 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white" placeholder="ชื่อผู้แจ้ง" type="text" />
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-[18px]">call</span>
                                </div>
                                <input required name="phone_reporter" value="{{ old('phone_reporter', auth()->user()->phone ?? '') }}" class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 pr-3 py-2.5 text-sm text-slate-900 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white" placeholder="เบอร์ติดต่อ" type="tel" />
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">ประเภทผู้แจ้ง</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                @foreach(['ผู้ประสบเหตุ', 'ญาติ', 'พลเมืองดี', 'อื่นๆ'] as $type)
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="type_reporter" value="{{ $type }}" class="peer sr-only" required onchange="toggleOtherReporter(this.value)">
                                    <div class="w-full py-0.5 px-1 rounded-lg border border-slate-200 bg-slate-50 hover:bg-white text-center peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary peer-checked:ring-1 peer-checked:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:hover:bg-slate-800 transition-all">
                                        <span class="text-xs font-medium">{{ $type }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <div id="other_reporter_wrapper" class="hidden mt-2">
                                <input type="text" id="type_reporter_other" name="type_reporter_other" placeholder="โปรดระบุประเภทผู้แจ้ง..." class="w-full rounded-lg border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">ข้อมูลการแจ้งเหตุ</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-[18px]">category</span>
                                </div>
                                <select required name="emergency_type" class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 pr-10 py-2.5 text-sm text-slate-900 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white appearance-none">
                                    <option disabled="" selected="" value="">เลือกประเภทเหตุการณ์</option>
                                    @foreach($emergencyTypes as $type)
                                        <option value="{{ $type->name_emergency }}" {{ old('emergency_type') == $type->name_emergency ? 'selected' : '' }}>
                                            {{ $type->name_emergency }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-[18px]">expand_more</span>
                                </div>
                            </div>
                            <div class="relative">
                                <textarea required name="emergency_detail" class="w-full rounded-lg border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white resize-none" placeholder="อธิบายรายละเอียดเหตุการณ์" rows="2">{{ old('emergency_detail') }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">แนบรูปภาพจุดเกิดเหตุ</label>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer flex flex-col items-center justify-center p-3 border border-slate-200 bg-slate-50 rounded-lg hover:bg-slate-100 dark:bg-slate-800/50 dark:border-slate-700 transition-colors">
                                    <span class="material-symbols-outlined text-primary mb-1 text-2xl">photo_camera</span>
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">เปิดกล้องถ่ายภาพ</span>
                                    <input name="photo_cam" id="input_cam" type="file" class="hidden" accept="image/*" capture="environment" onchange="previewImage(this, 'cam')" />
                                </label>
                                
                                <label class="cursor-pointer flex flex-col items-center justify-center p-3 border border-slate-200 bg-slate-50 rounded-lg hover:bg-slate-100 dark:bg-slate-800/50 dark:border-slate-700 transition-colors">
                                    <span class="material-symbols-outlined text-primary mb-1 text-2xl">image</span>
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">เลือกจากอัลบั้ม</span>
                                    <input name="photo_gal" id="input_gal" type="file" class="hidden" accept="image/*" onchange="previewImage(this, 'gal')" />
                                </label>
                            </div>

                            <div id="image-preview-container" class="hidden relative mt-2 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm bg-slate-100">
                                <img id="img-preview" src="" alt="พรีวิวรูปภาพ" class="w-full h-auto max-h-48 object-cover">
                                <button type="button" onclick="clearImage()" class="absolute top-2 right-2 bg-slate-900/60 hover:bg-red-500 text-white rounded-full p-1.5 backdrop-blur-sm transition-colors flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </section>

                <footer class="shrink-0 px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-700 flex flex-col-reverse sm:flex-row items-center justify-between gap-3 rounded-b-2xl">
                    <p class="text-xs text-slate-400 hidden sm:block">ตรวจสอบข้อมูลให้ถูกต้องก่อนส่ง</p>
                    
                    <button id="submitBtn" type="submit" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-primary hover:bg-blue-700 active:bg-blue-800 disabled:opacity-75 disabled:cursor-not-allowed rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                        <span id="submitText">ส่งข้อมูลแจ้งเหตุ</span>
                        <span id="submitIcon" class="material-symbols-outlined text-[18px]">send</span>
                    </button>
                </footer>
            </form>
        </div>
    </div>


    <div id="gpsAlertModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-all duration-300">
        <div class="relative w-full max-w-sm bg-white dark:bg-[#1a2632] rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 flex flex-col transform transition-all duration-300 scale-95 opacity-0 text-center" id="gpsAlertModalContent">
            <div class="p-6">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20 mb-4">
                    <span class="material-symbols-outlined text-4xl text-red-600 dark:text-red-500">location_off</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">ไม่พบพิกัด GPS</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">กรุณาเปิดการระบุตำแหน่ง (GPS) หรือทำการเลื่อนหมุดบนแผนที่ เพื่อระบุจุดเกิดเหตุก่อนดำเนินการต่อ</p>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-800/80 rounded-b-2xl">
                <button onclick="closeGpsAlertModal()" type="button" class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-red-700 transition-colors">
                    ตกลง เข้าใจแล้ว
                </button>
            </div>
        </div>
    </div>

    {{-- Script UI (รูปภาพและ Modal) --}}
    <script>
        const formElement = document.getElementById('sosForm');

        if (formElement) {
            formElement.addEventListener('submit', function() {
                const submitBtn = document.getElementById('submitBtn');
                const submitText = document.getElementById('submitText');
                const submitIcon = document.getElementById('submitIcon');

                // ล็อคปุ่มห้ามกดซ้ำ
                submitBtn.disabled = true;

                // เปลี่ยนข้อความ
                submitText.innerText = 'กำลังส่งข้อมูล...';

                // เปลี่ยนไอคอนเป็นรูปโหลด
                submitIcon.innerText = 'progress_activity';
                submitIcon.classList.add('animate-spin');
            });
        }

        // จัดการเปิด-ปิด Input อื่นๆ
        function toggleOtherReporter(value) {
            const wrapper = document.getElementById('other_reporter_wrapper');
            const input = document.getElementById('type_reporter_other');
            if (value === 'อื่นๆ') {
                wrapper.classList.remove('hidden');
                input.required = true;
                input.focus();
            } else {
                wrapper.classList.add('hidden');
                input.required = false;
                input.value = '';
            }
        }

        // จัดการพรีวิวรูปภาพ
        function previewImage(input, source) {
            const previewContainer = document.getElementById('image-preview-container');
            const previewImg = document.getElementById('img-preview');
            
            // เคลียร์ค่าของอีกปุ่มนึงทิ้ง ป้องกันการอัปโหลดซ้ำซ้อน 2 รูป
            if (source === 'cam') {
                const galInput = document.getElementById('input_gal');
                if(galInput) galInput.value = '';
            }
            if (source === 'gal') {
                const camInput = document.getElementById('input_cam');
                if(camInput) camInput.value = '';
            }

            const file = input.files[0];
            if (file) {
                // แสดงพรีวิวรูป
                previewImg.src = URL.createObjectURL(file);
                previewContainer.classList.remove('hidden');

                // สั่งให้ Scroll เลื่อนลงมาหารูปภาพที่เพิ่งแสดง
                setTimeout(() => {
                    previewContainer.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 100);
            }
        }

        // ปุ่มกากบาทเพื่อลบภาพทิ้ง
        function clearImage() {
            document.getElementById('input_cam').value = '';
            document.getElementById('input_gal').value = '';
            document.getElementById('image-preview-container').classList.add('hidden');
            document.getElementById('img-preview').src = '';
        }

        // Modal Function (ล็อคพื้นหลัง)
        function openModal() {
            const modal = document.getElementById('confirmModal');
            const modalContent = document.getElementById('modalContent');
            document.body.style.overflow = 'hidden'; // ล็อค Scroll พื้นหลัง
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('confirmModal');
            const modalContent = document.getElementById('modalContent');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // คืนค่า Scroll พื้นหลัง
            }, 300);
        }

        document.getElementById('confirmModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>

    {{-- Script Google Maps --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initMap&libraries=places" async defer></script>
    <script>
        let map;
        let marker = null;
        let geocoder;

        function initMap() {
            // ตั้งค่าเริ่มต้นเป็นประเทศไทย (ซูมระดับ 6)
            const thailandCenter = { lat: 15.8700, lng: 100.9925 };

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 6,
                center: thailandCenter,
                disableDefaultUI: true,
                zoomControl: true,
            });

            geocoder = new google.maps.Geocoder();

            document.getElementById('map-loading').style.display = 'none';
            
            // เริ่มต้นค้นหาพิกัด GPS ทันที
            getCurrentLocation();
        }

        function createMarker(position) {
            if (!marker) {
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    draggable: true, // อนุญาตให้ลากขยับหมุดได้
                    animation: google.maps.Animation.DROP,
                });

                marker.addListener("dragend", () => {
                    const pos = marker.getPosition();
                    updateLocationData(pos.lat(), pos.lng());
                });
            } else {
                // ถ้ามีหมุดอยู่แล้ว แค่ย้ายตำแหน่ง
                marker.setPosition(position);
            }
            
            // อัปเดตข้อมูลพิกัดลงใน Input ซ่อน และแสดงข้อความ
            updateLocationData(typeof position.lat === 'function' ? position.lat() : position.lat, 
                               typeof position.lng === 'function' ? position.lng() : position.lng);
        }

        function getCurrentLocation() {
            const statusText = document.getElementById('location-text');
            
            if (navigator.geolocation) {
                statusText.textContent = "กำลังค้นหาตำแหน่ง GPS...";
                
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        // กรณี: หา GPS เจอ!
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const pos = { lat: lat, lng: lng };
                        
                        map.setCenter(pos);
                        map.setZoom(15);
                        createMarker(pos);
                    },
                    (error) => {
                        // หา GPS ไม่เจอ หรือผู้ใช้กดไม่อนุญาต (Block)
                        console.warn("GPS Error:", error.message);
                        statusText.textContent = "กรุณาอนุญาตการเข้าถึง GPS";
                    },
                    { enableHighAccuracy: true, timeout: 5000 }
                );
            } else {
                // บราว์เซอร์เก่ามาก ไม่รองรับ GPS
                statusText.textContent = "อุปกรณ์ของคุณไม่รองรับการระบุตำแหน่ง";
            }
        }

        function updateLocationData(lat, lng) {
            document.getElementById('lat_input').value = lat;
            document.getElementById('lng_input').value = lng;
            
            const statusText = document.getElementById('location-text');
            statusText.textContent = `พิกัด: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
            
            geocoder.geocode({ location: latlng })
                .then((response) => {
                    if (response.results[0]) {
                        const address = response.results[0].formatted_address;
                        document.getElementById('location_input').value = address;
                        statusText.textContent = address; 
                    } else {
                        document.getElementById('location_input').value = `พิกัด: ${lat}, ${lng}`;
                    }
                })
                .catch((e) => {
                    console.log("Geocoder failed: " + e);
                    document.getElementById('location_input').value = `พิกัด: ${lat}, ${lng}`;
                });
        }

        // ตรวจสอบพิกัดก่อนเปิดฟอร์ม
        function checkGPSAndOpenModal() {
            const lat = document.getElementById('lat_input').value;
            const lng = document.getElementById('lng_input').value;

            if (!lat || !lng || lat === "" || lng === "") {
                // ถ้าไม่มีพิกัด ให้เปิด Modal แจ้งเตือน
                openGpsAlertModal();
            } else {
                // ถ้ามีพิกัดครบ ให้เปิด Modal ฟอร์มตามปกติ
                openModal();
            }
        }

        function openGpsAlertModal() {
            const modal = document.getElementById('gpsAlertModal');
            const modalContent = document.getElementById('gpsAlertModalContent');
            document.body.style.overflow = 'hidden'; 
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeGpsAlertModal() {
            const modal = document.getElementById('gpsAlertModal');
            const modalContent = document.getElementById('gpsAlertModalContent');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto'; 
            }, 300);
        }

        document.getElementById('gpsAlertModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeGpsAlertModal();
        });
    </script>
</body>
@endsection