<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Data_organization;
use Illuminate\Http\Request;

class Data_organizationsController extends Controller
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
            $data_organizations = Data_organization::where('name', 'LIKE', "%$keyword%")
                ->orWhere('full_name', 'LIKE', "%$keyword%")
                ->orWhere('phone', 'LIKE', "%$keyword%")
                ->orWhere('mail', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $data_organizations = Data_organization::latest()->paginate($perPage);
        }

        return view('data_organizations.index', compact('data_organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('data_organizations.create');
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
        
        Data_organization::create($requestData);

        return redirect('data_organizations')->with('flash_message', 'Data_organization added!');
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
        $data_organization = Data_organization::findOrFail($id);

        return view('data_organizations.show', compact('data_organization'));
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
        $data_organization = Data_organization::findOrFail($id);

        return view('data_organizations.edit', compact('data_organization'));
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
        
        $data_organization = Data_organization::findOrFail($id);
        $data_organization->update($requestData);

        return redirect('data_organizations')->with('flash_message', 'Data_organization updated!');
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
        Data_organization::destroy($id);

        return redirect('data_organizations')->with('flash_message', 'Data_organization deleted!');
    }
}
