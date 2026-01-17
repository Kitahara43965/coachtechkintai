<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Constants\LoginConstant;
use App\Constants\UserRole;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    static function getStringNameRouteRedirectOnMailVerification($authUser){

        $loginBladeUserRole = UserRole::UNDEFINED;
        if(session()->has('loginBladeUserRole')){
            $loginBladeUserRole = session('loginBladeUserRole');
        }

        if($authUser){
            if($loginBladeUserRole === UserRole::ADMIN){
                $stringNameRouteRedirect = "admin.attendanceList";
            }else{
                $stringNameRouteRedirect = "index";
            }
        }else{
            $stringNameRouteRedirect = "inex";
        }
        
        return($stringNameRouteRedirect);
    }

    public function verifyEmail() {
        $authUser = Auth::user();
        // 認証状態を更新
        $authUser->markEmailAsVerified();

        $stringNameRouteRedirect = self::getStringNameRouteRedirectOnMailVerification($authUser);

        return redirect()
            ->route($stringNameRouteRedirect)
            ->with('status', 'メール認証が完了しました！');
    }

    public function emailVerifyIdHash(EmailVerificationRequest $request){
        $request->fulfill(); // メール認証完了
        $authUser = Auth::user();
        $stringNameRouteRedirect = self::getStringNameRouteRedirectOnMailVerification($authUser);

        return redirect()->route($stringNameRouteRedirect); // 認証後のリダイレクト先
    }

    public function resendEmail(Request $request){
        $user = $request->user();
        $user->sendEmailVerificationNotification();
        return redirect()->route('verification.notice')->with('status', 'verification-link-sent');
    }
}
