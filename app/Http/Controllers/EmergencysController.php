<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Emergency;
use Illuminate\Http\Request;

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

class EmergencysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        $emergencyTypes = Emergency_type::where('status', 'Active')->get();

        $phoneEmergencies = Phone_emergency::where('status', 'Active')
            ->orderBy('priority', 'asc')
            ->get();

        return view('emergencys.index', compact('user', 'emergencyTypes', 'phoneEmergencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('emergencys.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // Validate ข้อมูล
        $request->validate([
            'name_reporter' => 'required|string|max:255',
            'phone_reporter' => 'required|string|max:20',
            'emergency_type' => 'required',
            'emergency_detail' => 'required',
            'photo_cam' => 'nullable|image|mimes:jpeg,png,jpg,gif', 
            'photo_gal' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        DB::beginTransaction();

        try {
            // อัปเดตเบอร์โทรศัพท์ผู้ใช้เข้าตาราง users
            if (auth()->check()) {
                auth()->user()->update([
                    'phone' => $request->phone_reporter
                ]);
            }

            // จัดการไฟล์รูปภาพ (ถ้ามี)
            $photoPath = null;
            // รับไฟล์จากปุ่มถ่ายภาพ หรือ ปุ่มเลือกจากอัลบั้ม
            $file = $request->file('photo_cam') ?? $request->file('photo_gal');
            
            if ($file) {
                // ดึงนามสกุลไฟล์เดิม
                $extension = $file->getClientOriginalExtension();
                
                // ตั้งชื่อไฟล์
                $filename = date('Ymd_His') . '_' . rand(100, 999) . '.' . $extension;
                
                // กำหนด Path ปลายทางที่เป็น Absolute Path (สำหรับ Intervention)
                $destinationPath = storage_path('app/public/emergencys');
                
                // สร้างโฟลเดอร์ถ้ายังไม่มี
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }

                // Intervention Image ย่อขนาดและลด Quality
                $img = Image::make($file->getRealPath());
                
                // ย่อขนาดให้ความกว้างไม่เกิน 1200px (ความสูงจะปรับตามสัดส่วน)
                $img->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // ป้องกันภาพเล็กถูกขยายจนแตก
                });

                // เซฟลงโฟลเดอร์ พร้อมลด Quality เหลือ 75%
                $img->save($destinationPath . '/' . $filename, 75);
                
                // Path สำหรับเรียกใช้งานหน้าเว็บ (ดึงผ่าน Storage Symlink)
                $photoPath = 'storage/emergencys/' . $filename;
            }

            $typeReporter = $request->input('type_reporter');

            // ถ้าเลือก "อื่นๆ" ให้เอาค่าจากช่องกรอกข้อความมาใช้แทน
            if ($typeReporter === 'อื่นๆ') {
                $typeReporter = $request->input('type_reporter_other');
            }

            // บันทึกตาราง emergencys
            $emergency = new Emergency();
            $emergency->user_id = auth()->id();
            $emergency->name_reporter = $request->name_reporter;
            $emergency->type_reporter = $typeReporter;
            $emergency->phone_reporter = $request->phone_reporter;
            $emergency->emergency_type = $request->emergency_type;
            $emergency->emergency_detail = $request->emergency_detail;
            
            // รับค่าพิกัดจาก Hidden Input
            $emergency->emergency_lat = $request->emergency_lat ?? 0.0;
            $emergency->emergency_lng = $request->emergency_lng ?? 0.0;
            $emergency->emergency_location = $request->emergency_location ?? 'Unknown Location';
            $emergency->emergency_photo = $photoPath;
            
            // ค่า default สำหรับส่วนประเมิน (ยังไม่มีคะแนนตอนแจ้งเหตุ)
            $emergency->score_impression = 0;
            $emergency->score_period = 0;
            $emergency->score_total = 0;
            $emergency->comment_help = null;
            
            $emergency->save();

            // บันทึกตาราง emergency_operations (สร้าง Case ใหม่)
            $operation = new Emergency_operation();
            $operation->emergency_id = $emergency->id;
            $operation->status = 'รับแจ้งเหตุ';
            $operation->notify = "none";
            $operation->time_create_sos = Carbon::now();
            $operation->save();

            DB::commit();

            return redirect()->route('emergency.tracking', ['id' => $emergency->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $emergency = Emergency::findOrFail($id);

        return view('emergencys.show', compact('emergency'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $emergency = Emergency::findOrFail($id);

        return view('emergencys.edit', compact('emergency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $emergency = Emergency::findOrFail($id);
        $emergency->update($requestData);

        return redirect('emergencys')->with('flash_message', 'Emergency updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Emergency::destroy($id);

        return redirect('emergencys')->with('flash_message', 'Emergency deleted!');
    }

    public function tracking($id)
    {
        // ดึงข้อมูลเหตุการณ์ และสถานะล่าสุดมาแสดง
        $emergency = Emergency::findOrFail($id);
        
        // ดึงข้อมูล Operation เพื่อดูสถานะ
        $operation = Emergency_operation::where('emergency_id', $id)->first();

        return view('emergencys.tracking', compact('emergency', 'operation'));
    }

    public function checkStatus($id)
    {
        $operation = Emergency_operation::where('emergency_id', $id)->first();
        $dbStatus = $operation->status ?? 'รับแจ้งเหตุ';
        $currentState = 1;

        if ($dbStatus == 'รับแจ้งเหตุ') {
            $currentState = 1;
        } elseif ($dbStatus == 'สั่งการ') {
            $currentState = 2;
        } elseif ($dbStatus == 'กำลังไปช่วยเหลือ') {
            $currentState = 3;
        } elseif ($dbStatus == 'ถึงที่เกิดเหตุ') {
            $currentState = 4;
        } elseif ($dbStatus == 'เสร็จสิ้น') {
            $currentState = 5;
        }

        // ดึงข้อมูลเจ้าหน้าที่เฉพาะจาก user_officers_id (คนที่รับงานแล้วเท่านั้น)
        $officerData = null;
        if (!empty($operation->user_officers_id)) {
            $officer = User_officer::with('user')->find($operation->user_officers_id);
            if ($officer) {
                $officerData = [
                    'name'  => $officer->name_officer,
                    'type'  => $officer->type ?? '',
                    'phone' => ($officer->user && $officer->user->phone) ? str_replace('-', '', $officer->user->phone) : '' 
                ];
            }
        }

        $times = [
            'time_create' => $operation->time_create_sos ? Carbon::parse($operation->time_create_sos)->format('H:i') . ' น.' : '',
            'time_go' => $operation->time_go_to_help ? Carbon::parse($operation->time_go_to_help)->format('H:i') . ' น.' : '',
            'time_arrive' => $operation->time_to_the_scene ? Carbon::parse($operation->time_to_the_scene)->format('H:i') . ' น.' : '',
            'time_success' => $operation->time_sos_success ? Carbon::parse($operation->time_sos_success)->format('H:i') . ' น.' : '',
        ];

        return response()->json([
            'state'   => $currentState,
            'officer' => $officerData,
            'times'   => $times
        ]);

    }

    public function monitor()
    {
        // -------------------------------------------------------
        // 1. "รับแจ้งเหตุ"
        // -------------------------------------------------------
        $pendingQuery = Emergency::with('operation')
            ->where(function ($query) {
                $query->whereHas('operation', function($q) {
                    $q->where('status', 'รับแจ้งเหตุ');
                })
                ->orWhereDoesntHave('operation');
            });

        $totalPending = $pendingQuery->count();
        $pendingCases = $pendingQuery->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // -------------------------------------------------------
        // 2. "กำลังดำเนินการ"
        // -------------------------------------------------------
        $inProgressQuery = Emergency::with(['operation.officer'])
            ->whereHas('operation', function($q) {
                $q->whereIn('status', [
                    'สั่งการ', 
                    'กำลังไปช่วยเหลือ', 
                    'ถึงที่เกิดเหตุ'
                ]);
            });

        $totalInProgress = $inProgressQuery->count(); // นับจำนวนทั้งหมด
        $inProgressCases = $inProgressQuery->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get();

        // -------------------------------------------------------
        // 3. "เสร็จสิ้น"
        // -------------------------------------------------------
        $completedQuery = Emergency::with('operation')
            ->whereHas('operation', function($q) {
                $q->where('status', 'เสร็จสิ้น');
            });

        $totalCompleted = $completedQuery->count();
        $completedCases = $completedQuery->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();

        // -------------------------------------------------------

        return view('emergencys.monitor', compact(
            'pendingCases', 'totalPending',
            'inProgressCases', 'totalInProgress',
            'completedCases', 'totalCompleted'
        ));
    }

    public function case_assign($id)
    {
        // ดึงข้อมูลเหตุการณ์และพิกัดจุดเกิดเหตุ
        $emergency = Emergency::with('operation')->findOrFail($id);
        $incidentLat = $emergency->emergency_lat;
        $incidentLng = $emergency->emergency_lng;

        // ดึงข้อมูลพื้นที่ที่เปิดใช้งานเพื่อตรวจสอบจุดเกิดเหตุว่าอยู่ในโพลิกอนใดบ้าง
        $areas = Area::where('status', 'active')->get(); 
        $matchedAreaIds = [];

        foreach ($areas as $area) {
            $polygon = json_decode($area->polygon, true) ?? [];
            
            if (!empty($polygon) && $this->isPointInPolygon($incidentLat, $incidentLng, $polygon)) {
                $matchedAreaIds[] = $area->id; 
            }
        }

        // ตรวจสอบว่าจุดเกิดเหตุอยู่นอกเขตพื้นที่รับผิดชอบทั้งหมดหรือไม่
        $isOutOfArea = empty($matchedAreaIds);

        // ดึงข้อมูลเจ้าหน้าที่ที่พร้อมปฏิบัติงาน
        $officersQuery = User_officer::where('status', 'Standby');

        // กรองเจ้าหน้าที่เฉพาะผู้ที่ได้รับอนุมัติให้อยู่ในพื้นที่เกิดเหตุจากคอลัมน์ area_id
        if (!$isOutOfArea) {
            $officersQuery->where(function($q) use ($matchedAreaIds) {
                foreach ($matchedAreaIds as $areaId) {
                    $q->orWhereJsonContains('area_id', (string)$areaId)
                      ->orWhereJsonContains('area_id', (int)$areaId);
                }
            });
        }

        $officers = $officersQuery->get();

        // คำนวณระยะทางจากพิกัดเจ้าหน้าที่ถึงจุดเกิดเหตุและจัดการกรณีที่เจ้าหน้าที่ยังไม่เคยส่งพิกัด
        foreach ($officers as $officer) {
            $offLat = $officer->lat ?? $incidentLat; 
            $offLng = $officer->lng ?? $incidentLng;

            $distance = $this->calculateDistance($incidentLat, $incidentLng, $offLat, $offLng);
            $officer->distance_km = round($distance, 1);
        }

        // เรียงลำดับเจ้าหน้าที่ตามระยะทางจากใกล้ไปไกล
        $officers = $officers->sortBy('distance_km')->values();

        return view('emergencys.case_assign', compact('emergency', 'officers', 'isOutOfArea'));
    }

    public function assign_officer(Request $request, $id)
    {
        $request->validate([
            'officer_id' => 'required|integer'
        ]);

        $emergency = Emergency::with('operation')->findOrFail($id);
        $operation = $emergency->operation;
        $newOfficerId = (int) $request->officer_id;
        
        $officer = User_officer::with('user')->findOrFail($newOfficerId);

        // ดึงรายการประวัติ log_command และผู้ที่ไม่ตอบรับเดิมออกมาจัดการ
        $logCommand = json_decode($operation->log_command, true) ?? [];
        $noRespondList = json_decode($operation->officer_no_respond, true) ?? [];
        
        // ตรวจสอบว่ามีเจ้าหน้าที่เดิมที่กำลังรอการตอบรับอยู่หรือไม่
        if (!empty($operation->waiting_reply)) {
            $previousOfficerId = (int) $operation->waiting_reply;
            
            // หาเวลาที่ใช้รอสำหรับเจ้าหน้าที่คนก่อนหน้า
            $timeCommandStr = $operation->time_command ?? now()->toISOString();
            $timeCommand = Carbon::parse($timeCommandStr);
            $sumTimeSeconds = now()->diffInSeconds($timeCommand);
            
            // นำเจ้าหน้าที่เดิมย้ายไปเก็บไว้ในรายชื่อผู้ที่ไม่ตอบรับ
            if (!in_array($previousOfficerId, $noRespondList)) {
                $noRespondList[] = $previousOfficerId;
            }
            
            // อัปเดตสถานะเจ้าหน้าที่เดิมใน log_command
            foreach ($logCommand as &$log) {
                if ($log['sendTo'] === $previousOfficerId && $log['status'] === 'pending') {
                    $log['status'] = 'no_respond';
                    $log['sum_time'] = $sumTimeSeconds;
                }
            }
            unset($log); // ล้างค่า Reference ป้องกันเขียนทับตัวสุดท้าย
        }

        // สร้าง Operating Code หากยังไม่มี
        if (empty($operation->operating_code)) {

            $areaIdForCode = 0;
            $areasArray = json_decode($officer->area_id, true) ?? [];
            if (!empty($areasArray)) {
                $areaIdForCode = $areasArray[0]; 
            }
            
            // วันที่
            $datePrefix = now()->format('ymd');
            
            // รหัสพื้นที่ 3 หลัก
            $formattedAreaId = str_pad($areaIdForCode, 3, '0', STR_PAD_LEFT);
            
            // หาลำดับ (Running Number) ตามเดือนปัจจุบัน และ Area ปัจจุบัน
            $currentYear = now()->year;
            $currentMonth = now()->month;

            // ค้นหาเคสล่าสุดของ "เดือนนี้" และ "พื้นที่นี้"
            $latestOperation = Emergency_operation::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->where('area_id', $areaIdForCode)
                ->whereNotNull('operating_code')
                ->orderBy('id', 'desc')
                ->first();

            $runningNumberValue = 1; // ค่าเริ่มต้นถ้ายังไม่มีเคสเลยในเดือนนี้

            if ($latestOperation && !empty($latestOperation->operating_code)) {
                // แยกข้อความ แล้วเอาตัวเลขชุดสุดท้ายมา +1
                $parts = explode('-', $latestOperation->operating_code);
                if (isset($parts[2])) {
                    $runningNumberValue = intval($parts[2]) + 1;
                }
            }
            
            // แปลงตัวเลขให้เป็น 4 หลัก
            $runningNumber = str_pad($runningNumberValue, 4, '0', STR_PAD_LEFT);
            
            // รวมโค้ด
            $operation->operating_code = "{$datePrefix}-{$formattedAreaId}-{$runningNumber}";
            
            // บันทึกพื้นที่รับผิดชอบของเคสนี้ลงไปเพื่อใช้อ้างอิงในอนาคต
            $operation->area_id = $areaIdForCode;
        }

        // เพิ่มการส่งงานให้เจ้าหน้าที่คนใหม่ลงใน Log
        $logCommand[] = [
            'datetime' => now()->toIso8601String(),
            'sendTo'   => $newOfficerId,
            'status'   => 'pending',
            'sum_time' => 0
        ];

        // อัปเดตข้อมูลตาราง Operation
        $operation->command_by = auth()->id();
        $operation->waiting_reply = $newOfficerId;
        $operation->status = 'สั่งการ';
        $operation->notify = 'success';
        $operation->time_command = now()->toDateTimeString();
        $operation->officer_no_respond = json_encode($noRespondList);
        $operation->log_command = json_encode($logCommand, JSON_UNESCAPED_UNICODE); 
        
        $operation->save();

        // ส่ง Flex Message แจ้งเตือนไปยัง Line OA ของเจ้าหน้าที่
        $lineUserId = $officer->user->provider_id ?? null;
        if ($lineUserId) {
            $this->sendLineFlexMessageToOfficer($lineUserId, $emergency, $operation, $officer);
        }

        return redirect()->back()->with('success', 'สั่งการและมอบหมายงานให้เจ้าหน้าที่เรียบร้อยแล้ว');
    }

    private function sendLineFlexMessageToOfficer($lineUserId, $emergency, $operation, $officer)
    {
        $template_path = public_path('json/flex-sos/send_sos.json'); 
        $string_json = file_get_contents($template_path);

        $string_json = str_replace("{emergency_type}", $emergency->emergency_type ?? 'ขอความช่วยเหลือ', $string_json);
        $string_json = str_replace("{emergency_location}", $emergency->emergency_location ?? 'ไม่ระบุสถานที่', $string_json);
        $string_json = str_replace("{emergency_detail}", $emergency->emergency_detail ?? 'ไม่มีรายละเอียดเพิ่มเติม', $string_json);
        $string_json = str_replace("{emergency_type}", $emergency->emergency_type ?? '-', $string_json);
        $string_json = str_replace("{name_reporter}", $emergency->name_reporter ?? 'ผู้แจ้งไม่ประสงค์ออกนาม', $string_json);
        $string_json = str_replace("{phone_reporter}", $emergency->phone_reporter ?? '-', $string_json);
        $string_json = str_replace("{type_reporter}", $emergency->type_reporter ?? '-', $string_json);
        $string_json = str_replace("{emergency_lat}", $emergency->emergency_lat ?? '', $string_json);
        $string_json = str_replace("{emergency_lng}", $emergency->emergency_lng ?? '', $string_json);
        $string_json = str_replace("{operation_id}", $operation->id, $string_json);

        $messages = [ json_decode($string_json, true) ];

        $body = [
            "to" => $lineUserId,
            "messages" => $messages,
        ];

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n" .
                             "Authorization: Bearer " . env('CHANNEL_ACCESS_TOKEN') . "\r\n",
                'content' => json_encode($body, JSON_UNESCAPED_UNICODE),
                'ignore_errors' => true // ป้องกันระบบพังถ้ายิง API ไม่ผ่าน
            ]
        ];

        $context  = stream_context_create($opts);
        $url = "https://api.line.me/v2/bot/message/push";
        
        // สั่งยิง Request ไปหา LINE
        $result = file_get_contents($url, false, $context);

        // บันทึก Log การส่งข้อมูล
        $logData = [
            "title" => "Send data sos to",
            "content" => "ID : " . $officer->id . " / NAME : " . $officer->name_officer,
        ];
        
        My_log::create($logData);
    }

    // ตรวจสอบว่าพิกัดอยู่ใน Polygon หรือไม่
    private function isPointInPolygon($lat, $lng, $polygon)
    {
        if (!$polygon || !is_array($polygon) || count($polygon) < 3) return false;

        $inside = false;
        $j = count($polygon) - 1;

        for ($i = 0; $i < count($polygon); $i++) {
            // ปรับ Key 'lat', 'lng' ตามรูปแบบ JSON ในตาราง areas ของคุณ
            $xi = $polygon[$i]['lat'] ?? $polygon[$i][0]; 
            $yi = $polygon[$i]['lng'] ?? $polygon[$i][1];
            $xj = $polygon[$j]['lat'] ?? $polygon[$j][0];
            $yj = $polygon[$j]['lng'] ?? $polygon[$j][1];

            $intersect = (($yi > $lng) != ($yj > $lng))
                && ($lat < ($xj - $xi) * ($lng - $yi) / ($yj - $yi) + $xi);
            if ($intersect) $inside = !$inside;

            $j = $i;
        }

        return $inside;
    }

    // คำนวณระยะทาง Haversine Formula (ออกมาเป็นกิโลเมตร)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // รัศมีโลก (กิโลเมตร)

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }

    public function completeCase(Request $request, $id)
    {
        $request->validate([
            'remark_status' => 'required|string|max:1000'
        ]);

        // ดึงข้อมูล Emergency 
        $emergency = Emergency::with('operation')->findOrFail($id);

        // ตรวจสอบว่ามีข้อมูล Operation ผูกอยู่หรือไม่
        if ($emergency->operation) {
            $operation = $emergency->operation;
            $operation->status = 'เสร็จสิ้น';
            $operation->remark_status = $request->remark_status;
            
            // บันทึกเวลาที่ปิดเคส (เวลาปัจจุบัน)
            $now = Carbon::now();
            $operation->time_sos_success = $now;

            // คำนวณเวลาที่ใช้ไปตั้งแต่รับแจ้ง (time_create_sos)
            if ($operation->time_create_sos) {
                $start = Carbon::parse($operation->time_create_sos);
                $diff = $start->diff($now);

                $days = $diff->d;
                $hours = $diff->h;
                $minutes = $diff->i;

                $timeString = '';

                // ต่อสตริงเฉพาะค่าที่มีตัวเลขมากกว่า 0 (ซ่อนวัน ซ่อนชั่วโมง ซ่อนนาที ที่เป็น 0)
                if ($days > 0) {
                    $timeString .= $days . ' วัน ';
                }
                if ($hours > 0) {
                    $timeString .= $hours . ' ชั่วโมง ';
                }
                if ($minutes > 0) {
                    $timeString .= $minutes . ' นาที';
                }

                // กรณีที่จัดการเสร็จภายในไม่กี่วินาที (ยังไม่ถึง 1 นาที)
                if (trim($timeString) == '') {
                    $timeString = 'ไม่ถึง 1 นาที';
                }

                // บันทึกสตริงเวลาลงในฐานข้อมูล
                $operation->time_sum_sos = trim($timeString);
            }

            $operation->save();
        } else {
            // กรณีไม่มี Record ในตาราง operations
            return redirect()->back()->with('error', 'ไม่พบข้อมูลการปฏิบัติการ (Operation) สำหรับเคสนี้');
        }

        // คืนค่ากลับไปหน้า Monitor (Dashboard) และแจ้งเตือน Success
        return redirect()->route('emergency.monitor')->with('success', 'บันทึกการเสร็จสิ้นภารกิจเรียบร้อยแล้ว');
    }

    public function showRatePage($id)
    {
        $emergency = Emergency::findOrFail($id);
        $operation = Emergency_operation::where('emergency_id', $id)->first();
        
        $officer = null;
        if ($operation && !empty($operation->user_officers_id)) {
            $officer = User_officer::find($operation->user_officers_id);
        }

        return view('emergencys.rate', compact('emergency', 'operation', 'officer'));
    }

    // สำหรับบันทึกข้อมูลการประเมิน
    public function submitRate(Request $request, $id)
    {
        $request->validate([
            'score_impression' => 'required|integer|min:1|max:5',
            'score_period'     => 'required|integer|min:1|max:5',
            'comment_help'     => 'nullable|string'
        ]);

        $emergency = Emergency::findOrFail($id);
        
        $emergency->score_impression = $request->score_impression;
        $emergency->score_period = $request->score_period;
        // คำนวณคะแนนรวม (เฉลี่ยจากทั้ง 2 หัวข้อ)
        $emergency->score_total = ($request->score_impression + $request->score_period) / 2; 
        $emergency->comment_help = $request->comment_help;
        
        $emergency->save();

        return response()->json([
            'success' => true, 
            'message' => 'บันทึกการประเมินเรียบร้อยแล้ว'
        ]);
    }

    public function history()
    {
        $userId = auth()->id();
        
        $emergencies = Emergency::with('operation')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('emergencys.history', compact('emergencies'));
    }

    public function trackingData($id)
    {
        $operation = DB::table('emergency_operations')->where('id', $id)->first();
        $officer = DB::table('user_officers')->where('id', $operation->officer_id)->first();

        return response()->json([
            'status' => $operation->status,
            'lat' => $officer->lat ?? null,   
            'lng' => $officer->lng ?? null,
            'start_lat' => $operation->start_lat ?? null,
            'start_lng' => $operation->start_lng ?? null,
            'arrived_time' => $operation->arrived_at ? Carbon::parse($operation->arrived_at)->format('H:i น.') : null
        ]);
    }

    public function getOperationData($emergency_id)
    {
        // ค้นหาข้อมูล Operation จาก emergency_id
        $operation = DB::table('emergency_operations')->where('emergency_id', $emergency_id)->first();

        if (!$operation) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // ค้นหาข้อมูลเจ้าหน้าที่เพื่อดึงพิกัดล่าสุด
        $officer = null;
        $locationDiffMinutes = null;

        if (!empty($operation->user_officers_id)) {
            $officer = DB::table('user_officers')->where('id', $operation->user_officers_id)->first();
            
            if ($officer) {
                // ตรวจสอบพิกัด
                $isLocationOld = false;
                
                if (empty($officer->last_update_location)) {
                    // ไม่มีพิกัด
                    $isLocationOld = true; 
                } else {
                    // มีพิกัด แต่เช็คว่าเกิน 3 นาทีไหม
                    $lastUpdate = Carbon::parse($officer->last_update_location);
                    $locationDiffMinutes = now()->diffInMinutes($lastUpdate); 
                    if ($locationDiffMinutes >= 3) {
                        $isLocationOld = true;
                    }
                }
                
                // ถ้าสถานะ "กำลังไปช่วยเหลือ" และ "พิกัดเก่า/ไม่มีพิกัด"
                if ($operation->status === 'กำลังไปช่วยเหลือ' && $isLocationOld) {
                    
                    // เช็คเวลาแจ้งเตือนครั้งล่าสุดจาก Database (line_notified_at)
                    $lastNotified = $officer->line_notified_at ? Carbon::parse($officer->line_notified_at) : null;
                    
                    // ถ้ายังไม่เคยส่งเลย หรือ ส่งครั้งล่าสุดผ่านไปแล้วอย่างน้อย 3 นาที
                    if (!$lastNotified || now()->diffInMinutes($lastNotified) >= 3) {
                        
                        // ค้นหา Provider ID จากตาราง users
                        $user = DB::table('users')->where('id', $officer->user_id)->first();
                        
                        if ($user && !empty($user->provider_id)) {
                                    
                            $message = "⚠️ แจ้งเตือนจากศูนย์ควบคุม\nกรุณากดปุ่มดำเนินการ เพื่อให้ศูนย์ควบคุมสามารถติดตามตำแหน่งปัจจุบันของท่านได้ครับ";
                            
                            \App\Http\Controllers\LineWebhookController::sendLineNotice($user->provider_id, $message);
                            
                            // อัปเดตเวลาที่ส่ง LINE ลง Database ทันที ป้องกันการส่งซ้ำ
                            DB::table('user_officers')
                                ->where('id', $officer->id)
                                ->update(['line_notified_at' => now()]);
                        }
                    }
                }
            }
        }

        return response()->json([
            'status' => $operation->status,
            'officer_lat' => $officer->lat ?? null,   
            'officer_lng' => $officer->lng ?? null,
            'photo_by_officer' => $operation->photo_by_officer ?? null,
            'time_create_sos' => $operation->time_create_sos ?? null,
            'time_command' => $operation->time_command ?? null,
            'time_go_to_help' => $operation->time_go_to_help ?? null,
            'time_to_the_scene' => $operation->time_to_the_scene ?? null,
            'time_sos_success' => $operation->time_sos_success ?? null,
            'time_sum_sos' => $operation->time_sum_sos ?? null,
            'remark_photo_by_officer' => $operation->remark_photo_by_officer ?? null,
            'photo_succeed' => $operation->photo_succeed ?? null,
            'remark_by_helper' => $operation->remark_by_helper ?? null,
            'officer_last_update' => $officer->last_update_location ?? null, 
            'location_diff_minutes' => $locationDiffMinutes,
            'start_lat' => $operation->start_lat ?? null,
            'start_lng' => $operation->start_lng ?? null,
            'waiting_reply' => $operation->waiting_reply,
            'officer_refuse' => json_decode($operation->officer_refuse, true) ?? [],
            'officer_no_respond' => json_decode($operation->officer_no_respond, true) ?? [],
            'log_command' => json_decode($operation->log_command, true) ?? []
        ]);
    }

    public function updateRouteLog(Request $request, $id)
    {
        $operation = Emergency_operation::where('emergency_id', $id)->first();
        
        if (!$operation) {
            return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูล Operation']);
        }

        $logs = json_decode($operation->log_command, true) ?? [];
        $isUpdated = false;

        for ($i = count($logs) - 1; $i >= 0; $i--) {
            // ตรวจสอบสถานะที่เกี่ยวข้องกับการรับงานและการเดินทางเพื่อบันทึกพิกัดเริ่มต้นและเส้นทาง
            if (isset($logs[$i]['status']) && in_array($logs[$i]['status'], ['accept', 'pending', 'go_to_help'])) {
                
                $logs[$i]['start_lat'] = $request->start_lat;
                $logs[$i]['start_lng'] = $request->start_lng;
                $logs[$i]['distance_text'] = $request->distance_text;
                $logs[$i]['duration_text'] = $request->duration_text;
                $logs[$i]['polyline'] = $request->polyline;
                
                $logs[$i]['status'] = 'go_to_help';
                
                $isUpdated = true;
                break;
            }
        }

        if ($isUpdated) {
            $operation->log_command = json_encode($logs, JSON_UNESCAPED_UNICODE);
            
            // บันทึกพิกัดจุดเริ่มต้นหลักของ Operation เพื่อใช้ในการคำนวณระยะทางคงที่
            $operation->start_lat = $request->start_lat;
            $operation->start_lng = $request->start_lng;
            
            $operation->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'ไม่พบสถานะที่อนุญาตให้อัปเดตเส้นทาง']);
    }
}
