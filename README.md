# TwSMS Notification Channel

📲 [TwSMS](https://www.twsms.com/) Notifications Channel for Laravel

## Contents

- [Installation](#installation)
	- [Setting up the TwSMS service](#setting-up-the-TwSMS-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

```bash
composer require laravel-notification-channels/twsms
```

Add the configuration to your `services.php` config file:

```php
'twsms' => [
    'username' => env('TWSMS_USERNAME'),
    'password' => env('TWSMS_PASSWORD'),
]
```

### Setting up the TwSMS service

You'll need an TwSMS account. Head over to their [website](https://example.com/) and create or log in to your account.

Generate API credentials by navigating to the API section in your account settings.

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use NotificationChannels\TwSMS\TwSMSMessage;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ['twsms'];
    }

    public function toTwSMS($notifiable)
    {
        return (new TwSMSMessage)->content("Task #{$notifiable->id} is complete!");
    }
}
```

In your notifiable model, make sure to include a `routeNotificationForTwSMS()` method, which returns a phone number in the appropriate format.

```php
public function routeNotificationForTwSMS()
{
    return $this->phone; // Example: +1234567890 , need to include country code
}
```

### Available methods

`content()`: Set the content of the notification message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security-related issues, please contact support@example.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.
