<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    private $otp;
    public function __construct(){
        $this->otp=new Otp();
    }
    public function resetPassword(ResetPasswordRequest $request){
        $otp2=$this->otp->validate($request->email,$request->otp);
        if(! $otp2->status){
            return response()->json([
                'success' => false,
                'data' => null,
                'errors' => $otp2->message,
                'message' => null,
            ], 400);

        }
        $user=User::where('email',$request->email)->first();
        $user->update(
            [
                'password'=>Hash::make($request->password)
            ]
        );
       $user->tokens()->delete();
        return response()->json([
            'success' => true,
            'data' => null,
            'errors' => null,
            'message' => null,
        ], 200);
    }
}
