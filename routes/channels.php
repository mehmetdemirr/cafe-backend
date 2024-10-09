<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

//TODO şimdi düz test ediyorum kesinlikle burası açılacak 
// Broadcast::channel('chat.{sender_id}.{receiver_id}', function ($user, $sender_id, $receiver_id) {
//     return (int) $user->id === (int) $sender_id || (int) $user->id === (int) $receiver_id;
// });
