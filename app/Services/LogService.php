<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LogService
{
    /**
     * Log an event with additional context like user ID and timestamp.
     *
     * @param string $event
     * @param string $message
     */
    public function logEvent($event, $message)
    {
        $this->log('info', $event, $message);
    }

    /**
     * Log an error with additional context like user ID and timestamp.
     *
     * @param string $error
     * @param string|null $details
     */
    public function logError($error, $details = null)
    {
        $this->log('error', $error, $details);
    }

    /**
     * Log a warning with additional context like user ID and timestamp.
     *
     * @param string $warning
     * @param string|null $details
     */
    public function logWarning($warning, $details = null)
    {
        $this->log('warning', $warning, $details);
    }

    /**
     * General log method to handle different log levels.
     *
     * @param string $level
     * @param string $event
     * @param string|null $message
     */
    private function log($level, $event, $message = null)
    {
        // Retrieve current user ID
        $userId = Auth::check() ? Auth::id() : 'guest';

        // Get the current timestamp
        $timestamp = Carbon::now()->toDateTimeString();

        // Build the log context
        $context = [
            'user_id' => $userId,
            'timestamp' => $timestamp,
            'event' => $event,
            'message' => $message,
        ];

        // Log the message with the appropriate level and context
        Log::{$level}('Event Log', $context);
    }
}
