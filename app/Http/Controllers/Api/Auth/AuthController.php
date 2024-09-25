<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $loginUser = User::where('email', $credentials['email'])->first();
        if ($loginUser) {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user = $request->user()->load('roles');
                $token = $loginUser->createToken('token')->plainTextToken;
                return response()->json([
                    'success'=> true,
                    'data' => [
                        "token"=> $token,
                        "user"=> $user,
                        "roles" => $user->getRoleNames(),
                    ],
                    'errors' => null,
                    'message' => "Login başarılı",
                    ],200,
                );
            }
        }
        return response()->json([
            'success'=> false,
            'data' => null,
            'errors' => 'Unauthorized',
            'message' => "Giriş başarısız .Lütfen mail veya password kontrol edin !",
            ], 400
        );
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $role = $request->validated('role');

        // if($role === UserRole::USER)
        // {
        //     // Kullanıcı oluşturulduktan sonra sepet oluştur(Kullanıcı)
        //     $this->cartRepository->createCart($user->id);
        // }
        // else if($role === UserRole::COMPANY)
        // {
        //     //Kullanıcı oluşturduktan sonra business oluştur.(Şirket)
        //     $this->businessRepository->create([
        //         "user_id" => $user->id,
        //     ]);
        // }else{
        //    //
        // }

        $user->assignRole($role);
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'success'=> true,
            'data' => [
                "token"=> $token,
                "user" => $user,
            ],
            'errors' => null,
            'message' => "Kayıt başarılı",
            ],200,
        );
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();

        // Kullanıcının tüm tokenlarını geçersiz kıl
        PersonalAccessToken::where('tokenable_id', $user->id)->delete();

        return response()->json([
            'success'=> true,
            'data' => null,
            'errors' => null,
            'message' => "Çıkış başarılı",
            ],200,
        );
    }
}
