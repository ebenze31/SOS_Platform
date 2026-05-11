@extends('layouts.theme') 

@section('content')
<main class="pt-20 p-6 max-w-7xl mx-auto w-full">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">จัดการสมาชิก</h1>
            <p class="text-sm text-slate-500 mt-1">ตรวจสอบและจัดการข้อมูลผู้ใช้งานและเจ้าหน้าที่ในระบบ</p>
        </div>
        @if(auth()->check() && auth()->user()->role == 'admin' && auth()->user()->userCommand && auth()->user()->userCommand->command_role == 'supervisor')
            <button onclick="openModal('createCommandModal')" class="flex items-center gap-2 bg-primary hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                สร้างสมาชิกศูนย์ควบคุม
            </button>
        @endif
    </div>

    <div class="flex items-center gap-2 mb-6 border-b border-slate-200 pb-px">
        <button onclick="switchTab('users')" id="tab-btn-users" class="tab-btn active px-4 py-2 text-sm font-medium border-b-2 border-primary text-primary transition-colors">
            ผู้ใช้ทั้งหมด
        </button>
        <button onclick="switchTab('commands')" id="tab-btn-commands" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors">
            เจ้าหน้าที่ศูนย์สั่งการ
        </button>
        <button onclick="switchTab('officers')" id="tab-btn-officers" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors">
            เจ้าหน้าที่ออกช่วยเหลือ
        </button>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        
        <div id="tab-users" class="tab-content block">
            <div class="p-4 border-b border-slate-200 bg-slate-50/50">
                <div class="relative max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400">search</span>
                    </div>
                    <input type="text" onkeyup="searchTable('users-tbody', this.value)" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm shadow-sm" placeholder="ค้นหาชื่อ, เบอร์โทร, สถานะ..." />
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">ชื่อ</th>
                            <th class="px-6 py-4 font-semibold">เบอร์โทรศัพท์</th>
                            <th class="px-6 py-4 font-semibold">เพศ / วันเกิด</th>
                            <th class="px-6 py-4 font-semibold text-center">Role</th>
                            <th class="px-6 py-4 font-semibold text-center">ขอความช่วยเหลือ (ครั้ง)</th>
                            <th class="px-6 py-4 font-semibold">เป็นสมาชิกเมื่อ</th>
                            <th class="px-6 py-4 font-semibold">สถานะเข้าสู่ระบบ</th>
                            <th class="px-6 py-4 font-semibold text-right">เปลี่ยน</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody" class="divide-y divide-slate-200 text-sm">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->phone }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->gender }} <br> <span class="text-xs text-slate-400">{{ $user->birthday }}</span></td>
                            <td class="px-6 py-4 text-center font-medium">
                                @if($user->role == 'admin')
                                    <span class="text-primary">ศูนย์สั่งการ</span>
                                @elseif($user->role == 'officer')
                                    <span class="text-orange-500">เจ้าหน้าที่</span>
                                @else
                                    <span class="text-slate-500">ผู้ใช้ทั่วไป</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-medium text-primary">{{ $user->emergencys_count }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4">
                                <span id="status-badge-{{ $user->id }}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    // เช็คว่าคนที่กำลังถูกลูป(แถวนี้) เป็นระดับ Supervisor หรือไม่
                                    $isTargetSupervisor = $user->role == 'admin' && $user->userCommand && $user->userCommand->command_role == 'supervisor';
                                    
                                    // เช็คว่าคนที่กำลังถูกลูป(แถวนี้) เป็นระดับ Command หรือไม่
                                    $isTargetCommand = $user->role == 'admin' && $user->userCommand && $user->userCommand->command_role == 'command';

                                    // เช็คสิทธิ์ของคนที่กำลังใช้งานระบบอยู่
                                    $currentUser = auth()->user();
                                    $isCurrentUserSupervisor = $currentUser && $currentUser->role == 'admin' && $currentUser->userCommand && $currentUser->userCommand->command_role == 'supervisor';
                                    $isCurrentUserCommand = $currentUser && $currentUser->role == 'admin' && $currentUser->userCommand && $currentUser->userCommand->command_role == 'command';
                                    
                                    // ตัวแปรกำหนดว่าจะแสดงปุ่มเปลี่ยนสถานะหรือไม่
                                    $canChangeStatus = false;

                                    if ($isCurrentUserSupervisor) {
                                        // Supervisor เปลี่ยนได้ทุกคน ยกเว้น Target ที่เป็น Supervisor
                                        if (!$isTargetSupervisor) {
                                            $canChangeStatus = true;
                                        }
                                    } elseif ($isCurrentUserCommand) {
                                        // Command เปลี่ยนได้เฉพาะ Officer และ Null (ห้ามเปลี่ยน Supervisor และ Command)
                                        if (!$isTargetSupervisor && !$isTargetCommand) {
                                            $canChangeStatus = true;
                                        }
                                    }
                                @endphp

                                @if($canChangeStatus)
                                    <button onclick="toggleUserStatus({{ $user->id }})" class="text-slate-400 hover:text-primary transition-colors cursor-pointer" title="เปลี่ยนสถานะ">
                                        <span class="material-symbols-outlined text-[20px]">sync_alt</span>
                                    </button>
                                @else
                                    <span class="text-slate-200 material-symbols-outlined text-[20px] cursor-not-allowed" title="ไม่มีสิทธิ์เปลี่ยนสถานะ">
                                        block
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $users->fragment('users')->links() }}
            </div>
        </div>

        <div id="tab-commands" class="tab-content hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50/50">
                <div class="relative max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400">search</span>
                    </div>
                    <input type="text" onkeyup="searchTable('commands-tbody', this.value)" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm shadow-sm" placeholder="ค้นหาชื่อเจ้าหน้าที่, ผู้สร้าง, Role, พื้นที่..." />
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">ชื่อศูนย์สั่งการ</th>
                            <th class="px-6 py-4 font-semibold">ผู้สร้าง (Creator)</th>
                            <th class="px-6 py-4 font-semibold text-center">Role</th>
                            <th class="px-6 py-4 font-semibold">พื้นที่ดูแล</th> {{-- เพิ่มหัวตารางพื้นที่ --}}
                            <th class="px-6 py-4 font-semibold text-center">สั่งการ (เสร็จ/ดำเนินการ/รวม)</th>
                            <th class="px-6 py-4 font-semibold">เริ่มงานเมื่อ</th>
                            <th class="px-6 py-4 font-semibold">สถานะ</th>
                            <th class="px-6 py-4 font-semibold text-right">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="commands-tbody" class="divide-y divide-slate-200 text-sm">
                        @foreach($commands as $command)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $command->name_command }}</td>
                            
                            <td class="px-6 py-4 text-slate-600">
                                {{ $command->creator_info ? $command->creator_info->name_command : 'ผู้ดูแล' }}
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                @if($command->command_role == 'supervisor')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-700">Supervisor</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">Command</span>
                                @endif
                            </td>

                            {{-- เพิ่มข้อมูลพื้นที่ --}}
                            <td class="px-6 py-4">
                                @if($command->command_role == 'supervisor')
                                    <span class="text-xs font-bold text-slate-400">(Supervisor)</span>
                                @else
                                    @if(isset($areas[$command->area_id]))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $areas[$command->area_id] }}
                                        </span>
                                    @else
                                        <span class="text-xs text-red-500">ไม่พบพื้นที่</span>
                                    @endif
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-center font-medium">
                                <span class="text-green-600" title="เสร็จสิ้น">{{ $command->success_ops ?? 0 }}</span> / 
                                <span class="text-orange-500" title="กำลังดำเนินการ">{{ $command->pending_ops ?? 0 }}</span> / 
                                <span class="text-slate-900" title="รวมทั้งหมด">{{ $command->total_ops ?? 0 }}</span>
                            </td>
                            
                            <td class="px-6 py-4 text-slate-600">{{ $command->created_at->diffForHumans() }}</td>
                            
                            <td class="px-6 py-4">
                                <span id="cmd-status-badge-{{ $command->id }}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $command->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $command->status }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 text-right">
                                @php
                                    $isTargetSupervisor = $command->command_role == 'supervisor';
                                    $currentUser = auth()->user();
                                    $isCurrentUserSupervisor = $currentUser && $currentUser->role == 'admin' && $currentUser->userCommand && $currentUser->userCommand->command_role == 'supervisor';
                                    
                                    $canChangeCommandStatus = false;
                                    if ($isCurrentUserSupervisor && !$isTargetSupervisor) {
                                        $canChangeCommandStatus = true;
                                    }
                                @endphp

                                @if($canChangeCommandStatus)
                                    <button onclick="toggleCommandStatus({{ $command->id }})" class="text-slate-400 hover:text-primary transition-colors cursor-pointer" title="เปลี่ยนสถานะ">
                                        <span class="material-symbols-outlined text-[20px]">sync_alt</span>
                                    </button>
                                @else
                                    <span class="text-slate-200 material-symbols-outlined text-[20px] cursor-not-allowed" title="ไม่สามารถเปลี่ยนสถานะระดับ Supervisor ได้">
                                        block
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $commands->fragment('commands')->links() }}
            </div>
        </div>

        <div id="tab-officers" class="tab-content hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50/50">
                <div class="relative max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400">search</span>
                    </div>
                    <input type="text" onkeyup="searchTable('officers-tbody', this.value)" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm shadow-sm" placeholder="ค้นหาชื่อเจ้าหน้าที่, ประเภทรถ, พื้นที่..." />
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">เจ้าหน้าที่</th>
                            <th class="px-6 py-4 font-semibold">พื้นที่ดูแล</th>
                            <th class="px-6 py-4 font-semibold text-center">ช่วยเหลือ/ปฏิเสธ</th>
                            <th class="px-6 py-4 font-semibold">เริ่มงานเมื่อ</th>
                            <th class="px-6 py-4 font-semibold">สถานะการทำงาน</th>
                            <th class="px-6 py-4 font-semibold">สถานะบัญชี</th>
                            <th class="px-6 py-4 font-semibold text-right">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="officers-tbody" class="divide-y divide-slate-200 text-sm">
                        @foreach($officers as $officer)
                        <tr class="hover:bg-slate-50 transition-colors">
                            
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $officer->name_officer }}</div>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">
                                        {{ $officer->vehicle_type }}
                                    </span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 text-slate-600">
                                @php
                                    $areaIds = json_decode($officer->area_id, true) ?? [];
                                    $areaNames = [];
                                    foreach($areaIds as $id) {
                                        if(isset($areas[$id])) {
                                            $areaNames[] = $areas[$id];
                                        }
                                    }
                                    echo empty($areaNames) ? '<span class="text-slate-400">ไม่ได้ระบุ</span>' : implode(', ', $areaNames);
                                @endphp
                            </td>
                            
                            <td class="px-6 py-4 text-center font-medium">
                                <span class="text-primary" title="ช่วยเหลือ">{{ $officer->amount_help ?? 0 }}</span> / 
                                <span class="text-red-500" title="ปฏิเสธ">{{ $officer->amount_refuse ?? 0 }}</span>
                            </td>
                            
                            <td class="px-6 py-4 text-slate-600">{{ $officer->created_at->diffForHumans() }}</td>
                            
                            <td class="px-6 py-4">
                                @php
                                    $workStatusClass = 'bg-slate-100 text-slate-700';
                                    if($officer->status == 'Standby') $workStatusClass = 'bg-green-100 text-green-700';
                                    if($officer->status == 'Helping') $workStatusClass = 'bg-blue-100 text-blue-700';
                                @endphp
                                <span id="off-work-badge-{{ $officer->id }}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $workStatusClass }}">
                                    {{ $officer->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @php $accStatus = $officer->user ? $officer->user->status : 'Unknown'; @endphp
                                <span id="off-acc-badge-{{ $officer->id }}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $accStatus == 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $accStatus }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 text-right">
                                <button onclick="toggleOfficerStatus({{ $officer->id }})" class="text-slate-400 hover:text-primary transition-colors cursor-pointer" title="เปลี่ยนสถานะบัญชีและระงับการทำงาน">
                                    <span class="material-symbols-outlined text-[20px]">sync_alt</span>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $officers->fragment('officers')->links() }}
            </div>
        </div>

    </div>
</main>

<div id="createCommandModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal('createCommandModal')"></div>
    
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full">
            <div class="bg-white px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-lg leading-6 font-semibold text-slate-900">สร้างสมาชิกศูนย์ควบคุม</h3>
                <button onclick="closeModal('createCommandModal')" class="text-slate-400 hover:text-slate-500">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div id="createCommandFormSection">
                <form id="createCommandForm" onsubmit="submitCreateCommand(event)">
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">ชื่อเจ้าหน้าที่ศูนย์สั่งการ</label>
                            <input type="text" name="name_command" required class="block w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">ชื่อผู้ใช้ (Username)</label>
                            <input type="text" name="username" required class="block w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">รหัสผ่าน (Password)</label>
                            <div class="flex gap-2">
                                <input type="text" name="password" id="command_password" required class="block w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm">
                                <button type="button" onclick="generatePassword()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 transition-colors whitespace-nowrap">
                                    สุ่มรหัสผ่าน
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">บทบาท (Command Role)</label>
                            <select name="command_role" id="select_command_role" onchange="toggleAreaRequirement()" required class="block w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm">
                                <option value="command">Command</option>
                                <option value="supervisor">Supervisor</option>
                            </select>
                        </div>

                        {{-- เพิ่มฟิลด์เลือกพื้นที่ (ซ่อนไว้ก่อนถ้าเลือก Supervisor) --}}
                        <div id="area_selection_wrapper">
                            <label class="block text-sm font-medium text-slate-700 mb-1">พื้นที่รับผิดชอบ <span class="text-red-500">*</span></label>
                            <select name="area_id" id="select_area_id" required class="block w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm">
                                <option value="">-- เลือกพื้นที่ดูแล --</option>
                                @foreach($areas as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('createCommandModal')" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                            ยกเลิก
                        </button>
                        <button type="submit" id="submitCommandBtn" class="px-4 py-2 bg-primary hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                            บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>

            <div id="createCommandSuccessSection" class="hidden">
                <div class="px-6 py-8 text-center">
                    <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-100 mb-4">
                        <span class="material-symbols-outlined text-green-600 text-3xl">check_circle</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">สร้างสมาชิกสำเร็จเรียบร้อย</h3>
                    <p class="text-sm text-slate-500 mb-6">กรุณาคัดลอกข้อมูลด้านล่างเพื่อส่งให้เจ้าหน้าที่เข้าใช้งานระบบ</p>
                    
                    <div class="relative text-left">
                        <textarea id="credentialsTextarea" readonly rows="7" class="block w-full px-4 py-3 border border-slate-200 rounded-lg bg-slate-50 text-slate-700 text-sm focus:outline-none resize-none font-mono"></textarea>
                        <button type="button" onclick="copyCredentials()" class="absolute top-2 right-2 p-1.5 bg-white border border-slate-200 rounded-md text-slate-500 hover:text-primary hover:border-primary transition-colors shadow-sm" title="คัดลอกข้อมูล">
                            <span class="material-symbols-outlined text-[18px]">content_copy</span>
                        </button>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end">
                    <button type="button" onclick="window.location.reload()" class="px-4 py-2 bg-primary hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                        ปิดและโหลดข้อมูลใหม่
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // สุ่มรหัสผ่าน 8 หลัก
    function generatePassword() {
        const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        let password = "";
        for (let i = 0; i < 8; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('command_password').value = password;
    }

    function submitCreateCommand(event) {
        event.preventDefault();
        
        const btn = document.getElementById('submitCommandBtn');
        btn.disabled = true;
        btn.innerHTML = 'กำลังบันทึก...';

        const form = document.getElementById('createCommandForm');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        fetch("{{ route('members.command.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(async response => {
            // ถ้าเซิร์ฟเวอร์ตอบกลับมาว่ามี Error (เช่น 422 Validation Failed)
            if (!response.ok) {
                const errorData = await response.json();
                throw errorData; // โยนไปให้ catch ด้านล่างจัดการ
            }
            return response.json();
        })
        .then(res => {
            if(res.success) {
                // ซ่อนฟอร์ม และแสดงหน้า Success
                document.getElementById('createCommandFormSection').classList.add('hidden');
                document.getElementById('createCommandSuccessSection').classList.remove('hidden');
                
                // จัดรูปแบบข้อความเพื่อแสดงใน textarea
                const text = `ข้อมูลการเข้าสู่ระบบศูนย์สั่งการ\n------------------------\nชื่อเจ้าหน้าที่: ${res.data.name_command}\nชื่อผู้ใช้ (Username): ${res.data.username}\nรหัสผ่าน (Password): ${res.data.password}\nบทบาท: ${res.data.command_role}`;
                document.getElementById('credentialsTextarea').value = text;
            } else {
                alert('ไม่สามารถสร้างสมาชิกได้: ' + (res.message || 'ข้อมูลไม่ถูกต้อง'));
                btn.disabled = false;
                btn.innerHTML = 'บันทึกข้อมูล';
            }
        })
        .catch(err => {
            console.error('Error:', err);
            
            // แจ้งเตือนกรณีข้อมูลไม่ผ่านเงื่อนไข (เช่น Username ซ้ำ, ไม่ได้กรอก Area)
            if (err.errors) {
                let errorMsg = 'กรุณาตรวจสอบข้อมูล:\n';
                for (let field in err.errors) {
                    errorMsg += `- ${err.errors[field][0]}\n`;
                }
                alert(errorMsg);
            } else {
                alert('เกิดข้อผิดพลาด: ' + (err.message || 'เชื่อมต่อเซิร์ฟเวอร์ไม่สำเร็จ'));
            }

            btn.disabled = false;
            btn.innerHTML = 'บันทึกข้อมูล';
        });
    }

    function copyCredentials() {
        const textarea = document.getElementById('credentialsTextarea');
        textarea.select();
        document.execCommand('copy');
        alert('คัดลอกข้อมูลเรียบร้อยแล้ว');
    }
</script>

<script>
    // เปลี่ยนแท็บตาราง
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.remove('block');
            el.classList.add('hidden');
        });
        
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-primary', 'text-primary', 'active');
            el.classList.add('border-transparent', 'text-slate-500');
        });

        document.getElementById('tab-' + tabId).classList.remove('hidden');
        document.getElementById('tab-' + tabId).classList.add('block');
        
        document.getElementById('tab-btn-' + tabId).classList.remove('border-transparent', 'text-slate-500');
        document.getElementById('tab-btn-' + tabId).classList.add('border-primary', 'text-primary', 'active');

        window.history.replaceState(null, null, '#' + tabId);
    }

    document.addEventListener('DOMContentLoaded', () => {

        const hash = window.location.hash.replace('#', '');
        
        // กำหนดชื่อแท็บที่อนุญาตให้เปิดได้ (ป้องกัน Error กรณีพิมพ์มั่ว)
        const validTabs = ['users', 'commands', 'officers'];
        
        if (validTabs.includes(hash)) {
            switchTab(hash);
        }

        // เรียกตอนโหลดเว็บ
        if(document.getElementById('select_command_role')) {
            toggleAreaRequirement();
        }
    });

    // ค้นหาข้อมูลในตาราง
    function searchTable(tbodyId, keyword) {
        const filter = keyword.toLowerCase();
        const rows = document.getElementById(tbodyId).getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const rowText = rows[i].textContent || rows[i].innerText;
            if (rowText.toLowerCase().indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    // สคริปต์สำหรับเปิดและปิด Modal
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function toggleUserStatus(userId) {
        fetch("{{ url('/') }}"+`/members/toggle-status/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const badge = document.getElementById(`status-badge-${userId}`);
                if(data.status === 'Active') {
                    badge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700';
                    badge.innerText = 'Active';
                } else {
                    badge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700';
                    badge.innerText = 'Inactive';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // เปลี่ยนสถานะเจ้าหน้าที่ศูนย์สั่งการ
    function toggleCommandStatus(commandId) {
        fetch("{{ url('/') }}"+`/members/command/toggle-status/${commandId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const badge = document.getElementById(`cmd-status-badge-${commandId}`);
                if(data.status === 'Active') {
                    badge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700';
                    badge.innerText = 'Active';
                } else {
                    badge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700';
                    badge.innerText = 'Inactive';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // เปลี่ยนสถานะเจ้าหน้าที่ออกช่วยเหลือ
    function toggleOfficerStatus(officerId) {
        fetch("{{ url('/') }}"+`/members/officer/toggle-status/${officerId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {

                const workBadge = document.getElementById(`off-work-badge-${officerId}`);
                workBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700';
                workBadge.innerText = data.officer_status;

                const accBadge = document.getElementById(`off-acc-badge-${officerId}`);
                if(data.user_status === 'Active') {
                    accBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700';
                    accBadge.innerText = 'Active';
                } else {
                    accBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700';
                    accBadge.innerText = 'Inactive';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function toggleAreaRequirement() {
        const roleSelect = document.getElementById('select_command_role').value;
        const areaWrapper = document.getElementById('area_selection_wrapper');
        const areaSelect = document.getElementById('select_area_id');

        if (roleSelect === 'supervisor') {
            // ถ้าเป็น Supervisor ให้ซ่อนและยกเลิกบังคับกรอก (required)
            areaWrapper.classList.add('hidden');
            areaSelect.removeAttribute('required');
            areaSelect.value = ''; // ล้างค่า
        } else {
            // ถ้าเป็น Command ให้โชว์และบังคับกรอก
            areaWrapper.classList.remove('hidden');
            areaSelect.setAttribute('required', 'required');
        }
    }
</script>
@endsection