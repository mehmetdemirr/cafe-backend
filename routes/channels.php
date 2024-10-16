<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::info('privatechannel App.Models.User.{id}');
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{user1}.{user2}', function (User $user, $user1, $user2) {
    // Kullanıcılar sadece kendi aralarındaki kanallara abone olabilir
    return in_array($user->id, [(int) $user1, (int) $user2]);
});
