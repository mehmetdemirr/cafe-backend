<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationRequest;
use App\Interfaces\NotificationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    // Kullanıcıya göre bildirimleri getir
    public function index(Request $request): JsonResponse
    {
        $notifications = $this->notificationRepository->getByUserId($request->user()->id);

        return response()->json([
            'data' => $notifications,
            'success' => true,
            'message' => 'Bildirimler başarıyla alındı.',
            'errors' => null,
        ]);
    }

    // // Yeni bildirim oluştur
    // public function store(NotificationRequest $request): JsonResponse
    // {
    //     $data = $request->validated();
    //     $data['user_id'] = $request->user()->id;

    //     $notification = $this->notificationRepository->create($data);

    //     return response()->json([
    //         'data' => $notification,
    //         'success' => true,
    //         'message' => 'Bildirim başarıyla oluşturuldu.',
    //         'errors' => null,
    //     ], 201);
    // }

    // Bildirim sil
    public function destroy($id): JsonResponse
    {
        $deleted = $this->notificationRepository->delete($id);

        if (!$deleted) {
            return response()->json([
                'data' => false,
                'success' => false,
                'message' => 'Bildirim bulunamadı veya silme işlemi başarısız.',
                'errors' => 'Silinmek istenen bildirim bulunamadı.',
            ], 404);
        }

        return response()->json([
            'data' => true,
            'success' => true,
            'message' => 'Bildirim başarıyla silindi.',
            'errors' => null,
        ], 200);
    }

}
