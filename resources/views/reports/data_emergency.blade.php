@extends('layouts.theme')

@section('content')
<!-- Material Icons & Google Fonts -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<style>
    /* Custom Animations & Styles */
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1) }
        50% { opacity: .5; transform: scale(.75) }
    }
    .animate-pulse-dot { animation: pulse-dot 1.5s infinite; }
    
    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    tbody tr { cursor: pointer; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="bg-gray-100 font-sans text-gray-900 min-h-[calc(100dvh-57px)] mt-[57px]">

    <!-- ═══ MAIN CONTENT ═══ -->
    <main class="max-w-7xl mx-auto px-4 md:px-6 py-7 space-y-5">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900">ประวัติการปฏิบัติงาน</h1>
                <p class="text-sm text-gray-400 mt-1">ตรวจสอบและติดตามบันทึกการเข้าช่วยเหลือเหตุฉุกเฉินทั้งหมดในระบบ</p>
            </div>
            <button onclick="exportCSV()" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm">
                <span class="material-icons-round text-base">download</span>ส่งออกรายงาน (CSV)
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <p class="text-xs font-medium text-gray-400 mb-1">เหตุทั้งหมด</p>
                <p id="statTotal" class="text-2xl font-extrabold font-mono text-gray-900">–</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm border-l-4 border-l-emerald-500">
                <p class="text-xs font-medium text-gray-400 mb-1 text-emerald-600">เสร็จสิ้นแล้ว</p>
                <p id="statDone" class="text-2xl font-extrabold font-mono text-emerald-500">–</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm border-l-4 border-l-amber-500">
                <p class="text-xs font-medium text-gray-400 mb-1 text-amber-600">อยู่ระหว่างดำเนินการ</p>
                <p id="statProgress" class="text-2xl font-extrabold font-mono text-amber-500">–</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white border border-gray-200 rounded-xl p-3.5 shadow-sm flex flex-wrap gap-3 items-center">
            <!-- Search Input -->
            <div class="relative flex-1 min-w-[250px]">
                <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                <input id="searchInput" type="text" placeholder="ค้นหา เลขที่เหตุ, สถานที่, เจ้าหน้าที่..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/10 outline-none transition-all" />
            </div>

            <!-- ประเภทเหตุ (ดึงจาก DB) -->
            <select id="filterType" class="py-2 pl-3 pr-9 border border-gray-200 rounded-lg bg-gray-50 text-sm outline-none focus:bg-white">
                <option value="">ประเภทเหตุทั้งหมด</option>
                @foreach($emergencyTypes as $type)
                    <option value="{{ $type->name_emergency }}">{{ $type->name_emergency }}</option>
                @endforeach
            </select>

            <!-- สถานะ (ตามที่คุณระบุ) -->
            <select id="filterStatus" class="py-2 pl-3 pr-9 border border-gray-200 rounded-lg bg-gray-50 text-sm outline-none focus:bg-white">
                <option value="">สถานะทั้งหมด</option>
                <option value="รับแจ้งเหตุ">รับแจ้งเหตุ</option>
                <option value="สั่งการ">สั่งการ</option>
                <option value="กำลังไปช่วยเหลือ">กำลังไปช่วยเหลือ</option>
                <option value="ถึงที่เกิดเหตุ">ถึงที่เกิดเหตุ</option>
                <option value="เสร็จสิ้น">เสร็จสิ้น</option>
            </select>

            <button onclick="clearFilters()" class="flex items-center gap-1.5 px-4 py-2 text-sm text-gray-500 hover:text-blue-600 transition-colors">
                <span class="material-icons-round text-sm">clear</span>ล้างตัวกรอง
            </button>
        </div>

        <!-- Data Table Container -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <p class="text-sm text-gray-500">พบ <span id="resultCount" class="font-bold text-gray-900">–</span> รายการ</p>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">วันที่ / เวลา</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">เลขที่เหตุ</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">ประเภทเหตุ</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">สถานที่ / เจ้าหน้าที่</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">ตอบสนอง / รวม</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">สถานะ</th>
                            <th class="px-5 py-4 text-right"></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-gray-100">
                        <!-- Data rows injected by JS -->
                    </tbody>
                </table>

                <!-- Empty State -->
                <div id="emptyState" class="hidden text-center py-20 text-gray-400">
                    <span class="material-icons-round text-5xl opacity-20">search_off</span>
                    <p class="mt-2 font-medium">ไม่พบข้อมูลที่ค้นหา</p>
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-400">แสดง <span id="showFrom">0</span> ถึง <span id="showTo">0</span> จาก <span id="showTotal">0</span> รายการ</p>
                <div id="pageBtns" class="flex gap-1.5 items-center"></div>
            </div>
        </div>
    </main>

    <!-- Toast Notification -->
    <div id="toastContainer" class="fixed bottom-6 right-6 z-[999] flex flex-col gap-2"></div>

</div>

<script>
    // ดึงข้อมูล JSON จาก Controller
    const ALL_DATA = {!! $reportsJson !!};
    
    let filtered = [...ALL_DATA];
    let currentPage = 1;
    const PER_PAGE = 10;
    
    document.addEventListener('DOMContentLoaded', () => {
        updateStats();
        applyFilters();

        // Listeners
        document.getElementById('searchInput').addEventListener('input', debounce(applyFilters, 300));
        document.getElementById('filterType').addEventListener('change', applyFilters);
        document.getElementById('filterStatus').addEventListener('change', applyFilters);
    });

    function updateStats() {
        document.getElementById('statTotal').textContent = ALL_DATA.length.toLocaleString();
        document.getElementById('statDone').textContent = ALL_DATA.filter(d => d.status === 'done').length.toLocaleString();
        document.getElementById('statProgress').textContent = ALL_DATA.filter(d => d.status === 'progress').length.toLocaleString();
    }

    function applyFilters() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const type = document.getElementById('filterType').value;
        const status = document.getElementById('filterStatus').value;

        filtered = ALL_DATA.filter(d => {
            const matchSearch = !search || 
                d.id_display?.toLowerCase().includes(search) || 
                d.location?.toLowerCase().includes(search) || 
                d.officer?.toLowerCase().includes(search) ||
                d.notes?.toLowerCase().includes(search);
                
            const matchType = !type || d.type === type;
            const matchStatus = !status || d.raw_status === status;

            return matchSearch && matchType && matchStatus;
        });

        currentPage = 1;
        render();
    }

    function render() {
        const tbody = document.getElementById('tableBody');
        const empty = document.getElementById('emptyState');
        const total = filtered.length;
        const pages = Math.ceil(total / PER_PAGE) || 1;

        document.getElementById('resultCount').textContent = total.toLocaleString();
        
        const from = total === 0 ? 0 : (currentPage - 1) * PER_PAGE + 1;
        const to = Math.min(currentPage * PER_PAGE, total);
        
        document.getElementById('showFrom').textContent = from;
        document.getElementById('showTo').textContent = to;
        document.getElementById('showTotal').textContent = total;

        if (total === 0) {
            tbody.innerHTML = '';
            empty.classList.remove('hidden');
        } else {
            empty.classList.add('hidden');
            const slice = filtered.slice((currentPage - 1) * PER_PAGE, currentPage * PER_PAGE);
            tbody.innerHTML = slice.map(renderRow).join('');
        }
        renderPagination(pages);
    }

    function renderRow(d) {
        // กำหนดสี Badge ตามสถานะจริง
        let statusBadge = '';
        if(d.raw_status === 'เสร็จสิ้น') {
            statusBadge = `<div class="flex items-center gap-1.5 text-emerald-600 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>${d.raw_status}</div>`;
        } else {

            statusBadge = `<div class="flex items-center gap-1.5 text-amber-500 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse-dot"></span>${d.raw_status}</div>`;
        }

        const respColor = d.response <= 8 ? 'text-emerald-600' : (d.response <= 15 ? 'text-amber-500' : 'text-red-500');
        const responseText = d.response ? `<span class="font-bold ${respColor}">${d.response} นาที</span>` : '<span class="text-gray-300">-</span>';
        const targetUrl = `{{ url('/case_assign') }}/${d.id_real}`;

        return `
            <tr class="hover:bg-gray-50 transition-all group" onclick="window.location.href='${targetUrl}'">
                <td class="px-5 py-4">
                    <div class="text-sm font-bold text-gray-900">${formatDateTh(d.date)}</div>
                    <div class="text-xs text-gray-400 font-mono mt-0.5">${d.time} น.</div>
                </td>
                <td class="px-5 py-4">
                    <div class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded inline-block">${d.id_display ?? 'N/A'}</div>
                </td>
                <td class="px-5 py-4">
                    <span class="text-xs font-semibold px-2 py-1 rounded-md bg-gray-100 text-gray-700 border border-gray-200">${d.type}</span>
                </td>
                <td class="px-5 py-4 max-w-[280px]">
                    <div class="text-sm font-medium text-gray-800 truncate" title="${d.location}">${d.location}</div>
                    <div class="flex items-center gap-1 text-[11px] text-gray-400 mt-1">
                        <span class="material-icons-round text-[14px]">person_outline</span>
                        <span>จนท: ${d.officer}</span>
                    </div>
                </td>
                <td class="px-5 py-4">
                    <div class="text-xs">${responseText}</div>
                    <div class="text-[10px] text-gray-400 mt-0.5">รวม: ${d.sum_time}</div>
                </td>
                <td class="px-5 py-4">${statusBadge}</td>
                <td class="px-5 py-4 text-right">
                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-300 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <span class="material-icons-round text-sm">arrow_forward</span>
                    </div>
                </td>
            </tr>
        `;
    }

    function renderPagination(pages) {
        const btns = document.getElementById('pageBtns');
        let html = '';
        
        const btnClass = "w-8 h-8 flex items-center justify-center rounded-lg border text-xs font-semibold transition-all";
        const activeClass = "bg-blue-600 border-blue-600 text-white";
        const inactiveClass = "bg-white border-gray-200 text-gray-600 hover:bg-gray-50";

        // Prev
        html += `<button onclick="goPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="${btnClass} ${currentPage === 1 ? 'opacity-30 cursor-not-allowed' : inactiveClass}"><span class="material-icons-round text-sm">chevron_left</span></button>`;

        // Page Numbers (Simple)
        for (let i = 1; i <= pages; i++) {
            if (i === 1 || i === pages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                html += `<button onclick="goPage(${i})" class="${btnClass} ${i === currentPage ? activeClass : inactiveClass}">${i}</button>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                html += `<span class="text-gray-400 text-xs">...</span>`;
            }
        }

        // Next
        html += `<button onclick="goPage(${currentPage + 1})" ${currentPage === pages ? 'disabled' : ''} class="${btnClass} ${currentPage === pages ? 'opacity-30 cursor-not-allowed' : inactiveClass}"><span class="material-icons-round text-sm">chevron_right</span></button>`;

        btns.innerHTML = html;
    }

    function goPage(p) {
        const pages = Math.ceil(filtered.length / PER_PAGE) || 1;
        if (p < 1 || p > pages) return;
        currentPage = p;
        render();
    }

    function formatDateTh(d) {
        const date = new Date(d);
        const months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear() + 543}`;
    }

    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterType').value = '';
        document.getElementById('filterStatus').value = '';
        applyFilters();
        showToast('ล้างตัวกรองแล้ว');
    }

    function debounce(fn, ms) {
        let t;
        return (...a) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...a), ms);
        };
    }

    function showToast(msg) {
        const t = document.createElement('div');
        t.className = 'flex items-center gap-2 bg-gray-900 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-xl animate-toast-in';
        t.innerHTML = `<span class="material-icons-round text-base text-blue-400">check_circle</span>${msg}`;
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transition = 'opacity 0.5s';
            setTimeout(() => t.remove(), 500);
        }, 3000);
    }

    function exportCSV() {
        if (filtered.length === 0) {
            showToast('ไม่มีข้อมูลในเงื่อนไขที่เลือก');
            return;
        }

        // 1. กำหนดโครงสร้าง Mapping (Key ใน DB => ชื่อภาษาไทย)
        // คอลัมน์ไหนไม่มีในนี้ และอยู่ในรายการ "ตัดออก" จะไม่ถูกนำมาแสดง
        const columnMapping = {
            // ตาราง emergencys
            'name_reporter': 'ชื่อผู้แจ้ง',
            'type_reporter': 'ประเภทผู้แจ้ง',
            'phone_reporter': 'เบอร์โทรศัพท์ผู้แจ้ง',
            'emergency_type': 'ประเภทเหตุ',
            'emergency_detail': 'รายละเอียดเหตุ',
            'emergency_lat': 'ละติจูด',
            'emergency_lng': 'ลองจิจูด',
            'emergency_location': 'สถานที่เกิดเหตุ',
            'score_impression': 'คะแนนความพึงพอใจ',
            'score_period': 'คะแนนระยะเวลา',
            'score_total': 'คะแนนรวม',
            'comment_help': 'ความคิดเห็นจากผู้แจ้ง',

            // ตาราง emergency_operations (เฉพาะตัวที่ต้องการ)
            'operating_code': 'เลขที่ปฏิบัติการ',
            'name_command': 'ผู้สั่งการ (Command)',
            'status': 'สถานะปัจจุบัน',
            'remark_status': 'หมายเหตุสถานะ',
            'name_area': 'พื้นที่รับผิดชอบ (Area)',
            'name_officer': 'เจ้าหน้าที่ปฏิบัติงาน',
            'time_create_sos': 'เวลาที่ได้รับแจ้ง',
            'time_command': 'เวลาที่สั่งการ',
            'time_go_to_help': 'เวลาที่กำลังไปช่วยเหลือ',
            'time_to_the_scene': 'เวลาที่ถึงที่เกิดเหตุ',
            'time_sos_success': 'เวลาที่เสร็จสิ้น',
            'time_sum_sos': 'ระยะเวลารวม',
            'calculated_response': 'เวลาตอบสนอง (นาที)',
            'remark_by_helper': 'หมายเหตุจากเจ้าหน้าที่'
        };

        // 2. สร้างหัวตารางภาษาไทย
        const headers = Object.values(columnMapping);
        const dataKeys = Object.keys(columnMapping);

        // 3. จัดการข้อมูลแต่ละแถว
        const csvRows = filtered.map(row => {
            // รวมข้อมูลทั้งหมดเข้าด้วยกันเพื่อให้ดึงง่าย
            const combinedData = {
                ...row.full_emergency,
                ...row.full_operation,
                ...row.extra_names
            };

            return dataKeys.map(key => {
                let val = combinedData[key];
                
                // จัดการค่า null / undefined
                if (val === null || val === undefined) val = '';
                
                // ล้างเครื่องหมายคำพูด
                const escaped = String(val).replace(/"/g, '""');
                return `"${escaped}"`;
            }).join(',');
        });

        // 4. กระบวนการดาวน์โหลด (ใส่ BOM สำหรับ Excel ภาษาไทย)
        const bom = "\uFEFF";
        const csvContent = bom + headers.join(',') + '\n' + csvRows.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.setAttribute("href", url);
        link.setAttribute("download", `รายงานฉบับเต็ม_${new Date().getTime()}.csv`);
        link.click();
        
        showToast(`ส่งออก ${filtered.length} รายการสำเร็จ`);
    }
</script>
@endsection