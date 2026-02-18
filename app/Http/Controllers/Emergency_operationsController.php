<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Emergency_operation;
use Illuminate\Http\Request;

class Emergency_operationsController extends Controller
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
            $emergency_operations = Emergency_operation::where('emergency_id', 'LIKE', "%$keyword%")
                ->orWhere('notify', 'LIKE', "%$keyword%")
                ->orWhere('command_by', 'LIKE', "%$keyword%")
                ->orWhere('operating_code', 'LIKE', "%$keyword%")
                ->orWhere('waiting_reply', 'LIKE', "%$keyword%")
                ->orWhere('officer_refuse', 'LIKE', "%$keyword%")
                ->orWhere('officer_no_respond', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('remark_status', 'LIKE', "%$keyword%")
                ->orWhere('area_id', 'LIKE', "%$keyword%")
                ->orWhere('user_officers_id', 'LIKE', "%$keyword%")
                ->orWhere('time_create_sos', 'LIKE', "%$keyword%")
                ->orWhere('time_command', 'LIKE', "%$keyword%")
                ->orWhere('time_go_to_help', 'LIKE', "%$keyword%")
                ->orWhere('time_to_the_scene', 'LIKE', "%$keyword%")
                ->orWhere('time_sos_success', 'LIKE', "%$keyword%")
                ->orWhere('time_sum_sos', 'LIKE', "%$keyword%")
                ->orWhere('photo_by_officer', 'LIKE', "%$keyword%")
                ->orWhere('remark_photo_by_officer', 'LIKE', "%$keyword%")
                ->orWhere('photo_succeed', 'LIKE', "%$keyword%")
                ->orWhere('remark_by_helper', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $emergency_operations = Emergency_operation::latest()->paginate($perPage);
        }

        return view('emergency_operations.index', compact('emergency_operations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('emergency_operations.create');
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
        
        Emergency_operation::create($requestData);

        return redirect('emergency_operations')->with('flash_message', 'Emergency_operation added!');
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
        $emergency_operation = Emergency_operation::findOrFail($id);

        return view('emergency_operations.show', compact('emergency_operation'));
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
        $emergency_operation = Emergency_operation::findOrFail($id);

        return view('emergency_operations.edit', compact('emergency_operation'));
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
        
        $emergency_operation = Emergency_operation::findOrFail($id);
        $emergency_operation->update($requestData);

        return redirect('emergency_operations')->with('flash_message', 'Emergency_operation updated!');
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
        Emergency_operation::destroy($id);

        return redirect('emergency_operations')->with('flash_message', 'Emergency_operation deleted!');
    }
}
