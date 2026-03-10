@extends('layouts.theme')

@section('content')

<script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "primary": "#137fec",
          "primary-dark": "#0d6bc7",
          "background-light": "#f5f3f0",
          "background-dark": "#121212",
          "surface-dark": "#1a1a1a",
          "panel-dark": "#222222",
        },
        fontFamily: {
          "display": ["IBM Plex Sans Thai", "sans-serif"],
          "mono": ["IBM Plex Mono", "monospace"],
        },
      },
    },
  }
</script>
<style>
  * { font-family: 'IBM Plex Sans Thai', sans-serif; }
  .mono { font-family: 'IBM Plex Mono', monospace; }

  /* Scrollbar */
  ::-webkit-scrollbar { width: 4px; height: 4px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: #137fec; border-radius: 2px; }

  /* Pulse animations */
  @keyframes pulse-ring {
    0% { transform: scale(0.8); opacity: 1; }
    100% { transform: scale(2); opacity: 0; }
  }
  @keyframes slide-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  @keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
  }
  @keyframes scan {
    0% { top: 0%; }
    100% { top: 100%; }
  }
  @keyframes counter-up {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .live-dot::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #22c55e;
    animation: pulse-ring 1.5s ease-out infinite;
  }

  .live-dot-red::before {
    background: #ef4444;
  }

  .alert-item {
    animation: slide-in 0.3s ease forwards;
  }

  .blink-critical {
    animation: blink 1s ease-in-out infinite;
  }

  .map-scan::after {
    content: '';
    position: absolute;
    left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #137fec, transparent);
    animation: scan 3s linear infinite;
    opacity: 0.6;
  }

  /* Tab active */
  .tab-btn.active {
    background: #137fec;
    color: white;
  }

  /* Case status colors */
  .status-new { background: #dbeafe; color: #1d4ed8; }
  .status-progress { background: #fef3c7; color: #92400e; }
  .status-done { background: #d1fae5; color: #065f46; }
  .status-critical { background: #fee2e2; color: #991b1b; }

  /* Officer status */
  .officer-available { background: #d1fae5; color: #065f46; }
  .officer-busy { background: #fef3c7; color: #92400e; }
  .officer-unavailable { background: #fee2e2; color: #991b1b; }

  /* Chart bars */
  .bar-fill {
    transition: height 1s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  /* Notification bell shake */
  @keyframes bell-shake {
    0%, 100% { transform: rotate(0deg); }
    20% { transform: rotate(-15deg); }
    40% { transform: rotate(15deg); }
    60% { transform: rotate(-10deg); }
    80% { transform: rotate(10deg); }
  }
  .bell-shake { animation: bell-shake 0.5s ease 0.2s; }

  /* Sidebar nav active */
  .nav-link.active {
    background: rgba(236,91,19,0.12);
    color: #137fec;
    border-left: 3px solid #137fec;
  }

  .modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 200;
    align-items: center;
    justify-content: center;
  }
  .modal-overlay.open { display: flex; }

  /* Map pin bounce */
  @keyframes pin-drop {
    0% { transform: translateY(-20px); opacity: 0; }
    60% { transform: translateY(3px); opacity: 1; }
    100% { transform: translateY(0); opacity: 1; }
  }
  .pin { animation: pin-drop 0.4s ease forwards; }

  /* Tooltip */
  .map-tooltip {
    display: none;
    position: absolute;
    z-index: 100;
    pointer-events: none;
  }
  .map-pin:hover .map-tooltip { display: block; }

  /* Progress bar animation */
  @keyframes fill-bar {
    from { width: 0; }
  }
  .animated-bar { animation: fill-bar 1.2s ease forwards; }

  /* Donut chart */
  .donut-ring {
    stroke-dashoffset: 0;
    transition: stroke-dashoffset 1s ease;
  }

  /* Number counter animation */
  .counter { animation: counter-up 0.5s ease forwards; }

  /* Highlight row */
  tr:hover td { background: rgba(236,91,19,0.04); }
</style>
</head>
<div class="bg-background-light font-display text-slate-900 min-h-screen">

<!-- ===== MAIN LAYOUT ===== -->
<div class="flex min-h-[calc(100vh-53px)] mt-[53px]">

  <!-- ===== SIDEBAR ===== -->
  <aside class="hidden lg:flex flex-col w-14 bg-white border-r border-slate-200 sticky top-[53px] h-[calc(100vh-53px)] items-center py-4 gap-2 z-2 pt-10">
    <button onclick="switchTab('overview')" class="nav-icon-btn active w-10 h-10 flex flex-col items-center justify-center rounded-xl hover:bg-blue-500/10 transition-colors group" title="ภาพรวม" data-tab="overview">
      <span class="material-symbols-outlined text-[20px] text-blue-600">dashboard</span>
    </button>
    <button onclick="switchTab('map')" class="nav-icon-btn w-10 h-10 flex flex-col items-center justify-center rounded-xl hover:bg-blue-500/10 transition-colors group" title="แผนที่" data-tab="map">
      <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover:text-blue-600">map</span>
    </button>
    <button onclick="switchTab('cases')" class="nav-icon-btn w-10 h-10 flex flex-col items-center justify-center rounded-xl hover:bg-blue-500/10 transition-colors group" title="รายการเคส" data-tab="cases">
      <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover:text-blue-600">list_alt</span>
    </button>
    <button onclick="switchTab('officers')" class="nav-icon-btn w-10 h-10 flex flex-col items-center justify-center rounded-xl hover:bg-blue-500/10 transition-colors group" title="เจ้าหน้าที่" data-tab="officers">
      <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover:text-blue-600">badge</span>
    </button>
    <button onclick="switchTab('analytics')" class="nav-icon-btn w-10 h-10 flex flex-col items-center justify-center rounded-xl hover:bg-blue-500/10 transition-colors group" title="วิเคราะห์" data-tab="analytics">
      <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover:text-blue-600">bar_chart</span>
    </button>
    <div class="flex-1"></div>
    <button class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-100 transition-colors" title="ตั้งค่า">
      <span class="material-symbols-outlined text-[20px] text-slate-400">settings</span>
    </button>
  </aside>

  <!-- ===== CONTENT AREA ===== -->
  <main class="flex-1 overflow-auto pt-5">

    <!-- ============ TAB: OVERVIEW ============ -->
    <div id="tab-overview" class="tab-content p-5 space-y-5">

      <!-- Section header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-bold">ภาพรวมระบบ (System Overview)</h1>
          <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
            <span class="relative inline-flex w-2 h-2"><span class="live-dot absolute w-full h-full rounded-full bg-green-500"></span><span class="relative w-2 h-2 rounded-full bg-green-500"></span></span>
            ข้อมูล Real-time · อัปเดตล่าสุด <span id="last-update" class="mono font-bold text-slate-700 ml-1">--:--:--</span>
          </p>
        </div>
        <div class="flex gap-2">
          <button class="text-xs font-bold border border-slate-200 bg-white px-3 py-1.5 rounded-lg flex items-center gap-1.5">
            <span class="material-symbols-outlined text-sm">calendar_today</span> 24 ชม.ล่าสุด
          </button>
          <button class="text-xs font-bold bg-blue-600 text-white px-3 py-1.5 rounded-lg flex items-center gap-1.5 shadow shadow-blue-500/30">
            <span class="material-symbols-outlined text-sm">download</span> ส่งออก
          </button>
        </div>
      </div>

      <!-- ===== KPI CARDS ROW ===== -->
      <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
        <!-- Total cases -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
          <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-slate-400 text-[20px]">folder_open</span>
            <span class="text-[10px] text-slate-400 font-mono uppercase">ทั้งหมด</span>
          </div>
          <div class="text-2xl font-bold mono" id="kpi-total">178</div>
          <p class="text-xs text-slate-500 mt-1">เคสวันนี้</p>
          <div class="flex items-center gap-1 mt-2 text-emerald-600 text-[10px] font-bold">
            <span class="material-symbols-outlined text-[12px]">trending_up</span>+8% vs เมื่อวาน
          </div>
        </div>

        <!-- New cases -->
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-4 shadow-sm">
          <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-blue-600 text-[20px]">fiber_new</span>
            <span class="text-[10px] text-blue-500 font-mono uppercase">ใหม่</span>
          </div>
          <div class="text-2xl font-bold mono text-blue-700" id="kpi-new">12</div>
          <p class="text-xs text-blue-600 mt-1">รอดำเนินการ</p>
          <div class="mt-2 h-1 bg-blue-100 rounded-full"><div class="animated-bar h-full bg-blue-500 rounded-full" style="width:67%"></div></div>
        </div>

        <!-- In progress -->
        <div class="bg-amber-50 rounded-xl border border-amber-200 p-4 shadow-sm">
          <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-amber-600 text-[20px]">sync</span>
            <span class="text-[10px] text-amber-500 font-mono uppercase">ดำเนินการ</span>
          </div>
          <div class="text-2xl font-bold mono text-amber-700" id="kpi-progress">5</div>
          <p class="text-xs text-amber-600 mt-1">กำลังช่วยเหลือ</p>
          <div class="mt-2 h-1 bg-amber-100 rounded-full"><div class="animated-bar h-full bg-amber-500 rounded-full" style="width:28%"></div></div>
        </div>

        <!-- Completed -->
        <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-4 shadow-sm">
          <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">task_alt</span>
            <span class="text-[10px] text-emerald-500 font-mono uppercase">เสร็จสิ้น</span>
          </div>
          <div class="text-2xl font-bold mono text-emerald-700" id="kpi-done">156</div>
          <p class="text-xs text-emerald-600 mt-1">ปิดเคสแล้ว</p>
          <div class="mt-2 h-1 bg-emerald-100 rounded-full"><div class="animated-bar h-full bg-emerald-500 rounded-full" style="width:88%"></div></div>
        </div>

        <!-- Critical -->
        <div class="bg-red-50 rounded-xl border border-red-300 p-4 shadow-sm">
          <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-red-600 text-[20px] blink-critical">warning</span>
            <span class="text-[10px] text-red-500 font-mono uppercase">เร่งด่วน</span>
          </div>
          <div class="text-2xl font-bold mono text-red-700" id="kpi-critical">2</div>
          <p class="text-xs text-red-600 mt-1">ต้องการด่วน!</p>
          <div class="mt-2 h-1 bg-red-100 rounded-full"><div class="animated-bar h-full bg-red-500 rounded-full" style="width:100%"></div></div>
        </div>

        <!-- SLA Rate -->
        <div class="bg-white rounded-xl border-l-4 border-l-emerald-500 border border-slate-200 p-4 shadow-sm">
          <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">verified</span>
            <span class="text-[10px] text-slate-400 font-mono uppercase">SLA</span>
          </div>
          <div class="text-2xl font-bold mono text-emerald-600">98.5%</div>
          <p class="text-xs text-slate-500 mt-1">อัตราผ่านเกณฑ์</p>
          <div class="flex items-center gap-1 mt-2 text-emerald-600 text-[10px] font-bold">
            <span class="material-symbols-outlined text-[12px]">check_circle</span> เกินเป้าหมาย
          </div>
        </div>
      </div>


      <!-- ===== ALERTS + LIVE CASES ===== -->
      <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        <!-- Alerts Panel -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col">
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 class="font-bold flex items-center gap-2 text-sm">
              <span class="material-symbols-outlined text-blue-600 text-[18px]">notifications_active</span>
              การแจ้งเตือน (Alerts)
            </h3>
            <span class="mono text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold blink-critical">3 ใหม่</span>
          </div>
          <div class="divide-y divide-slate-50 overflow-y-auto flex-1 max-h-72" id="alerts-panel">
            <div class="flex gap-3 p-4 bg-red-50 alert-item">
              <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-red-600 text-sm">emergency</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-red-700">🚨 เคสเร่งด่วน #EM-2024-007</p>
                <p class="text-[11px] text-slate-500">อุบัติเหตุรุนแรง ถ.สุขุมวิท ซอย 36 – ยังไม่มีเจ้าหน้าที่รับ</p>
                <div class="flex items-center gap-2 mt-1.5">
                  <span class="text-[10px] text-red-500 font-bold mono">8 นาทีที่ผ่านมา</span>
                  <button class="text-[10px] bg-red-500 text-white px-2 py-0.5 rounded font-bold">สั่งการ</button>
                </div>
              </div>
            </div>
            <div class="flex gap-3 p-4 alert-item">
              <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-blue-600 text-sm">add_alert</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold">🔔 เคสใหม่ #EM-2024-008</p>
                <p class="text-[11px] text-slate-500">ผู้ป่วยฉุกเฉิน แขวงคลองเตย</p>
                <span class="text-[10px] text-slate-400 mono">5 นาทีที่แล้ว</span>
              </div>
            </div>
            <div class="flex gap-3 p-4 bg-amber-50 alert-item">
              <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-amber-600 text-sm">schedule</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-amber-700">⏰ #EM-2024-005 รอเกิน 15 นาที</p>
                <p class="text-[11px] text-slate-500">ยังไม่ได้รับการจัดส่งเจ้าหน้าที่</p>
                <span class="text-[10px] text-amber-500 mono font-bold">12 นาทีที่แล้ว</span>
              </div>
            </div>
            <div class="flex gap-3 p-4 alert-item">
              <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-slate-500 text-sm">info</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold">#EM-2024-003 ปิดเคสสำเร็จ</p>
                <p class="text-[11px] text-slate-500">เจ้าหน้าที่: ร.ต.อ. วิชัย</p>
                <span class="text-[10px] text-slate-400 mono">18 นาทีที่แล้ว</span>
              </div>
            </div>
          </div>
          <div class="p-3 border-t border-slate-100">
            <button class="w-full text-xs text-blue-600 font-bold hover:underline">ดูการแจ้งเตือนทั้งหมด →</button>
          </div>
        </div>

        <!-- Live Case Status Table -->
        <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col">
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 class="font-bold flex items-center gap-2 text-sm">
              <span class="material-symbols-outlined text-blue-600 text-[18px]">pending_actions</span>
              เคสล่าสุด (Live Cases)
            </h3>
            <div class="flex gap-2 items-center">
              <div class="flex items-center gap-1 text-[10px] font-bold text-green-600">
                <span class="relative inline-flex w-2 h-2"><span class="live-dot absolute w-full h-full rounded-full bg-green-500"></span><span class="relative w-2 h-2 rounded-full bg-green-500"></span></span>
                LIVE
              </div>
              <button onclick="switchTab('cases')" class="text-xs text-blue-600 font-bold border border-blue-500/30 px-2 py-1 rounded-lg hover:bg-blue-600/5">ดูทั้งหมด</button>
            </div>
          </div>
          <div class="overflow-x-auto flex-1">
            <table class="w-full text-xs text-left">
              <thead class="bg-slate-50 text-[10px] uppercase text-slate-400 font-bold">
                <tr>
                  <th class="px-4 py-3">เลขที่เคส</th>
                  <th class="px-4 py-3">ประเภทเหตุ</th>
                  <th class="px-4 py-3">สถานที่</th>
                  <th class="px-4 py-3">เวลา</th>
                  <th class="px-4 py-3">เจ้าหน้าที่</th>
                  <th class="px-4 py-3">สถานะ</th>
                  <th class="px-4 py-3">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50" id="live-cases-tbody">
                <tr>
                  <td class="px-4 py-3 font-bold mono text-red-600">#EM-007 🚨</td>
                  <td class="px-4 py-3">อุบัติเหตุจราจร</td>
                  <td class="px-4 py-3 text-slate-500">สุขุมวิท ซ.36</td>
                  <td class="px-4 py-3 mono text-slate-500">14:22</td>
                  <td class="px-4 py-3 text-red-500 font-bold">ยังไม่ระบุ</td>
                  <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-critical text-[10px] font-bold">CRITICAL</span></td>
                  <td class="px-4 py-3"><button onclick="openDispatchModal()" class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded font-bold">สั่งการ</button></td>
                </tr>
                <tr>
                  <td class="px-4 py-3 font-bold mono">#EM-008</td>
                  <td class="px-4 py-3">ผู้ป่วยฉุกเฉิน</td>
                  <td class="px-4 py-3 text-slate-500">คลองเตย</td>
                  <td class="px-4 py-3 mono text-slate-500">14:25</td>
                  <td class="px-4 py-3 text-slate-500">รอมอบหมาย</td>
                  <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-new text-[10px] font-bold">NEW</span></td>
                  <td class="px-4 py-3"><button onclick="openDispatchModal()" class="text-[10px] bg-blue-500 text-white px-2 py-1 rounded font-bold">มอบหมาย</button></td>
                </tr>
                <tr>
                  <td class="px-4 py-3 font-bold mono">#EM-001</td>
                  <td class="px-4 py-3">อุบัติเหตุจราจร</td>
                  <td class="px-4 py-3 text-slate-500">สุขุมวิท ซ.24</td>
                  <td class="px-4 py-3 mono text-slate-500">14:12</td>
                  <td class="px-4 py-3">ร.ต.อ. สมชาย</td>
                  <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-progress text-[10px] font-bold">IN PROGRESS</span></td>
                  <td class="px-4 py-3"><button class="text-[10px] text-slate-500 border border-slate-200 px-2 py-1 rounded">ติดตาม</button></td>
                </tr>
                <tr>
                  <td class="px-4 py-3 font-bold mono">#EM-002</td>
                  <td class="px-4 py-3">อัคคีภัย</td>
                  <td class="px-4 py-3 text-slate-500">รัชดาภิเษก</td>
                  <td class="px-4 py-3 mono text-slate-500">14:26</td>
                  <td class="px-4 py-3">ร.ต.ท. วิชัย</td>
                  <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-new text-[10px] font-bold">NEW</span></td>
                  <td class="px-4 py-3"><button class="text-[10px] text-slate-500 border border-slate-200 px-2 py-1 rounded">ดูรายละเอียด</button></td>
                </tr>
                <tr>
                  <td class="px-4 py-3 font-bold mono">#EM-003</td>
                  <td class="px-4 py-3">ผู้ป่วยฉุกเฉิน</td>
                  <td class="px-4 py-3 text-slate-500">ลุมพินี</td>
                  <td class="px-4 py-3 mono text-slate-500">13:55</td>
                  <td class="px-4 py-3">ร.ต.อ. ประสิทธิ์</td>
                  <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-done text-[10px] font-bold">COMPLETED</span></td>
                  <td class="px-4 py-3"><button class="text-[10px] text-slate-400 border border-slate-100 px-2 py-1 rounded">รายงาน</button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>


       <!-- ===== SLA METRICS ROW ===== -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
          <div class="flex justify-between items-start mb-3">
            <div class="bg-blue-500/10 p-2 rounded-lg"><span class="material-symbols-outlined text-blue-600">send_time_extension</span></div>
            <span class="text-emerald-500 flex items-center text-xs font-bold"><span class="material-symbols-outlined text-xs">trending_down</span>-12%</span>
          </div>
          <p class="text-xs text-slate-500">เวลาสั่งการ (Dispatch)</p>
          <h3 class="text-2xl font-bold mono mt-1">2.5 <span class="text-sm text-slate-400 font-normal">นาที</span></h3>
          <div class="mt-3">
            <div class="flex justify-between text-[10px] mb-1 text-slate-400 font-bold uppercase">
              <span>เป้า &lt; 3 นาที</span><span>85%</span>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full"><div class="animated-bar bg-blue-600 h-full rounded-full" style="width:85%"></div></div>
          </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
          <div class="flex justify-between items-start mb-3">
            <div class="bg-blue-500/10 p-2 rounded-lg"><span class="material-symbols-outlined text-blue-600">near_me</span></div>
            <span class="text-emerald-500 flex items-center text-xs font-bold"><span class="material-symbols-outlined text-xs">trending_down</span>-5%</span>
          </div>
          <p class="text-xs text-slate-500">เวลาตอบสนอง/ถึงที่เกิดเหตุ</p>
          <h3 class="text-2xl font-bold mono mt-1">8.2 <span class="text-sm text-slate-400 font-normal">นาที</span></h3>
          <div class="mt-3">
            <div class="flex justify-between text-[10px] mb-1 text-slate-400 font-bold uppercase">
              <span>เป้า &lt; 10 นาที</span><span>92%</span>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full"><div class="animated-bar bg-blue-600 h-full rounded-full" style="width:92%"></div></div>
          </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
          <div class="flex justify-between items-start mb-3">
            <div class="bg-red-50 p-2 rounded-lg"><span class="material-symbols-outlined text-red-500">task_alt</span></div>
            <span class="text-red-500 flex items-center text-xs font-bold"><span class="material-symbols-outlined text-xs">trending_up</span>+3%</span>
          </div>
          <p class="text-xs text-slate-500">เวลาแก้ไขปัญหา</p>
          <h3 class="text-2xl font-bold mono mt-1">45 <span class="text-sm text-slate-400 font-normal">นาที</span></h3>
          <div class="mt-3">
            <div class="flex justify-between text-[10px] mb-1 text-slate-400 font-bold uppercase">
              <span>เป้า &lt; 40 นาที</span><span class="text-red-500">78%</span>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full"><div class="animated-bar bg-red-400 h-full rounded-full" style="width:78%"></div></div>
          </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
          <div class="flex justify-between items-start mb-3">
            <div class="bg-slate-100 p-2 rounded-lg"><span class="material-symbols-outlined text-slate-600">cancel</span></div>
            <span class="text-red-500 flex items-center text-xs font-bold"><span class="material-symbols-outlined text-xs">trending_up</span>+1.2%</span>
          </div>
          <p class="text-xs text-slate-500">อัตราปฏิเสธ/โอนงาน</p>
          <h3 class="text-2xl font-bold mono mt-1">3.2 <span class="text-sm text-slate-400 font-normal">%</span></h3>
          <div class="mt-3">
            <div class="flex justify-between text-[10px] mb-1 text-slate-400 font-bold uppercase">
              <span>เป้า &lt; 5%</span><span class="text-emerald-500">ปกติ</span>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full"><div class="animated-bar bg-slate-400 h-full rounded-full" style="width:64%"></div></div>
          </div>
        </div>
      </div>
      <!-- ===== OFFICER STATUS + PEAK HOURS ===== -->
      <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

        <!-- Officer Status Quick View -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 class="font-bold flex items-center gap-2 text-sm">
              <span class="material-symbols-outlined text-blue-600 text-[18px]">badge</span>
              สถานะเจ้าหน้าที่ (Agent Overview)
            </h3>
            <div class="flex gap-3 text-[10px] font-bold">
              <span class="flex items-center gap-1 text-emerald-600">🟢 ว่าง: <span class="mono">4</span></span>
              <span class="flex items-center gap-1 text-amber-600">🟡 ปฏิบัติ: <span class="mono">3</span></span>
              <span class="flex items-center gap-1 text-red-500">🔴 ไม่พร้อม: <span class="mono">1</span></span>
            </div>
          </div>
          <div class="p-4">
            <!-- Agent workload progress -->
            <div class="mb-4">
              <div class="flex justify-between text-[10px] text-slate-400 mb-1 font-bold uppercase">
                <span>อัตราใช้งานกำลังพล</span><span>62.5%</span>
              </div>
              <div class="w-full bg-slate-100 h-2 rounded-full"><div class="animated-bar h-full bg-blue-600 rounded-full" style="width:62.5%"></div></div>
            </div>
            <div class="space-y-2 max-h-48 overflow-y-auto">
              <!-- officers -->
              <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50">
                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold text-xs">สช</div>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold truncate">ร.ต.อ. สมชาย ใจดี</p>
                  <p class="text-[10px] text-slate-400">เขต A – สุขุมวิท</p>
                </div>
                <div class="text-right">
                  <span class="px-2 py-0.5 rounded-full officer-available text-[10px] font-bold">🟢 ว่าง</span>
                  <p class="text-[10px] text-slate-400 mt-1 mono">0 เคส</p>
                </div>
              </div>
              <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50">
                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center text-amber-700 font-bold text-xs">วช</div>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold truncate">ร.ต.ท. วิชัย แกล้วกล้า</p>
                  <p class="text-[10px] text-slate-400">เขต B – คลองเตย</p>
                </div>
                <div class="text-right">
                  <span class="px-2 py-0.5 rounded-full officer-busy text-[10px] font-bold">🟡 ปฏิบัติ</span>
                  <p class="text-[10px] text-slate-400 mt-1 mono">1 เคส</p>
                </div>
              </div>
              <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold text-xs">ปส</div>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold truncate">ร.ต.อ. ประสิทธิ์ มุ่งมั่น</p>
                  <p class="text-[10px] text-slate-400">เขต C – ราชเทวี</p>
                </div>
                <div class="text-right">
                  <span class="px-2 py-0.5 rounded-full officer-available text-[10px] font-bold">🟢 ว่าง</span>
                  <p class="text-[10px] text-slate-400 mt-1 mono">0 เคส</p>
                </div>
              </div>
              <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50">
                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center text-amber-700 font-bold text-xs">กล</div>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold truncate">ส.ต.ต. กล้าหาญ สู้ศึก</p>
                  <p class="text-[10px] text-slate-400">เขต D – พญาไท</p>
                </div>
                <div class="text-right">
                  <span class="px-2 py-0.5 rounded-full officer-busy text-[10px] font-bold">🟡 ปฏิบัติ</span>
                  <p class="text-[10px] text-slate-400 mt-1 mono">2 เคส</p>
                </div>
              </div>
              <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-700 font-bold text-xs">มง</div>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold truncate">ส.ต.ท. มงคล รวดเร็ว</p>
                  <p class="text-[10px] text-slate-400">เขต E – บางรัก</p>
                </div>
                <div class="text-right">
                  <span class="px-2 py-0.5 rounded-full officer-unavailable text-[10px] font-bold">🔴 ไม่พร้อม</span>
                  <p class="text-[10px] text-slate-400 mt-1 mono">–</p>
                </div>
              </div>
            </div>
          </div>
          <div class="px-5 pb-4">
            <button onclick="switchTab('officers')" class="w-full text-xs text-blue-600 font-bold border border-blue-500/30 px-3 py-2 rounded-lg hover:bg-blue-600/5 transition-colors">ดูเจ้าหน้าที่ทั้งหมด →</button>
          </div>
        </div>

        <!-- Peak Hours Chart -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 class="font-bold flex items-center gap-2 text-sm">
              <span class="material-symbols-outlined text-blue-600 text-[18px]">bar_chart</span>
              ช่วงเวลาเหตุสูงสุด (Peak Hours)
            </h3>
            <select class="text-[10px] border border-slate-200 rounded-lg px-2 py-1">
              <option>7 วันล่าสุด</option>
              <option>30 วันล่าสุด</option>
            </select>
          </div>
          <div class="p-5">
            <div class="flex items-end gap-1.5 h-40 mb-3">
              <div class="flex-1 flex flex-col items-center gap-1 group">
                <span class="text-[10px] text-slate-400 group-hover:text-blue-600 transition-colors opacity-0 group-hover:opacity-100 font-bold">12</span>
                <div class="w-full bg-blue-500/20 hover:bg-blue-600 rounded-t transition-all cursor-pointer" style="height:30%"></div>
                <span class="text-[9px] text-slate-400 mono">00:00</span>
              </div>
              <div class="flex-1 flex flex-col items-center gap-1 group">
                <span class="text-[10px] text-slate-400 group-hover:text-blue-600 transition-colors opacity-0 group-hover:opacity-100 font-bold">6</span>
                <div class="w-full bg-blue-500/20 hover:bg-blue-600 rounded-t transition-all cursor-pointer" style="height:15%"></div>
                <span class="text-[9px] text-slate-400 mono">04:00</span>
              </div>
              <div class="flex-1 flex flex-col items-center gap-1 group">
                <span class="text-[10px] text-slate-400 group-hover:text-blue-600 transition-colors opacity-0 group-hover:opacity-100 font-bold">42</span>
                <div class="w-full bg-blue-500/40 hover:bg-blue-600 rounded-t transition-all cursor-pointer" style="height:60%"></div>
                <span class="text-[9px] text-slate-400 mono">08:00</span>
              </div>
              <div class="flex-1 flex flex-col items-center gap-1 group">
                <span class="text-[10px] text-slate-400 group-hover:text-blue-600 transition-colors opacity-0 group-hover:opacity-100 font-bold">28</span>
                <div class="w-full bg-blue-500/30 hover:bg-blue-600 rounded-t transition-all cursor-pointer" style="height:40%"></div>
                <span class="text-[9px] text-slate-400 mono">12:00</span>
              </div>
              <div class="flex-1 flex flex-col items-center gap-1 group">
                <span class="text-[10px] text-blue-600 font-bold opacity-100">76</span>
                <div class="w-full bg-blue-600 rounded-t transition-all cursor-pointer" style="height:95%"></div>
                <span class="text-[9px] text-blue-600 font-bold mono">16:00</span>
              </div>
              <div class="flex-1 flex flex-col items-center gap-1 group">
                <span class="text-[10px] text-slate-400 group-hover:text-blue-600 transition-colors opacity-0 group-hover:opacity-100 font-bold">54</span>
                <div class="w-full bg-blue-500/60 hover:bg-blue-600 rounded-t transition-all cursor-pointer" style="height:72%"></div>
                <span class="text-[9px] text-slate-400 mono">20:00</span>
              </div>
            </div>
            <div class="grid grid-cols-3 gap-3 pt-3 border-t border-slate-100">
              <div class="text-center">
                <div class="text-sm font-bold text-blue-600 mono">76</div>
                <div class="text-[10px] text-slate-400">Peak (16:00)</div>
              </div>
              <div class="text-center">
                <div class="text-sm font-bold mono">36.2</div>
                <div class="text-[10px] text-slate-400">เฉลี่ย/ชม.</div>
              </div>
              <div class="text-center">
                <div class="text-sm font-bold mono">6</div>
                <div class="text-[10px] text-slate-400">ต่ำสุด (04:00)</div>
              </div>
            </div>
            <p class="text-[11px] text-slate-500 mt-3">พบความหนาแน่นสูงสุดช่วง <strong class="text-blue-600">16:00–18:00 น.</strong> · ควรเพิ่มกะ 2 นาย</p>
          </div>
        </div>
      </div>

    </div><!-- end tab-overview -->

    <!-- ============ TAB: MAP ============ -->
    <div id="tab-map" class="tab-content hidden p-5 space-y-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold">แผนที่เหตุการณ์ (Live Map)</h1>
        <div class="flex gap-2">
          <div class="flex items-center gap-3 text-xs font-bold">
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500"></span>เคสใหม่</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-400"></span>ดำเนินการ</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500"></span>เสร็จแล้ว</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-500"></span>เจ้าหน้าที่</span>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-4 gap-4">
        <!-- Map Area -->
        <div class="xl:col-span-3 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden relative" style="height: 520px;">
          <!-- Map background -->
          <div class="absolute inset-0 bg-slate-200">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
              <!-- Grid pattern simulating streets -->
              <defs>
                <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
                  <path d="M 60 0 L 0 0 0 60" fill="none" stroke="#c8c8c8" stroke-width="1"/>
                </pattern>
                <pattern id="grid2" width="180" height="180" patternUnits="userSpaceOnUse">
                  <path d="M 180 0 L 0 0 0 180" fill="none" stroke="#b0b0b0" stroke-width="2"/>
                </pattern>
              </defs>
              <rect width="100%" height="100%" fill="#e8e6e0"/>
              <rect width="100%" height="100%" fill="url(#grid)"/>
              <rect width="100%" height="100%" fill="url(#grid2)"/>
              <!-- Simulated roads -->
              <line x1="0" y1="40%" x2="100%" y2="42%" stroke="#d4d0c8" stroke-width="12" opacity="0.8"/>
              <line x1="0" y1="65%" x2="100%" y2="63%" stroke="#d4d0c8" stroke-width="8" opacity="0.8"/>
              <line x1="30%" y1="0" x2="28%" y2="100%" stroke="#d4d0c8" stroke-width="14" opacity="0.8"/>
              <line x1="65%" y1="0" x2="67%" y2="100%" stroke="#d4d0c8" stroke-width="10" opacity="0.8"/>
              <!-- Parks -->
              <rect x="10%" y="20%" width="12%" height="15%" rx="4" fill="#c8d8b0" opacity="0.6"/>
              <rect x="55%" y="55%" width="10%" height="12%" rx="4" fill="#c8d8b0" opacity="0.6"/>
              <!-- Heatmap blobs -->
              <radialGradient id="heat1" cx="50%" cy="50%" r="50%">
                <stop offset="0%" stop-color="#1a8fff" stop-opacity="0.3"/>
                <stop offset="100%" stop-color="#1a8fff" stop-opacity="0"/>
              </radialGradient>
              <radialGradient id="heat2" cx="50%" cy="50%" r="50%">
                <stop offset="0%" stop-color="#137fec" stop-opacity="0.25"/>
                <stop offset="100%" stop-color="#137fec" stop-opacity="0"/>
              </radialGradient>
              <ellipse cx="62%" cy="42%" rx="12%" ry="10%" fill="url(#heat1)"/>
              <ellipse cx="30%" cy="60%" rx="10%" ry="8%" fill="url(#heat2)"/>
            </svg>
          </div>

          <!-- Map scan line -->
          <div class="absolute inset-0 map-scan pointer-events-none overflow-hidden"></div>

          <!-- Incident Pins -->
          <!-- Critical -->
          <div class="map-pin absolute" style="left:58%;top:38%" id="pin-007">
            <div class="relative cursor-pointer" onclick="showPinInfo('007')">
              <div class="absolute -inset-3 bg-red-500/20 rounded-full animate-ping"></div>
              <div class="relative w-5 h-5 bg-red-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center">
                <span class="text-white text-[8px] font-bold">!</span>
              </div>
              <div class="map-tooltip left-6 top-0 w-52 bg-white rounded-xl shadow-xl border border-slate-200 p-3">
                <div class="flex justify-between items-start mb-1">
                  <span class="font-bold text-xs">#EM-007</span>
                  <span class="px-1.5 py-0.5 rounded status-critical text-[10px] font-bold">CRITICAL</span>
                </div>
                <p class="text-[11px] text-slate-600 mb-1">อุบัติเหตุจราจร</p>
                <p class="text-[10px] text-slate-400 flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">location_on</span>ถ.สุขุมวิท ซ.36</p>
                <p class="text-[10px] text-slate-400 flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">schedule</span>14:22 น.</p>
                <p class="text-[10px] text-slate-400">ผู้แจ้ง: นางสาวอรุณี สดใส</p>
                <button onclick="openDispatchModal()" class="mt-2 w-full text-[10px] bg-blue-600 text-white py-1 rounded font-bold">🚔 สั่งการเจ้าหน้าที่</button>
              </div>
            </div>
          </div>

          <!-- New case -->
          <div class="map-pin absolute" style="left:32%;top:55%">
            <div class="relative cursor-pointer">
              <div class="relative w-5 h-5 bg-blue-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center">
                <span class="text-white text-[8px] font-bold">N</span>
              </div>
              <div class="map-tooltip left-6 top-0 w-48 bg-white rounded-xl shadow-xl border border-slate-200 p-3">
                <div class="flex justify-between items-start mb-1">
                  <span class="font-bold text-xs">#EM-008</span>
                  <span class="px-1.5 py-0.5 rounded status-new text-[10px] font-bold">NEW</span>
                </div>
                <p class="text-[11px] text-slate-600 mb-1">ผู้ป่วยฉุกเฉิน</p>
                <p class="text-[10px] text-slate-400">คลองเตย · 14:25 น.</p>
                <button onclick="openDispatchModal()" class="mt-2 w-full text-[10px] bg-blue-500 text-white py-1 rounded font-bold">มอบหมายเจ้าหน้าที่</button>
              </div>
            </div>
          </div>

          <!-- In progress -->
          <div class="map-pin absolute" style="left:55%;top:62%">
            <div class="relative cursor-pointer">
              <div class="relative w-5 h-5 bg-yellow-400 rounded-full border-2 border-white shadow-lg flex items-center justify-center">
                <span class="text-white text-[8px] font-bold">P</span>
              </div>
              <div class="map-tooltip left-6 top-0 w-48 bg-white rounded-xl shadow-xl border border-slate-200 p-3">
                <div class="flex justify-between items-start mb-1">
                  <span class="font-bold text-xs">#EM-001</span>
                  <span class="px-1.5 py-0.5 rounded status-progress text-[10px] font-bold">IN PROG.</span>
                </div>
                <p class="text-[11px] text-slate-600 mb-1">อุบัติเหตุจราจร</p>
                <p class="text-[10px] text-slate-400">สุขุมวิท ซ.24 · 14:12 น.</p>
                <p class="text-[10px] text-emerald-600 font-bold">เจ้าหน้าที่: ร.ต.อ. สมชาย</p>
              </div>
            </div>
          </div>

          <!-- Completed -->
          <div class="map-pin absolute" style="left:20%;top:35%">
            <div class="relative cursor-pointer">
              <div class="relative w-5 h-5 bg-emerald-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center">
                <span class="text-white text-[8px] font-bold">✓</span>
              </div>
              <div class="map-tooltip left-6 top-0 w-48 bg-white rounded-xl shadow-xl border border-slate-200 p-3">
                <div class="flex justify-between items-start mb-1">
                  <span class="font-bold text-xs">#EM-003</span>
                  <span class="px-1.5 py-0.5 rounded status-done text-[10px] font-bold">DONE</span>
                </div>
                <p class="text-[11px] text-slate-600 mb-1">ผู้ป่วยฉุกเฉิน</p>
                <p class="text-[10px] text-slate-400">ลุมพินี · 13:55 น.</p>
              </div>
            </div>
          </div>

          <!-- Officer pins -->
          <div class="map-pin absolute" style="left:48%;top:50%">
            <div class="relative cursor-pointer">
              <div class="relative w-5 h-5 bg-blue-600 rounded-full border-2 border-white shadow-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-[10px]">person</span>
              </div>
              <div class="map-tooltip left-6 top-0 w-44 bg-white rounded-xl shadow-xl border border-slate-200 p-3">
                <p class="font-bold text-xs">ร.ต.อ. สมชาย ใจดี</p>
                <p class="text-[10px] text-slate-400 mt-0.5">เขต A – กำลังเดินทาง</p>
                <span class="text-[10px] text-amber-600 font-bold">🟡 ปฏิบัติงาน</span>
              </div>
            </div>
          </div>

          <!-- Map controls -->
          <div class="absolute bottom-4 left-4 flex flex-col gap-2">
            <button class="w-9 h-9 bg-white rounded-lg shadow-md border border-slate-200 flex items-center justify-center hover:bg-slate-50"><span class="material-symbols-outlined text-[18px]">add</span></button>
            <button class="w-9 h-9 bg-white rounded-lg shadow-md border border-slate-200 flex items-center justify-center hover:bg-slate-50"><span class="material-symbols-outlined text-[18px]">remove</span></button>
            <button class="w-9 h-9 bg-white rounded-lg shadow-md border border-slate-200 flex items-center justify-center hover:bg-slate-50"><span class="material-symbols-outlined text-[18px]">my_location</span></button>
          </div>

          <!-- Area labels -->
          <div class="absolute top-4 left-1/2 -translate-x-1/2 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full shadow text-xs font-bold text-slate-600">
            กรุงเทพมหานคร (Bangkok)
          </div>
        </div>

        <!-- Map sidebar -->
        <div class="space-y-4">
          <!-- Area stats -->
          <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <h3 class="text-sm font-bold mb-3 flex items-center gap-2">
              <span class="material-symbols-outlined text-blue-600 text-[16px]">analytics</span>
              วิเคราะห์รายพื้นที่
            </h3>
            <div class="space-y-3">
              <div>
                <div class="flex justify-between text-xs font-bold mb-1">
                  <span>เขตปทุมวัน</span><span class="text-red-500 mono">12.4 นาที</span>
                </div>
                <div class="h-1.5 bg-slate-100 rounded-full"><div class="animated-bar h-full bg-red-500 rounded-full" style="width:82%"></div></div>
              </div>
              <div>
                <div class="flex justify-between text-xs font-bold mb-1">
                  <span>เขตวัฒนา</span><span class="text-amber-500 mono">10.1 นาที</span>
                </div>
                <div class="h-1.5 bg-slate-100 rounded-full"><div class="animated-bar h-full bg-amber-500 rounded-full" style="width:65%"></div></div>
              </div>
              <div>
                <div class="flex justify-between text-xs font-bold mb-1">
                  <span>เขตบางรัก</span><span class="text-emerald-500 mono">7.8 นาที</span>
                </div>
                <div class="h-1.5 bg-slate-100 rounded-full"><div class="animated-bar h-full bg-emerald-500 rounded-full" style="width:45%"></div></div>
              </div>
              <div>
                <div class="flex justify-between text-xs font-bold mb-1">
                  <span>เขตคลองเตย</span><span class="text-blue-400 mono">11.2 นาที</span>
                </div>
                <div class="h-1.5 bg-slate-100 rounded-full"><div class="animated-bar h-full bg-blue-400 rounded-full" style="width:72%"></div></div>
              </div>
            </div>
          </div>
          <!-- Top areas -->
          <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <h3 class="text-sm font-bold mb-3">เคสสะสม 24 ชม.</h3>
            <div class="space-y-2">
              <div class="flex items-center justify-between p-2 bg-slate-50 rounded-lg">
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 bg-blue-500/20 rounded-full flex items-center justify-center text-blue-600 font-bold text-[10px]">1</div>
                  <span class="text-xs font-semibold">คลองเตย</span>
                </div>
                <span class="text-xs font-bold mono">45 เคส</span>
              </div>
              <div class="flex items-center justify-between p-2 bg-slate-50 rounded-lg">
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 bg-blue-500/10 rounded-full flex items-center justify-center text-blue-600/70 font-bold text-[10px]">2</div>
                  <span class="text-xs font-semibold">ราชเทวี</span>
                </div>
                <span class="text-xs font-bold mono">38 เคส</span>
              </div>
              <div class="flex items-center justify-between p-2 bg-slate-50 rounded-lg">
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 bg-slate-200 rounded-full flex items-center justify-center text-slate-500 font-bold text-[10px]">3</div>
                  <span class="text-xs font-semibold">พญาไท</span>
                </div>
                <span class="text-xs font-bold mono">29 เคส</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- end tab-map -->

    <!-- ============ TAB: CASES ============ -->
    <div id="tab-cases" class="tab-content hidden p-5 space-y-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold">รายการเคส (Case Management)</h1>
        <button onclick="openModal('modal-new-case')" class="flex items-center gap-1.5 bg-blue-600 text-white text-xs font-bold px-3 py-2 rounded-lg shadow shadow-blue-500/30">
          <span class="material-symbols-outlined text-sm">add</span> เพิ่มเคสใหม่
        </button>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-3">
          <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ค้นหา</label>
            <input type="text" placeholder="เลขที่เคส / ชื่อ..." class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-600" oninput="filterCases()"/>
          </div>
          <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">พื้นที่</label>
            <select class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2" onchange="filterCases()">
              <option value="">ทั้งหมด</option>
              <option>สุขุมวิท</option>
              <option>คลองเตย</option>
              <option>ราชเทวี</option>
              <option>พญาไท</option>
              <option>บางรัก</option>
            </select>
          </div>
          <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ประเภทเหตุ</label>
            <select class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2" onchange="filterCases()">
              <option value="">ทั้งหมด</option>
              <option>อุบัติเหตุจราจร</option>
              <option>อัคคีภัย</option>
              <option>ผู้ป่วยฉุกเฉิน</option>
              <option>การทะเลาะวิวาท</option>
              <option>อื่นๆ</option>
            </select>
          </div>
          <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">สถานะ</label>
            <select class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2" onchange="filterCases()">
              <option value="">ทั้งหมด</option>
              <option>ใหม่</option>
              <option>กำลังดำเนินการ</option>
              <option>เสร็จสิ้น</option>
              <option>เร่งด่วน</option>
            </select>
          </div>
          <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">วันที่</label>
            <input type="date" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2"/>
          </div>
        </div>
      </div>

      <!-- Cases Table -->
      <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100">
          <span class="text-sm font-bold">ผลการค้นหา: <span class="text-blue-600">178 เคส</span></span>
          <div class="flex gap-2">
            <button class="text-xs border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50">นำออก CSV</button>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-xs text-left" id="cases-table">
            <thead class="bg-slate-50 text-[10px] uppercase text-slate-400 font-bold border-b border-slate-100">
              <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">เวลาแจ้ง</th>
                <th class="px-4 py-3">ผู้แจ้ง</th>
                <th class="px-4 py-3">พื้นที่</th>
                <th class="px-4 py-3">ประเภทเหตุ</th>
                <th class="px-4 py-3">เจ้าหน้าที่</th>
                <th class="px-4 py-3">ระยะเวลา</th>
                <th class="px-4 py-3">สถานะ</th>
                <th class="px-4 py-3">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold mono text-red-600">#EM-007</td>
                <td class="px-4 py-3 mono text-slate-500">14:22</td>
                <td class="px-4 py-3">นางสาวอรุณี สดใส</td>
                <td class="px-4 py-3 text-slate-500">สุขุมวิท ซ.36</td>
                <td class="px-4 py-3">อุบัติเหตุจราจร</td>
                <td class="px-4 py-3 text-red-500">ยังไม่ระบุ</td>
                <td class="px-4 py-3 mono">8 นาที</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-critical text-[10px] font-bold">CRITICAL</span></td>
                <td class="px-4 py-3"><button onclick="openDispatchModal()" class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded font-bold">สั่งการ</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold mono">#EM-008</td>
                <td class="px-4 py-3 mono text-slate-500">14:25</td>
                <td class="px-4 py-3">นาย ก้องเกียรติ ดีงาม</td>
                <td class="px-4 py-3 text-slate-500">คลองเตย</td>
                <td class="px-4 py-3">ผู้ป่วยฉุกเฉิน</td>
                <td class="px-4 py-3 text-slate-400">รอมอบหมาย</td>
                <td class="px-4 py-3 mono">5 นาที</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-new text-[10px] font-bold">NEW</span></td>
                <td class="px-4 py-3"><button onclick="openDispatchModal()" class="text-[10px] bg-blue-500 text-white px-2 py-1 rounded font-bold">มอบหมาย</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold mono">#EM-001</td>
                <td class="px-4 py-3 mono text-slate-500">14:12</td>
                <td class="px-4 py-3">นาย ธนวัฒน์ เจริญ</td>
                <td class="px-4 py-3 text-slate-500">สุขุมวิท ซ.24</td>
                <td class="px-4 py-3">อุบัติเหตุจราจร</td>
                <td class="px-4 py-3">ร.ต.อ. สมชาย</td>
                <td class="px-4 py-3 mono">18 นาที</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-progress text-[10px] font-bold">IN PROG.</span></td>
                <td class="px-4 py-3"><button class="text-[10px] text-slate-500 border border-slate-200 px-2 py-1 rounded">ติดตาม</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold mono">#EM-002</td>
                <td class="px-4 py-3 mono text-slate-500">14:26</td>
                <td class="px-4 py-3">นางวิมล ดวงดี</td>
                <td class="px-4 py-3 text-slate-500">รัชดาภิเษก</td>
                <td class="px-4 py-3">อัคคีภัย</td>
                <td class="px-4 py-3">ร.ต.ท. วิชัย</td>
                <td class="px-4 py-3 mono">4 นาที</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-new text-[10px] font-bold">NEW</span></td>
                <td class="px-4 py-3"><button class="text-[10px] text-slate-500 border border-slate-200 px-2 py-1 rounded">ดูรายละเอียด</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold mono">#EM-003</td>
                <td class="px-4 py-3 mono text-slate-500">13:55</td>
                <td class="px-4 py-3">นางสาวพรรณี มีสุข</td>
                <td class="px-4 py-3 text-slate-500">ลุมพินี</td>
                <td class="px-4 py-3">ผู้ป่วยฉุกเฉิน</td>
                <td class="px-4 py-3">ร.ต.อ. ประสิทธิ์</td>
                <td class="px-4 py-3 mono">25 นาที</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-done text-[10px] font-bold">DONE</span></td>
                <td class="px-4 py-3"><button class="text-[10px] text-slate-400 border border-slate-100 px-2 py-1 rounded">รายงาน</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold mono">#EM-004</td>
                <td class="px-4 py-3 mono text-slate-500">13:40</td>
                <td class="px-4 py-3">นาย สุรศักดิ์ ขยัน</td>
                <td class="px-4 py-3 text-slate-500">พญาไท</td>
                <td class="px-4 py-3">การทะเลาะวิวาท</td>
                <td class="px-4 py-3">ส.ต.ต. กล้าหาญ</td>
                <td class="px-4 py-3 mono">35 นาที</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full status-done text-[10px] font-bold">DONE</span></td>
                <td class="px-4 py-3"><button class="text-[10px] text-slate-400 border border-slate-100 px-2 py-1 rounded">รายงาน</button></td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100">
          <span class="text-[11px] text-slate-400">แสดง 1-6 จาก 178 รายการ</span>
          <div class="flex gap-1">
            <button class="w-8 h-8 rounded-lg border border-slate-200 text-xs flex items-center justify-center hover:bg-slate-50">←</button>
            <button class="w-8 h-8 rounded-lg bg-blue-600 text-white text-xs flex items-center justify-center">1</button>
            <button class="w-8 h-8 rounded-lg border border-slate-200 text-xs flex items-center justify-center hover:bg-slate-50">2</button>
            <button class="w-8 h-8 rounded-lg border border-slate-200 text-xs flex items-center justify-center hover:bg-slate-50">3</button>
            <button class="w-8 h-8 rounded-lg border border-slate-200 text-xs flex items-center justify-center hover:bg-slate-50">→</button>
          </div>
        </div>
      </div>
    </div><!-- end tab-cases -->

    <!-- ============ TAB: OFFICERS ============ -->
    <div id="tab-officers" class="tab-content hidden p-5 space-y-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold">สถานะเจ้าหน้าที่ (Officer Status)</h1>
        <div class="flex gap-3 text-xs font-bold">
          <span class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">🟢 ว่าง: 4 คน</span>
          <span class="px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">🟡 ปฏิบัติ: 3 คน</span>
          <span class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 border border-red-200">🔴 ไม่พร้อม: 1 คน</span>
        </div>
      </div>

      <!-- Officer workload summary -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm text-center">
          <div class="text-3xl font-bold mono text-emerald-600">4</div>
          <div class="text-xs text-slate-500 mt-1">เจ้าหน้าที่ว่าง</div>
          <div class="mt-2 text-[10px] text-slate-400">พร้อมรับงานทันที</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm text-center">
          <div class="text-3xl font-bold mono text-blue-600">87%</div>
          <div class="text-xs text-slate-500 mt-1">อัตราความพร้อม</div>
          <div class="mt-2 h-1.5 bg-slate-100 rounded-full"><div class="animated-bar h-full bg-blue-600 rounded-full" style="width:87%"></div></div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm text-center">
          <div class="text-3xl font-bold mono">32</div>
          <div class="text-xs text-slate-500 mt-1">เคสที่ปิดวันนี้</div>
          <div class="mt-2 text-[10px] text-emerald-600 font-bold">↑ +15% vs เมื่อวาน</div>
        </div>
      </div>

      <!-- Full officer table -->
      <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex justify-between items-center">
          <span class="text-sm font-bold">รายชื่อเจ้าหน้าที่ทั้งหมด</span>
          <input type="text" placeholder="ค้นหาชื่อ..." class="text-xs border border-slate-200 rounded-lg px-3 py-1.5 w-48"/>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-[10px] uppercase text-slate-400 font-bold border-b border-slate-100">
              <tr>
                <th class="px-4 py-3">ชื่อ-สกุล</th>
                <th class="px-4 py-3">ยศ/ตำแหน่ง</th>
                <th class="px-4 py-3">พื้นที่รับผิดชอบ</th>
                <th class="px-4 py-3">สถานะ</th>
                <th class="px-4 py-3">เคสวันนี้</th>
                <th class="px-4 py-3">เคสปิด</th>
                <th class="px-4 py-3">อัตราปฏิเสธ</th>
                <th class="px-4 py-3">เวลาตอบสนองเฉลี่ย</th>
                <th class="px-4 py-3">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold">สมชาย ใจดี</td>
                <td class="px-4 py-3 text-slate-500">ร.ต.อ.</td>
                <td class="px-4 py-3">เขต A – สุขุมวิท</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full officer-available text-[10px] font-bold">🟢 ว่าง</span></td>
                <td class="px-4 py-3 mono font-bold">5</td>
                <td class="px-4 py-3 mono">5</td>
                <td class="px-4 py-3 mono text-emerald-600">0%</td>
                <td class="px-4 py-3 mono">7.2 นาที</td>
                <td class="px-4 py-3"><button onclick="openDispatchModal()" class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded font-bold">มอบหมาย</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold">วิชัย แกล้วกล้า</td>
                <td class="px-4 py-3 text-slate-500">ร.ต.ท.</td>
                <td class="px-4 py-3">เขต B – คลองเตย</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full officer-busy text-[10px] font-bold">🟡 ปฏิบัติ</span></td>
                <td class="px-4 py-3 mono font-bold">8</td>
                <td class="px-4 py-3 mono">7</td>
                <td class="px-4 py-3 mono text-emerald-600">1.2%</td>
                <td class="px-4 py-3 mono">8.5 นาที</td>
                <td class="px-4 py-3"><button class="text-[10px] text-slate-500 border border-slate-200 px-2 py-1 rounded">ดูรายละเอียด</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold">ประสิทธิ์ มุ่งมั่น</td>
                <td class="px-4 py-3 text-slate-500">ร.ต.อ.</td>
                <td class="px-4 py-3">เขต C – ราชเทวี</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full officer-available text-[10px] font-bold">🟢 ว่าง</span></td>
                <td class="px-4 py-3 mono font-bold">6</td>
                <td class="px-4 py-3 mono">6</td>
                <td class="px-4 py-3 mono text-emerald-600">0%</td>
                <td class="px-4 py-3 mono">9.1 นาที</td>
                <td class="px-4 py-3"><button onclick="openDispatchModal()" class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded font-bold">มอบหมาย</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold">กล้าหาญ สู้ศึก</td>
                <td class="px-4 py-3 text-slate-500">ส.ต.ต.</td>
                <td class="px-4 py-3">เขต D – พญาไท</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full officer-busy text-[10px] font-bold">🟡 ปฏิบัติ</span></td>
                <td class="px-4 py-3 mono font-bold">7</td>
                <td class="px-4 py-3 mono">5</td>
                <td class="px-4 py-3 mono text-amber-500">2.8%</td>
                <td class="px-4 py-3 mono">10.3 นาที</td>
                <td class="px-4 py-3"><button class="text-[10px] text-slate-500 border border-slate-200 px-2 py-1 rounded">ดูรายละเอียด</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold">มงคล รวดเร็ว</td>
                <td class="px-4 py-3 text-slate-500">ส.ต.ท.</td>
                <td class="px-4 py-3">เขต E – บางรัก</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full officer-unavailable text-[10px] font-bold">🔴 ไม่พร้อม</span></td>
                <td class="px-4 py-3 mono font-bold">4</td>
                <td class="px-4 py-3 mono">4</td>
                <td class="px-4 py-3 mono text-emerald-600">0%</td>
                <td class="px-4 py-3 mono">–</td>
                <td class="px-4 py-3"><span class="text-[10px] text-slate-400">ไม่พร้อม</span></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold">อรุณ เช้าตรู่</td>
                <td class="px-4 py-3 text-slate-500">ส.ต.อ.</td>
                <td class="px-4 py-3">เขต F – ลุมพินี</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full officer-available text-[10px] font-bold">🟢 ว่าง</span></td>
                <td class="px-4 py-3 mono font-bold">3</td>
                <td class="px-4 py-3 mono">3</td>
                <td class="px-4 py-3 mono text-emerald-600">0%</td>
                <td class="px-4 py-3 mono">8.8 นาที</td>
                <td class="px-4 py-3"><button onclick="openDispatchModal()" class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded font-bold">มอบหมาย</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold">นิรันดร์ คงมั่น</td>
                <td class="px-4 py-3 text-slate-500">ส.ต.ท.</td>
                <td class="px-4 py-3">เขต G – ปทุมวัน</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full officer-busy text-[10px] font-bold">🟡 ปฏิบัติ</span></td>
                <td class="px-4 py-3 mono font-bold">9</td>
                <td class="px-4 py-3 mono">8</td>
                <td class="px-4 py-3 mono text-red-500">5.5%</td>
                <td class="px-4 py-3 mono">12.1 นาที</td>
                <td class="px-4 py-3"><button class="text-[10px] text-red-500 border border-red-200 px-2 py-1 rounded font-bold">ตรวจสอบ ⚠️</button></td>
              </tr>
              <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-bold">สุนิสา รอบรู้</td>
                <td class="px-4 py-3 text-slate-500">ร.ต.ต.</td>
                <td class="px-4 py-3">เขต H – วัฒนา</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full officer-available text-[10px] font-bold">🟢 ว่าง</span></td>
                <td class="px-4 py-3 mono font-bold">4</td>
                <td class="px-4 py-3 mono">4</td>
                <td class="px-4 py-3 mono text-emerald-600">0%</td>
                <td class="px-4 py-3 mono">7.9 นาที</td>
                <td class="px-4 py-3"><button onclick="openDispatchModal()" class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded font-bold">มอบหมาย</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div><!-- end tab-officers -->

    <!-- ============ TAB: ANALYTICS ============ -->
    <div id="tab-analytics" class="tab-content hidden p-5 space-y-5">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold">สถิติและวิเคราะห์ (Analytics)</h1>
        <div class="flex gap-2">
          <button class="text-xs border border-slate-200 bg-white px-3 py-1.5 rounded-lg">7 วัน</button>
          <button class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg">30 วัน</button>
          <button class="text-xs border border-slate-200 bg-white px-3 py-1.5 rounded-lg">90 วัน</button>
        </div>
      </div>

      <!-- SLA Summary Row -->
      <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
          <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Dispatch Time</p>
          <div class="text-2xl font-bold mono text-blue-600">2.5 <span class="text-sm text-slate-400 font-normal">นาที</span></div>
          <p class="text-[10px] text-emerald-600 font-bold mt-1">↓ ดีกว่าเป้า 17%</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
          <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Response Time</p>
          <div class="text-2xl font-bold mono text-blue-600">8.2 <span class="text-sm text-slate-400 font-normal">นาที</span></div>
          <p class="text-[10px] text-emerald-600 font-bold mt-1">↓ ดีกว่าเป้า 18%</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
          <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Resolution Time</p>
          <div class="text-2xl font-bold mono text-red-500">45 <span class="text-sm text-slate-400 font-normal">นาที</span></div>
          <p class="text-[10px] text-red-500 font-bold mt-1">↑ เกินเป้า 12.5%</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
          <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">SLA Compliance</p>
          <div class="text-2xl font-bold mono text-emerald-600">98.5%</div>
          <p class="text-[10px] text-emerald-600 font-bold mt-1">↑ เกินเป้า (95%)</p>
        </div>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

        <!-- Cases by Type -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
          <h3 class="text-sm font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600 text-[16px]">pie_chart</span>
            เคสตามประเภทเหตุ (30 วัน)
          </h3>
          <div class="space-y-3">
            <div>
              <div class="flex justify-between text-xs mb-1"><span class="font-semibold">อุบัติเหตุจราจร</span><span class="mono font-bold">842 (38%)</span></div>
              <div class="h-2 bg-slate-100 rounded-full"><div class="animated-bar h-full bg-red-500 rounded-full" style="width:38%"></div></div>
            </div>
            <div>
              <div class="flex justify-between text-xs mb-1"><span class="font-semibold">ผู้ป่วยฉุกเฉิน</span><span class="mono font-bold">620 (28%)</span></div>
              <div class="h-2 bg-slate-100 rounded-full"><div class="animated-bar h-full bg-blue-500 rounded-full" style="width:28%"></div></div>
            </div>
            <div>
              <div class="flex justify-between text-xs mb-1"><span class="font-semibold">อัคคีภัย</span><span class="mono font-bold">310 (14%)</span></div>
              <div class="h-2 bg-slate-100 rounded-full"><div class="animated-bar h-full bg-blue-400 rounded-full" style="width:14%"></div></div>
            </div>
            <div>
              <div class="flex justify-between text-xs mb-1"><span class="font-semibold">การทะเลาะวิวาท</span><span class="mono font-bold">265 (12%)</span></div>
              <div class="h-2 bg-slate-100 rounded-full"><div class="animated-bar h-full bg-purple-500 rounded-full" style="width:12%"></div></div>
            </div>
            <div>
              <div class="flex justify-between text-xs mb-1"><span class="font-semibold">อื่นๆ</span><span class="mono font-bold">177 (8%)</span></div>
              <div class="h-2 bg-slate-100 rounded-full"><div class="animated-bar h-full bg-slate-400 rounded-full" style="width:8%"></div></div>
            </div>
          </div>
          <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between text-[11px] text-slate-500">
            <span>รวมทั้งหมด: <strong class="text-slate-700 mono">2,214 เคส</strong></span>
            <span class="text-blue-600 font-bold cursor-pointer">ดูรายละเอียด →</span>
          </div>
        </div>

        <!-- Cases by Area -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
          <h3 class="text-sm font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600 text-[16px]">location_city</span>
            เคสตามพื้นที่ (30 วัน)
          </h3>
          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <span class="text-[10px] text-slate-400 w-20 text-right font-mono">คลองเตย</span>
              <div class="flex-1 h-6 bg-slate-100 rounded-lg overflow-hidden relative">
                <div class="animated-bar h-full bg-blue-500/80 rounded-lg flex items-center px-2" style="width:85%">
                  <span class="text-white text-[10px] font-bold">1,245</span>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-[10px] text-slate-400 w-20 text-right font-mono">ราชเทวี</span>
              <div class="flex-1 h-6 bg-slate-100 rounded-lg overflow-hidden">
                <div class="animated-bar h-full bg-blue-500/65 rounded-lg flex items-center px-2" style="width:68%">
                  <span class="text-white text-[10px] font-bold">980</span>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-[10px] text-slate-400 w-20 text-right font-mono">พญาไท</span>
              <div class="flex-1 h-6 bg-slate-100 rounded-lg overflow-hidden">
                <div class="animated-bar h-full bg-blue-500/50 rounded-lg flex items-center px-2" style="width:52%">
                  <span class="text-white text-[10px] font-bold">756</span>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-[10px] text-slate-400 w-20 text-right font-mono">ปทุมวัน</span>
              <div class="flex-1 h-6 bg-slate-100 rounded-lg overflow-hidden">
                <div class="animated-bar h-full bg-blue-500/40 rounded-lg flex items-center px-2" style="width:42%">
                  <span class="text-white text-[10px] font-bold">612</span>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-[10px] text-slate-400 w-20 text-right font-mono">วัฒนา</span>
              <div class="flex-1 h-6 bg-slate-100 rounded-lg overflow-hidden">
                <div class="animated-bar h-full bg-blue-500/30 rounded-lg flex items-center px-2" style="width:35%">
                  <span class="text-slate-600 text-[10px] font-bold">510</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Top performing officers -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
          <h3 class="text-sm font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600 text-[16px]">military_tech</span>
            เจ้าหน้าที่รับเคสสูงสุด (30 วัน)
          </h3>
          <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-xl border border-amber-100">
              <div class="w-8 h-8 bg-amber-400 rounded-full flex items-center justify-center text-white font-bold text-sm">🥇</div>
              <div class="flex-1">
                <p class="text-xs font-bold">ร.ต.ท. วิชัย แกล้วกล้า</p>
                <p class="text-[10px] text-slate-500">เขต B – Response avg: 8.5 นาที</p>
              </div>
              <div class="text-right">
                <div class="text-sm font-bold mono text-amber-600">89 เคส</div>
                <div class="text-[10px] text-emerald-600">100% ผ่าน SLA</div>
              </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
              <div class="w-8 h-8 bg-slate-300 rounded-full flex items-center justify-center text-white font-bold text-sm">🥈</div>
              <div class="flex-1">
                <p class="text-xs font-bold">ร.ต.อ. สมชาย ใจดี</p>
                <p class="text-[10px] text-slate-500">เขต A – Response avg: 7.2 นาที</p>
              </div>
              <div class="text-right">
                <div class="text-sm font-bold mono">76 เคส</div>
                <div class="text-[10px] text-emerald-600">98% ผ่าน SLA</div>
              </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
              <div class="w-8 h-8 bg-amber-700 rounded-full flex items-center justify-center text-white font-bold text-sm">🥉</div>
              <div class="flex-1">
                <p class="text-xs font-bold">ส.ต.ต. กล้าหาญ สู้ศึก</p>
                <p class="text-[10px] text-slate-500">เขต D – Response avg: 10.3 นาที</p>
              </div>
              <div class="text-right">
                <div class="text-sm font-bold mono">68 เคส</div>
                <div class="text-[10px] text-amber-600">92% ผ่าน SLA</div>
              </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-red-50 rounded-xl border border-red-100">
              <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600 font-bold text-sm">⚠️</div>
              <div class="flex-1">
                <p class="text-xs font-bold">ส.ต.ท. นิรันดร์ คงมั่น</p>
                <p class="text-[10px] text-slate-500">เขต G – อัตราปฏิเสธสูง 5.5%</p>
              </div>
              <div class="text-right">
                <div class="text-sm font-bold mono text-red-500">54 เคส</div>
                <div class="text-[10px] text-red-500">ต้องตรวจสอบ</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Daily trend -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
          <h3 class="text-sm font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600 text-[16px]">trending_up</span>
            แนวโน้มเคสรายวัน (7 วัน)
          </h3>
          <div class="flex items-end gap-2 h-36">
            <div class="flex-1 flex flex-col items-center gap-1 group">
              <span class="text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-500">142</span>
              <div class="w-full bg-blue-500/30 hover:bg-blue-600 rounded-t transition-all" style="height:55%"></div>
              <span class="text-[9px] text-slate-400 mono">จ.</span>
            </div>
            <div class="flex-1 flex flex-col items-center gap-1 group">
              <span class="text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-500">168</span>
              <div class="w-full bg-blue-500/50 hover:bg-blue-600 rounded-t transition-all" style="height:65%"></div>
              <span class="text-[9px] text-slate-400 mono">อ.</span>
            </div>
            <div class="flex-1 flex flex-col items-center gap-1 group">
              <span class="text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-500">195</span>
              <div class="w-full bg-blue-500/60 hover:bg-blue-600 rounded-t transition-all" style="height:76%"></div>
              <span class="text-[9px] text-slate-400 mono">พ.</span>
            </div>
            <div class="flex-1 flex flex-col items-center gap-1 group">
              <span class="text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-500">158</span>
              <div class="w-full bg-blue-600/45 hover:bg-blue-600 rounded-t transition-all" style="height:61%"></div>
              <span class="text-[9px] text-slate-400 mono">พฤ.</span>
            </div>
            <div class="flex-1 flex flex-col items-center gap-1 group">
              <span class="text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-500">210</span>
              <div class="w-full bg-blue-500/80 hover:bg-blue-600 rounded-t transition-all" style="height:82%"></div>
              <span class="text-[9px] text-slate-400 mono">ศ.</span>
            </div>
            <div class="flex-1 flex flex-col items-center gap-1 group">
              <span class="text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-500">248</span>
              <div class="w-full bg-blue-600 rounded-t transition-all" style="height:96%"></div>
              <span class="text-[9px] text-blue-600 font-bold mono">ส.</span>
            </div>
            <div class="flex-1 flex flex-col items-center gap-1 group">
              <span class="text-[10px] opacity-0 group-hover:opacity-100 font-bold text-slate-500">178</span>
              <div class="w-full bg-blue-600/55 hover:bg-blue-600 rounded-t transition-all" style="height:70%"></div>
              <span class="text-[9px] text-slate-400 mono">อา.</span>
            </div>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
            <div><div class="text-xs font-bold mono">185.6</div><div class="text-[10px] text-slate-400">เฉลี่ย/วัน</div></div>
            <div><div class="text-xs font-bold mono text-blue-600">248</div><div class="text-[10px] text-slate-400">สูงสุด (ส.)</div></div>
            <div><div class="text-xs font-bold mono">142</div><div class="text-[10px] text-slate-400">ต่ำสุด (จ.)</div></div>
          </div>
        </div>

      </div>
    </div><!-- end tab-analytics -->

  </main>
</div>

<!-- ===== MODAL: NEW CASE ===== -->
<div id="modal-new-case" class="modal-overlay">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-blue-600/5">
      <h3 class="font-bold flex items-center gap-2"><span class="material-symbols-outlined text-blue-600">add_circle</span>เพิ่มเคสใหม่</h3>
      <button onclick="closeModal('modal-new-case')" class="text-slate-400 hover:text-slate-600">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <div class="p-6 space-y-4">
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ชื่อผู้แจ้ง *</label>
          <input type="text" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="ชื่อ-สกุล"/>
        </div>
        <div>
          <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">เบอร์ติดต่อ *</label>
          <input type="tel" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="08X-XXX-XXXX"/>
        </div>
      </div>
      <div>
        <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">สถานที่เกิดเหตุ *</label>
        <input type="text" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="ที่อยู่ / แผนที่"/>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ประเภทเหตุ *</label>
          <select class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2">
            <option>อุบัติเหตุจราจร</option>
            <option>อัคคีภัย</option>
            <option>ผู้ป่วยฉุกเฉิน</option>
            <option>การทะเลาะวิวาท</option>
            <option>อื่นๆ</option>
          </select>
        </div>
        <div>
          <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ระดับความเร่งด่วน *</label>
          <select class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2">
            <option>ปกติ</option>
            <option>เร่งด่วน</option>
            <option>วิกฤต (Critical)</option>
          </select>
        </div>
      </div>
      <div>
        <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">รายละเอียดเพิ่มเติม</label>
        <textarea rows="3" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-600 resize-none" placeholder="บรรยายเหตุการณ์..."></textarea>
      </div>
      <div>
        <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">มอบหมายเจ้าหน้าที่ (ถ้ามี)</label>
        <select class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2">
          <option value="">– มอบหมายทีหลัง –</option>
          <option>ร.ต.อ. สมชาย ใจดี (เขต A – ว่าง)</option>
          <option>ร.ต.อ. ประสิทธิ์ มุ่งมั่น (เขต C – ว่าง)</option>
          <option>ส.ต.อ. อรุณ เช้าตรู่ (เขต F – ว่าง)</option>
          <option>ร.ต.ต. สุนิสา รอบรู้ (เขต H – ว่าง)</option>
        </select>
      </div>
    </div>
    <div class="flex gap-3 px-6 pb-6">
      <button onclick="closeModal('modal-new-case')" class="flex-1 text-xs font-bold border border-slate-200 py-2.5 rounded-xl hover:bg-slate-50">ยกเลิก</button>
      <button onclick="submitNewCase()" class="flex-1 text-xs font-bold bg-blue-600 text-white py-2.5 rounded-xl shadow shadow-blue-500/30 hover:bg-blue-600-dark">บันทึกเคส</button>
    </div>
  </div>
</div>

<!-- ===== MODAL: DISPATCH ===== -->
<div id="modal-dispatch" class="modal-overlay">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-red-50">
      <h3 class="font-bold flex items-center gap-2 text-red-700"><span class="material-symbols-outlined text-red-600">send</span>สั่งการเจ้าหน้าที่ – #EM-007</h3>
      <button onclick="closeModal('modal-dispatch')" class="text-slate-400"><span class="material-symbols-outlined">close</span></button>
    </div>
    <div class="p-6 space-y-4">
      <div class="bg-red-50 border border-red-200 rounded-xl p-3">
        <p class="text-xs font-bold text-red-700">🚨 เคสเร่งด่วน</p>
        <p class="text-[11px] text-slate-600 mt-1">อุบัติเหตุจราจร · ถ.สุขุมวิท ซ.36 · แจ้งเมื่อ 14:22 น.</p>
        <p class="text-[11px] text-slate-600">ผู้แจ้ง: นางสาวอรุณี สดใส</p>
      </div>
      <div>
        <label class="text-[10px] font-bold text-slate-400 uppercase mb-2 block">เลือกเจ้าหน้าที่ (เรียงตามระยะทาง)</label>
        <div class="space-y-2">
          <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-blue-500/50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600/5">
            <input type="radio" name="officer" class="text-blue-600"/>
            <div class="flex-1">
              <p class="text-xs font-bold">ร.ต.อ. สมชาย ใจดี</p>
              <p class="text-[10px] text-slate-400">เขต A · ห่าง ~1.2 กม. · ว่าง</p>
            </div>
            <span class="text-[10px] text-emerald-600 font-bold">~4 นาที</span>
          </label>
          <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-blue-500/50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600/5">
            <input type="radio" name="officer" class="text-blue-600"/>
            <div class="flex-1">
              <p class="text-xs font-bold">ร.ต.ต. สุนิสา รอบรู้</p>
              <p class="text-[10px] text-slate-400">เขต H · ห่าง ~2.1 กม. · ว่าง</p>
            </div>
            <span class="text-[10px] text-amber-500 font-bold">~7 นาที</span>
          </label>
          <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-blue-500/50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600/5">
            <input type="radio" name="officer" class="text-blue-600"/>
            <div class="flex-1">
              <p class="text-xs font-bold">ส.ต.อ. อรุณ เช้าตรู่</p>
              <p class="text-[10px] text-slate-400">เขต F · ห่าง ~3.5 กม. · ว่าง</p>
            </div>
            <span class="text-[10px] text-red-500 font-bold">~12 นาที</span>
          </label>
        </div>
      </div>
      <div>
        <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">หมายเหตุการสั่งการ</label>
        <textarea rows="2" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="คำสั่ง/ข้อมูลเพิ่มเติม..."></textarea>
      </div>
    </div>
    <div class="flex gap-3 px-6 pb-6">
      <button onclick="closeModal('modal-dispatch')" class="flex-1 text-xs font-bold border border-slate-200 py-2.5 rounded-xl hover:bg-slate-50">ยกเลิก</button>
      <button onclick="confirmDispatch()" class="flex-1 text-xs font-bold bg-red-500 text-white py-2.5 rounded-xl shadow shadow-red-500/30 hover:bg-red-600">🚔 ยืนยันสั่งการ</button>
    </div>
  </div>
</div>

<!-- ===== TOAST ===== -->
<div id="toast" class="fixed bottom-6 right-6 z-[300] hidden">
  <div class="bg-slate-900 text-white text-sm font-bold px-5 py-3 rounded-xl shadow-xl flex items-center gap-3">
    <span class="material-symbols-outlined text-emerald-400">check_circle</span>
    <span id="toast-msg">บันทึกสำเร็จ</span>
  </div>
</div>

<script>
  // ===== CLOCK =====
  function updateClock() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('th-TH', { hour12: false });
    const dateStr = now.toLocaleDateString('th-TH', { weekday: 'short', day: 'numeric', month: 'short' });
    document.getElementById('live-clock').textContent = timeStr;
    document.getElementById('live-date').textContent = dateStr;
    document.getElementById('last-update').textContent = timeStr;
  }
  setInterval(updateClock, 1000);
  updateClock();

  // ===== TABS =====
  function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.nav-icon-btn').forEach(btn => {
      btn.classList.remove('active');
      btn.querySelector('.material-symbols-outlined').style.color = '';
    });
    const target = document.getElementById('tab-' + tab);
    if (target) target.classList.remove('hidden');
    // Highlight sidebar icon
    document.querySelectorAll('.nav-icon-btn').forEach(btn => {
      if (btn.dataset.tab === tab) {
        btn.classList.add('active');
        btn.querySelector('.material-symbols-outlined').style.color = '#137fec';
      }
    });
  }

  // ===== MODALS =====
  function openModal(id) { document.getElementById(id).classList.add('open'); }
  function closeModal(id) { document.getElementById(id).classList.remove('open'); }
  function openDispatchModal() { openModal('modal-dispatch'); }

  // Click outside to close
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) overlay.classList.remove('open');
    });
  });

  // ===== NOTIFICATIONS DROPDOWN =====
  function toggleNotifs() {
    const panel = document.getElementById('notif-panel');
    panel.classList.toggle('hidden');
  }
  document.addEventListener('click', (e) => {
    const bell = document.getElementById('bell-btn');
    const panel = document.getElementById('notif-panel');
    if (!bell.contains(e.target) && !panel.contains(e.target)) {
      panel.classList.add('hidden');
    }
  });

  // ===== TOAST =====
  function showToast(msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
  }

  function submitNewCase() {
    closeModal('modal-new-case');
    showToast('✅ บันทึกเคสใหม่สำเร็จ');
    // Update KPI
    const kpiNew = document.getElementById('kpi-new');
    kpiNew.textContent = parseInt(kpiNew.textContent) + 1;
    const kpiTotal = document.getElementById('kpi-total');
    kpiTotal.textContent = parseInt(kpiTotal.textContent) + 1;
  }

  function confirmDispatch() {
    closeModal('modal-dispatch');
    showToast('🚔 สั่งการเจ้าหน้าที่สำเร็จ');
  }

  // ===== SIMULATE REAL-TIME UPDATES =====
  let newCaseTimer = 0;
  setInterval(() => {
    newCaseTimer++;
    // Every 30 seconds simulate new case notification
    if (newCaseTimer % 30 === 0) {
      addNewAlert();
    }
  }, 1000);

  function addNewAlert() {
    const alertsPanel = document.getElementById('alerts-panel');
    const div = document.createElement('div');
    div.className = 'flex gap-3 p-4 bg-blue-50 alert-item';
    div.innerHTML = `
      <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
        <span class="material-symbols-outlined text-blue-600 text-sm">add_alert</span>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-xs font-bold">🔔 เคสใหม่ (Real-time)</p>
        <p class="text-[11px] text-slate-500">เพิ่งเข้ามาในระบบ</p>
        <span class="text-[10px] text-slate-400 mono">เมื่อสักครู่</span>
      </div>`;
    alertsPanel.insertBefore(div, alertsPanel.firstChild);
    // Remove oldest if > 6
    if (alertsPanel.children.length > 6) {
      alertsPanel.removeChild(alertsPanel.lastChild);
    }
  }

  // ===== FILTER CASES (placeholder) =====
  function filterCases() {
    // In real implementation, would filter table rows
  }

  // ===== INIT =====
  switchTab('overview');
</script>
</div>
@endsection