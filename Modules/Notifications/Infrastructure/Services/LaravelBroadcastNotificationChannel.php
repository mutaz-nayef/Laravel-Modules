<?php

namespace Modules\Notifications\Infrastructure\Services;

use Illuminate\Support\Facades\Mail;
use Modules\Notifications\Domain\Contracts\NotificationChannelInterface;
use Modules\Notifications\Domain\Contracts\NotificationPayload;

class LaravelBroadcastNotificationChannel implements NotificationChannelInterface
{

    public function supports(string $channel): bool
    {
        return $channel === 'broadcast';
    }

    public function send(array $recipients, NotificationPayload $payload): void
    {
        foreach ($recipients as $recipient) {
            Mail::raw($payload->body(), static function ($message) use ($recipient, $payload): void {
                $message->to($recipient->email())->subject($payload->subject());
            });
        }
    }
}
