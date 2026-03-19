<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Phone_emergency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Phone_emergencysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ดึงข้อมูลทั้งหมด เรียงตามลำดับความสำคัญ (priority) จากน้อยไปมาก
        $phones = DB::table('phone_emergencys')
            ->orderBy('priority', 'asc')
            ->orderBy('id', 'desc') // ถ้า priority เท่ากัน ให้อันใหม่ขึ้นก่อน
            ->get();
            
        return view('phone_emergencys.index', compact('phones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('phone_emergencys.create');
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
        // Validate ตรวจสอบข้อมูลเบื้องต้น
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'priority' => 'nullable|integer',
        ]);

        $id = $request->input('id'); 
        
        // เตรียมข้อมูลสำหรับบันทึก
        $data = [
            'name'       => $request->input('name'),
            'phone'      => $request->input('phone'),
            'priority'   => $request->input('priority', 999999), // ถ้าไม่ใส่ ให้เริ่มต้นที่ 999999
            'status'     => $request->input('status', 'Active'),
            'updated_at' => Carbon::now(),
        ];

        if (!empty($id)) {
            // กรณี แก้ไข (Update)
            DB::table('phone_emergencys')->where('id', $id)->update($data);
            return back()->with('success', 'แก้ไขข้อมูลเบอร์โทรฉุกเฉินสำเร็จ!');
        } else {
            // กรณี เพิ่มใหม่ (Insert)
            $data['created_at'] = Carbon::now();
            DB::table('phone_emergencys')->insert($data);
            return back()->with('success', 'เพิ่มเบอร์โทรฉุกเฉินใหม่สำเร็จ!');
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
        $phone_emergency = Phone_emergency::findOrFail($id);

        return view('phone_emergencys.show', compact('phone_emergency'));
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
        $phone_emergency = Phone_emergency::findOrFail($id);

        return view('phone_emergencys.edit', compact('phone_emergency'));
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
        
        $phone_emergency = Phone_emergency::findOrFail($id);
        $phone_emergency->update($requestData);

        return redirect('phone_emergencys')->with('flash_message', 'Phone_emergency updated!');
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
            DB::table('phone_emergencys')->where('id', $id)->delete();
            return back()->with('success', 'ลบข้อมูลสำเร็จ!');
        }
        
        return back()->with('error', 'ไม่พบข้อมูลที่ต้องการลบ');
    }

    public function updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        if (!empty($id) && !empty($status)) {
            DB::table('phone_emergencys')->where('id', $id)->update([
                'status' => $status,
                'updated_at' => Carbon::now(),
            ]);
            
            return response()->json(['success' => true, 'message' => 'อัปเดตสถานะสำเร็จ']);
        }

        return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการส่งข้อมูล'], 400);
    }

    // อัปเดตลำดับการแสดงผลจากการลาก (Drag & Drop)
    public function updatePriority(Request $request)
    {
        $order = $request->input('order');

        if (!empty($order) && is_array($order)) {
            DB::beginTransaction();
            try {
                foreach ($order as $index => $id) {
                    DB::table('phone_emergencys')->where('id', $id)->update([
                        'priority' => $index + 1,
                        'updated_at' => Carbon::now()
                    ]);
                }
                
                DB::commit();
                return response()->json(['success' => true]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        return response()->json(['success' => false]);
    }
}
