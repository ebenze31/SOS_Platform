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
        if (!Auth::check()) {
            return redirect()->guest('/login'); 
        }

        $user = Auth::user();

        // --- เพิ่มการตรวจสอบสถานะของผู้ใช้ที่ Login แล้ว ---
        if ($user->status !== 'Active') {
            Auth::logout();
            return redirect('/login')->withErrors(['username' => 'บัญชีของคุณถูกระงับชั่วคราว กรุณาติดต่อผู้ดูแลระบบ']);
        }

        $role = $user->role;
        $allowed_list = array_slice(func_get_args(), 2);

        if (in_array($role, $allowed_list, true)) {
            return $next($request); 
        }

        if ($role === 'admin') {
            return redirect('/monitor');
        } elseif ($role === 'officer') {
            return redirect('/officer/open_status');
        } else {
            return redirect('/sos');
        }
    }
}
