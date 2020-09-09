<p align="center">
    <img src="https://cdn.marshmallow-office.com/media/images/logo/marshmallow.transparent.red.png">
</p>
<p align="center">
    <a href="https://github.com/Marshmallow-Development">
        <img src="https://img.shields.io/github/issues/Marshmallow-Development/package-helper-functions.svg" alt="Issues">
    </a>
    <a href="https://github.com/Marshmallow-Development">
        <img src="https://img.shields.io/github/forks/Marshmallow-Development/package-helpers-functions.svg" alt="Forks">
    </a>
    <a href="https://github.com/Marshmallow-Development">
        <img src="https://img.shields.io/github/stars/Marshmallow-Development/package-helpers-functions.svg" alt="Stars">
    </a>
    <a href="https://github.com/Marshmallow-Development">
        <img src="https://img.shields.io/github/license/Marshmallow-Development/package-helpers-functions.svg" alt="License">
    </a>
</p>

# Laravel Google Analytics Package
Send request to you Google Analytics has never been easier.

### Installing
```
composer require marshmallow/google-analytics
```

# Usage

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
