@extends('layouts.theme_user')

@section('content')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<style>
    /* เฉพาะที่ Tailwind ทำไม่ได้ */
    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1;
            transform: scale(1)
        }

        50% {
            opacity: .5;
            transform: scale(.75)
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(16px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    @keyframes toast-in {
        from {
            opacity: 0;
            transform: translateX(16px)
        }

        to {
            opacity: 1;
            transform: translateX(0)
        }
    }

    .animate-pulse-dot {
        animation: pulse-dot 1.5s infinite;
    }

    .animate-slide-up {
        animation: slide-up .2s ease;
    }

    .animate-toast-in {
        animation: toast-in .2s ease;
    }

    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    tbody tr {
        cursor: pointer;
    }

    .tl-line::after {
        content: '';
        position: absolute;
        left: 8px;
        top: 20px;
        bottom: 0;
        width: 1px;
        background: #e5e7eb;
    }
</style>
</head>
<div class="bg-gray-100 font-sans text-gray-900 min-h-[calc(100dvh-57px)] mt-[57px]">


    <!-- ═══ MAIN ═══ -->
    <main class="max-w-7xl mx-auto px-4 md:px-6 py-7 space-y-5">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900">ประวัติการปฏิบัติงาน</h1>
                <p class="text-sm text-gray-400 mt-1">ตรวจสอบและติดตามบันทึกการเข้าช่วยเหลือเหตุฉุกเฉินย้อนหลังทั้งหมดในระบบ</p>
            </div>
            <button onclick="exportCSV()" class="flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:-translate-y-px hover:shadow-md active:translate-y-0 whitespace-nowrap">
                <span class="material-icons-round text-base">download</span>ส่งออกรายงาน
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <p class="text-xs font-medium text-gray-400 mb-1.5">เหตุทั้งหมด</p>
                <p id="statTotal" class="text-2xl font-extrabold font-mono tracking-tight text-gray-900">–</p>
                <p class="text-[11px] text-gray-400 mt-0.5">รายการในระบบ</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <p class="text-xs font-medium text-gray-400 mb-1.5">เสร็จสิ้น</p>
                <p id="statDone" class="text-2xl font-extrabold font-mono tracking-tight text-emerald-500">–</p>
                <p class="text-[11px] text-gray-400 mt-0.5">ดำเนินการแล้ว</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <p class="text-xs font-medium text-gray-400 mb-1.5">กำลังดำเนินการ</p>
                <p id="statProgress" class="text-2xl font-extrabold font-mono tracking-tight text-amber-500">–</p>
                <p class="text-[11px] text-gray-400 mt-0.5">รอผลลัพธ์</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <p class="text-xs font-medium text-gray-400 mb-1.5">ยกเลิก</p>
                <p id="statCancel" class="text-2xl font-extrabold font-mono tracking-tight text-gray-400">–</p>
                <p class="text-[11px] text-gray-400 mt-0.5">ไม่ได้ดำเนินการ</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white border border-gray-200 rounded-xl p-3.5 shadow-sm flex flex-wrap gap-2.5 items-center">
            <div class="relative flex-1 min-w-52">
                <span class="material-icons-round absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg pointer-events-none">search</span>
                <input id="searchInput" type="text" placeholder="ค้นหาด้วย เลขที่เหตุ, สถานที่ หรือรายละเอียด..."
                    class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-sm placeholder-gray-400 outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 focus:bg-white transition-all" />
            </div>
            <select id="filterType" class="py-2 pl-3 pr-8 border border-gray-200 rounded-lg bg-gray-50 text-sm text-gray-700 outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 focus:bg-white transition-all cursor-pointer">
                <option value="">ประเภทเหตุทั้งหมด</option>
                <option>อุบัติเหตุรถยนต์</option>
                <option>อัคคีภัย</option>
                <option>เจ็บป่วยฉุกเฉิน</option>
                <option>อุบัติเหตุทั่วไป</option>
                <option>น้ำท่วม</option>
                <option>สารเคมี</option>
            </select>
            <select id="filterStatus" class="py-2 pl-3 pr-8 border border-gray-200 rounded-lg bg-gray-50 text-sm text-gray-700 outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 focus:bg-white transition-all cursor-pointer">
                <option value="">สถานะทั้งหมด</option>
                <option value="done">เสร็จสิ้น</option>
                <option value="progress">กำลังดำเนินการ</option>
                <option value="cancel">ยกเลิก</option>
            </select>
            <select id="filterPeriod" class="py-2 pl-3 pr-8 border border-gray-200 rounded-lg bg-gray-50 text-sm text-gray-700 outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 focus:bg-white transition-all cursor-pointer">
                <option value="">ช่วงเวลาทั้งหมด</option>
                <option value="7">7 วันล่าสุด</option>
                <option value="30">30 วันล่าสุด</option>
                <option value="90">3 เดือนล่าสุด</option>
            </select>
            <button onclick="clearFilters()" class="flex items-center gap-1.5 px-3.5 py-2 border border-gray-200 rounded-lg bg-gray-50 text-sm text-gray-500 hover:bg-white hover:text-primary hover:border-primary transition-all">
                <span class="material-icons-round text-sm">clear</span>ล้างตัวกรอง
            </button>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 bg-gray-50/60">
                <p class="text-sm text-gray-500">พบ <span id="resultCount" class="font-bold text-gray-900">–</span> รายการ</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/40">
                            <th onclick="sortTable('date')" data-col="date" class="px-5 py-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 whitespace-nowrap select-none">
                                วันที่/เวลา <span class="material-icons-round sort-icon text-sm align-middle opacity-40">unfold_more</span>
                            </th>
                            <th class="px-5 py-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">เลขที่เหตุ</th>
                            <th class="px-5 py-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">ประเภทเหตุ</th>
                            <th class="px-5 py-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider">สถานที่</th>
                            <th onclick="sortTable('response')" data-col="response" class="px-5 py-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 whitespace-nowrap select-none">
                                เวลาตอบสนอง <span class="material-icons-round sort-icon text-sm align-middle opacity-40">unfold_more</span>
                            </th>
                            <th class="px-5 py-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider">สถานะ</th>
                            <th class="px-5 py-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-right">รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-gray-100"></tbody>
                </table>
                <div id="emptyState" class="hidden text-center py-16 text-gray-400">
                    <span class="material-icons-round text-5xl opacity-30">search_off</span>
                    <p class="mt-3 font-semibold text-gray-500">ไม่พบข้อมูลที่ตรงกัน</p>
                    <p class="text-sm mt-1">ลองเปลี่ยนเงื่อนไขการค้นหาหรือตัวกรอง</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between px-5 py-3.5 border-t border-gray-100 bg-gray-50/60 gap-3">
                <p class="text-xs text-gray-400">แสดง <strong id="showFrom" class="text-gray-700">–</strong> ถึง <strong id="showTo" class="text-gray-700">–</strong> จากทั้งหมด <strong id="showTotal" class="text-gray-700">–</strong> รายการ</p>
                <div id="pageBtns" class="flex gap-1.5 items-center"></div>
            </div>
        </div>
    </main>

    <!-- ═══ MODAL ═══ -->
    <div id="modalOverlay" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4" onclick="closeModal(event)">
        <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl animate-slide-up">
            <div class="flex items-start justify-between px-6 py-5 border-b border-gray-100">
                <div>
                    <h2 id="modalTitle" class="text-base font-bold text-gray-900">–</h2>
                    <p id="modalId" class="text-xs text-gray-400 font-mono mt-0.5">–</p>
                </div>
                <button onclick="closeModalDirect()" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition-colors ml-4 flex-shrink-0">
                    <span class="material-icons-round text-lg">close</span>
                </button>
            </div>
            <div class="px-6 py-5">
                <div id="modalGrid" class="grid grid-cols-2 gap-4 mb-6"></div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">ไทม์ไลน์เหตุการณ์</p>
                <div id="modalTimeline"></div>
            </div>
            <div class="flex gap-2.5 px-6 py-4 border-t border-gray-100 bg-gray-50">
                <button onclick="closeModalDirect()" class="flex-1 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-white transition-colors">ปิด</button>
                <button onclick="printReport()" class="flex-1 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-1.5">
                    <span class="material-icons-round text-sm">print</span>พิมพ์รายงาน
                </button>
            </div>
        </div>
    </div>

    <!-- ═══ TOAST ═══ -->
    <div id="toastContainer" class="fixed bottom-6 right-6 z-[999] flex flex-col gap-2"></div>

    <script>
        const ALL_DATA = [{
                id: 'INC-2566-0248',
                date: '2566-10-24',
                time: '08:30',
                type: 'อุบัติเหตุรถยนต์',
                location: 'ถ.สุขุมวิท กม. 12 ขาออก',
                district: 'เขตคลองเตย',
                status: 'done',
                response: 8,
                victims: 3,
                notes: 'รถชนท้าย 2 คัน มีผู้บาดเจ็บเล็กน้อย 3 ราย ส่งโรงพยาบาลกรุงเทพ'
            },
            {
                id: 'INC-2566-0247',
                date: '2566-10-23',
                time: '14:15',
                type: 'อัคคีภัย',
                location: 'ซอยลาดพร้าว 101 หมู่บ้านกรีนวิลล์',
                district: 'เขตลาดพร้าว',
                status: 'done',
                response: 11,
                victims: 0,
                notes: 'ไฟไหม้ห้องครัวชั้น 2 ดับไฟได้ภายใน 45 นาที ไม่มีผู้บาดเจ็บ'
            },
            {
                id: 'INC-2566-0246',
                date: '2566-10-23',
                time: '23:45',
                type: 'เจ็บป่วยฉุกเฉิน',
                location: 'คอนโดริทึ่ม รัชดา-ห้วยขวาง',
                district: 'เขตห้วยขวาง',
                status: 'done',
                response: 6,
                victims: 1,
                notes: 'ผู้ป่วยหัวใจวาย ปฐมพยาบาลและนำส่ง รพ.รามาธิบดีทันเวลา'
            },
            {
                id: 'INC-2566-0245',
                date: '2566-10-22',
                time: '10:20',
                type: 'อุบัติเหตุทั่วไป',
                location: 'แยกอโศกมนตรี หน้าห้าง Terminal 21',
                district: 'เขตวัฒนา',
                status: 'cancel',
                response: null,
                victims: 0,
                notes: 'ผู้แจ้งโทรยกเลิกก่อนชุดปฏิบัติการถึงจุดเกิดเหตุ'
            },
            {
                id: 'INC-2566-0244',
                date: '2566-10-21',
                time: '19:05',
                type: 'อุบัติเหตุรถยนต์',
                location: 'ถ.ประดิษฐ์มนูธรรม ใกล้ CDC',
                district: 'เขตลาดพร้าว',
                status: 'done',
                response: 9,
                victims: 5,
                notes: 'รถจักรยานยนต์ชนรถยนต์ มีผู้บาดเจ็บ 5 ราย ส่งโรงพยาบาลเลิดสิน'
            },
            {
                id: 'INC-2566-0243',
                date: '2566-10-21',
                time: '07:30',
                type: 'น้ำท่วม',
                location: 'ซอยรามคำแหง 24 ชุมชนร่วมใจ',
                district: 'เขตบึงกุ่ม',
                status: 'done',
                response: 15,
                victims: 12,
                notes: 'น้ำท่วมขังระดับเข่า อพยพผู้ประสบภัย 12 ราย'
            },
            {
                id: 'INC-2566-0242',
                date: '2566-10-20',
                time: '13:00',
                type: 'สารเคมี',
                location: 'โรงงานย่านอ่อนนุช ซ.77',
                district: 'เขตประเวศ',
                status: 'done',
                response: 13,
                victims: 2,
                notes: 'สารเคมีรั่วไหลในโรงงาน พนักงานได้รับสารพิษ 2 ราย ส่ง รพ.วชิระ'
            },
            {
                id: 'INC-2566-0241',
                date: '2566-10-20',
                time: '03:10',
                type: 'อัคคีภัย',
                location: 'ตลาดนัด JJ Mall ชั้น 3',
                district: 'เขตจตุจักร',
                status: 'done',
                response: 10,
                victims: 0,
                notes: 'ไฟไหม้ร้านค้าตลาดนัด ดับได้ก่อนลุกลาม ไม่มีผู้บาดเจ็บ'
            },
            {
                id: 'INC-2566-0240',
                date: '2566-10-19',
                time: '16:45',
                type: 'เจ็บป่วยฉุกเฉิน',
                location: 'สถานีรถไฟฟ้า BTS อ่อนนุช',
                district: 'เขตประเวศ',
                status: 'done',
                response: 5,
                victims: 1,
                notes: 'ผู้โดยสารเป็นลมในสถานี ให้การช่วยเหลือเบื้องต้นและนำส่ง รพ.'
            },
            {
                id: 'INC-2566-0239',
                date: '2566-10-19',
                time: '11:20',
                type: 'อุบัติเหตุรถยนต์',
                location: 'ทางด่วนโทลล์เวย์ กม.18',
                district: 'เขตสาทร',
                status: 'progress',
                response: 7,
                victims: 4,
                notes: 'อุบัติเหตุบนทางด่วน รถพลิกคว่ำ กำลังดำเนินการ'
            },
            {
                id: 'INC-2566-0238',
                date: '2566-10-18',
                time: '22:00',
                type: 'อัคคีภัย',
                location: 'อาคารพาณิชย์ถนนสีลม',
                district: 'เขตบางรัก',
                status: 'done',
                response: 9,
                victims: 1,
                notes: 'ไฟไหม้ชั้น 4 บาดเจ็บจากควัน 1 ราย นำส่งโรงพยาบาล'
            },
            {
                id: 'INC-2566-0237',
                date: '2566-10-18',
                time: '09:15',
                type: 'น้ำท่วม',
                location: 'ชุมชนบ้านครัว ถ.พระราม 4',
                district: 'เขตปทุมวัน',
                status: 'cancel',
                response: null,
                victims: 0,
                notes: 'แจ้งผิดพลาด น้ำไม่ท่วม ยกเลิกการส่งหน่วย'
            },
            {
                id: 'INC-2566-0236',
                date: '2566-10-17',
                time: '18:30',
                type: 'อุบัติเหตุทั่วไป',
                location: 'สวนลุมพินี ทางเดินริมบึง',
                district: 'เขตปทุมวัน',
                status: 'done',
                response: 12,
                victims: 1,
                notes: 'นักท่องเที่ยวลื่นล้มกระดูกหัก นำส่ง รพ.จุฬาลงกรณ์'
            },
            {
                id: 'INC-2566-0235',
                date: '2566-10-17',
                time: '06:00',
                type: 'เจ็บป่วยฉุกเฉิน',
                location: 'หมู่บ้านจัดสรรพระราม 9 ซ.12',
                district: 'เขตห้วยขวาง',
                status: 'done',
                response: 8,
                victims: 1,
                notes: 'ผู้สูงอายุหมดสติ ให้การช่วยเหลือและนำส่ง รพ.เวชธานี'
            },
            {
                id: 'INC-2566-0234',
                date: '2566-10-16',
                time: '14:00',
                type: 'สารเคมี',
                location: 'สถาบันการศึกษา ม.เกษตร บางเขน',
                district: 'เขตบางเขน',
                status: 'progress',
                response: 20,
                victims: 5,
                notes: 'สารเคมีในห้องปฏิบัติการรั่ว กำลังดำเนินการจัดการ'
            },
            {
                id: 'INC-2566-0233',
                date: '2566-10-16',
                time: '10:45',
                type: 'อุบัติเหตุรถยนต์',
                location: 'ถ.วิภาวดีรังสิต ใกล้สนามบินดอนเมือง',
                district: 'เขตดอนเมือง',
                status: 'done',
                response: 14,
                victims: 6,
                notes: 'รถชน 3 คัน บาดเจ็บ 6 ราย ส่งโรงพยาบาลภูมิพล'
            },
        ];

        let filtered = [...ALL_DATA],
            currentPage = 1;
        const PER_PAGE = 8;
        let sortCol = 'date',
            sortDir = 'desc',
            currentRecord = null;

        document.addEventListener('DOMContentLoaded', () => {
            updateStats();
            applyFilters();
            document.getElementById('searchInput').addEventListener('input', debounce(applyFilters, 250));
            ['filterType', 'filterStatus', 'filterPeriod'].forEach(id => document.getElementById(id).addEventListener('change', applyFilters));
        });

        const debounce = (fn, ms) => {
            let t;
            return (...a) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...a), ms);
            };
        };

        function updateStats() {
            document.getElementById('statTotal').textContent = ALL_DATA.length;
            document.getElementById('statDone').textContent = ALL_DATA.filter(d => d.status === 'done').length;
            document.getElementById('statProgress').textContent = ALL_DATA.filter(d => d.status === 'progress').length;
            document.getElementById('statCancel').textContent = ALL_DATA.filter(d => d.status === 'cancel').length;
        }

        function applyFilters() {
            const search = document.getElementById('searchInput').value.toLowerCase().trim();
            const type = document.getElementById('filterType').value;
            const status = document.getElementById('filterStatus').value;
            const period = parseInt(document.getElementById('filterPeriod').value);
            const now = new Date();
            filtered = ALL_DATA.filter(d => {
                const ms = !search || [d.id, d.location, d.district, d.type, d.notes].some(v => v.toLowerCase().includes(search));
                const mt = !type || d.type === type;
                const mst = !status || d.status === status;
                let mp = !period || ((now - new Date(d.date.replace('2566', '2023'))) / 86400000) <= period;
                return ms && mt && mst && mp;
            });
            sortData();
            currentPage = 1;
            render();
        }

        function clearFilters() {
            ['searchInput', 'filterType', 'filterStatus', 'filterPeriod'].forEach(id => document.getElementById(id).value = '');
            applyFilters();
            showToast('ล้างตัวกรองทั้งหมดแล้ว');
        }

        function sortTable(col) {
            sortDir = sortCol === col ? (sortDir === 'asc' ? 'desc' : 'asc') : 'desc';
            sortCol = col;
            sortData();
            render();
            document.querySelectorAll('th[data-col]').forEach(th => {
                const i = th.querySelector('.sort-icon');
                i.textContent = 'unfold_more';
                i.className = 'material-icons-round sort-icon text-sm align-middle opacity-40';
            });
            const ai = document.querySelector(`th[data-col="${col}"] .sort-icon`);
            if (ai) {
                ai.textContent = sortDir === 'asc' ? 'keyboard_arrow_up' : 'keyboard_arrow_down';
                ai.className = 'material-icons-round sort-icon text-sm align-middle text-primary';
            }
        }

        function sortData() {
            filtered.sort((a, b) => {
                const va = sortCol === 'date' ? a.date + a.time : (a.response ?? 999);
                const vb = sortCol === 'date' ? b.date + b.time : (b.response ?? 999);
                return sortDir === 'asc' ? (va > vb ? 1 : -1) : (va < vb ? 1 : -1);
            });
        }

        function render() {
            const tbody = document.getElementById('tableBody');
            const empty = document.getElementById('emptyState');
            const total = filtered.length;
            const pages = Math.ceil(total / PER_PAGE) || 1;
            if (currentPage > pages) currentPage = pages;
            document.getElementById('resultCount').textContent = total;
            const from = total === 0 ? 0 : (currentPage - 1) * PER_PAGE + 1;
            const to = Math.min(currentPage * PER_PAGE, total);
            ['showFrom', 'showTo', 'showTotal'].forEach((id, i) => document.getElementById(id).textContent = [from, to, total][i]);
            if (total === 0) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
            } else {
                empty.classList.add('hidden');
                tbody.innerHTML = filtered.slice((currentPage - 1) * PER_PAGE, currentPage * PER_PAGE).map(renderRow).join('');
            }
            renderPagination(pages);
        }

        function renderRow(d) {
           
            const bc = 'bg-red-50 text-red-600 border-red-200' || 'bg-gray-50 text-gray-600 border-gray-200';
            const dc = 'bg-red-500' || 'bg-gray-400';
            const status = d.status === 'done' ?
                `<div class="flex items-center gap-1.5 text-emerald-600 text-xs font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>เสร็จสิ้น</div>` :
                d.status === 'progress' ?
                `<div class="flex items-center gap-1.5 text-amber-500 text-xs font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse-dot"></span>กำลังดำเนินการ</div>` :
                `<div class="flex items-center gap-1.5 text-gray-400 text-xs font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>ยกเลิก</div>`;
            const rc = d.response <= 8 ? 'text-emerald-600' : d.response <= 14 ? 'text-amber-500' : 'text-red-500';
            const resp = d.response !== null ? `<span class="font-mono text-sm font-medium ${rc}">${d.response} นาที</span>` : '<span class="text-gray-400 text-sm">–</span>';

            return `<tr class="hover:bg-gray-50/60 transition-colors" onclick="openModal('${d.id}')">
    <td class="px-5 py-4">
      <div class="text-sm font-semibold text-gray-900">${formatDate(d.date)}</div>
      <div class="text-xs text-gray-400 font-mono mt-0.5">${d.time} น.</div>
    </td>
    <td class="px-5 py-4"><div class="text-xs text-gray-400 font-mono">${d.id}</div></td>
    <td class="px-5 py-4">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border ${bc}">
        <span class="w-1.5 h-1.5 rounded-full ${dc}"></span>${d.type}
      </span>
    </td>
    <td class="px-5 py-4 max-w-xs">
      <div class="text-sm font-medium text-gray-800 truncate">${d.location}</div>
      <div class="text-xs text-gray-400 mt-0.5">${d.district}</div>
    </td>
    <td class="px-5 py-4">${resp}</td>
    <td class="px-5 py-4">${status}</td>
    <td class="px-5 py-4 text-right">
      <button onclick="event.stopPropagation();openModal('${d.id}')" class="inline-flex items-center gap-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold text-primary hover:bg-primary-bg hover:border-primary transition-all">
        ดูข้อมูล <span class="material-icons-round text-xs">arrow_forward_ios</span>
      </button>
    </td>
  </tr>`;
        }

        function renderPagination(pages) {
            const base = 'w-8 h-8 flex items-center justify-center rounded-lg border text-xs font-semibold transition-all';
            const n = `${base} border-gray-200 bg-white text-gray-600 hover:bg-gray-50`;
            const a = `${base} border-primary bg-primary text-white`;
            const dis = `${base} border-gray-100 bg-white text-gray-300 cursor-not-allowed`;
            const btns = [];
            btns.push(`<button class="${currentPage===1?dis:n}" onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}><span class="material-icons-round text-sm">chevron_left</span></button>`);
            const mb = p => `<button class="${p===currentPage?a:n}" onclick="goPage(${p})">${p}</button>`;
            if (pages <= 7) {
                for (let i = 1; i <= pages; i++) btns.push(mb(i));
            } else {
                btns.push(mb(1));
                if (currentPage > 3) btns.push('<span class="text-gray-400 text-xs px-1">...</span>');
                for (let i = Math.max(2, currentPage - 1); i <= Math.min(pages - 1, currentPage + 1); i++) btns.push(mb(i));
                if (currentPage < pages - 2) btns.push('<span class="text-gray-400 text-xs px-1">...</span>');
                btns.push(mb(pages));
            }
            btns.push(`<button class="${currentPage===pages?dis:n}" onclick="goPage(${currentPage+1})" ${currentPage===pages?'disabled':''}><span class="material-icons-round text-sm">chevron_right</span></button>`);
            document.getElementById('pageBtns').innerHTML = btns.join('');
        }

        function goPage(p) {
            const pages = Math.ceil(filtered.length / PER_PAGE) || 1;
            if (p < 1 || p > pages) return;
            currentPage = p;
            render();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function formatDate(d) {
            const m = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
            const [y, mo, day] = d.split('-');
            return `${parseInt(day)} ${m[parseInt(mo)-1]} ${y}`;
        }

        function openModal(id) {
            const d = ALL_DATA.find(x => x.id === id);
            if (!d) return;
            currentRecord = d;
            document.getElementById('modalTitle').textContent = d.type + ' — ' + d.district;
            document.getElementById('modalId').textContent = d.id;
            const sc = {
                done: 'text-emerald-600',
                progress: 'text-amber-500',
                cancel: 'text-gray-400'
            } [d.status];
            const sl = {
                done: 'เสร็จสิ้น',
                progress: 'กำลังดำเนินการ',
                cancel: 'ยกเลิก'
            } [d.status];
            const item = (k, v, full = false) => `
    <div class="${full?'col-span-2':''}">
      <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">${k}</p>
      <p class="text-sm font-medium text-gray-900">${v}</p>
    </div>`;
            document.getElementById('modalGrid').innerHTML = `
    ${item('วันที่', formatDate(d.date))}
    ${item('เวลา', d.time+' น.')}
    ${item('สถานะ', `<span class="${sc} font-bold">${sl}</span>`)}
    ${item('เวลาตอบสนอง', d.response !== null ? d.response+' นาที' : '–')}
    ${item('สถานที่เกิดเหตุ', d.location, true)}
    ${item('ผู้ประสบภัย/บาดเจ็บ', d.victims > 0 ? d.victims+' ราย' : 'ไม่มี')}
    ${item('บันทึก', d.notes, true)}`;
            const [h, m] = d.time.split(':').map(Number);
            const addMin = n => {
                const nm = (m + n) % 60,
                    nh = (h + Math.floor((m + n) / 60)) % 24;
                return `${String(nh).padStart(2,'0')}:${String(nm).padStart(2,'0')} น.`;
            };
            const steps = [{
                    label: 'รับแจ้งเหตุ',
                    time: d.time + ' น.',
                    done: true
                },
                {
                    label: 'ส่งหน่วยปฏิบัติการ',
                    time: addMin(2),
                    done: d.status !== 'cancel'
                },
                {
                    label: 'ถึงจุดเกิดเหตุ',
                    time: d.response ? addMin(d.response) : '–',
                    done: d.status !== 'cancel' && d.response !== null
                },
                {
                    label: 'ดำเนินการช่วยเหลือ',
                    time: d.response ? addMin(d.response + 10) : '–',
                    done: d.status === 'done'
                },
                {
                    label: 'ปิดเหตุการณ์',
                    time: d.response ? addMin(d.response + 40) : '–',
                    done: d.status === 'done'
                },
            ];
            document.getElementById('modalTimeline').innerHTML = steps.map((s, i) => `
    <div class="flex gap-3 pb-4 relative ${i<steps.length-1?'tl-line':''}">
      <div class="w-[17px] h-[17px] rounded-full flex-shrink-0 mt-0.5 border-2 ${s.done?'bg-emerald-500 border-emerald-100':'bg-gray-200 border-gray-100'}"></div>
      <div>
        <p class="text-sm font-semibold text-gray-800">${s.label}</p>
        <p class="text-xs text-gray-400 font-mono mt-0.5">${s.time}</p>
      </div>
    </div>`).join('');
            document.getElementById('modalOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(e) {
            if (e.target === e.currentTarget) closeModalDirect();
        }

        function closeModalDirect() {
            document.getElementById('modalOverlay').classList.add('hidden');
            document.body.style.overflow = '';
            currentRecord = null;
        }

        function printReport() {
            showToast('กำลังเตรียมรายงานสำหรับพิมพ์...');
            setTimeout(() => window.print(), 500);
        }

        function exportCSV() {
            const headers = ['เลขที่เหตุ', 'วันที่', 'เวลา', 'ประเภทเหตุ', 'สถานที่', 'เขต', 'สถานะ', 'เวลาตอบสนอง(นาที)', 'ผู้บาดเจ็บ', 'บันทึก'];
            const rows = filtered.map(d => [d.id, d.date, d.time, d.type, `"${d.location}"`, d.district, {
                done: 'เสร็จสิ้น',
                progress: 'กำลังดำเนินการ',
                cancel: 'ยกเลิก'
            } [d.status], d.response ?? '', d.victims, `"${d.notes}"`].join(','));
            const a = Object.assign(document.createElement('a'), {
                href: URL.createObjectURL(new Blob(['\uFEFF' + [headers.join(','), ...rows].join('\n')], {
                    type: 'text/csv;charset=utf-8;'
                })),
                download: `ems-history-${new Date().toISOString().slice(0,10)}.csv`
            });
            a.click();
            showToast(`ส่งออกข้อมูล ${filtered.length} รายการสำเร็จ`);
        }

        function showToast(msg, dur = 3000) {
            const t = document.createElement('div');
            t.className = 'flex items-center gap-2 bg-gray-900 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-xl animate-toast-in';
            t.innerHTML = `<span class="material-icons-round text-base text-primary">check_circle</span>${msg}`;
            document.getElementById('toastContainer').appendChild(t);
            setTimeout(() => {
                t.style.cssText = 'opacity:0;transition:opacity .2s';
                setTimeout(() => t.remove(), 200);
            }, dur);
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModalDirect();
        });
    </script>
    </body>
    @endsection