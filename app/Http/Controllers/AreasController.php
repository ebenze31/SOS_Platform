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

    public function get_groups_ajax()
    {
        $groups = DB::table('group_lines')
                    ->whereNull('area_id')
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

    public function area_main(Request $request)
    {
        $query = Area::withCount('operations');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_area', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        $areas = $query->orderBy('id', 'desc')->paginate(10);

        return view('areas.area_main', compact('areas'));
    }

    public function manage_area($id)
    {
        $area = Area::findOrFail($id);
        
        $registerUrl = route('user_officers.register', ['area_id' => $area->id]);

        // ดึงข้อมูลเจ้าหน้าที่ที่อยู่ในพื้นที่
        $officers = User_officer::whereJsonContains('area_id', (string)$area->id)
                            ->orWhereJsonContains('area_id', (int)$area->id)
                            ->get();

        return view('areas.manage_area', compact('area', 'registerUrl', 'officers'));
    }

    public function update_manage_area(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $request->validate([
            'name_area' => 'required|string|max:255',
            'status'    => 'required|string',
            'polygon'   => 'required|json',
        ]);

        $area->name_area = $request->name_area;
        $area->status    = $request->status;
        $area->polygon   = $request->polygon;
        $area->save();

        return redirect()->back()->with('success', 'บันทึกการแก้ไขข้อมูลพื้นที่เรียบร้อยแล้ว');
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
