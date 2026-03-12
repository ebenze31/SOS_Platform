<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // ตรวจสอบ Login
        if (!Auth::check()) {
            return redirect()->guest('/login'); 
        }

        $role = Auth::user()->role;
        $allowed_list = array_slice(func_get_args(), 2);

        // Login แล้ว เช็ค Role มีสิทธิ์เข้าหน้านี้หรือไม่
        if (in_array($role, $allowed_list, true)) {
            return $next($request); // มีสิทธิ์ -> ปล่อยผ่านให้ไปหน้าที่ต้องการ
        }

        // ไม่มีสิทธิ์ ไปหน้า Default ตาม Role ของผู้ใช้
        if ($role === 'admin') {
            return redirect('/monitor');
        } elseif ($role === 'officer') {
            return redirect('/officer/open_status');
        } else {
            // กรณี role เป็น null หรืออื่นๆ
            return redirect('/sos');
        }
    }
}
