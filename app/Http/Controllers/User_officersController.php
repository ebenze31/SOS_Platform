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
        
        // รับหมายเหตุจาก Modal
        if ($request->has('remark')) {
            $operation->remark_by_helper = $request->input('remark');
        }

        // จัดการอัปโหลดไฟล์รูปภาพ
        if ($request->hasFile('photo_succeed')) {
            $file = $request->file('photo_succeed');
            $filename = time() . '_' . $file->getClientOriginalName();
            // ย้ายไฟล์ไปที่ public/uploads/emergencys
            $file->move(public_path('uploads/emergencys'), $filename);
            
            $operation->photo_succeed = 'uploads/emergencys/' . $filename;
        }

        $operation->save();

        return response()->json([
            'success' => true,
            'photo_url' => asset($operation->photo_succeed),
            'remark' => $operation->remark_by_helper
        ]);
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
            // เตรียมข้อมูลอัปเดต
            $updateData = ['status' => $status];
            
            // ถ้ามีการส่งพิกัดมาด้วย ให้อัปเดตพิกัดด้วย
            if ($lat != null && $lng != null) {
                $updateData['lat'] = $lat;
                $updateData['lng'] = $lng;
            }

            DB::table('user_officers')
                ->where('user_id', $user_id)
                ->update($updateData);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'สถานะไม่ถูกต้อง'], 400);
    }
}
