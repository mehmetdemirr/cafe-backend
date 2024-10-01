<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $logService;
    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function user(Request $request)
    {
        $this->logService->logWarning('Kullanıcı bilgisi');
        // Kullanıcıyı rollerle birlikte yükle
        $user = $request->user()->load('roles');

        return response()->json([
            'success'=> true,
            'data' => [
                'user' => $user,
                'roles' => $user->getRoleNames(),
            ],
            'errors' => null,
            'message' => null,
        ], 200);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        $user->update($request->only(['name', 'email', 'password']));

        // Şifreyi hash'le
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Kullanıcı bilgileri güncellendi.',
            'errors' => null,
        ], 200);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        // Mevcut parolayı kontrol et
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mevcut parola hatalı.',
                'errors' => null,
                'data' =>false,
            ], 400);
        }

        // Yeni parolayı hash'le ve kaydet
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Parola başarıyla değiştirildi.',
            'errors' => null,
            'data' =>true,
        ], 200);
    }
}
