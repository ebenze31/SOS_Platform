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
        // 1. Validate ข้อมูล
        $request->validate([
            'name_reporter' => 'required|string|max:255',
            'phone_reporter' => 'required|string|max:20',
            'emergency_type' => 'required',
            'emergency_detail' => 'required',
            'emergency_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        DB::beginTransaction();

        try {
            // 2. จัดการไฟล์รูปภาพ (ถ้ามี)
            $photoPath = null;
            if ($request->hasFile('emergency_photo')) {
                $file = $request->file('emergency_photo');
                
                // ดึงนามสกุลไฟล์เดิม (เช่น jpg, png)
                $extension = $file->getClientOriginalExtension();
                
                // ตั้งชื่อไฟล์
                $filename = date('Ymd_His') . '_' . rand(100, 999) . '.' . $extension;
                
                // บันทึกไปที่ storage/app/public/emergencies
                $path = $file->storeAs('public/emergencys', $filename);
                
                // Path สำหรับเรียกใช้งานหน้าเว็บ
                $photoPath = 'storage/emergencys/' . $filename;
            }

            $typeReporter = $request->input('type_reporter');

            // ถ้าเลือก "อื่นๆ" ให้เอาค่าจากช่องกรอกข้อความมาใช้แทน
            if ($typeReporter === 'อื่นๆ') {
                $typeReporter = $request->input('type_reporter_other');
            }

            // 3. บันทึกตาราง emergencys
            $emergency = new Emergency();
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

            $user_commands = User_command::where('status', 'Standby')
                ->orderBy('number', 'ASC')
                ->first();

            if ($user_commands) {
                $to_command_id = $user_commands->id;
            } else {
                $to_command_id = "No Command";
            }

            // 4. บันทึกตาราง emergency_operations (สร้าง Case ใหม่)
            $operation = new Emergency_operation();
            $operation->emergency_id = $emergency->id;
            $operation->status = 'รับแจ้งเหตุ';
            $operation->notify = $to_command_id;
            $operation->time_create_sos = Carbon::now();
            $operation->save();

            DB::commit();

            return redirect()->route('emergency.tracking', ['id' => $emergency->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }

        // $requestData = $request->all();
        // Emergency::create($requestData);
        // return redirect('emergencys')->with('flash_message', 'Emergency added!');
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
        $newOfficerId = $request->officer_id;
        
        $officer = User_officer::with('user')->findOrFail($newOfficerId);

        // ดึงรายการประวัติ log_command และผู้ที่ไม่ตอบรับเดิมออกมาจัดการ
        $logCommand = json_decode($operation->log_command, true) ?? [];
        $noRespondList = json_decode($operation->officer_no_respond, true) ?? [];
        
        // ตรวจสอบว่ามีเจ้าหน้าที่เดิมที่กำลังรอการตอบรับอยู่หรือไม่
        if (!empty($operation->waiting_reply)) {
            $previousOfficerId = $operation->waiting_reply;
            
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
                if ($log['sendTo'] == $previousOfficerId && $log['status'] == 'pending') {
                    $log['status'] = 'no_respond';
                    $log['sum_time'] = $sumTimeSeconds;
                }
            }
        }

        // สร้าง Operating Code หากยังไม่มี
        if (empty($operation->operating_code)) {
            // ดึง area_id จากการถอดรหัสพื้นที่ที่ครอบคลุมของเคส หรือใช้พื้นที่ของเจ้าหน้าที่
            $areaIdForCode = 0;
            $areasArray = json_decode($officer->area_id, true) ?? [];
            if (!empty($areasArray)) {
                $areaIdForCode = $areasArray[0]; 
            }
            
            $datePrefix = now()->format('ymd');
            
            // ค้นหาจำนวนเคสที่เกิดขึ้นในวันนี้เพื่อสร้าง Running Number
            $todayCasesCount = Emergency_operation::whereDate('created_at', now()->toDateString())->count();
            $runningNumber = str_pad($todayCasesCount + 1, 4, '0', STR_PAD_LEFT);
            
            // แปลงรหัสพื้นที่ให้เป็นตัวเลข 3 หลักด้วยการเติม 0 ด้านหน้า
            $formattedAreaId = str_pad($areaIdForCode, 3, '0', STR_PAD_LEFT);
            
            $operation->operating_code = "{$datePrefix}-{$formattedAreaId}-{$runningNumber}";
            // บันทึกพื้นที่รับผิดชอบของเคสนี้
            $operation->area_id = $areaIdForCode;
        }

        // เพิ่มการส่งงานให้เจ้าหน้าที่คนใหม่ลงใน Log
        $logCommand[] = [
            'datetime' => now()->toDateTimeString(),
            'sendTo'   => $newOfficerId,
            'status'   => 'pending',
            'sum_time' => 0
        ];

        // อัปเดตข้อมูลตาราง Operation
        $operation->command_by = auth()->id();
        $operation->waiting_reply = $newOfficerId;
        $operation->status = 'สั่งการ';
        $operation->time_command = now()->toDateTimeString();
        $operation->officer_no_respond = json_encode($noRespondList);
        $operation->log_command = json_encode($logCommand);
        
        $operation->save();

        // ส่ง Flex Message แจ้งเตือนไปยัง Line OA ของเจ้าหน้าที่
        $lineUserId = $officer->user->provider_id ?? null;
        if ($lineUserId) {
            // เรียกฟังก์ชันส่ง Flex Message
            // $this->sendLineFlexMessageToOfficer($lineUserId, $emergency);
        }

        return redirect()->back()->with('success', 'สั่งการและมอบหมายงานให้เจ้าหน้าที่เรียบร้อยแล้ว');
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
}
