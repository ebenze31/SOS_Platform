<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_officer;
use App\Models\Area;
use Illuminate\Support\Facades\DB;
use App\User;

class CommandController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลพื้นที่ทั้งหมดมาเก็บไว้
        $areas = Area::all()->keyBy('id');

        // ดึงเจ้าหน้าที่ทั้งหมดที่มีข้อมูล status_register
        $officers = User_officer::whereNotNull('status_register')->get();

        $pendingRequests = [];

        // วนลูปแกะ JSON เพื่อหาว่ามีคำขอไหนที่รออนุมัติบ้าง
        foreach ($officers as $officer) {
            $statusArray = json_decode($officer->status_register, true) ?? [];
            
            foreach ($statusArray as $item) {
                if ($item['status'] === 'Pending') {
                    $areaId = $item['area_id'];
                    $area = $areas->get($areaId);

                    if ($area) {
                        $pendingRequests[] = [
                            'officer_id'   => $officer->id,
                            'name_officer' => $officer->name_officer,
                            'vehicle_type' => $officer->vehicle_type,
                            'area_id'      => $area->id,
                            'area_name'    => $area->name_area,
                            'area_type'    => $area->type,
                            'requested_at' => $officer->updated_at,
                        ];
                    }
                }
            }
        }

        return view('admin.requests', compact('pendingRequests'));
    }

    public function manage_request(Request $request)
    {
        $request->validate([
            'officer_id' => 'required|integer',
            'area_id'    => 'required|integer',
            'action'     => 'required|in:approve,reject',
            'remark'     => 'nullable|string'
        ]);

        $officer = User_officer::findOrFail($request->officer_id);
        $statusArray = json_decode($officer->status_register, true) ?? [];
        $existingAreas = json_decode($officer->area_id, true) ?? [];

        // 1. อัปเดตสถานะใน JSON ของฝั่งเจ้าหน้าที่
        foreach ($statusArray as $index => $item) {
            if ($item['area_id'] == $request->area_id) {
                if ($request->action === 'approve') {
                    $statusArray[$index]['status'] = 'Approve';
                    $statusArray[$index]['remark'] = null;
                    
                    // ถ้าอนุมัติ -> เพิ่ม area_id ลงในคอลัมน์ area_id (อาร์เรย์พื้นที่ที่ใช้งานได้จริง)
                    if (!in_array((string)$request->area_id, $existingAreas)) {
                        $existingAreas[] = (string)$request->area_id;
                    }

                    // --- จัดการ Role ของ User ---
                    if ($officer->user_id) {
                        $user = User::find($officer->user_id);
                        if ($user) {
                            if (empty($user->role)) {
                                $user->role = 'officer';
                                $user->save();
                            }
                        }
                    }

                } else {
                    $statusArray[$index]['status'] = 'Reject';
                    $statusArray[$index]['remark'] = $request->remark;
                }
                break;
            }
        }

        $officer->status_register = json_encode($statusArray);
        $officer->area_id = json_encode($existingAreas);
        $officer->save(); // บันทึกข้อมูลเจ้าหน้าที่ก่อน

        // 2. จัดการ Auto Assign และ Priority ไปยังตาราง areas (เฉพาะกรณีอนุมัติ)
        if ($request->action === 'approve') {
            // ดึงค่า auto_assign ของเจ้าหน้าที่มา (ถ้าไม่มีให้เป็น No)
            $officerAutoAssign = $officer->auto_assign ?? 'No';

            // วนลูปทุกพื้นที่ที่เจ้าหน้าที่คนนี้สังกัดอยู่
            foreach ($existingAreas as $aId) {
                $area = Area::find($aId);
                if ($area) {
                    // แกะ JSON officer_priority ออกมา (ถ้าไม่มีให้เป็น Array ว่าง)
                    $priorities = json_decode($area->officer_priority, true) ?? [];
                    $isFound = false;

                    // วนลูปหาว่าในพื้นที่นี้ มีเจ้าหน้าที่คนนี้อยู่ในคิวหรือยัง
                    foreach ($priorities as $key => $p) {
                        if (isset($p['user_officers_id']) && $p['user_officers_id'] == $officer->id) {
                            // ถ้าเจอแล้ว -> อัปเดตแค่ auto_assign
                            $priorities[$key]['auto_assign'] = $officerAutoAssign;
                            $isFound = true;
                            break;
                        }
                    }

                    // ถ้ายังไม่เคยมีในพื้นที่นี้ (สร้างใหม่)
                    if (!$isFound) {
                        $priorities[] = [
                            'user_officers_id' => $officer->id,
                            'priority'         => null, // ค่าเริ่มต้นให้เป็น null
                            'auto_assign'      => $officerAutoAssign
                        ];
                    }

                    // แปลงกลับเป็น JSON แล้วบันทึกลงตาราง areas
                    $area->officer_priority = json_encode($priorities);
                    $area->save();
                }
            }
        }

        $msg = $request->action === 'approve' ? 'อนุมัติคำขอเข้าพื้นที่เรียบร้อยแล้ว' : 'ปฏิเสธคำขอเรียบร้อยแล้ว';
        return redirect()->back()->with('success', $msg);
    }
}
