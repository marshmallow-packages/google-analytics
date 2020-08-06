<?php

namespace Marshmallow\GoogleAnalytics\Contracts;

use Marshmallow\GoogleAnalytics\GoogleAnalytics;

interface GoogleAnalyticsEvent
{
    public function withAnalytics(GoogleAnalytics $analytics): GoogleAnalytics;
}
