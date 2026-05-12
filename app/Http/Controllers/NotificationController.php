<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Emergency_operation;

class NotificationController extends Controller
{
    public function check()
    {
        // 1. ดึงข้อมูลสิทธิ์และพื้นที่ของ User ที่ล็อกอินอยู่
        $userId = auth()->id();
        $userCommand = DB::table('user_commands')->where('user_id', $userId)->first();
        
        $isSupervisor = $userCommand && $userCommand->command_role === 'supervisor';
        $myAreaId = $userCommand ? $userCommand->area_id : null;

        // 2. เตรียม Query เริ่มต้น (แก้ไข select กลับไปเป็นเหมือนค่าเริ่มต้นของคุณ)
        $newCasesQuery = DB::table('emergency_operations')
            ->join('emergencys', 'emergency_operations.emergency_id', '=', 'emergencys.id')
            ->select('emergency_operations.*', 'emergencys.name_reporter', 'emergencys.type_reporter', 'emergencys.emergency_detail')
            ->where('emergency_operations.notify', 'none');

        $alertCasesQuery = DB::table('emergency_operations')
            ->join('emergencys', 'emergency_operations.emergency_id', '=', 'emergencys.id')
            ->select('emergency_operations.*', 'emergencys.name_reporter', 'emergencys.type_reporter', 'emergencys.emergency_detail')
            ->where('emergency_operations.notify', 'alert');

        $monitorQuery = DB::table('emergency_operations')
            ->join('emergencys', 'emergency_operations.emergency_id', '=', 'emergencys.id')
            ->where('emergency_operations.status', '!=', 'เสร็จสิ้น');

        $registerQuery = DB::table('user_officers')
            ->where('status_register', 'LIKE', '%"Pending"%');

        // 3. กรองพื้นที่ ถ้า "ไม่ใช่ Supervisor" และ "มี Area ID"
        if (!$isSupervisor && $myAreaId) {
            
            // กรองเคสโดยใช้ area_id จากตาราง emergency_operations แทน
            $newCasesQuery->where('emergency_operations.area_id', $myAreaId);
            $alertCasesQuery->where('emergency_operations.area_id', $myAreaId);
            $monitorQuery->where('emergency_operations.area_id', $myAreaId);

            // กรองการลงทะเบียนเจ้าหน้าที่ตามพื้นที่ 
            $registerQuery->where(function($q) use ($myAreaId) {
                $q->where('area_id', $myAreaId)
                  ->orWhere('area_id', 'LIKE', '%"' . $myAreaId . '"%');
            });
        }

        // 4. สั่งรัน Query และส่งข้อมูลกลับไป
        $newCases = $newCasesQuery->orderBy('emergency_operations.id', 'desc')->get();
        $alertCases = $alertCasesQuery->orderBy('emergency_operations.id', 'desc')->get();
        $hasActiveMonitor = $monitorQuery->exists();
        $hasPendingRegister = $registerQuery->exists();

        return response()->json([
            'new_cases'      => $newCases,
            'alert_cases'    => $alertCases,
            'badge_monitor'  => $hasActiveMonitor,
            'badge_register' => $hasPendingRegister
        ]);
    }

    public function markAlert(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            // อัปเดตจาก none เป็น alert ทันที
            DB::table('emergency_operations')
                ->whereIn('id', $ids)
                ->update(['notify' => 'alert']);
        }

        return response()->json(['status' => 'success']);
    }
}