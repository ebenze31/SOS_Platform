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
    public function report_data_emergency(Request $request)
    {
        // 1. ดึงประเภทเหตุทั้งหมดจาก DB สำหรับตัวเลือกใน Filter
        $emergencyTypes = DB::table('emergency_types')->select('name_emergency')->get();

        // 2. ดึงข้อมูล Emergency พร้อมความสัมพันธ์
        $data = Emergency::with([
            'operation.officer', 
            'operation.commander',
            'operation.area' 
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        $formattedData = $data->map(function ($item) {
            $op = $item->operation;
            
            $rawStatus = $op->status ?? 'รับแจ้งเหตุ';
            
            // Mapping สำหรับ CSS/Logic ภายใน (ทำเหมือนเดิม)
            $mappedStatus = ($rawStatus === 'เสร็จสิ้น') ? 'done' : 'progress';

            $responseTime = null;
            if ($op && $op->time_create_sos && $op->time_to_the_scene) {
                $start = \Carbon\Carbon::parse($op->time_create_sos);
                $end = \Carbon\Carbon::parse($op->time_to_the_scene);
                $responseTime = $start->diffInMinutes($end);
            }

            return [
                'id_real' => $item->id,
                'id_display' => $op->operating_code ?? 'N/A',
                'date' => $item->created_at->format('Y-m-d'),
                'time' => $item->created_at->format('H:i'),
                'type' => $item->emergency_type ?? 'ไม่ระบุ',
                'location' => $item->emergency_location,
                'status' => $mappedStatus,
                'raw_status' => $rawStatus, // ใช้ค่านี้ในการกรองสถานะภาษาไทย
                'response' => $responseTime,
                'officer' => $op->officer->name_officer ?? 'รอรับเรื่อง',
                'export_row' => [
                    'ชื่อผู้แจ้ง' => $item->name_reporter,
                    'ประเภทผู้แจ้ง' => $item->type_reporter,
                    'เบอร์โทรศัพท์' => $item->phone_reporter,
                    'ประเภทเหตุ' => $item->emergency_type,
                    'รายละเอียดเหตุ' => $item->emergency_detail,
                    'สถานที่เกิดเหตุ' => $item->emergency_location,
                    'ผู้สั่งการ' => $op->commander->name_command ?? '-',
                    'สถานะ' => $rawStatus,
                    'พื้นที่รับผิดชอบ' => $op->area->name_area ?? '-',
                    'เจ้าหน้าที่ผู้ปฏิบัติงาน' => $op->officer->name_officer ?? '-',
                    'เวลาที่ได้รับแจ้ง' => $op->time_create_sos ?? '-',
                    'เวลาตอบสนอง (นาที)' => $responseTime ?? 0,
                    'ระยะเวลารวม' => $op->time_sum_sos ?? '-',
                ]
            ];
        });

        return view('reports.data_emergency', [
            'reportsJson' => $formattedData->toJson(),
            'emergencyTypes' => $emergencyTypes // ส่งตัวแปรนี้ไปที่ Blade
        ]);
    }
}
