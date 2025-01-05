<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Notification;

use Illuminate\Notifications\Notification as LaravelNotification;
use Illuminate\Support\Facades\Config;

abstract class Notification extends LaravelNotification
{
    public function via($notifiable): array
    {
        return Config::get('notification.channels');
    }
}
