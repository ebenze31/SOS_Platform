<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Emergency_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Emergency_typesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $emergencyTypes = DB::table('emergency_types')
            ->orderBy('id', 'desc')
            ->get();
            
        return view('emergency_types.index', compact('emergencyTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('emergency_types.create');
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
        $id = $request->input('id');
        
        $data = [
            'name_emergency' => $request->input('name_emergency'),
            'status'         => $request->input('status', 'active'),
            'updated_at'     => Carbon::now(),
        ];

        if (!empty($id)) {
            // กรณี แก้ไข (Update)
            DB::table('emergency_types')->where('id', $id)->update($data);
            return back()->with('success', 'แก้ไขข้อมูลสำเร็จ!');
        } else {
            // กรณี เพิ่มใหม่ (Insert)
            $data['created_at'] = Carbon::now();
            DB::table('emergency_types')->insert($data);
            return back()->with('success', 'เพิ่มประเภทการแจ้งเหตุใหม่สำเร็จ!');
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
        $emergency_type = Emergency_type::findOrFail($id);

        return view('emergency_types.show', compact('emergency_type'));
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
        $emergency_type = Emergency_type::findOrFail($id);

        return view('emergency_types.edit', compact('emergency_type'));
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
        
        $emergency_type = Emergency_type::findOrFail($id);
        $emergency_type->update($requestData);

        return redirect('emergency_types')->with('flash_message', 'Emergency_type updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        
        if (!empty($id)) {
            DB::table('emergency_types')->where('id', $id)->delete();
            return back()->with('success', 'ลบข้อมูลสำเร็จ!');
        }
        
        return back()->with('error', 'ไม่พบข้อมูลที่ต้องการลบ');
    }

    public function updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        if (!empty($id) && !empty($status)) {
            DB::table('emergency_types')->where('id', $id)->update([
                'status' => $status,
                'updated_at' => Carbon::now(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
        }

        return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาด'], 400);
    }
}
