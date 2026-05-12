<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Emergency;
use App\Models\Emergency_operation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User_command;
use App\Models\Emergency_type;
use App\Models\Phone_emergency;
use App\Models\User_officer;
use App\Models\Area;
use App\Models\My_log;
use Intervention\Image\Facades\Image;

class ReportController extends Controller
{
    // public function report_data_emergency(Request $request)
    // {
    //     $emergencyTypes = DB::table('emergency_types')->select('name_emergency')->get();

    //     $data = Emergency::with([
    //         'operation.officer', 
    //         'operation.commander',
    //         'operation.area' 
    //     ])
    //     ->orderBy('created_at', 'desc')
    //     ->get();

    //     $formattedData = $data->map(function ($item) {
    //         $op = $item->operation;
    //         $rawStatus = $op->status ?? 'รับแจ้งเหตุ';
            
    //         // คำนวณ Response Time
    //         $responseTime = null;
    //         if ($op && $op->time_create_sos && $op->time_to_the_scene) {
    //             $start = Carbon::parse($op->time_create_sos);
    //             $end = Carbon::parse($op->time_to_the_scene);
    //             $responseTime = $start->diffInMinutes($end);
    //         }

    //         return [
    //             // ส่วนแสดงผลบนหน้าเว็บ (UI)
    //             'id_real'    => $item->id,
    //             'id_display' => $op->operating_code ?? 'N/A',
    //             'date'       => $item->created_at->format('Y-m-d'),
    //             'time'       => $item->created_at->format('H:i'),
    //             'type'       => $item->emergency_type ?? 'ไม่ระบุ',
    //             'location'   => $item->emergency_location,
    //             'status'     => ($rawStatus === 'เสร็จสิ้น') ? 'done' : 'progress',
    //             'raw_status' => $rawStatus,
    //             'response'   => $responseTime,
    //             'officer'    => $op->officer->name_officer ?? 'รอรับเรื่อง',
    //             'sum_time'   => $op->time_sum_sos ?? '-',
                
    //             // ข้อมูลดิบทั้งหมดสำหรับ Export (ส่งไปทั้ง Object)
    //             'full_emergency' => $item->toArray(),
    //             'full_operation' => $op ? $op->toArray() : [],
    //             // ข้อมูลจากตารางอื่นที่ต้องการดึงชื่อมาแทน ID
    //             'extra_names' => [
    //                 'name_command' => $op->commander->name_command ?? '-',
    //                 'name_area'    => $op->area->name_area ?? '-',
    //                 'name_officer' => $op->officer->name_officer ?? '-',
    //                 'calculated_response' => $responseTime ?? 0
    //             ]
    //         ];
    //     });

    //     return view('reports.data_emergency', [
    //         'reportsJson' => $formattedData->toJson(),
    //         'emergencyTypes' => $emergencyTypes 
    //     ]);
    // }

    public function report_data_emergency(Request $request)
    {
        // 1. ตรวจสอบสิทธิ์และพื้นที่ของ User
        $user = auth()->user();
        $userCommand = $user->userCommand ?? null;
        
        $isSupervisor = $userCommand && $userCommand->command_role === 'supervisor';
        $myAreaId = $userCommand ? $userCommand->area_id : null;

        $emergencyTypes = DB::table('emergency_types')->select('name_emergency')->get();

        // 2. สร้าง Query ดึงข้อมูล
        $query = Emergency::with([
            'operation.officer', 
            'operation.commander',
            'operation.area' 
        ]);

        // 3. กรองข้อมูล: ถ้าไม่ใช่ Supervisor ให้ดูได้เฉพาะเคสที่มี operation และตรงกับพื้นที่ตัวเอง
        if (!$isSupervisor && $myAreaId) {
            $query->whereHas('operation', function($q) use ($myAreaId) {
                $q->where('area_id', $myAreaId);
            });
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $formattedData = $data->map(function ($item) {
            $op = $item->operation;
            $rawStatus = $op->status ?? 'รับแจ้งเหตุ';
            
            // คำนวณ Response Time
            $responseTime = null;
            if ($op && $op->time_create_sos && $op->time_to_the_scene) {
                $start = Carbon::parse($op->time_create_sos);
                $end = Carbon::parse($op->time_to_the_scene);
                $responseTime = $start->diffInMinutes($end);
            }

            return [
                // ส่วนแสดงผลบนหน้าเว็บ (UI)
                'id_real'    => $item->id,
                'id_display' => $op->operating_code ?? 'N/A',
                'date'       => $item->created_at->format('Y-m-d'),
                'time'       => $item->created_at->format('H:i'),
                'type'       => $item->emergency_type ?? 'ไม่ระบุ',
                'area_name'  => $op->area->name_area ?? 'ไม่ระบุพื้นที่',
                'location'   => $item->emergency_location,
                'status'     => ($rawStatus === 'เสร็จสิ้น') ? 'done' : 'progress',
                'raw_status' => $rawStatus,
                'response'   => $responseTime,
                'officer'    => $op->officer->name_officer ?? 'รอรับเรื่อง',
                'sum_time'   => $op->time_sum_sos ?? '-',
                
                // ข้อมูลดิบทั้งหมดสำหรับ Export (ส่งไปทั้ง Object)
                'full_emergency' => $item->toArray(),
                'full_operation' => $op ? $op->toArray() : [],
                'extra_names' => [
                    'name_command' => $op->commander->name_command ?? '-',
                    'name_area'    => $op->area->name_area ?? '-',
                    'name_officer' => $op->officer->name_officer ?? '-',
                    'calculated_response' => $responseTime ?? 0
                ]
            ];
        });

        return view('reports.data_emergency', [
            'reportsJson' => $formattedData->toJson(),
            'emergencyTypes' => $emergencyTypes 
        ]);
    }
}
