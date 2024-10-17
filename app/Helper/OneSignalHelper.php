<?php

namespace App\Helper;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;

class OneSignalHelper
{
    public static function sendToAllUsers(string $message, string $url = null)
    {
        try {
            // OneSignal kullanarak genel mesaj gönderme
            return OneSignal::sendNotificationToAll(
                $message,
                $url ? ['url' => $url] : []
            );
        } catch (\Exception $e) {
            // return ['error' => $e->getMessage()];
        }
    }

    public static function sendToUser(?string $userOneSignalId, string $message, string $url = null)
    {
        try {
            if($userOneSignalId !=null){
                // Belirli bir kullanıcıya OneSignal kullanarak mesaj gönderme
                return OneSignal::sendNotificationToUser(
                    $message,
                    $userOneSignalId,
                    $url ? ['url' => $url] : []
                );
            }
        } catch (\Exception $e) {
            // return ['error' => $e->getMessage()];
        }
    }
}
