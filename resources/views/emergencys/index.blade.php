@extends('layouts.theme')

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
                            <div id="map-loading" class="absolute inset-0 flex items-center justify-center bg-slate-100/80 z-10">
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
                            <button onclick="openModal()" class="flex w-full min-w-[200px] cursor-pointer items-center justify-center gap-2 rounded-lg bg-primary px-8 py-3.5 text-base font-bold text-white shadow-lg transition-all hover:bg-blue-700 active:scale-[0.98] order-1 sm:order-2">
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

    <div id="confirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm transition-all duration-300 ">
        <div class="flex min-h-full items-center justify-center p-4 ">
            <div class=" relative w-full max-w-md my-8 bg-white dark:bg-[#1a2632] rounded-2xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">

                <header class="px-6 md:px-8 py-5 md:py-6 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between bg-white dark:bg-[#1a2632] sticky top-0 z-10 rounded-t-2xl">
                    <div>
                        <h1 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white tracking-tight">รายงานเหตุการณ์</h1>
                        <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide">แจ้งเหตุฉุกเฉิน</p>
                    </div>
                    <button onclick="closeModal()" class="h-10 w-10 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center shadow-sm transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </header>

                <form action="{{ route('emergency.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col bg-white dark:bg-[#1a2632] rounded-b-2xl">
                    @csrf
                    
                    <input type="hidden" name="emergency_lat" id="lat_input" value="">
                    <input type="hidden" name="emergency_lng" id="lng_input" value="">
                    <input type="hidden" name="emergency_location" id="location_input" value="">

                    <section class="px-6 md:px-8 py-5 md:py-6 border-b border-slate-100 dark:border-slate-700/50">
                        <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-4">ข้อมูลผู้แจ้ง</h2>
                        <div class="grid grid-cols-1 gap-4 md:gap-5">
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-200" for="reporter-name">ชื่อผู้แจ้ง</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                        <span class="material-symbols-outlined text-[18px]">person</span>
                                    </div>
                                    <input required name="name_reporter" value="{{ old('name_reporter', auth()->user()->name ?? '') }}" class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 pr-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white dark:placeholder:text-slate-500 transition-shadow" id="reporter-name" placeholder="ชื่อ-นามสกุล" type="text" />
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-200" for="phone-number">เบอร์โทรศัพท์</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                        <span class="material-symbols-outlined text-[18px]">call</span>
                                    </div>
                                    <input required name="phone_reporter" value="{{ old('phone_reporter', auth()->user()->phone ?? '') }}" class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 pr-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white dark:placeholder:text-slate-500 transition-shadow" id="phone-number" placeholder="08X-XXX-XXXX" type="tel" />
                                </div>
                            </div>
                        </div>

                        {{-- เปลี่ยนประเภทผู้แจ้ง --}}
                        <div class="space-y-3 pt-4">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">ประเภทผู้แจ้ง</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                
                                @foreach(['ผู้ประสบเหตุ', 'ญาติ', 'พลเมืองดี', 'อื่นๆ'] as $type)
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="type_reporter" value="{{ $type }}" class="peer sr-only" required onchange="toggleOtherReporter(this.value)">
                                    <div class="w-full py-2 px-1 rounded-lg border border-slate-200 bg-slate-50 dark:bg-slate-800/50 dark:border-slate-700 hover:bg-white dark:hover:bg-slate-800 transition-all text-center peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary peer-checked:ring-1 peer-checked:ring-primary dark:peer-checked:bg-primary/20">
                                        <span class="text-xs font-medium">{{ $type }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            {{-- ช่องกรอกอื่นๆ ซ่อนไว้เป็นค่าเริ่มต้น --}}
                            <div id="other_reporter_wrapper" class="hidden mt-2">
                                <input type="text" id="type_reporter_other" name="type_reporter_other" placeholder="โปรดระบุประเภทผู้แจ้ง..." class="w-full rounded-lg border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white transition-shadow">
                            </div>
                        </div>
                    </section>
                    
                    <section class="px-6 md:px-8 py-5 md:py-6 border-b border-slate-100 dark:border-slate-700/50">
                        <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-4">ประเภทเหตุการณ์</h2>
                        <div class="space-y-1.5">
                            <label class="sr-only" for="topic">ประเภท</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-[18px]">category</span>
                                </div>
                                <select required name="emergency_type" class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 pr-10 py-2.5 text-sm text-slate-900 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white transition-shadow appearance-none" id="topic">
                                    <option disabled="" selected="" value="">เลือกประเภทเหตุการณ์...</option>
                                    {{-- ดึงข้อมูลจาก DB --}}
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
                        </div>
                    </section>

                    <section class="px-6 md:px-8 py-5 md:py-6 border-b border-slate-100 dark:border-slate-700/50">
                        <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-4">รายละเอียดเหตุการณ์</h2>
                        <div class="space-y-1.5">
                            <textarea required name="emergency_detail" class="w-full rounded-lg border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary focus:ring-primary dark:bg-slate-800/50 dark:border-slate-700 dark:text-white dark:placeholder:text-slate-500 transition-shadow resize-none" id="description" placeholder="กรุณาอธิบายเหตุการณ์โดยละเอียด รวมถึงสถานที่และสถานการณ์ปัจจุบัน..." rows="4">{{ old('emergency_detail') }}</textarea>
                        </div>
                    </section>

                    <section class="px-6 md:px-8 py-5 md:py-6">
                        <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-4">แนบรูปภาพเหตุการณ์</h2>
                        <label for="dropzone-file" class="group relative flex flex-col items-center justify-center w-full h-28 md:h-32 border-2 border-dashed border-slate-300 rounded-lg bg-slate-50 hover:bg-slate-100 hover:border-primary/50 dark:bg-slate-800/30 dark:border-slate-700 dark:hover:bg-slate-800 dark:hover:border-primary/50 transition-all cursor-pointer">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="preview-area">
                                <div class="h-9 w-9 md:h-10 md:w-10 mb-2 md:mb-3 rounded-full bg-white shadow-sm flex items-center justify-center text-primary dark:bg-slate-700 dark:text-primary-400 ring-1 ring-slate-900/5 group-hover:scale-110 transition-transform duration-300">
                                    <span class="material-symbols-outlined text-lg md:text-xl">cloud_upload</span>
                                </div>
                                <p class="mb-1 text-xs md:text-sm text-slate-600 dark:text-slate-300" id="file-label"><span class="font-semibold">คลิกเพื่ออัปโหลด</span> หรือลากไฟล์มาวาง</p>
                                <p class="text-[10px] md:text-xs text-slate-400 dark:text-slate-500">รองรับไฟล์รูปภาพ (ขนาดสูงสุด 5MB)</p>
                            </div>
                            <input name="emergency_photo" id="dropzone-file" type="file" class="hidden" accept="image/*" onchange="previewFile(this)" />
                        </label>
                    </section>

                    <footer class="px-6 md:px-8 py-4 md:py-5 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-700 flex flex-col-reverse sm:flex-row items-center justify-between gap-3 sticky bottom-0 rounded-b-2xl">
                        <p class="text-xs text-slate-400 hidden sm:block">กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนส่ง</p>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-primary hover:bg-blue-700 active:bg-blue-800 rounded-lg shadow-sm shadow-blue-900/20 transition-all flex items-center justify-center gap-2">
                            <span>ส่งข้อมูลแจ้งเหตุ</span>
                            <span class="material-symbols-outlined text-[18px]">send</span>
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>

    {{-- Script สำหรับซ่อน/แสดงช่อง "อื่นๆ" --}}
    <script>
        function toggleOtherReporter(value) {
            const wrapper = document.getElementById('other_reporter_wrapper');
            const input = document.getElementById('type_reporter_other');
            
            if (value === 'อื่นๆ') {
                wrapper.classList.remove('hidden');
                input.required = true; // บังคับกรอกถ้าเลือกอื่นๆ
                input.focus();
            } else {
                wrapper.classList.add('hidden');
                input.required = false;
                input.value = ''; // เคลียร์ค่าทิ้ง
            }
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initMap&libraries=places" async defer></script>
    <script>
        let map;
        let marker;
        let geocoder;

        function initMap() {
            const defaultLocation = { lat: 13.7563, lng: 100.5018 };

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: defaultLocation,
                disableDefaultUI: true,
                zoomControl: true,
            });

            geocoder = new google.maps.Geocoder();

            marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
            });

            marker.addListener("dragend", () => {
                const position = marker.getPosition();
                updateLocationData(position.lat(), position.lng());
            });

            document.getElementById('map-loading').style.display = 'none';
            getCurrentLocation();
        }

        function getCurrentLocation() {
            const statusText = document.getElementById('location-text');
            
            if (navigator.geolocation) {
                statusText.textContent = "กำลังค้นหาตำแหน่ง...";
                
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        const pos = { lat: lat, lng: lng };
                        map.setCenter(pos);
                        marker.setPosition(pos);

                        updateLocationData(lat, lng);
                    },
                    () => {
                        statusText.textContent = "ไม่สามารถระบุตำแหน่งได้ (โปรดตรวจสอบการอนุญาต GPS)";
                    }
                );
            } else {
                statusText.textContent = "เบราว์เซอร์ของคุณไม่รองรับการระบุตำแหน่ง";
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

        function openModal() {
            const modal = document.getElementById('confirmModal');
            const modalContent = document.getElementById('modalContent');
            document.body.style.overflow = 'hidden';
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
                document.body.style.overflow = 'auto';
            }, 300);
        }
        
        function previewFile(input) {
            const file = input.files[0];
            const label = document.getElementById('file-label');
            if(file){
                label.innerHTML = `<span class="text-primary font-bold">${file.name}</span> (อัปโหลดแล้ว)`;
            }
        }

        document.getElementById('confirmModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>
@endsection