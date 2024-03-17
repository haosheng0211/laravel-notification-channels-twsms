<?php

namespace NotificationChannels\TwSMS\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function missingTo(): self
    {
        return new static('Notification was not sent. Missing `to` number.');
    }

    public static function invalidPhoneNumber(): self
    {
        return new static('Notification was not sent. Invalid phone number.');
    }

    public static function serviceRespondedWithAnError(string $exception): self
    {
        return new static('TwSMS responded with an error: '.$exception);
    }
}
