<?php

namespace NotificationChannels\TwSMS\Test;

class Notifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForTwSMS(): string
    {
        return '+8860900123456';
    }
}
