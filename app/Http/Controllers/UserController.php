<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user_id = auth()->id();

        $data_user = DB::table('users')
            ->where('users.id', $user_id)
            ->leftJoin('user_officers', 'users.id', '=', 'user_officers.user_id')
            ->first();

        // แปลง JSON → array
        $area_ids = json_decode($data_user->area_id, true);

        $areas = DB::table('areas')
            ->whereIn('id', $area_ids ?? [])
            ->get();

        return view('demo.profile', compact('data_user', 'areas', 'area_ids'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
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

        User::create($requestData);

        return redirect('users')->with('flash_message', 'User added!');
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
        $user_officer = User::findOrFail($id);

        return view('users.show', compact('user_officer'));
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
        $user_officer = User::findOrFail($id);

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

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
    
        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
        ]);

        DB::table('user_officers')
            ->updateOrInsert(
                ['user_id' => $id],
                [
                    'vehicle_type' => $request->vehicle_type,
                ]
            );
        return redirect()->back()->with('success', 'อัปเดตสำเร็จ');
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
        User::destroy($id);

        return redirect('user_officers')->with('flash_message', 'User deleted!');
    }
}
