<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User_officer;
use Illuminate\Support\Facades\DB;

class AreasController extends Controller
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
            $areas = Area::where('name_area', 'LIKE', "%$keyword%")
                ->orWhere('type', 'LIKE', "%$keyword%")
                ->orWhere('polygon', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('creator', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $areas = Area::latest()->paginate($perPage);
        }

        return view('areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('areas.create');
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
        
        Area::create($requestData);

        return redirect('areas')->with('flash_message', 'Area added!');
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
        $area = Area::findOrFail($id);

        return view('areas.show', compact('area'));
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
        $area = Area::findOrFail($id);

        return view('areas.edit', compact('area'));
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
        
        $area = Area::findOrFail($id);
        $area->update($requestData);

        return redirect('areas')->with('flash_message', 'Area updated!');
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
        Area::destroy($id);

        return redirect('areas')->with('flash_message', 'Area deleted!');
    }

    // เปิดหน้าฟอร์มวาดแผนที่
    public function create_polygon()
    {
        // ตรวจสอบสิทธิ์จากตาราง user_commands
        $isSupervisor = DB::table('user_commands')
            ->where('user_id', auth()->id())
            ->where('command_role', 'supervisor')
            ->exists();

        if (!$isSupervisor) {
            return redirect()->back()->with('error', 'ข้อผิดพลาด: เฉพาะผู้ดูแลระบบ (Supervisor) เท่านั้นที่สามารถสร้างพื้นที่ใหม่ได้');
        }

        $groups = DB::table('group_lines')
                    ->whereNull('area_id')
                    ->where('status', 'active')
                    ->get();

        return view('areas.create_polygon', compact('groups'));
    }

    public function get_groups_ajax(Request $request)
    {
        // รับค่า area_id ที่ส่งมาจากหน้า manage_area (ถ้ามี)
        $currentAreaId = $request->query('area_id');

        $groups = DB::table('group_lines')
                    ->where(function($query) use ($currentAreaId) {
                        // เงื่อนไขที่ 1: กลุ่มที่ยังว่างอยู่
                        $query->whereNull('area_id');
                        
                        // เงื่อนไขที่ 2: หรือเป็นกลุ่มที่ผูกกับพื้นที่นี้อยู่แล้ว (ถ้าส่ง currentAreaId มา)
                        if ($currentAreaId) {
                            $query->orWhere('area_id', $currentAreaId);
                        }
                    })
                    ->where('status', 'active')
                    ->select('id', 'groupName')
                    ->get();

        return response()->json($groups);
    }

    // บันทึกข้อมูลลง DB
    public function store_polygon(Request $request)
    {
        // 1. ตรวจสอบสิทธิ์ระดับ Supervisor
        $isSupervisor = DB::table('user_commands')
            ->where('user_id', auth()->id())
            ->where('command_role', 'supervisor')
            ->exists();

        if (!$isSupervisor) {
            abort(403, 'Unauthorized: Supervisor role required.');
        }

        $request->validate([
            'name_area' => 'required|string|max:255',
            'type'      => 'required|string|max:100',
            'status'    => 'required|string',
            'polygon'   => 'required|json',
            'groupID'   => 'required_if:auto_assign,Yes',
        ]);

        // 2. บันทึกลงตาราง areas
        $area = new Area();
        $area->name_area = $request->name_area;
        $area->type      = $request->type;
        $area->status    = $request->status;
        $area->polygon   = $request->polygon;
        $area->creator   = auth()->id();

        if ($request->auto_assign === 'Yes') {
            $area->auto_assign = 'Yes';
            $area->day_command = json_encode($request->day_command, JSON_UNESCAPED_UNICODE);
            
            $area->time_start_command = $request->time_start_command ? $request->time_start_command . ':00' : null;
            $area->time_end_command   = $request->time_end_command ? $request->time_end_command . ':00' : null;
            
            $area->groupID = $request->groupID;
        } else {
            $area->auto_assign = 'No';
        }
        $area->save();

        // 3. ผูกกลุ่มไลน์ (ถ้ามี)
        if ($request->auto_assign === 'Yes' && $request->groupID) {
            DB::table('group_lines')
                ->where('id', $request->groupID)
                ->update(['area_id' => $area->id, 'updated_at' => now()]);
        }

        return redirect()->route('area.manage_area', $area->id)
                         ->with('success', 'สร้างพื้นที่เรียบร้อยแล้ว');
    }

    // public function area_main(Request $request)
    // {
    //     $query = Area::withCount('operations');

    //     if ($request->has('search') && $request->search != '') {
    //         $search = $request->search;
    //         $query->where(function($q) use ($search) {
    //             $q->where('name_area', 'like', '%' . $search . '%')
    //               ->orWhere('type', 'like', '%' . $search . '%');
    //         });
    //     }

    //     $areas = $query->orderBy('id', 'desc')->paginate(10);

    //     return view('areas.area_main', compact('areas'));
    // }

    // public function manage_area($id)
    // {
    //     $area = Area::findOrFail($id);
        
    //     $registerUrl = route('user_officers.register', ['area_id' => $area->id]);

    //     // ดึงข้อมูลเจ้าหน้าที่ที่อยู่ในพื้นที่
    //     $officers = User_officer::whereJsonContains('area_id', (string)$area->id)
    //                         ->orWhereJsonContains('area_id', (int)$area->id)
    //                         ->get();

    //     return view('areas.manage_area', compact('area', 'registerUrl', 'officers'));
    // }

    public function area_main(Request $request)
    {
        $user = auth()->user();

        // 1. เช็คสิทธิ์ว่าเป็น Supervisor หรือไม่
        $isSupervisor = DB::table('user_commands')
            ->where('user_id', $user->id)
            ->where('command_role', 'supervisor')
            ->exists();

        // 2. สร้าง Base Query: ดึง Area + Join หาชื่อกลุ่มไลน์
        // **แก้ไขแล้ว**: ย้าย withCount() มาไว้หลังสุด เพื่อไม่ให้ ->select() ไปเขียนทับมัน
        $query = Area::leftJoin('group_lines', 'areas.groupID', '=', 'group_lines.id')
            ->select('areas.*', 'group_lines.groupName as group_name')
            ->withCount('operations');

        // 3. กรองข้อมูลตามสิทธิ์ (ถ้าระดับ Command ให้ดูได้เฉพาะ Area ตัวเอง)
        if (!$isSupervisor) {
            $allowedAreaIds = DB::table('user_commands')
                ->where('user_id', $user->id)
                ->where('command_role', 'command')
                ->pluck('area_id');

            $query->whereIn('areas.id', $allowedAreaIds);
        }

        // 4. ค้นหาข้อมูล (ต้องระบุ areas. นำหน้าเพื่อป้องกัน column ambiguity)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('areas.name_area', 'like', '%' . $search . '%')
                  ->orWhere('group_lines.groupName', 'like', '%' . $search . '%');
            });
        }

        $areas = $query->orderBy('areas.id', 'desc')->paginate(10);

        return view('areas.area_main', compact('areas', 'isSupervisor'));
    }

    public function manage_area($id)
    {
        $area = Area::findOrFail($id);
        $user = auth()->user();

        // --- ตรวจสอบสิทธิ์ ---
        $userCommand = DB::table('user_commands')
            ->where('user_id', $user->id)
            ->where('area_id', $id)
            ->first();

        $isSupervisor = DB::table('user_commands')
            ->where('user_id', $user->id)
            ->where('command_role', 'supervisor')
            ->exists();

        // ถ้าไม่ใช่ supervisor และ ไม่มีสิทธิ์ในพื้นที่นี้ (ไม่ใช่ command ของพื้นที่นี้) ให้เตะออก
        if (!$isSupervisor && (!$userCommand || $userCommand->command_role !== 'command')) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์เข้าถึงการจัดการพื้นที่นี้');
        }

        $registerUrl = route('user_officers.register', ['area_id' => $area->id]);

        // ดึงข้อมูลเจ้าหน้าที่
        $officers = User_officer::whereJsonContains('area_id', (string)$area->id)
                                ->orWhereJsonContains('area_id', (int)$area->id)
                                ->get();

        // ดึงกลุ่มไลน์: เอาที่ว่างอยู่ (area_id is null) หรือ กลุ่มที่ผูกกับพื้นที่นี้อยู่แล้ว
        $groups = DB::table('group_lines')
                    ->where(function($query) use ($area) {
                        $query->whereNull('area_id')
                              ->orWhere('area_id', $area->id);
                    })
                    ->where('status', 'active')
                    ->get();

        return view('areas.manage_area', compact('area', 'registerUrl', 'officers', 'groups'));
    }

    // public function update_manage_area(Request $request, $id)
    // {
    //     $area = Area::findOrFail($id);

    //     $request->validate([
    //         'name_area' => 'required|string|max:255',
    //         'status'    => 'required|string',
    //         'polygon'   => 'required|json',
    //     ]);

    //     $area->name_area = $request->name_area;
    //     $area->status    = $request->status;
    //     $area->polygon   = $request->polygon;
    //     $area->save();

    //     return redirect()->back()->with('success', 'บันทึกการแก้ไขข้อมูลพื้นที่เรียบร้อยแล้ว');
    // }

    public function update_manage_area(Request $request, $id)
    {
        $area = Area::findOrFail($id);
        
        // ตรวจสอบสิทธิ์ซ้ำอีกครั้ง (Security)
        $isSupervisor = DB::table('user_commands')->where('user_id', auth()->id())->where('command_role', 'supervisor')->exists();
        $isCommandOfArea = DB::table('user_commands')->where('user_id', auth()->id())->where('area_id', $id)->where('command_role', 'command')->exists();

        if (!$isSupervisor && !$isCommandOfArea) {
            abort(403);
        }

        $request->validate([
            'name_area' => 'required|string|max:255',
            'status'    => 'required|string',
            'polygon'   => 'required|json',
            'groupID'   => 'required_if:auto_assign,Yes',
        ]);

        // เดิมทีกลุ่มไลน์ไหนผูกอยู่กับพื้นที่นี้ ให้เคลียร์ออกก่อน (เผื่อมีการเปลี่ยนกลุ่ม)
        DB::table('group_lines')->where('area_id', $id)->update(['area_id' => null]);

        // อัปเดตข้อมูล Area
        $area->name_area = $request->name_area;
        $area->status    = $request->status;
        $area->polygon   = $request->polygon;

        if ($request->auto_assign === 'Yes') {
            $area->auto_assign = 'Yes';
            $area->day_command = json_encode($request->day_command);
            // เติม :00 เพื่อให้ Format ตรงกับ TIME ใน DB
            $area->time_start_command = $request->time_start_command ? $request->time_start_command . ':00' : null;
            $area->time_end_command   = $request->time_end_command ? $request->time_end_command . ':00' : null;
            $area->groupID = $request->groupID;

            // ผูกกลุ่มไลน์ใหม่
            DB::table('group_lines')->where('id', $request->groupID)->update(['area_id' => $id]);
        } else {
            $area->auto_assign = 'No';
            // เคลียร์ค่าที่เกี่ยวข้องถ้าปิดใช้งาน
            $area->day_command = null;
            $area->time_start_command = null;
            $area->time_end_command = null;
            $area->groupID = null;
        }

        $area->save();

        return redirect()->back()->with('success', 'อัปเดตข้อมูลพื้นที่เรียบร้อยแล้ว');
    }

    public function toggle_status(Request $request, $id)
    {
        try {
            $area = Area::findOrFail($id);
            $area->status = $request->status;
            $area->save();

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตสถานะเรียบร้อยแล้ว'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

}
