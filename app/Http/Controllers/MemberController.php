<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\User_officer;
use App\Models\User_command;
use App\Models\Emergency;
use App\Models\Emergency_operation;
use App\Models\Area;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount('emergencys')->paginate(25, ['*'], 'users_page');
        
        $commands = User_command::with('creator_info')
            ->withCount([
                'emergency_operations as total_ops',
                'emergency_operations as success_ops' => function ($query) {
                    $query->where('status', 'เสร็จสิ้น');
                },
                'emergency_operations as pending_ops' => function ($query) {
                    $query->where('status', '!=', 'เสร็จสิ้น');
                }
            ])
            ->paginate(25, ['*'], 'commands_page');
        
        $officers = User_officer::with('user')->paginate(25, ['*'], 'officers_page');

        $areas = Area::pluck('name_area', 'id')->toArray();

        return view('members.index', compact('users', 'commands', 'officers', 'areas'));
    }

    public function storeCommand(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูล (Username ต้องไม่ซ้ำในระบบ)
        $request->validate([
            'name_command' => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username',
            'password'     => 'required|string|min:6',
            'command_role' => 'required|in:command,supervisor',
        ]);

        // ใช้ Transaction เพื่อป้องกันกรณีบันทึกตารางใดตารางหนึ่งไม่สำเร็จ
        DB::beginTransaction();
        try {
            // สร้างข้อมูลในตาราง users
            $user = User::create([
                'name'     => $request->name_command,
                'email' => $request->username,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => 'admin', 
                'status'   => 'Active',
            ]);

            // นำ id ที่ได้ไปผูกกับตาราง user_commands
            User_command::create([
                'name_command' => $request->name_command,
                'command_role' => $request->command_role,
                'creator'      => Auth::id() ?? null, 
                'user_id'      => $user->id,
                'status'       => 'Active',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data'    => [
                    'name_command' => $request->name_command,
                    'username'     => $request->username,
                    'password'     => $request->password,
                    'command_role' => $request->command_role,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        $user->status = $user->status === 'Active' ? 'Inactive' : 'Active';
        $user->save();

        // ตรวจสอบว่าผู้ใช้คนนี้มีข้อมูลอยู่ในตาราง user_officers หรือไม่
        if ($user->userOfficer) {
            // หากใช่ ให้ปรับสถานะของเจ้าหน้าที่คนนั้นเป็น None
            $user->userOfficer->status = 'None';
            $user->userOfficer->save();
        }

        return response()->json([
            'success' => true,
            'status' => $user->status
        ]);
    }

    public function toggleCommandStatus($id)
    {
        $command = User_command::findOrFail($id);
        
        $newStatus = $command->status === 'Active' ? 'Inactive' : 'Active';
        
        // อัปเดตในตาราง user_commands
        $command->status = $newStatus;
        $command->save();

        // อัปเดตในตาราง users
        if ($command->user) {
            $command->user->status = $newStatus;
            $command->user->save();
        }

        return response()->json([
            'success' => true,
            'status' => $newStatus
        ]);
    }

    public function toggleOfficerStatus($id)
    {
        $officer = User_officer::findOrFail($id);
        
        // อัปเดตสถานะการปฏิบัติงานเป็น None
        $officer->status = 'None';
        $officer->save();

        // สถานะในตาราง users
        $newUserStatus = 'Inactive';
        if ($officer->user) {
            $newUserStatus = $officer->user->status === 'Active' ? 'Inactive' : 'Active';
            $officer->user->status = $newUserStatus;
            $officer->user->save();
        }

        return response()->json([
            'success' => true,
            'user_status' => $newUserStatus,
            'officer_status' => 'None'
        ]);
    }
}