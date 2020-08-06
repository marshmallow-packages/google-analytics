<?php

namespace Marshmallow\GoogleAnalytics\Types\Traits;

trait Currency
{
    protected $currency;

    public function currency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }
}
