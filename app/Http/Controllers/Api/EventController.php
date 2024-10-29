<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventActiveRequest;
use App\Http\Requests\EventCreateRequest;
use App\Interfaces\EventRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Kullanıcının favori işletmelerindeki belirli bir kategoriye göre aktif etkinlikleri getirir.
     *
     * @param EventActiveRequest $request
     * @return JsonResponse
     */
    public function getFavoriteBusinessActiveEvents(EventActiveRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $category = $request->input('category');  // İsteğe bağlı kategori filtresi
        $perPage = $request->input('per_page', 10);  // Sayfa başına gösterilecek etkinlik sayısı, varsayılan 10
        $page = $request->input('page', 1);  // Varsayılan sayfa numarası 1

        try {
            $events = $this->eventRepository->getActiveEventsByUserFavoriteBusinesses($userId, $category, $perPage, $page);

            return response()->json([
                'success' => true,
                'data' => $events,
                'errors' => null,
                'message' => 'Etkinlikler başarıyla alındı.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'errors' => $e->getMessage(),
                'message' => 'Etkinlikleri alırken bir hata oluştu.'
            ], 500);
        }
    }

     /**
     * Yeni bir etkinlik oluşturur.
     *
     * @param EventCreateRequest $request
     * @return JsonResponse
     */
    public function createEvent(EventCreateRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        try {
            $event = $this->eventRepository->createEvent($request->validated(),$userId);

            return response()->json([
                'success' => true,
                'data' => $event,
                'errors' => null,
                'message' => 'Etkinlik başarıyla oluşturuldu.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'errors' => $e->getMessage(),
                'message' => null,
            ], 500);
        }
    }

    // Etkinlik güncelleme
    public function updateEvent(EventCreateRequest $request, int $eventId): JsonResponse
    {
        $data = $request->validated();
        $event = $this->eventRepository->updateEvent($eventId, $data);

        return response()->json([
            'success' => true,
            'data' => $event,
            'errors' => null,
            'message' => 'Etkinlik başarıyla güncellendi.'
        ]);
    }

    // Etkinlik silme
    public function deleteEvent(int $eventId): JsonResponse
    {
        $deleted = $this->eventRepository->deleteEvent($eventId);

        return response()->json([
            'success' => $deleted,
            'data' => null,
            'errors' => null,
            'message' => $deleted ? 'Etkinlik başarıyla silindi.' : 'Etkinlik bulunamadı.'
        ]);
    }
}
