@extends('layouts.theme')

@section('content')

<style>
    * {
        font-family: 'IBM Plex Sans Thai', sans-serif;
    }

    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }

    .dropdown-menu {
        display: none;
    }

    .dropdown-menu.open {
        display: block;
    }

    #filterPanel {
        display: none;
    }

    #filterPanel.open {
        display: block;
    }

    #addModal {
        display: none;
    }

    #addModal.open {
        display: flex;
    }

    #editModal {
        display: none;
    }

    #editModal.open {
        display: flex;
    }

    #deleteModal {
        display: none;
    }

    #deleteModal.open {
        display: flex;
    }

    #toast {
        transition: opacity 0.3s, transform 0.3s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(6px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    .fade-in {
        animation: fadeIn 0.2s ease;
    }
</style>
</head>
<div class="bg-background-light text-text-main antialiased">

    <div class="h-[75px] bg-white border-b border-border-color fixed top-0 left-0 right-0 z-30 flex items-center px-8 shadow-sm">
        <span class="text-primary font-bold text-xl tracking-tight">🚑 ระบบจัดการเจ้าหน้าที่</span>
    </div>

    <div class="mt-[75px]">
        <main class="flex flex-col max-w-[1440px] mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 gap-6">

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-text-main mb-2">รายชื่อเจ้าหน้าที่ทั้งหมด</h2>
                    <p class="text-text-sub text-sm">ตารางแสดงรายละเอียดหน่วยงานทั้งหมดในระบบ พร้อมสถานะและการจัดการพื้นที่</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="toggleFilter()" class="inline-flex items-center justify-center px-4 py-2 border border-border-color rounded-lg text-sm font-medium text-text-main bg-white hover:bg-gray-50 shadow-sm transition-all">
                        <span class="material-symbols-outlined mr-2 text-lg">filter_alt</span>ตัวกรอง
                        <span id="filterBadge" class="hidden ml-2 bg-primary text-white text-[10px] rounded-full w-4 h-4 items-center justify-center font-bold" style="display:none">!</span>
                    </button>
                    <button onclick="openAddModal()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-primary hover:bg-blue-700 shadow-sm transition-all">
                        <span class="material-symbols-outlined mr-2 text-lg">person_add</span>เพิ่มเจ้าหน้าที่
                    </button>
                </div>
            </div>

            <div id="filterPanel" class="bg-white border border-border-color rounded-xl shadow-sm p-5 fade-in">
                <div class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-text-sub mb-1">สถานะ</label>
                        <select id="filterStatus" onchange="applyFilters()" class="border border-border-color rounded-lg px-3 py-2 text-sm focus:outline-none bg-background-light">
                            <option value="">ทั้งหมด</option>
                            <option>พร้อมปฏิบัติงาน</option>
                            <option>กำลังปฏิบัติหน้าที่</option>
                            <option>พัก/ออกเวร</option>
                            <option>ออฟไลน์</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-sub mb-1">โซนรับผิดชอบ</label>
                        <select id="filterZone" onchange="applyFilters()" class="border border-border-color rounded-lg px-3 py-2 text-sm focus:outline-none bg-background-light">
                            <option value="">ทั้งหมด</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-text-sub mb-1">หน่วยงาน</label>
                        <select id="filterOrg" onchange="applyFilters()" class="border border-border-color rounded-lg px-3 py-2 text-sm focus:outline-none bg-background-light">
                            <option value="">ทั้งหมด</option>
                        </select>
                    </div>
                    <button onclick="clearFilters()" class="px-4 py-2 text-sm border border-border-color rounded-lg hover:bg-gray-50 text-text-sub transition-all flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">close</span>ล้างตัวกรอง
                    </button>
                </div>
            </div>

            <div class="bg-surface-light rounded-xl border border-border-color shadow-sm flex flex-col">
                <div class="px-5 py-4 border-b border-border-color flex justify-between items-center bg-white rounded-t-xl">
                    <div id="selectedInfo" class="text-sm text-text-sub" style="display:none">
                        <span id="selectedCount" class="font-semibold text-text-main"></span> รายการที่เลือก
                        <button onclick="deleteSelected()" class="ml-3 text-red-600 hover:text-red-800 font-medium text-xs border border-red-200 bg-red-50 rounded px-2 py-0.5 transition-all">
                            <span class="material-symbols-outlined text-sm">delete</span> ลบที่เลือก
                        </button>
                    </div>
                    <div class="flex items-center gap-3 ml-auto">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                            <input id="searchInput" oninput="applyFilters()" class="pl-9 pr-4 py-2 text-sm border border-border-color rounded-lg w-64 bg-background-light outline-none focus:border-primary" placeholder="ค้นหาชื่อ, พื้นที่..." type="text" />
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-border-color text-[11px] uppercase tracking-wider text-text-sub font-semibold">
                                <th class="px-5 py-3 w-8"><input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" class="rounded border-gray-300 cursor-pointer" /></th>
                                <th class="px-5 py-3 w-[25%] cursor-pointer select-none" onclick="sortBy('name')">ข้อมูลเจ้าหน้าที่ <span id="sort-name" class="material-symbols-outlined text-xs ml-1 opacity-40">unfold_more</span></th>
                                <th class="px-5 py-3 w-[15%] cursor-pointer select-none" onclick="sortBy('status')">สถานะปัจจุบัน <span id="sort-status" class="material-symbols-outlined text-xs ml-1 opacity-40">unfold_more</span></th>
                                <th class="px-5 py-3 w-[20%]">หน่วยงาน/สังกัด</th>
                                <th class="px-5 py-3 w-[15%] cursor-pointer select-none" onclick="sortBy('zone')">โซนรับผิดชอบ <span id="sort-zone" class="material-symbols-outlined text-xs ml-1 opacity-40">unfold_more</span></th>
                                <th class="px-5 py-3 w-[15%]">การติดต่อ</th>
                                <th class="px-5 py-3 w-[5%] text-right"></th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="divide-y divide-border-color bg-white text-sm"></tbody>
                    </table>
                </div>

                <div id="emptyState" class="hidden text-center py-16 text-text-sub">
                    <span class="material-symbols-outlined text-5xl block mb-3 text-gray-300">search_off</span>
                    ไม่พบรายการที่ตรงกับการค้นหา
                </div>

                <div class="px-5 py-3 border-t border-border-color flex items-center justify-between bg-gray-50/50 rounded-b-xl">
                    <div class="text-xs text-text-sub" id="paginationInfo"></div>
                    <div class="flex gap-2 items-center">
                        <button id="prevBtn" onclick="changePage(currentPage-1)" class="px-2.5 py-1 border border-border-color rounded text-xs text-text-sub hover:bg-white bg-white shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">ก่อนหน้า</button>
                        <div id="pageNumbers" class="hidden sm:flex gap-1"></div>
                        <button id="nextBtn" onclick="changePage(currentPage+1)" class="px-2.5 py-1 border border-border-color rounded text-xs text-text-main hover:bg-white bg-white shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">ถัดไป</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ADD MODAL -->
    <div id="addModal" class="fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 p-7 fade-in">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold">เพิ่มเจ้าหน้าที่ใหม่</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">ชื่อ-นามสกุล *</label><input id="add-name" type="text" placeholder="นาย สมชาย ใจดี" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none focus:border-primary bg-background-light" /></div>
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">รหัสประจำตัว *</label><input id="add-code" type="text" placeholder="OP-00129" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none focus:border-primary bg-background-light" /></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">สถานะ</label><select id="add-status" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none bg-background-light">
                            <option>พร้อมปฏิบัติงาน</option>
                            <option>กำลังปฏิบัติหน้าที่</option>
                            <option>พัก/ออกเวร</option>
                            <option>ออฟไลน์</option>
                        </select></div>
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">หน่วยงาน/สังกัด *</label><input id="add-org" type="text" placeholder="กู้ภัยหลักสี่" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none focus:border-primary bg-background-light" /></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">โซนรับผิดชอบ *</label><input id="add-zone" type="text" placeholder="เขตหลักสี่" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none focus:border-primary bg-background-light" /></div>
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">เบอร์ติดต่อ *</label><input id="add-tel" type="text" placeholder="081-234-5678" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none focus:border-primary bg-background-light" /></div>
                </div>
                <div id="addError" class="text-red-500 text-xs hidden">กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน</div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button onclick="closeAddModal()" class="px-4 py-2 text-sm border border-border-color rounded-lg hover:bg-gray-50 text-text-sub">ยกเลิก</button>
                <button onclick="submitAdd()" class="px-5 py-2 text-sm bg-primary text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm flex items-center gap-1"><span class="material-symbols-outlined text-base">save</span>บันทึก</button>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 p-7 fade-in">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold">แก้ไขข้อมูลเจ้าหน้าที่</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600"><span class="material-symbols-outlined">close</span></button>
            </div>
            <input type="hidden" id="edit-id" />
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">ชื่อ-นามสกุล *</label><input id="edit-name" type="text" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none focus:border-primary bg-background-light" /></div>
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">รหัสประจำตัว</label><input id="edit-code" type="text" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm bg-gray-100 text-text-sub" readonly /></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">สถานะ</label><select id="edit-status" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none bg-background-light">
                            <option>พร้อมปฏิบัติงาน</option>
                            <option>กำลังปฏิบัติหน้าที่</option>
                            <option>พัก/ออกเวร</option>
                            <option>ออฟไลน์</option>
                        </select></div>
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">หน่วยงาน/สังกัด</label><input id="edit-org" type="text" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none focus:border-primary bg-background-light" /></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">โซนรับผิดชอบ</label><input id="edit-zone" type="text" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none focus:border-primary bg-background-light" /></div>
                    <div><label class="block text-xs font-semibold text-text-sub mb-1">เบอร์ติดต่อ</label><input id="edit-tel" type="text" class="w-full border border-border-color rounded-lg px-3 py-2 text-sm outline-none focus:border-primary bg-background-light" /></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button onclick="closeEditModal()" class="px-4 py-2 text-sm border border-border-color rounded-lg hover:bg-gray-50 text-text-sub">ยกเลิก</button>
                <button onclick="submitEdit()" class="px-5 py-2 text-sm bg-primary text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm flex items-center gap-1"><span class="material-symbols-outlined text-base">save</span>บันทึก</button>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteModal" class="fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-7 fade-in text-center">
            <span class="material-symbols-outlined text-5xl text-red-400 block mb-3">delete_forever</span>
            <h3 class="text-lg font-bold mb-2">ยืนยันการลบ</h3>
            <p class="text-sm text-text-sub mb-6">คุณต้องการลบ <span id="deleteTargetName" class="font-semibold text-text-main"></span> ออกจากระบบ? การดำเนินการนี้ไม่สามารถยกเลิกได้</p>
            <div class="flex justify-center gap-3">
                <button onclick="closeDeleteModal()" class="px-5 py-2 text-sm border border-border-color rounded-lg hover:bg-gray-50 text-text-sub">ยกเลิก</button>
                <button onclick="confirmDelete()" class="px-5 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-sm">ลบออก</button>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast" class="fixed bottom-6 right-6 z-[100] pointer-events-none" style="opacity:0;transform:translateY(16px);transition:opacity 0.3s,transform 0.3s">
        <div class="flex items-center gap-2 px-4 py-3 rounded-xl shadow-lg text-sm font-medium bg-white border border-border-color min-w-[220px]">
            <span id="toastIcon" class="material-symbols-outlined text-lg"></span>
            <span id="toastMsg"></span>
        </div>
    </div>

    <script>
        let staff = [{
                id: 1,
                name: 'นาย สมชาย ใจดี',
                code: 'OP-00124',
                status: 'พร้อมปฏิบัติงาน',
                org: 'กู้ภัยหลักสี่',
                zone: 'เขตหลักสี่',
                tel: '081-234-5678',
                avatar: 'photo'
            },
            {
                id: 2,
                name: 'นาย วิชัย มั่นคง',
                code: 'OP-00125',
                status: 'กำลังปฏิบัติหน้าที่',
                org: 'ศูนย์นเรนทร',
                zone: 'ทั่วกรุงเทพฯ',
                tel: '082-345-6789',
                avatar: 'ว'
            },
            {
                id: 3,
                name: 'นาย สมศักดิ์ กล้าหาญ',
                code: 'OP-00126',
                status: 'พร้อมปฏิบัติงาน',
                org: 'ร่วมกตัญญู (ดอนเมือง)',
                zone: 'เขตดอนเมือง',
                tel: '089-999-8888',
                avatar: 'ส'
            },
            {
                id: 4,
                name: 'พญ. ปราณี รักษา',
                code: 'MD-00045',
                status: 'พัก/ออกเวร',
                org: 'กู้ชีพวชิระ',
                zone: 'เขตดุสิต',
                tel: '02-244-3000',
                avatar: 'ป'
            },
            {
                id: 5,
                name: 'นาย กิตติ ช่วยเหลือ',
                code: 'OP-00127',
                status: 'พร้อมปฏิบัติงาน',
                org: 'ป่อเต็กตึ๊ง (บางซื่อ)',
                zone: 'เขตบางซื่อ',
                tel: '085-555-4444',
                avatar: 'ก'
            },
            {
                id: 6,
                name: 'น.ส. จิตรา พยาบาล',
                code: 'RN-00089',
                status: 'พร้อมปฏิบัติงาน',
                org: 'รพ.รามาธิบดี',
                zone: 'เขตราชเทวี',
                tel: '02-201-1000',
                avatar: 'จ'
            },
            {
                id: 7,
                name: 'นาย ธนดล รวดเร็ว',
                code: 'OP-00128',
                status: 'ออฟไลน์',
                org: 'กู้ภัยสว่าง',
                zone: 'เขตปทุมวัน',
                tel: '087-654-3210',
                avatar: 'ธ'
            },
            {
                id: 8,
                name: 'นาง สุนีย์ แข็งแรง',
                code: 'OP-00130',
                status: 'พร้อมปฏิบัติงาน',
                org: 'กู้ภัยลาดกระบัง',
                zone: 'เขตลาดกระบัง',
                tel: '083-111-2222',
                avatar: 'ส'
            },
            {
                id: 9,
                name: 'นาย ประเสริฐ ดีใจ',
                code: 'OP-00131',
                status: 'กำลังปฏิบัติหน้าที่',
                org: 'กู้ภัยบางนา',
                zone: 'เขตบางนา',
                tel: '086-333-4444',
                avatar: 'ป'
            },
            {
                id: 10,
                name: 'น.ส. รัตนา สุขใจ',
                code: 'RN-00090',
                status: 'พัก/ออกเวร',
                org: 'รพ.ภูมิพล',
                zone: 'เขตสายไหม',
                tel: '02-534-7000',
                avatar: 'ร'
            },
        ];
        let nextId = 11,
            filteredData = [...staff],
            currentPage = 1;
        const pageSize = 7;
        let sortField = null,
            sortDir = 1,
            deleteTargetId = null;
        let selectedIds = new Set();

        const avatarColors = {
            'ว': 'bg-blue-100 text-blue-700',
            'ส': 'bg-orange-100 text-orange-700',
            'ป': 'bg-teal-100 text-teal-700',
            'ก': 'bg-yellow-100 text-yellow-800',
            'จ': 'bg-purple-100 text-purple-700',
            'ธ': 'bg-pink-100 text-pink-700',
            'ร': 'bg-rose-100 text-rose-700'
        };

        function ac(a) {
            return avatarColors[a] || 'bg-indigo-100 text-indigo-700';
        }
        const SC = {
            'พร้อมปฏิบัติงาน': {
                dot: 'bg-green-500',
                badge: 'bg-green-50 text-green-700 border-green-200'
            },
            'กำลังปฏิบัติหน้าที่': {
                dot: 'bg-red-500',
                badge: 'bg-red-50 text-red-700 border-red-200'
            },
            'พัก/ออกเวร': {
                dot: 'bg-yellow-500',
                badge: 'bg-yellow-50 text-yellow-700 border-yellow-200'
            },
            'ออฟไลน์': {
                dot: 'bg-gray-500',
                badge: 'bg-gray-100 text-gray-700 border-gray-200'
            }
        };

        function init() {
            populateDDs();
            applyFilters();
        }

        function populateDDs() {
            const zones = [...new Set(staff.map(s => s.zone))].sort();
            const orgs = [...new Set(staff.map(s => s.org))].sort();
            const zs = document.getElementById('filterZone'),
                os = document.getElementById('filterOrg');
            const zv = zs.value,
                ov = os.value;
            zs.innerHTML = '<option value="">ทั้งหมด</option>';
            os.innerHTML = '<option value="">ทั้งหมด</option>';
            zones.forEach(z => {
                let o = document.createElement('option');
                o.value = z;
                o.textContent = z;
                zs.appendChild(o);
            });
            orgs.forEach(o => {
                let op = document.createElement('option');
                op.value = o;
                op.textContent = o;
                os.appendChild(op);
            });
            zs.value = zv;
            os.value = ov;
        }

        function applyFilters() {
            const q = document.getElementById('searchInput').value.trim().toLowerCase();
            const st = document.getElementById('filterStatus').value;
            const zo = document.getElementById('filterZone').value;
            const or = document.getElementById('filterOrg').value;
            filteredData = staff.filter(s => {
                const mq = !q || s.name.toLowerCase().includes(q) || s.zone.toLowerCase().includes(q) || s.org.toLowerCase().includes(q) || s.code.toLowerCase().includes(q);
                return mq && (!st || s.status === st) && (!zo || s.zone === zo) && (!or || s.org === or);
            });
            if (sortField) filteredData.sort((a, b) => (a[sortField] || '').localeCompare(b[sortField] || '', 'th') * sortDir);
            const hasF = st || zo || or || q;
            const fb = document.getElementById('filterBadge');
            fb.style.display = hasF ? 'inline-flex' : 'none';
            selectedIds.clear();
            currentPage = 1;
            render();
        }

        function sortBy(f) {
            if (sortField === f) sortDir *= -1;
            else {
                sortField = f;
                sortDir = 1;
            }
            ['name', 'status', 'zone'].forEach(x => {
                const el = document.getElementById('sort-' + x);
                if (!el) return;
                if (x === f) {
                    el.textContent = sortDir === 1 ? 'arrow_upward' : 'arrow_downward';
                    el.classList.remove('opacity-40');
                } else {
                    el.textContent = 'unfold_more';
                    el.classList.add('opacity-40');
                }
            });
            applyFilters();
        }

        function clearFilters() {
            ['filterStatus', 'filterZone', 'filterOrg'].forEach(id => document.getElementById(id).value = '');
            document.getElementById('searchInput').value = '';
            applyFilters();
        }

        function toggleFilter() {
            document.getElementById('filterPanel').classList.toggle('open');
        }

        function render() {
            const total = filteredData.length;
            const totalP = Math.max(1, Math.ceil(total / pageSize));
            currentPage = Math.min(currentPage, totalP);
            const s0 = (currentPage - 1) * pageSize,
                e0 = Math.min(s0 + pageSize, total);
            const page = filteredData.slice(s0, e0);
            const tb = document.getElementById('tableBody');
            tb.innerHTML = '';
            document.getElementById('emptyState').classList.toggle('hidden', page.length > 0);
            page.forEach(s => {
                const cfg = SC[s.status] || SC['ออฟไลน์'];
                const chk = selectedIds.has(s.id) ? 'checked' : '';
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-primary-light/30 transition-colors fade-in';
                tr.innerHTML = `
      <td class="px-5 py-2.5"><input type="checkbox" data-id="${s.id}" ${chk} onchange="toggleSelect(this)" class="rounded border-gray-300 cursor-pointer"/></td>
      <td class="px-5 py-2.5">
        <div class="flex items-center gap-3">
          ${s.avatar==='photo'
            ?`<div class="w-8 h-8 rounded-full bg-gray-200 bg-cover bg-center flex-shrink-0" style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuAlKiXG7HUhmG8TOCVWJnPrU7zlamCokzfFQOF8mo9Y2N20VC1nfyOdrAhO_C3y1Gw-nRfuaVKOFiLNTOeTkbNSYL38l_jBlxks6ZxhC9qAutLpcRFKpmOB9z7n3bCOzfRkIsPViHyCOHaRA30BqNuceO8FGeRHT2R0JCqQAuAP2wnt8O-OtHvF9eu2hGDtSTRPsfG3FUm_ro4AT5QwbS4Irh9_nfObnrFJFsB42qLcexDIyn4WWmq4BgeiWQMLhY_DDSewXQ46lz9b')"></div>`
            :`<div class="w-8 h-8 rounded-full ${ac(s.avatar)} flex items-center justify-center font-bold text-xs flex-shrink-0">${s.avatar}</div>`}
          <div>
            <div class="font-bold text-text-main leading-tight">${s.name}</div>
            <div class="text-[11px] text-text-sub mt-0.5">รหัส: ${s.code}</div>
          </div>
        </div>
      </td>
      <td class="px-5 py-2.5">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium ${cfg.badge} border whitespace-nowrap">
          <span class="w-1.5 h-1.5 ${cfg.dot} rounded-full mr-1.5"></span>${s.status}
        </span>
      </td>
      <td class="px-5 py-2.5 text-text-main">${s.org}</td>
      <td class="px-5 py-2.5 text-text-sub">${s.zone}</td>
      <td class="px-5 py-2.5 text-text-sub text-xs">${s.tel}</td>
      <td class="px-5 py-2.5 text-right relative">
        <button onclick="toggleDD(event,${s.id})" class="text-gray-400 hover:text-primary transition-colors">
          <span class="material-symbols-outlined text-lg">more_vert</span>
        </button>
        <div id="dd-${s.id}" class="dropdown-menu absolute right-8 top-1/2 -translate-y-1/2 z-20 bg-white border border-border-color rounded-xl shadow-xl w-44 py-1 text-sm">
          <button onclick="openEditModal(${s.id})" class="flex items-center gap-2 w-full px-4 py-2 hover:bg-primary-light/40 text-text-main">
            <span class="material-symbols-outlined text-base text-primary">edit</span> แก้ไขข้อมูล
          </button>
          <button onclick="cycleStatus(${s.id})" class="flex items-center gap-2 w-full px-4 py-2 hover:bg-primary-light/40 text-text-main">
            <span class="material-symbols-outlined text-base text-yellow-600">swap_horiz</span> เปลี่ยนสถานะ
          </button>
          <hr class="my-1 border-border-color"/>
          <button onclick="openDeleteModal(${s.id})" class="flex items-center gap-2 w-full px-4 py-2 hover:bg-red-50 text-red-600">
            <span class="material-symbols-outlined text-base">delete</span> ลบเจ้าหน้าที่
          </button>
        </div>
      </td>`;
                tb.appendChild(tr);
            });

            // pagination
            document.getElementById('paginationInfo').innerHTML = total === 0 ? 'ไม่พบรายการ' :
                `แสดง <span class="font-medium text-text-main">${s0+1}</span> ถึง <span class="font-medium text-text-main">${e0}</span> จากทั้งหมด <span class="font-medium text-text-main">${total}</span> รายการ`;
            const pn = document.getElementById('pageNumbers');
            pn.innerHTML = '';
            getRange(currentPage, totalP).forEach(p => {
                if (p === '...') {
                    const sp = document.createElement('span');
                    sp.className = 'px-2 py-1 text-text-sub text-xs';
                    sp.textContent = '...';
                    pn.appendChild(sp);
                } else {
                    const btn = document.createElement('button');
                    btn.className = p === currentPage ? 'px-2.5 py-1 border border-primary bg-primary text-white rounded text-xs shadow-sm' : 'px-2.5 py-1 border border-border-color bg-white text-text-main rounded text-xs hover:bg-gray-50 shadow-sm';
                    btn.textContent = p;
                    btn.onclick = () => changePage(p);
                    pn.appendChild(btn);
                }
            });
            document.getElementById('prevBtn').disabled = currentPage <= 1;
            document.getElementById('nextBtn').disabled = currentPage >= totalP;

            const allOnPage = page.every(s => selectedIds.has(s.id));
            const sa = document.getElementById('selectAll');
            sa.checked = page.length > 0 && allOnPage;
            sa.indeterminate = !allOnPage && page.some(s => selectedIds.has(s.id));
            const si = document.getElementById('selectedInfo');
            si.style.display = selectedIds.size > 0 ? 'block' : 'none';
            if (selectedIds.size > 0) document.getElementById('selectedCount').textContent = selectedIds.size;
        }

        function getRange(c, t) {
            if (t <= 7) return Array.from({
                length: t
            }, (_, i) => i + 1);
            if (c <= 4) return [1, 2, 3, 4, 5, '...', t];
            if (c >= t - 3) return [1, '...', t - 4, t - 3, t - 2, t - 1, t];
            return [1, '...', c - 1, c, c + 1, '...', t];
        }

        function changePage(p) {
            const t = Math.ceil(filteredData.length / pageSize);
            if (p < 1 || p > t) return;
            currentPage = p;
            render();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function toggleSelect(cb) {
            const id = parseInt(cb.dataset.id);
            if (cb.checked) selectedIds.add(id);
            else selectedIds.delete(id);
            render();
        }

        function toggleSelectAll(cb) {
            const s0 = (currentPage - 1) * pageSize;
            const page = filteredData.slice(s0, s0 + pageSize);
            page.forEach(s => cb.checked ? selectedIds.add(s.id) : selectedIds.delete(s.id));
            render();
        }

        function deleteSelected() {
            const n = selectedIds.size;
            staff = staff.filter(s => !selectedIds.has(s.id));
            selectedIds.clear();
            populateDDs();
            applyFilters();
            showToast(`ลบ ${n} รายการเรียบร้อย`, 'delete', 'red');
        }

        function toggleDD(e, id) {
            e.stopPropagation();
            document.querySelectorAll('.dropdown-menu').forEach(d => {
                if (d.id !== 'dd-' + id) d.classList.remove('open');
            });
            document.getElementById('dd-' + id).classList.toggle('open');
        }
        document.addEventListener('click', () => document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('open')));

        function openAddModal() {
            ['add-name', 'add-code', 'add-org', 'add-zone', 'add-tel'].forEach(id => document.getElementById(id).value = '');
            document.getElementById('add-status').value = 'พร้อมปฏิบัติงาน';
            document.getElementById('addError').classList.add('hidden');
            document.getElementById('addModal').classList.add('open');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('open');
        }

        function submitAdd() {
            const name = document.getElementById('add-name').value.trim();
            const code = document.getElementById('add-code').value.trim();
            const org = document.getElementById('add-org').value.trim();
            const zone = document.getElementById('add-zone').value.trim();
            const tel = document.getElementById('add-tel').value.trim();
            if (!name || !code || !org || !zone || !tel) {
                document.getElementById('addError').classList.remove('hidden');
                return;
            }
            const av = [...name].find(c => /[\u0E00-\u0E7F]/.test(c)) || '?';
            staff.unshift({
                id: nextId++,
                name,
                code,
                status: document.getElementById('add-status').value,
                org,
                zone,
                tel,
                avatar: av
            });
            closeAddModal();
            populateDDs();
            applyFilters();
            showToast(`เพิ่ม ${name} เรียบร้อย`, 'check_circle', 'green');
        }

        function openEditModal(id) {
            document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('open'));
            const s = staff.find(x => x.id === id);
            if (!s) return;
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = s.name;
            document.getElementById('edit-code').value = s.code;
            document.getElementById('edit-status').value = s.status;
            document.getElementById('edit-org').value = s.org;
            document.getElementById('edit-zone').value = s.zone;
            document.getElementById('edit-tel').value = s.tel;
            document.getElementById('editModal').classList.add('open');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('open');
        }

        function submitEdit() {
            const id = parseInt(document.getElementById('edit-id').value);
            const name = document.getElementById('edit-name').value.trim();
            const s = staff.find(x => x.id === id);
            if (!s || !name) return;
            s.name = name;
            s.status = document.getElementById('edit-status').value;
            s.org = document.getElementById('edit-org').value.trim() || s.org;
            s.zone = document.getElementById('edit-zone').value.trim() || s.zone;
            s.tel = document.getElementById('edit-tel').value.trim() || s.tel;
            if (s.avatar !== 'photo') {
                const av = [...name].find(c => /[\u0E00-\u0E7F]/.test(c)) || s.avatar;
                s.avatar = av;
            }
            closeEditModal();
            populateDDs();
            applyFilters();
            showToast(`อัปเดต ${name} เรียบร้อย`, 'check_circle', 'green');
        }

        function openDeleteModal(id) {
            document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('open'));
            deleteTargetId = id;
            const s = staff.find(x => x.id === id);
            document.getElementById('deleteTargetName').textContent = s ? s.name : '';
            document.getElementById('deleteModal').classList.add('open');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
            deleteTargetId = null;
        }

        function confirmDelete() {
            const s = staff.find(x => x.id === deleteTargetId);
            const name = s ? s.name : '';
            staff = staff.filter(x => x.id !== deleteTargetId);
            selectedIds.delete(deleteTargetId);
            closeDeleteModal();
            populateDDs();
            applyFilters();
            showToast(`ลบ ${name} เรียบร้อย`, 'delete', 'red');
        }

        const statusCycle = ['พร้อมปฏิบัติงาน', 'กำลังปฏิบัติหน้าที่', 'พัก/ออกเวร', 'ออฟไลน์'];

        function cycleStatus(id) {
            document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('open'));
            const s = staff.find(x => x.id === id);
            if (!s) return;
            const i = statusCycle.indexOf(s.status);
            s.status = statusCycle[(i + 1) % statusCycle.length];
            applyFilters();
            showToast(`${s.name}: ${s.status}`, 'swap_horiz', 'blue');
        }

        let toastTimer;

        function showToast(msg, icon, color) {
            clearTimeout(toastTimer);
            const t = document.getElementById('toast');
            const colorMap = {
                green: 'text-green-600',
                red: 'text-red-500',
                blue: 'text-blue-600'
            };
            document.getElementById('toastIcon').textContent = icon;
            document.getElementById('toastIcon').className = `material-symbols-outlined text-lg ${colorMap[color]||'text-gray-600'}`;
            document.getElementById('toastMsg').textContent = msg;
            t.style.opacity = '1';
            t.style.transform = 'translateY(0)';
            t.style.pointerEvents = 'auto';
            toastTimer = setTimeout(() => {
                t.style.opacity = '0';
                t.style.transform = 'translateY(16px)';
                t.style.pointerEvents = 'none';
            }, 3000);
        }

        ['addModal', 'editModal', 'deleteModal'].forEach(id => {
            document.getElementById(id).addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('open');
            });
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape')['addModal', 'editModal', 'deleteModal'].forEach(id => document.getElementById(id).classList.remove('open'));
        });

        init();
    </script>
    </div>

    @endsection