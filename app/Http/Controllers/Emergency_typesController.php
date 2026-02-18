<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Emergency_type;
use Illuminate\Http\Request;

class Emergency_typesController extends Controller
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
            $emergency_types = Emergency_type::where('name_emergency', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $emergency_types = Emergency_type::latest()->paginate($perPage);
        }

        return view('emergency_types.index', compact('emergency_types'));
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
        
        $requestData = $request->all();
        
        Emergency_type::create($requestData);

        return redirect('emergency_types')->with('flash_message', 'Emergency_type added!');
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
    public function destroy($id)
    {
        Emergency_type::destroy($id);

        return redirect('emergency_types')->with('flash_message', 'Emergency_type deleted!');
    }
}
