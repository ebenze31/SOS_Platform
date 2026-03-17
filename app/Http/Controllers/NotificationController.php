<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Emergency_operation;

class NotificationController extends Controller
{
    public function check()
    {
        // ดึงข้อมูลที่เพิ่งเข้ามาใหม่
        $newCases = DB::table('emergency_operations')
            ->join('emergencys', 'emergency_operations.emergency_id', '=', 'emergencys.id')
            ->select('emergency_operations.*', 'emergencys.name_reporter', 'emergencys.type_reporter', 'emergencys.emergency_detail')
            ->where('emergency_operations.notify', 'none')
            ->orderBy('emergency_operations.id', 'desc')
            ->get();

        // ดึงข้อมูลที่รอการรับเรื่อง
        $alertCases = DB::table('emergency_operations')
            ->join('emergencys', 'emergency_operations.emergency_id', '=', 'emergencys.id')
            ->select('emergency_operations.*', 'emergencys.name_reporter', 'emergencys.type_reporter', 'emergencys.emergency_detail')
            ->where('emergency_operations.notify', 'alert')
            ->orderBy('emergency_operations.id', 'desc')
            ->get();

        // เช็คจุดสีเมนู Monitor
        $hasActiveMonitor = DB::table('emergency_operations')
            ->where('status', '!=', 'เสร็จสิ้น')
            ->exists();

        // เช็คจุดสีเมนู คำขอลงทะเบียน
        $hasPendingRegister = DB::table('user_officers')
            ->where('status_register', 'LIKE', '%"Pending"%')
            ->exists();

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
