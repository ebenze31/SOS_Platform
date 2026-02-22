<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        return view('areas.create_polygon');
    }

    // บันทึกข้อมูลลง DB
    public function store_polygon(Request $request)
    {
        $request->validate([
            'name_area' => 'required|string|max:255',
            'type'      => 'required|string|max:100',
            'status'    => 'required|string',
            'polygon'   => 'required|json',
        ]);

        // บันทึกลงตาราง areas
        $area = new Area();
        $area->name_area = $request->name_area;
        $area->type      = $request->type;
        $area->status    = $request->status;
        $area->polygon   = $request->polygon;
        $area->creator = auth()->user()->id; 
        $area->save();

        // ส่งต่อไปหน้าแสดง QR Code สำหรับให้เจ้าหน้าที่สแกนลงทะเบียน
        return redirect()->route('area.show_qrcode', $area->id)
                         ->with('success', 'สร้างพื้นที่รับผิดชอบเรียบร้อยแล้ว');
    }

    public function show_qrcode($id)
    {
        $area = Area::findOrFail($id);
        
        $registerUrl = route('officer.register', ['area_id' => $area->id]);

        return view('areas.show_qrcode', compact('area', 'registerUrl'));
    }
}
