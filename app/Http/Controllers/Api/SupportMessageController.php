<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportMessageRequest;
use App\Interfaces\SupportMessageRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportMessageController extends Controller
{
    protected $supportMessageRepository;

    public function __construct(SupportMessageRepositoryInterface $supportMessageRepository)
    {
        $this->supportMessageRepository = $supportMessageRepository;
    }

    // Kullanıcının tüm destek mesajlarını al
    public function index(int $userId): JsonResponse
    {
        $messages = $this->supportMessageRepository->getAllByUserId($userId);

        return response()->json([
            'data' => $messages,
            'success' => true,
            'message' => 'Destek mesajları başarıyla alındı.',
            'errors' => null,
        ]);
    }

    // Yeni bir destek mesajı oluştur
    public function store(SupportMessageRequest $request): JsonResponse
    {
        $user_id = $request->user()->id;
        $data = array_merge($request->validated(), ['user_id' => $user_id]);
        $message = $this->supportMessageRepository->create($data);

        return response()->json([
            'data' => $message,
            'success' => true,
            'message' => 'Destek mesajı başarıyla oluşturuldu.',
            'errors' => null,
        ], 201);
    }

    // Bir destek mesajını sil
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->supportMessageRepository->delete($id);

        if (!$deleted) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Destek mesajı bulunamadı.',
                'errors' => null,
            ], 404);
        }

        return response()->json([
            'data' => null,
            'success' => true,
            'message' => 'Destek mesajı başarıyla silindi.',
            'errors' => null,
        ]);
    }
}
