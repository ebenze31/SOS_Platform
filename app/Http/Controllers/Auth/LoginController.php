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
use Illuminate\Validation\ValidationException;

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

    // --- ตรวจสอบสถานะสำหรับการล็อกอินแบบปกติ (Username/Password) ---
    protected function credentials(Request $request)
    {
        // ดึงข้อมูล username และ password ตามปกติ พร้อมเพิ่มเงื่อนไข status
        return array_merge($request->only($this->username(), 'password'), ['status' => 'Active']);
    }

    // Override ฟังก์ชัน sendFailedLoginResponse เพื่อจัดการข้อความแจ้งเตือนเมื่อ Login ไม่ผ่าน
    protected function sendFailedLoginResponse(Request $request)
    {
        // เช็คก่อนว่า username/password ถูกไหม แต่ status ไม่ใช่ Active
        $user = User::where($this->username(), $request->{$this->username()})->first();

        if ($user && Hash::check($request->password, $user->password) && $user->status !== 'Active') {
            throw ValidationException::withMessages([
                $this->username() => ['บัญชีของคุณถูกระงับชั่วคราว กรุณาติดต่อผู้ดูแลระบบ'],
            ]);
        }

        // กรณีอื่นๆ (รหัสผิด หรือไม่มีบัญชี)
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
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
            $lineAvatarUrl = $lineUser->getAvatar(); 

            $user = User::where('provider_id', $lineUser->getId())->first();
            $isNewUser = false;

            if (!$user) {
                $isNewUser = true; // ตั้งค่าเป็น true สำหรับผู้ใช้ใหม่
                $user = new User();
                $user->name = $lineUser->getName();
                $user->provider_id = $lineUser->getId();
                $user->role = null; 
                $user->email = $lineUser->getEmail() ?? $lineUser->getId() . '@line.me';
                $user->password = Hash::make(Str::random(24));
                
                // --- ตั้งค่าสถานะเป็น Active สำหรับการสมัครด้วย LINE ครั้งแรก ---
                $user->status = 'Active';
                $user->save();
            } else {
                // --- ตรวจสอบสถานะสำหรับการล็อกอินด้วย LINE (คนมีบัญชีอยู่แล้ว) ---
                if ($user->status !== 'Active') {
                    // ถ้าไม่ใช่ Active ให้เด้งกลับไปหน้าล็อกอินพร้อมข้อความแจ้งเตือน
                    return redirect('/login')->withErrors(['username' => 'บัญชีของคุณถูกระงับชั่วคราว กรุณาติดต่อผู้ดูแลระบบ']);
                }
            }

            // จัดการรูปโปรไฟล์ (Avatar & Photo)
            if (!empty($lineAvatarUrl) && $user->avatar !== $lineAvatarUrl) {
                if (!empty($user->photo) && file_exists(public_path($user->photo))) {
                    unlink(public_path($user->photo)); 
                }

                $destinationPath = public_path('avatars');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $filename = 'avatars/' . $lineUser->getId() . '_' . time() . '.jpg';
                $imageContent = file_get_contents($lineAvatarUrl);

                if ($imageContent !== false) {
                    file_put_contents(public_path($filename), $imageContent);
                    $user->avatar = $lineAvatarUrl;
                    $user->photo = $filename;
                    $user->save(); // บันทึกรูปที่อัปเดต
                }
            }

            // ล็อกอิน
            Auth::login($user);

            // บันทึกประวัติลง My_log
            $title = $isNewUser ? 'ผู้ใช้สมัครสมาชิกใหม่ด้วย LINE' : 'ผู้ใช้เข้าสู่ระบบด้วย LINE';
            $textContent = 'ชื่อ: ' . $user->name . ' (Provider ID: ' . $user->provider_id . ')';

            if (mb_strlen($textContent, 'UTF-8') > 250) {
                $textContent = mb_substr($textContent, 0, 247, 'UTF-8') . '...';
            }

            $log = new My_log();
            $log->title = $title;
            $log->content = $textContent; 
            $log->event_arr = json_encode($lineUser->user, JSON_UNESCAPED_UNICODE);
            $log->save();

            // แยกตาม Role
            if ($user->role === 'admin') {
                return redirect()->intended('/monitor');
            } elseif ($user->role === 'officer') {
                return redirect()->intended('/officer/open_status');
            } else {
                return redirect()->intended('/sos'); 
            }

        } catch (\Exception $e) {
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
