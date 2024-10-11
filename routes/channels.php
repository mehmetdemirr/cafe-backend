<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::info('privatechannel App.Models.User.{id}');
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('chat.{sender_id}.{receiver_id}', function ($user, $sender_id, $receiver_id) {
//     Log::info('privatechannel chat.{sender_id}.{receiver_id}');
//     return (int) $user->id === (int) $sender_id || (int) $user->id === (int) $receiver_id;
// });
