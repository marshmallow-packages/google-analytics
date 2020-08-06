<?php

namespace Marshmallow\GoogleAnalytics\Types;

use Marshmallow\GoogleAnalytics\EventDataException;
use Marshmallow\GoogleAnalytics\Types\Traits\Currency;
use Marshmallow\GoogleAnalytics\Types\Traits\TransaciontId;
use Marshmallow\GoogleAnalytics\Contracts\GoogleAnalyticsType;

class EcommerceTracking implements GoogleAnalyticsType
{
    use Currency;
    use TransaciontId;

    protected $tax;
    protected $revenue;
    protected $shipping;
    protected $affiliation;

    public function affiliation($affiliation)
    {
        $this->affiliation = $affiliation;
        return $this;
    }

    public function getAffiliation()
    {
        return $this->affiliation;
    }

    public function revenue($revenue)
    {
        $this->revenue = $revenue;
        return $this;
    }

    public function getRevenue()
    {
        return $this->revenue;
    }

    public function shipping($shipping)
    {
        $this->shipping = $shipping;
        return $this;
    }

    public function getShipping()
    {
        return $this->shipping;
    }

    public function tax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    public function getTax()
    {
        return $this->tax;
    }

    public function validate(): void
    {
        if (!$this->getId()) {
            throw new EventDataException('Please provide a transaction id to your ecommerce tracking. This is mandatory by Google.');
        }
    }
}
