<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Constants\UserRole;
use App\Providers\RouteServiceProvider;
use App\Constants\LoginConstant;

class LoginController extends Controller
{

    public function loginStore(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $authUser = Auth::user();
        $authUser->login_time_number++;
        $authUser->save();

        if($authUser->role === UserRole::ADMIN){
            $authUser->markEmailAsVerified();
        }else{
            // 未認証なら認証画面
            if (!$authUser->hasVerifiedEmail()) {
                $authUser->sendEmailVerificationNotification();
                return redirect()->route('verification.notice');
            }
            if ($authUser->login_time_number <= LoginConstant::CHECKIN_LOGIN_TIME_NUMBER) {
                $authUser->sendEmailVerificationNotification();
                return redirect()->route('verification.notice');
            }
        }

        $loginBladeUserRole = UserRole::UNDEFINED;
        if(session()->has('loginBladeUserRole')){
            $loginBladeUserRole = session('loginBladeUserRole');
        }

        // 役割別遷移
        if ($loginBladeUserRole === UserRole::ADMIN) {
            return redirect()->route('admin.attendanceList');
        }

        return redirect()->route('index');
    }

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
