<?php

namespace App\Enum;

enum NotificationCategory
{
    const PointsEarned = 'points_earned';
    const GoalCompleted = 'goal_completed';
    const NewMatch = 'new_match';
    const MessageReceived = 'message_received';
    const FavoriteUser = 'favorite_user';
}
