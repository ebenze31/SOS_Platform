<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\User_officer;
use App\Models\Emergency;
use App\Models\Emergency_operation;
use Illuminate\Http\Request;
use App\Models\Area;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class User_officersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $user_officers = User_officer::where('name_officer', 'LIKE', "%$keyword%")
                ->orWhere('type', 'LIKE', "%$keyword%")
                ->orWhere('vehicle_type', 'LIKE', "%$keyword%")
                ->orWhere('level', 'LIKE', "%$keyword%")
                ->orWhere('amount_help', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('lat', 'LIKE', "%$keyword%")
                ->orWhere('lng', 'LIKE', "%$keyword%")
                ->orWhere('user_id', 'LIKE', "%$keyword%")
                ->orWhere('area_id', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $user_officers = User_officer::latest()->paginate($perPage);
        }

        return view('user_officers.index', compact('user_officers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('user_officers.create');
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
        
        $requestData = $request->all();
        
        User_officer::create($requestData);

        return redirect('user_officers')->with('flash_message', 'User_officer added!');
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
        $user_officer = User_officer::findOrFail($id);

        return view('user_officers.show', compact('user_officer'));
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
        $user_officer = User_officer::findOrFail($id);

        return view('user_officers.edit', compact('user_officer'));
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
        
        $user_officer = User_officer::findOrFail($id);
        $user_officer->update($requestData);

        return redirect('user_officers')->with('flash_message', 'User_officer updated!');
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
        User_officer::destroy($id);

        return redirect('user_officers')->with('flash_message', 'User_officer deleted!');
    }

    public function scan_area()
    {
        $areas = Area::where('status', 'active')->get();
        
        return view('user_officers.scan', compact('areas'));
    }

    public function register_form(Request $request)
    {
        $selectedAreaId = $request->query('area_id');

        // ถ้าไม่มี area_id แนบมาให้เด้งกลับไปหน้าสแกน
        if (!$selectedAreaId) {
            return redirect()->route('user_officers.scan')->with('error', 'กรุณาสแกน QR Code หรือเลือกพื้นที่รับผิดชอบก่อนทำการลงทะเบียน');
        }

        $selectedArea = Area::where('status', 'active')->findOrFail($selectedAreaId);

        // ดึงโปรไฟล์เดิม ถ้ามี
        $userProfile = User_officer::where('user_id', auth()->id())->first();

        // ตัวแปรสำหรับเก็บสถานะปัจจุบันของพื้นที่ที่กำลังสแกน
        $currentStatus = null;
        $remark = null;

        if ($userProfile && $userProfile->status_register) {
            // แปลง JSON status_register เป็น Array เพื่อเช็คประวัติการลงพื้นที่นี้
            $statusArray = json_decode($userProfile->status_register, true) ?? [];
            
            foreach ($statusArray as $item) {
                if ($item['area_id'] == $selectedAreaId) {
                    $currentStatus = $item['status']; // 'Pending', 'Approve', 'Reject'
                    $remark = $item['remark'] ?? null;
                    break;
                }
            }
        }

        return view('user_officers.register', compact('selectedArea', 'userProfile', 'currentStatus', 'remark'));
    }

    public function register_store(Request $request)
    {
        $request->validate([
            'name_officer' => 'required|string|max:255',
            'vehicle_type' => 'required|string',
            'area_id'      => 'required',
        ]);

        $areaId = (int) $request->area_id;

        // ค้นหาประวัติเดิม
        $officer = User_officer::where('user_id', auth()->id())->first();

        if ($officer) {
            // == กรณีที่ 1: เคยมี Row ในระบบแล้ว (อัปเดตข้อมูล) ==
            $officer->name_officer = $request->name_officer;
            $officer->vehicle_type = $request->vehicle_type;

            // จัดการ status_register JSON
            $statusArray = json_decode($officer->status_register, true) ?? [];
            $foundIndex = -1;

            foreach ($statusArray as $index => $item) {
                if ($item['area_id'] == $areaId) {
                    $foundIndex = $index;
                    break;
                }
            }

            if ($foundIndex >= 0) {
                // ถ้าเคยมีประวัติพื้นที่นี้ ให้ปรับเป็น Pending ใหม่ และล้างเหตุผลการปฏิเสธเดิม
                $statusArray[$foundIndex]['status'] = 'Pending';
                $statusArray[$foundIndex]['remark'] = null;
            } else {
                // ถ้าสแกนพื้นที่ใหม่ที่ไม่เคยลงทะเบียน ให้ Push เข้า Array
                $statusArray[] = [
                    'area_id' => $areaId,
                    'status'  => 'Pending',
                    'remark'  => null
                ];
            }

            $officer->status_register = json_encode($statusArray);

        } else {
            // == กรณีที่ 2: เพิ่งเคยลงทะเบียนครั้งแรกสุด (สร้าง Row ใหม่) ==
            $officer = new User_officer();
            $officer->user_id      = auth()->id();
            $officer->name_officer = $request->name_officer;
            $officer->vehicle_type = $request->vehicle_type;
            $officer->area_id = json_encode([]); 
            $officer->status  = 'Inactive'; 

            // สร้าง JSON status_register อันแรก
            $officer->status_register = json_encode([
                [
                    'area_id' => $areaId,
                    'status'  => 'Pending',
                    'remark'  => null
                ]
            ]);
        }

        $officer->save();

        return redirect()->route('user_officers.register', ['area_id' => $request->area_id]);
    }

    public function actionPage($id)
    {
        $emergency = Emergency::findOrFail($id);
        $operation = Emergency_operation::where('emergency_id', $id)->firstOrFail();
        
        return view('user_officers.action', compact('emergency', 'operation'));
    }

    public function updateStatus(Request $request, $id)
    {
        $operation = Emergency_operation::where('emergency_id', $id)->firstOrFail();
        $status = $request->input('status');
        
        if ($status == 'ถึงที่เกิดเหตุ') {
            $operation->status = 'ถึงที่เกิดเหตุ';
            $operation->time_to_the_scene = now();
        } elseif ($status == 'เสร็จสิ้น') {
            $operation->status = 'เสร็จสิ้น';
            $operation->remark_by_helper = $request->input('remark');
            $operation->time_sos_success = now();
            
            // จัดการอัปโหลดไฟล์รูปภาพ ถ้ามีการแนบมา
            if ($request->hasFile('photo_succeed')) {
                $file = $request->file('photo_succeed');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/emergencys'), $filename);
                $operation->photo_succeed = 'uploads/emergencys/' . $filename;
            }
            
            // คำนวณเวลารวม
            if ($operation->time_create_sos) {
                $created = Carbon::parse($operation->time_create_sos);
                $diff = now()->diff($created);
                $operation->time_sum_sos = $diff->format('%h ชม. %i นาที');
            }
        }
        
        $operation->save();
        
        return response()->json(['success' => true]);
    }

    public function uploadPhoto(Request $request, $id)
    {
        $operation = Emergency_operation::where('emergency_id', $id)->firstOrFail();
        $response = ['success' => true];

        // กำหนด Path ปลายทางที่เป็น Absolute Path (สำหรับ Intervention)
        $destinationPath = storage_path('app/public/emergencys');
        
        // สร้างโฟลเดอร์ถ้ายังไม่มี
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }

        // ==========================================
        // 1. จัดการส่วนของ "ภาพที่เกิดเหตุ"
        // ==========================================
        if ($request->has('remark_photo_by_officer')) {
            $operation->remark_photo_by_officer = $request->input('remark_photo_by_officer');
        }

        if ($request->hasFile('photo_by_officer')) {
            $file = $request->file('photo_by_officer');
            
            // ตรวจสอบและจัดการนามสกุลไฟล์
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            if (in_array(strtolower($extension), ['octet-stream', 'tmp', ''])) {
                $extension = 'jpg'; 
            }
            
            $filename = date('Ymd_His') . '_scene_' . rand(100, 999) . '.' . $extension;

            // ดึงข้อมูลภาพดิบมาสร้าง Object เพื่อหลีกเลี่ยง Error GD อ่านไฟล์ชั่วคราวไม่ออก
            $img = Image::make($file->get());
            
            // ย่อขนาดความกว้างไม่เกิน 1200px
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); 
            });

            // บังคับเข้ารหัสเป็น JPG และลด Quality เหลือ 75% แล้วเซฟ
            $img->encode('jpg', 75)->save($destinationPath . '/' . $filename);
            
            // เซฟ path ลง DB (ดึงผ่าน Storage Symlink)
            $operation->photo_by_officer = 'emergencys/' . $filename;
            
            // ส่ง URL กลับไปให้ JS พรีวิว
            $response['photo_by_officer_url'] = $operation->photo_by_officer;
        }

        // ==========================================
        // 2. จัดการส่วนของ "ภาพเสร็จสิ้นภารกิจ"
        // ==========================================
        if ($request->has('remark_by_helper')) {
            $operation->remark_by_helper = $request->input('remark_by_helper');
        }

        if ($request->hasFile('photo_succeed')) {
            $file = $request->file('photo_succeed');
            
            // ตรวจสอบและจัดการนามสกุลไฟล์
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            if (in_array(strtolower($extension), ['octet-stream', 'tmp', ''])) {
                $extension = 'jpg'; 
            }
            
            $filename = date('Ymd_His') . '_succeed_' . rand(100, 999) . '.' . $extension;

            // ดึงข้อมูลภาพดิบมาสร้าง Object
            $img = Image::make($file->get());
            
            // ย่อขนาดความกว้างไม่เกิน 1200px
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); 
            });

            // บังคับเข้ารหัสเป็น JPG และลด Quality เหลือ 75% แล้วเซฟ
            $img->encode('jpg', 75)->save($destinationPath . '/' . $filename);
            
            // เซฟ path ลง DB (ดึงผ่าน Storage Symlink)
            $operation->photo_succeed = 'emergencys/' . $filename;
            
            // ส่ง URL กลับไปให้ JS พรีวิว
            $response['photo_succeed_url'] = $operation->photo_succeed;
        }

        // บันทึกลงฐานข้อมูล
        $operation->save();

        return response()->json($response);
    }

    public function showStatus()
    {
        $user_id = Auth::id();
        $officer = DB::table('user_officers')->where('user_id', $user_id)->first();

        // ดักจับกรณีที่ User นี้ยังไม่ได้ลงทะเบียนเป็นเจ้าหน้าที่
        if (!$officer) {
            return redirect('/')->with('error', 'คุณยังไม่ได้ลงทะเบียนเป็นเจ้าหน้าที่ระบบครับ');
        }

        return view('user_officers.switch_status', compact('officer'));
    }

    public function updateStatusStandby(Request $request)
    {
        $status = $request->input('status');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $user_id = auth()->id();

        // กันเหนียว เผื่อมีคนพยายามยิง API แก้สถานะตอนติดเคส
        $officer = DB::table('user_officers')->where('user_id', $user_id)->first();
        if($officer && $officer->status == 'Helping') {
            return response()->json(['success' => false, 'message' => 'คุณติดเคสอยู่ ไม่สามารถเปลี่ยนสถานะได้']);
        }

        if (in_array($status, ['Standby', 'None'])) {
            
            // --- บังคับรับค่าพิกัดเมื่อต้องการเปิด Standby ---
            if ($status === 'Standby' && (empty($lat) || empty($lng))) {
                return response()->json([
                    'success' => false, 
                    'message' => 'ระบบต้องการพิกัด GPS ปัจจุบันเพื่อเข้าสู่สถานะพร้อมปฏิบัติงาน'
                ]);
            }

            // เตรียมข้อมูลอัปเดต
            $updateData = ['status' => $status];
            
            // ถ้ามีการส่งพิกัดมาด้วย ให้อัปเดตพิกัด + เวลาล่าสุด
            if (!empty($lat) && !empty($lng)) {
                $updateData['lat'] = $lat;
                $updateData['lng'] = $lng;
                $updateData['last_update_location'] = now(); // บันทึกเวลาที่ได้พิกัดล่าสุด
            }

            DB::table('user_officers')
                ->where('user_id', $user_id)
                ->update($updateData);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'สถานะไม่ถูกต้อง'], 400);
    }

    public function updateStatus_CaseSuccess(Request $request, $id) 
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $remark = $request->input('remark');
        $user_id = auth()->id();

        $operation = DB::table('emergency_operations')->where('emergency_id', $id)->first();
        if (!$operation) {
            return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลเคส']);
        }

        $photoPath = null;
        // 1. จัดการอัปโหลดไฟล์รูปภาพ ถ้ามีแนบมา
        if ($request->hasFile('photo_succeed')) {
            $file = $request->file('photo_succeed');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/emergencys'), $filename);
            $photoPath = 'uploads/emergencys/' . $filename;
        }

        // 2. คำนวณเวลารวม (Time Sum)
        $timeSum = null;
        if ($operation->time_create_sos) {
            $created = \Carbon\Carbon::parse($operation->time_create_sos);
            $diff = now()->diff($created);
            
            $parts = [];
            if ($diff->h > 0) $parts[] = $diff->h . ' ชม.';
            if ($diff->i > 0 || empty($parts)) $parts[] = $diff->i . ' นาที';
            
            $timeSum = implode(' ', $parts);
        }

        // อัปเดตข้อมูลของ "เคสช่วยเหลือ"
        $updateCaseData = [
            'status' => 'เสร็จสิ้น',
            'remark_by_helper' => $remark,
            'time_sos_success' => now(),
            'time_sum_sos' => $timeSum
        ];

        // ถ้ามีรูปภาพให้เพิ่มเข้าไปใน Array อัปเดตด้วย
        if ($photoPath) {
            $updateCaseData['photo_succeed'] = $photoPath;
        }

        DB::table('emergency_operations')
            ->where('emergency_id', $id)
            ->update($updateCaseData);

        // อัปเดตสถานะของ "เจ้าหน้าที่"
        $updateOfficerData = ['status' => 'Standby'];
        if (!empty($lat) && !empty($lng)) {
            $updateOfficerData['lat'] = $lat;
            $updateOfficerData['lng'] = $lng;
            $updateOfficerData['last_update_location'] = now();
        }

        DB::table('user_officers')
            ->where('user_id', $user_id)
            ->update($updateOfficerData);

        return response()->json(['success' => true]);
    }

    // รับพิกัด Background โดยเฉพาะ
    public function updateLocationOnly(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $user_id = auth()->id();

        if (!empty($lat) && !empty($lng)) {
            // อัปเดตเฉพาะคนที่มีสถานะ Standby หรือ Helping เท่านั้น
            DB::table('user_officers')
                ->where('user_id', $user_id)
                ->whereIn('status', ['Standby', 'Helping'])
                ->update([
                    'lat' => $lat,
                    'lng' => $lng,
                    'last_update_location' => now()
                ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function officer_history()
    {
        $user_id = Auth::id();
        $officer = User_officer::where('user_id', $user_id)->first();

        // ตรวจสอบว่า user นี้เป็นเจ้าหน้าที่หรือไม่
        if (!$officer) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลเจ้าหน้าที่');
        }

        $operations = Emergency_operation::where('user_officers_id', $officer->id)
            ->with('emergency') 
            ->orderBy('time_create_sos', 'desc')
            ->get();

        return view('user_officers.officer_history', compact('operations', 'officer'));
    }

    public function syncOperation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'emergency_id' => 'required|integer'
        ]);

        // อัปเดตพิกัดเจ้าหน้าที่ (user_officers)
        $officer = User_officer::where('user_id', Auth::id())->first();
        if ($officer) {
            $officer->update([
                'lat' => $request->lat,
                'lng' => $request->lng,
                'last_update_location' => now()
            ]);
        }

        // ดึง log_command จากตาราง emergency_operations คืนกลับไป
        $operation = Emergency_operation::where('emergency_id', $request->emergency_id)->first();
        
        return response()->json([
            'success' => true,
            'log_command' => $operation ? $operation->log_command : null
        ]);
    }
}
