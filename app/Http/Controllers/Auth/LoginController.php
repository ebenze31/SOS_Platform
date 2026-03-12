<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Models\My_log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function authenticated(Request $request, $user)
    {
        $role = $user->role;
        
        if ($role === 'admin') {
            return redirect()->intended('/monitor');
        } elseif ($role === 'officer') {
            return redirect()->intended('/officer/open_status');
        } else {
            return redirect()->intended('/sos');
        }
    }

    // ส่งผู้ใช้ไปหน้าเว็บ LINE เพื่อกดยืนยัน
    public function redirectToLine()
    {
        return Socialite::driver('line')->redirect();
    }

    // รับข้อมูลกลับมาจาก LINE
    public function handleLineCallback()
    {
        try {
            $lineUser = Socialite::driver('line')->user();
            $lineAvatarUrl = $lineUser->getAvatar(); // ดึง URL รูปจาก LINE

            // หา User ในระบบ
            $user = User::where('provider_id', $lineUser->getId())->first();

            if (!$user) {
                // ถ้าเป็นผู้ใช้ใหม่ ให้สร้างอินสแตนซ์ใหม่รอไว้
                $user = new User();
                $user->email = "-";
                $user->name = $lineUser->getName();
                $user->provider_id = $lineUser->getId();
                $user->role = null; 
                // ถ้า LINE ส่งอีเมลมาก็ใช้เลย ถ้าไม่ส่งมา ให้ใช้อีเมลจำลอง
                $user->email = $lineUser->getEmail() ?? $lineUser->getId() . '@line.me';
                // สุ่มรหัสผ่านยาวๆ 24 ตัวอักษร แล้วเข้ารหัสให้ปลอดภัย (ให้ผ่านเงื่อนไข DB)
                $user->password = Hash::make(Str::random(24));
            }

            // ==========================================
            // จัดการรูปโปรไฟล์ (Avatar & Photo)
            // ==========================================
            // เช็คว่า LINE ส่งรูปมาให้ และ URL ไม่ตรงกับของเดิมที่บันทึกไว้ใน DB
            if (!empty($lineAvatarUrl) && $user->avatar !== $lineAvatarUrl) {

                // ถ้ามีไฟล์รูปเก่าอยู่ใน Server ให้ลบก่อน
                if (!empty($user->photo) && file_exists(public_path($user->photo))) {
                    unlink(public_path($user->photo)); // ลบไฟล์เก่า
                }

                // สร้างโฟลเดอร์สำหรับเก็บรูป
                $destinationPath = public_path('avatars');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // โหลดรูปจาก LINE มาบันทึกลงเครื่อง
                // ตั้งชื่อไฟล์ใหม่โดยใช้ ID_Timestamp เพื่อป้องกันชื่อซ้ำและปัญหารูปแคช (Cache)
                $filename = 'avatars/' . $lineUser->getId() . '_' . time() . '.jpg';
                $imageContent = file_get_contents($lineAvatarUrl);

                if ($imageContent !== false) {
                    file_put_contents(public_path($filename), $imageContent);

                    // อัปเดตข้อมูลลง Object
                    $user->avatar = $lineAvatarUrl;
                    $user->photo = $filename;
                }
            }

            // บันทึกข้อมูล
            $user->save();

            // ล็อกอิน
            Auth::login($user);

            // ==========================================
            // บันทึกประวัติลง My_log
            // ==========================================
            $title = $isNewUser ? 'ผู้ใช้สมัครสมาชิกใหม่ด้วย LINE' : 'ผู้ใช้เข้าสู่ระบบด้วย LINE';
            $textContent = 'ชื่อ: ' . $user->name . ' (Provider ID: ' . $user->provider_id . ')';

            // ตัดคำถ้าเกิน 250 ตัวอักษร
            if (mb_strlen($textContent, 'UTF-8') > 250) {
                $textContent = mb_substr($textContent, 0, 247, 'UTF-8') . '...';
            }

            $log = new My_log();
            $log->title = $title;
            $log->content = $textContent; 
            
            // เก็บข้อมูล Array ดิบของ LINE (เช่น displayName, userId, pictureUrl) เป็น JSON
            // $lineUser->user จะให้ข้อมูล Array ดิบจาก Socialite
            $log->event_arr = json_encode($lineUser->user, JSON_UNESCAPED_UNICODE);
            $log->save();
            // ==========================================

            // แยกตาม Role
            if ($user->role === 'admin') {
                return redirect()->intended('/monitor');
            } elseif ($user->role === 'officer') {
                return redirect()->intended('/officer/open_status');
            } else {
                return redirect()->intended('/sos'); 
            }

        } catch (\Exception $e) {
            // เก็บ Log แบบ Error หากระบบพัง
            $errorLog = new My_log();
            $errorLog->title = 'เกิดข้อผิดพลาด: LINE Login';
            $errorLog->content = mb_substr($e->getMessage(), 0, 250, 'UTF-8');
            $errorLog->event_arr = json_encode([]);
            $errorLog->save();

            \Illuminate\Support\Facades\Log::error('LINE Login Error: ' . $e->getMessage()); 
            return redirect('/login')->with('error', 'เกิดข้อผิดพลาดในการล็อกอินด้วย LINE');
        }
    }
}
