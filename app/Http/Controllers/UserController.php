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

        return view('profile.profile', compact('data_user', 'areas', 'area_ids'));
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
    // public function update(Request $request, $id)
    // {
        
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email',
    //     ]);

    //     $user = User::findOrFail($id);

    //     $data = [
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'gender' => $request->gender,
    //         'birthday' => $request->birthday,
    //     ];

    //     // เช็คว่ามีไฟล์ไหม
    //     if ($request->hasFile('photo')) {
    //         $data['photo'] = $request->file('photo')->store('uploads', 'public');
    //     }

    //     $user->update($data);

    //     DB::table('user_officers')
    //         ->updateOrInsert(
    //             ['user_id' => $id],
    //             [
    //                 'vehicle_type' => $request->vehicle_type,
    //             ]
    //         );
    //     return redirect()->back()->with('success', 'อัปเดตสำเร็จ');
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $user = User::findOrFail($id);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
        ];

        // --- จัดการรูปภาพ (เก็บที่ public/avatars) ---
        if ($request->hasFile('photo')) {
            
            // 1. ลบรูปเก่าทิ้ง ถ้ามีอยู่ในโฟลเดอร์ public
            if (!empty($user->photo) && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo)); 
            }

            // 2. ตรวจสอบและสร้างโฟลเดอร์ avatars ถ้ายังไม่มี
            $destinationPath = public_path('avatars');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // 3. ตั้งชื่อไฟล์ใหม่
            $file = $request->file('photo');
            $filename = 'avatars/' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // 4. ย้ายไฟล์ไปที่ public/avatars
            $file->move($destinationPath, basename($filename));

            // 5. อัปเดต Path ไฟล์ลงใน $data เพื่อรอ save
            $data['photo'] = $filename;
        }
        // ------------------------------------------

        $user->update($data);

        if($user->role == "officer"){
            // อัปเดตข้อมูลรถ
            DB::table('user_officers')
                ->update(
                    ['user_id' => $id],
                    [
                        'vehicle_type' => $request->vehicle_type,
                    ]
                );
        }

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
