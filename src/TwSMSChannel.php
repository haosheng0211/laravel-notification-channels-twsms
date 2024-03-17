<?php

namespace NotificationChannels\TwSMS;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use NotificationChannels\TwSMS\Exceptions\CouldNotSendNotification;

class TwSMSChannel
{
    protected $client;

    public function __construct(TwSMS $client)
    {
        $this->client = $client;
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('twsms')) {
            throw CouldNotSendNotification::missingTo();
        }

        $phone = $this->parsePhoneNumber($to);

        if ($phone->getCountryCode() === 886) {
            $to = $this->formatTaiwanPhoneNumber($phone);
        } else {
            $to = $this->formatGlobalPhoneNumber($phone);
        }

        if (! method_exists($notification, 'toTwSMS')) {
            throw new \InvalidArgumentException('Notification does not have a toTwSMS method');
        }

        $message = $notification->toTwSMS($notifiable);

        if (is_string($message)) {
            $message = new TwSMSMessage($message);
        }

        try {
            $response = $this->client->sendSMS($to, $message->content);

            $response = json_decode($response, true);

            if ((int) $response['code'] != '00000') {
                throw CouldNotSendNotification::serviceRespondedWithAnError($response['text']);
            }
        } catch (GuzzleException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function parsePhoneNumber(string $to): PhoneNumber
    {
        try {
            return PhoneNumberUtil::getInstance()->parse($to);
        } catch (NumberParseException $exception) {
            throw CouldNotSendNotification::invalidPhoneNumber();
        }
    }

    public function formatGlobalPhoneNumber(PhoneNumber $phone): string
    {
        $number = PhoneNumberUtil::getInstance()->format($phone, PhoneNumberFormat::E164);

        return preg_replace('/\D/', '', $number);
    }

    private function formatTaiwanPhoneNumber(PhoneNumber $phone)
    {
        $number = $phone->getNationalNumber();

        return "0{$number}";
    }
}
