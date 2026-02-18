<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\User_officer;
use Illuminate\Http\Request;

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
}
