@extends('layouts.theme')

@section('content')

<div class="bg-background-light text-text-main font-display antialiased min-h-screen flex flex-col overflow-x-hidden mt-[61px]">
  
    <div class="flex-grow flex flex-col max-w-[1440px] mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 gap-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-text-main mb-2">ประเภทการแจ้งเหตุ</h2>
                <p class="text-text-sub text-sm">จัดการหมวดหมู่และประเภทของการแจ้งเหตุฉุกเฉินในระบบ (Emergency Types)</p>
            </div>
            <div class="flex gap-3">
                <button onclick="toggleModal('formModal', 'add')" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-sm transition-all">
                    <span class="material-symbols-outlined mr-2 text-lg">add</span>
                    เพิ่มประเภทการแจ้งเหตุ
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative flex items-center gap-2">
            <span class="material-symbols-outlined text-green-500">check_circle</span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative flex items-center gap-2">
            <span class="material-symbols-outlined text-red-500">error</span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
        @endif

        <div class="bg-surface-light rounded-xl border border-border-color shadow-sm flex flex-col">
            <div class="flex-wrap px-6 py-5 border-b border-border-color flex justify-between items-center bg-gray-50/30">
                <div class="flex items-center gap-3">
                    <h3 class="font-bold text-lg text-text-main">รายการประเภทการแจ้งเหตุ</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-text-sub border border-gray-200">ทั้งหมด {{ count($emergencyTypes) }} รายการ</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                        <input class="pl-9 pr-4 py-2 text-sm border-border-color rounded-lg focus:ring-primary focus:border-primary w-64 bg-white" placeholder="ค้นหาประเภท..." type="text" id="searchInput" onkeyup="searchTable()" />
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="dataTable">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-border-color text-xs uppercase tracking-wider text-text-sub font-semibold">
                            <th class="px-6 py-4 w-[50%] min-w-[200px]">ชื่อประเภทการแจ้งเหตุ</th>
                            <th class="px-6 py-4 w-[20%] min-w-[150px]">สถานะ</th>
                            <th class="px-6 py-4 w-[20%] text-right min-w-[120px]">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-color bg-white">
                        @forelse($emergencyTypes as $type)
                        <tr class="group hover:bg-primary-light/30 transition-colors {{ $type->status == 'Inactive' ? 'bg-gray-50/30' : '' }}">
                            <td class="px-6 py-4 align-middle search-target">
                                <div class="font-bold text-sm {{ $type->status == 'Inactive' ? 'text-gray-400' : 'text-text-main' }}">
                                    {{ $type->name_emergency }}
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" 
                                           {{ $type->status == 'Active' ? 'checked' : '' }} 
                                           onchange="updateStatus({{ $type->id }}, this.checked)">
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                                    <span class="ml-2 text-sm font-medium {{ $type->status == 'Active' ? 'text-green-600' : 'text-gray-400' }}" id="status-text-{{ $type->id }}">
                                        {{ $type->status == 'Active' ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                    </span>
                                </label>
                            </td>
                            <td class="px-6 py-4 align-middle text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="toggleModal('formModal', 'edit', '{{ $type->id }}', '{{ $type->name_emergency }}', '{{ $type->status }}')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="แก้ไข">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <button onclick="toggleModal('deleteModal', 'delete', '{{ $type->id }}')" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="ลบ">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-text-sub">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-gray-300">category</span>
                                    <p>ยังไม่มีข้อมูลประเภทการแจ้งเหตุ</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="formModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
    <form action="{{ url('/emergency-types/store') }}" method="POST" class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform scale-95 transition-transform duration-300" id="formModalContent">
        @csrf
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-lg text-text-main" id="modalTitle">เพิ่มประเภทการแจ้งเหตุ</h3>
            <button type="button" onclick="toggleModal('formModal')" class="text-gray-400 hover:text-gray-600 rounded-lg p-1 hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <div class="px-6 py-6 space-y-4">
            <input type="hidden" id="formId" name="id">
            
            <div>
                <label class="block text-sm font-medium text-text-main mb-1.5">ชื่อประเภทการแจ้งเหตุ <span class="text-red-500">*</span></label>
                <input type="text" id="name_emergency" name="name_emergency" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors text-sm" placeholder="เช่น อุบัติเหตุจราจร, อัคคีภัย" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-text-main mb-1.5">สถานะ</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors text-sm text-text-main">
                    <option value="Active">เปิดใช้งาน (Active)</option>
                    <option value="Inactive">ปิดใช้งาน (Inactive)</option>
                </select>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
            <button type="button" onclick="toggleModal('formModal')" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">ยกเลิก</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-blue-700 shadow-sm transition-colors" id="btnSave">บันทึกข้อมูล</button>
        </div>
    </form>
</div>

<div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
    <form action="{{ url('/emergency-types/destroy') }}" method="POST" class="bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden transform scale-95 transition-transform duration-300" id="deleteModalContent">
        @csrf
        <div class="px-6 py-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center text-red-600 mx-auto mb-4">
                <span class="material-symbols-outlined text-[32px]">warning</span>
            </div>
            <h3 class="font-bold text-lg text-text-main mb-2">ยืนยันการลบข้อมูล</h3>
            <p class="text-sm text-text-sub">คุณแน่ใจหรือไม่ว่าต้องการลบประเภทการแจ้งเหตุนี้? ข้อมูลที่ถูกลบจะไม่สามารถกู้คืนได้</p>
            <input type="hidden" id="deleteId" name="id">
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-center gap-3">
            <button type="button" onclick="toggleModal('deleteModal')" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors w-full">ยกเลิก</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-sm transition-colors w-full">ยืนยันการลบ</button>
        </div>
    </form>
</div>

<script>
    function toggleModal(modalID, action = '', id = '', name = '', status = '') {
        const modal = document.getElementById(modalID);
        const modalContent = document.getElementById(modalID + 'Content');
        
        if (modal.classList.contains('hidden')) {
            // โชว์ Modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);

            // เซ็ตข้อมูลลงฟอร์มตาม Action
            if(modalID === 'formModal') {
                const title = document.getElementById('modalTitle');
                const btnSave = document.getElementById('btnSave');
                const inputId = document.getElementById('formId');
                const inputName = document.getElementById('name_emergency');
                const inputStatus = document.getElementById('status');
                
                if(action === 'add') {
                    title.innerText = 'เพิ่มประเภทการแจ้งเหตุ';
                    btnSave.innerText = 'บันทึกข้อมูล';
                    inputId.value = '';
                    inputName.value = '';
                    inputStatus.value = 'Active';
                } else if (action === 'edit') {
                    title.innerText = 'แก้ไขประเภทการแจ้งเหตุ';
                    btnSave.innerText = 'บันทึกการเปลี่ยนแปลง';
                    inputId.value = id;
                    inputName.value = name;
                    inputStatus.value = status;
                }
            } else if (modalID === 'deleteModal') {
                document.getElementById('deleteId').value = id;
            }

        } else {
            // ปิด Modal
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }
    }

    function updateStatus(id, isChecked) {
        const newStatus = isChecked ? 'Active' : 'Inactive';
        const statusTextSpan = document.getElementById(`status-text-${id}`);
        const trElement = statusTextSpan.closest('tr');
        const nameElement = trElement.querySelector('.search-target div');

        if (isChecked) {
            statusTextSpan.innerText = 'เปิดใช้งาน';
            statusTextSpan.classList.remove('text-gray-400');
            statusTextSpan.classList.add('text-green-600');
            trElement.classList.remove('bg-gray-50/30');
            nameElement.classList.remove('text-gray-400');
            nameElement.classList.add('text-text-main');
        } else {
            statusTextSpan.innerText = 'ปิดใช้งาน';
            statusTextSpan.classList.remove('text-green-600');
            statusTextSpan.classList.add('text-gray-400');
            trElement.classList.add('bg-gray-50/30');
            nameElement.classList.remove('text-text-main');
            nameElement.classList.add('text-gray-400');
        }

        fetch("{{ url('/emergency-types/update-status') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                id: id, 
                status: newStatus 
            })
        })
        .then(response => response.json())
        .then(data => {
            if(!data.success) {
                alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
            location.reload();
        });
    }

    // ฟังก์ชันค้นหาข้อมูลในตาราง (แถมให้ครับ)
    function searchTable() {
        let input = document.getElementById("searchInput");
        let filter = input.value.toUpperCase();
        let table = document.getElementById("dataTable");
        let tr = table.getElementsByTagName("tr");

        // ข้าม <thead> (i = 1)
        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].querySelector('.search-target');
            if (td) {
                let txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }
</script>

@endsection