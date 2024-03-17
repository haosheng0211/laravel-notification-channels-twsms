<?php

namespace NotificationChannels\TwSMS;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class TwSMSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(TwSMS::class, function ($app) {
            $username = $app['config']['services.twsms.username'];
            $password = $app['config']['services.twsms.password'];

            if (empty($username) || empty($password)) {
                throw new \InvalidArgumentException('Missing TwSMS config in services');
            }

            return new TwSMS($username, $password);
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('twsms', function ($app) {
                return new TwSMSChannel($app->make(TwSMS::class));
            });
        });
    }

    public function provides(): array
    {
        return [TwSMS::class];
    }
}
