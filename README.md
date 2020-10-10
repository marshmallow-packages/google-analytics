![alt text](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")

# Laravel Google Analytics Package
[![Version](https://img.shields.io/packagist/v/marshmallow/google-analytics)](https://github.com/marshmallow-packages/google-analytics)
[![Issues](https://img.shields.io/github/issues/marshmallow-packages/google-analytics)](https://github.com/marshmallow-packages/google-analytics)
[![Licence](https://img.shields.io/github/license/marshmallow-packages/google-analytics)](https://github.com/marshmallow-packages/google-analytics)
![PHP Syntax Checker](https://github.com/marshmallow-packages/google-analytics/workflows/PHP%20Syntax%20Checker/badge.svg)

Send request to you Google Analytics has never been easier.

## Installation
You can install the package via composer:
``` bash
composer require marshmallow/google-analytics
```

## Usage
## Use with an event listener
The easiest way to use this package is to use Events and Listners. For instance. If you want to use the ecommerce tracking your should create an event like `OrderCreated`. This event should implement `GoogleAnalyticsEvent`. This will require you to implement the `withAnalytics` method.
```php
namespace App\Events;

use Marshmallow\GoogleAnalytics\GoogleAnalytics;
use Marshmallow\GoogleAnalytics\Contracts\GoogleAnalyticsEvent;

class OrderCreated implements GoogleAnalyticsEvent
{
    //...
    
    public function withAnalytics(GoogleAnalytics $analytics): GoogleAnalytics
    {
        //
    }
}
```

### The withAnalytics method
`withAnalytics` will provide you with an instance of `GoogleAnalytics` where you can add the `Google Types` you wish to send to Google Analytics. Below is an example of how this could look.
```php
class OrderCreated implements GoogleAnalyticsEvent
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    
    public function withAnalytics(GoogleAnalytics $analytics): GoogleAnalytics
    {
        $ecommerce = (new EcommerceTracking)
                        ->id($this->order->id)
                        ->affiliation(env('APP_NAME'))
                        ->revenue($this->order->revenue())
                        ->shipping($this->order->shippingCost())
                        ->tax($this->order->tax())
                        ->currency('EUR');

        return $analytics->ecommerceTracking($ecommerce);
    }
}
```

### Register the Listener
Add the `GoogleEcommerceTrigger` listner to your `EventServiceProvider.php`.
```php
OrderCreated::class => [
    GoogleEcommerceTrigger::class
]
```

### Concatunate types
You can add as many types as you want. To stay with the example above, lets say you want to let Google Analytics now which products where sold with this order.
```php
class OrderCreated implements GoogleAnalyticsEvent
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    
    public function withAnalytics(GoogleAnalytics $analytics): GoogleAnalytics
    {
        $analytics->ecommerceTracking($ecommerce);

        foreach ($this->order->products as $product) {

            $hitItem = (new ItemHit)
                        ->id($this->order->id)
                        ->name($product->name())
                        ->price($product->price())
                        ->quantity($product->quantity())
                        ->code($product->sku())
                        ->variation($product->category->name)
                        ->currency('EUR');

            $analytics->itemHit($hitItem);
        }
    }
}
```

## Google Event Types
Below you find an overview of the different Google Types we currently support and how you can use them.

### Event
```php
$pageview = (new Pageview)
            ->hostname('marshmallow.dev')
            ->page('/home')
            ->title('homepage');

$analytics->pageview($pageview);
```

### Event
```php
$event = (new Event)
            ->category('video')
            ->action('play')
            ->label('holiday')
            ->value(300);

$analytics->event($event);
```

### Ecommerce Tracking
```php
$ecommerce = (new EcommerceTracking)
                ->id(123456)
                ->affiliation('westernWear')
                ->revenue(50.00)
                ->shipping(32.00)
                ->tax(12.00)
                ->currency('EUR');

$analytics->ecommerceTracking($ecommerce);
```

### Ecommerce item (Item Hit)
```php
$item1 = (new ItemHit)
                ->id(12345)
                ->name('sofa')
                ->price(300)
                ->quantity(2)
                ->code('u3eqds43')
                ->variation('furniture')
                ->currency('EUR');

$analytics->itemHit($item1);
```

## Stand alone example
If you don't want to use this with an event and listner, you can use it stand alone as well.
```php

$pageview = (new Pageview)
            ->hostname('marshmallow.dev')
            ->page('/home')
            ->title('homepage');

(new GoogleAnalytics())
        ->version(1)
        ->trackingId(env('SEO_GA'))
        ->anonymousClientId()
        ->pageview($pageview)

        /**
         * Call the sendToGoogle method at the end
         */
        sendToGoogle();
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email stef@marshmallow.dev instead of using the issue tracker.

## Credits

- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
