<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Emergency;
use Illuminate\Http\Request;

class EmergencysController extends Controller
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
            $emergencys = Emergency::where('name_reporter', 'LIKE', "%$keyword%")
                ->orWhere('type_reporter', 'LIKE', "%$keyword%")
                ->orWhere('phone_reporter', 'LIKE', "%$keyword%")
                ->orWhere('emergency_type', 'LIKE', "%$keyword%")
                ->orWhere('emergency_detail', 'LIKE', "%$keyword%")
                ->orWhere('emergency_lat', 'LIKE', "%$keyword%")
                ->orWhere('emergency_lng', 'LIKE', "%$keyword%")
                ->orWhere('emergency_location', 'LIKE', "%$keyword%")
                ->orWhere('emergency_photo', 'LIKE', "%$keyword%")
                ->orWhere('score_impression', 'LIKE', "%$keyword%")
                ->orWhere('score_period', 'LIKE', "%$keyword%")
                ->orWhere('score_total', 'LIKE', "%$keyword%")
                ->orWhere('comment_help', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $emergencys = Emergency::latest()->paginate($perPage);
        }

        return view('emergencys.index', compact('emergencys'));
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
        
        $requestData = $request->all();
        
        Emergency::create($requestData);

        return redirect('emergencys')->with('flash_message', 'Emergency added!');
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
}
