<?php

namespace Marshmallow\GoogleAnalytics\Types;

use Marshmallow\GoogleAnalytics\EventDataException;
use Marshmallow\GoogleAnalytics\Types\Traits\Currency;
use Marshmallow\GoogleAnalytics\Types\Traits\TransaciontId;
use Marshmallow\GoogleAnalytics\Contracts\GoogleAnalyticsType;

class ItemHit implements GoogleAnalyticsType
{
    use Currency;
    use TransaciontId;

    protected $name;
    protected $code;
    protected $price;
    protected $quantity;
    protected $variation;

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function price($price)
    {
        $this->price = $price;
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function quantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function code($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function variation($variation)
    {
        $this->variation = $variation;
        return $this;
    }

    public function getVariation()
    {
        return $this->variation;
    }

    public function validate(): void
    {
        if (!$this->getId()) {
            throw new EventDataException('Please provide a transaction id to your item hit. This is mandatory by Google.');
        }

        if (!$this->getName()) {
            throw new EventDataException('Please provide a name to your item hit. This is mandatory by Google.');
        }
    }
}
