<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComplaintRequest;
use App\Interfaces\ComplaintRepositoryInterface;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    protected $complaintRepository;

    public function __construct(ComplaintRepositoryInterface $complaintRepository)
    {
        $this->complaintRepository = $complaintRepository;
    }

    public function reportUser(ComplaintRequest $request)
    {
        $data = [
            'user_id' => $request->user()->id,
            'reported_user_id' => $request->reported_user_id,
            'reason' => $request->reason,
            'details' => $request->details,
        ];

        $isSuccessful = $this->complaintRepository->reportUser($data);

        if ($isSuccessful) {
            return response()->json([
                'success' => true,
                'message' => 'Kullanıcı başarıyla şikayet edildi.',
                'data' => null,
                'errors' => null
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Şikayet işlemi sırasında bir hata oluştu.',
                'data' => null,
                'errors' => 'Şikayet kaydedilemedi.'
            ], 400); 
        }
    }
}
