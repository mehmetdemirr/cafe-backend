<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordForgotRequest;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(PasswordForgotRequest $request){
        
        $input=$request->only('email');
        $user=User::where('email',$input)->first();
        if(!$user){
            return response()->json([
                'success'=> false,
                'data' => null,
                'errors' => "Kullanıcı bulunamadı",
                'message' => null,
                ], 400
            );
        }
        $user->notify(new ResetPasswordNotification());
        return response()->json([
            'success'=> true,
            'data' => null,
            'errors' => null,
            'message' => "Gönderildi",
            ], 200
        );
    }
}
