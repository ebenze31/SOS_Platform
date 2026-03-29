@extends('layouts.theme_user')

@section('content')

<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    /* Fade-in sections */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-section {
        animation: fadeInUp 0.45s ease both;
    }

    .animate-section:nth-child(1) {
        animation-delay: 0.05s;
    }

    .animate-section:nth-child(2) {
        animation-delay: 0.15s;
    }

    .animate-section:nth-child(3) {
        animation-delay: 0.25s;
    }

    .animate-section:nth-child(4) {
        animation-delay: 0.35s;
    }

    /* Modal Animation */
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-modal {
        animation: scaleIn 0.25s ease-out both;
    }

    /* Avatar pulse ring on hover */
    .avatar-ring {
        position: relative;
        display: inline-block;
        border-radius: 9999px;
    }

    .avatar-ring::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 9999px;
        border: 2px solid transparent;
        transition: border-color 0.3s ease, transform 0.3s ease;
    }

    .avatar-ring:hover::after {
        border-color: #f97316;
        transform: scale(1.06);
    }

    /* Camera button */
    .camera-btn {
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .camera-btn:hover {
        transform: scale(1.15);
    }

    .camera-btn:active {
        transform: scale(0.92);
    }

    /* Input focus animation */
    input,
    select {
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
    }

    input:focus,
    select:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.12);
    }

    /* Save button */
    .btn-save {
        position: relative;
        overflow: hidden;
        transition: transform 0.15s ease, box-shadow 0.2s ease;
    }

    .btn-save::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(-100%);
        transition: transform 0.35s ease;
    }

    .btn-save:hover::after {
        transform: translateX(100%);
    }

    .btn-save:hover {
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.35);
        transform: translateY(-1px);
    }

    .btn-save:active {
        transform: scale(0.97);
        box-shadow: none;
    }

    /* Cancel button */
    .btn-cancel {
        transition: background-color 0.2s ease, color 0.2s ease, transform 0.15s ease;
    }

    .btn-cancel:hover {
        background-color: #e2e8f0;
    }

    .btn-cancel:active {
        transform: scale(0.97);
    }

    /* Change password button */
    .btn-change-pw {
        transition: background-color 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
    }

    .btn-change-pw:hover {
        background-color: #f8fafc;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }

    .btn-change-pw:active {
        transform: scale(0.97);
    }

    /* Toast notification */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    #toast {
        display: none;
        position: fixed;
        top: 70px;
        left: 50%;
        transform: translateX(-50%);
        background: #16a34a;
        color: #fff;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        z-index: 9999;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        gap: 8px;
        align-items: center;
    }

    #toast.show {
        display: flex;
        animation: slideDown 0.3s ease forwards;
    }

    #toast.hide {
        animation: fadeOut 0.3s ease forwards;
    }
</style>

<div id="toast">
    <span class="material-symbols-outlined" style="font-size:18px;">check_circle</span>
    บันทึกข้อมูลเรียบร้อยแล้ว
</div>



<div class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display mt-[50px]">
    <div class="relative flex h-auto w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="flex flex-1 justify-center py-8 px-4 md:px-0">
                <div class="layout-content-container flex flex-col max-w-[720px] flex-1 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 md:p-10">

                    <div class="flex flex-col items-center mb-10 animate-section">
                        <div class="relative group avatar-ring">
                            <div class="h-32 w-32 rounded-full border-4 border-white dark:border-slate-800 shadow-md overflow-hidden">
                                <img id="main-avatar" class="h-full w-full object-cover"
                                    src="{{ !empty($data_user->photo) 
        ? asset('storage/' . $data_user->photo) 
        : 'https://ui-avatars.com/api/?name=' . urlencode($data_user->name) . '&background=e2e8f0&color=94a3b8&size=200&rounded=true'
    }}"
                                    alt="avatar">


                            </div>
                            <button type="button" id="open-camera-btn" class="camera-btn absolute bottom-0 right-0 bg-primary text-white p-2 w-[30px] h-[30px] flex justify-center items-center rounded-full shadow-lg z-10">
                                <span class="material-symbols-outlined text-sm">photo_camera</span>
                            </button>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-slate-900 dark:text-slate-100 text-xl font-bold">{{$data_user->name_officer ?? $data_user->name}}</p>
                        </div>
                    </div>

                    <form id="profile-form" action="{{ route('profile.update', auth()->id()) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="file" id="hidden_avatar_input" name="avatar" class="hidden" accept="image/png, image/jpeg, image/webp">

                        <section class="mb-10 animate-section">
                            <div class="flex items-center gap-2 mb-6 border-b border-slate-100 dark:border-slate-800 pb-2">
                                <span class="material-symbols-outlined text-primary">person</span>
                                <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold">ข้อมูลส่วนตัว</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <label class="flex flex-col gap-2">
                                    <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">ชื่อ-นามสกุล</span>
                                    <input class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" type="text" name="name" value="{{$data_user->name}}" />
                                </label>
                                <label class="flex flex-col gap-2">
                                    <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">อีเมล</span>
                                    <input class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" type="email" name="email" value="{{$data_user->email}}" />
                                </label>
                                <label class="flex flex-col gap-2">
                                    <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">เบอร์โทรศัพท์ด่วน</span>
                                    <input class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" type="tel" name="phone" value="{{$data_user->phone}}" />
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex flex-col gap-2 w-1/2">
                                        <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">เพศ</span>
                                        <select name="gender" class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4">
                                            <option value="">--เลือกเพศ--</option>
                                            <option value="ชาย" {{ $data_user->gender == 'ชาย'  ? 'selected' : '' }}>ชาย</option>
                                            <option value="หญิง" {{ $data_user->gender == 'หญิง' ? 'selected' : '' }}>หญิง</option>
                                            <option value="อื่นๆ" {{ $data_user->gender == 'อื่นๆ' ? 'selected' : '' }}>อื่นๆ</option>
                                        </select>
                                    </label>
                                    <label class="flex flex-col gap-2 w-1/2">
                                        <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">วันเกิด</span>
                                        <input class="rounded-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" type="date" name="birthday" value="{{ date('Y-m-d', strtotime($data_user->birthday)) }}" />
                                    </label>
                                </div>
                            </div>
                        </section>

                        @if($data_user->role == "officer")
                        <section class="mb-10 animate-section">
                            <div class="flex items-center gap-2 mb-6 border-b border-slate-100 dark:border-slate-800 pb-2">
                                <span class="material-symbols-outlined text-primary">corporate_fare</span>
                                <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold">สังกัดและตำแหน่ง</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <label class="flex flex-col gap-2">
                                    <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">ยานภาหนะ</span>
                                    <input class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4" name="vehicle_type" type="text" value="{{$data_user->vehicle_type}}" />
                                </label>
                                <label class="flex flex-col gap-2">
                                    <span class="text-slate-700 dark:text-slate-300 text-sm font-semibold">พื้นที่ที่สังกัด</span>
                                    <select class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary h-12 px-4">
                                        @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ in_array($area->id, $area_ids ?? []) ? 'selected' : '' }}>
                                            {{ $area->name_area }}
                                        </option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </section>
                        @endif

                        <section class="mb-6 animate-section">
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
                                    <a href="{{url('password/reset')}}" type="button" class="btn-change-pw bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 px-4 py-2 rounded-lg text-sm font-bold shadow-sm">
                                        เปลี่ยนรหัสผ่าน
                                    </a>
                                </div>
                            </div>
                        </section>

                        <div class="mt-10 flex flex-col md:flex-row md:justify-end gap-3 animate-section">
                            <button id="save-btn" type="submit" class="btn-save md:order-2 px-5 bg-primary text-white py-4 rounded-lg font-bold shadow-md">
                                <span id="save-label" class="flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-base" style="font-size:18px;">save</span>
                                    บันทึกข้อมูลทั้งหมด
                                </span>
                                <span id="save-loading" class="hidden items-center justify-center gap-2">
                                    <svg class="animate-spin" style="width:18px;height:18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                    กำลังบันทึก...
                                </span>
                            </button>
                            <a href="{{ url()->previous() }}" type="button" id="cancel-btn" class="btn-cancel md:order-1 px-5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 py-4 rounded-lg font-bold">
                                กลับ
                            </a>
                        </div>


                        <!-- modal -->
                        <div id="avatar-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity">
                            <div class="relative w-full max-w-sm p-4 animate-modal">
                                <div class="relative rounded-xl bg-white dark:bg-slate-900 shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col">
                                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 p-4">
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                            <span class="material-symbols-outlined text-primary">photo_camera</span>
                                            เปลี่ยนรูปโปรไฟล์
                                        </h3>
                                        <button type="button" id="close-modal-btn" class="inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-slate-400 hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-slate-800 dark:hover:text-white transition-colors">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>

                                    <div class="p-5 flex flex-col items-center gap-5">
                                        <div class="relative h-32 w-32 rounded-full border-4 border-slate-100 dark:border-slate-800 overflow-hidden bg-slate-100 dark:bg-slate-800 shadow-inner">
                                            <img id="modal-avatar-preview" class="h-full w-full object-cover"
                                                src="{{ !empty($data_user->photo) 
        ? asset('storage/' . $data_user->photo) 
        : 'https://ui-avatars.com/api/?name=' . urlencode($data_user->name) . '&background=e2e8f0&color=94a3b8&size=200&rounded=true'
    }}"
                                                alt="avatar">
                                        </div>

                                        <div class="w-full">
                                            <label class="block mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300" for="avatar_upload">อัปโหลดรูปภาพใหม่</label>
                                            <input name="photo" class="block w-full text-sm text-slate-900 border border-slate-300 rounded-lg cursor-pointer bg-slate-50 dark:text-slate-400 focus:outline-none dark:bg-slate-800 dark:border-slate-700 dark:placeholder-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-[#e0650e] file:transition-colors file:cursor-pointer" id="avatar_upload" type="file" accept="image/png, image/jpeg, image/webp">
                                            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">รองรับไฟล์ PNG, JPG หรือ WEBP (ขนาดไม่เกิน 2MB)</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800 p-4">
                                        <button type="button" id="cancel-modal-btn" class="btn-cancel px-4 py-2 text-sm font-bold rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                            ยกเลิก
                                        </button>
                                        <button type="button" id="confirm-avatar-btn" class="btn-save px-4 py-2 text-sm font-bold text-white bg-primary rounded-lg shadow-md">
                                            ใช้รูปนี้
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- Form & Toast Logic ---
    const form = document.getElementById('profile-form');
    const saveBtn = document.getElementById('save-btn');
    const saveLabel = document.getElementById('save-label');
    const saveLoad = document.getElementById('save-loading');
    const toast = document.getElementById('toast');

    form.addEventListener('submit', function(e) {
        saveLabel.classList.add('hidden');
        saveLoad.classList.remove('hidden');
        saveLoad.classList.add('flex');
        saveBtn.disabled = true;
    });

    const params = new URLSearchParams(window.location.search);
    if (params.get('saved') === '1') {
        showToast();
    }

    function showToast() {
        toast.classList.remove('hide');
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => toast.classList.remove('show', 'hide'), 300);
        }, 3000);
    }

    document.getElementById('cancel-btn').addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });


    // --- Modal & Avatar Logic ---
    const modal = document.getElementById('avatar-modal');
    const openCameraBtn = document.getElementById('open-camera-btn');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelModalBtn = document.getElementById('cancel-modal-btn');
    const confirmAvatarBtn = document.getElementById('confirm-avatar-btn');

    const avatarUploadInput = document.getElementById('avatar_upload');
    const hiddenAvatarInput = document.getElementById('hidden_avatar_input');
    const modalAvatarPreview = document.getElementById('modal-avatar-preview');
    const mainAvatar = document.getElementById('main-avatar');

    // ฟังก์ชันเปิด/ปิด Modal
    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden'; // ป้องกันการ scroll ข้างหลัง
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    openCameraBtn.addEventListener('click', openModal);
    closeModalBtn.addEventListener('click', closeModal);
    cancelModalBtn.addEventListener('click', closeModal);

    // ปิด Modal เมื่อคลิกพื้นที่ว่างข้างนอก
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    // แสดง Preview เมื่อเลือกไฟล์รูปภาพ
    avatarUploadInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                modalAvatarPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    confirmAvatarBtn.addEventListener('click', function() {
        const file = avatarUploadInput.files[0];

        if (file) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            hiddenAvatarInput.files = dataTransfer.files;

            mainAvatar.src = modalAvatarPreview.src;
        }

        closeModal();
    });
</script>

@endsection