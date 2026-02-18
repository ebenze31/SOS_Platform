<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Phone_emergency;
use Illuminate\Http\Request;

class Phone_emergencysController extends Controller
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
            $phone_emergencys = Phone_emergency::where('name', 'LIKE', "%$keyword%")
                ->orWhere('phone', 'LIKE', "%$keyword%")
                ->orWhere('priority', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $phone_emergencys = Phone_emergency::latest()->paginate($perPage);
        }

        return view('phone_emergencys.index', compact('phone_emergencys'));
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
        
        $requestData = $request->all();
        
        Phone_emergency::create($requestData);

        return redirect('phone_emergencys')->with('flash_message', 'Phone_emergency added!');
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
    public function destroy($id)
    {
        Phone_emergency::destroy($id);

        return redirect('phone_emergencys')->with('flash_message', 'Phone_emergency deleted!');
    }
}
