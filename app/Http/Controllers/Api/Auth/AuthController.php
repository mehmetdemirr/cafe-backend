<?php

namespace App\Http\Controllers\Api\Auth;

use App\enum\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\BusinessRepositoryInterface;
use App\Interfaces\UserProfileRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $businessRepository;
    protected $userProfileRepository;

    public function __construct(BusinessRepositoryInterface $businessRepository, UserProfileRepositoryInterface $userProfileRepository)
    {
        $this->businessRepository = $businessRepository;
        $this->userProfileRepository = $userProfileRepository;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $loginUser = User::where('email', $credentials['email'])->first();
        if ($loginUser) {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user = $request->user()->load('roles');

                 // OneSignal ID'yi güncelle
                $user->update(['onesignal_id' => $request->input('onesignal_id')]);

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
            'onesignal_id' => $request->input('onesignal_id'),
        ]);

        $role = $request->validated('role');

        // Rol COMPANY ise işletme oluşturma işlemi
        if ($role === UserRoleEnum::BUSINESS) {
            // Kullanıcının zaten bir işletmesi var mı kontrol et
            if ($this->businessRepository->exists(['user_id' => $user->id])) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Kullanıcı zaten bir işletmeye sahip.',
                    'errors' => 'Bir kullanıcı yalnızca bir işletme oluşturabilir.',
                ], 400);
            }

            // İşletme oluştur
            $this->businessRepository->create([
                "user_id" => $user->id,
                "slug" => Str::uuid(),
                "qr_code" => Str::uuid(),
                // Diğer işletme bilgilerini de buraya ekleyebilirsiniz.
            ]);
        }// Rol USER ise profil oluşturma işlemi
        else if ($role === UserRoleEnum::USER) {
            // Kullanıcının zaten bir profili var mı kontrol et
            if ($this->userProfileRepository->exists($user->id)) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Kullanıcı zaten bir profile sahip.',
                    'errors' => 'Bir kullanıcı yalnızca bir profil oluşturabilir.',
                ], 400);
            }

            // Profil oluştur
            $this->userProfileRepository->create([
                'user_id' => $user->id,
            ]);
        }

        // Kullanıcıya rol atama işlemi
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
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        // Kullanıcının onesignal_id'sini null yap
        $user->update(['onesignal_id' => null]);

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
