<?php

namespace Marshmallow\GoogleAnalytics;

class GoogleAnalyticsRequest
{
    protected $parameters = [];

    public function addParameter($key, $value)
    {
        if ($value) {
            $this->parameters[$key] = $value;
        }
        return $this;
    }

    public function getCurlPostFields()
    {
        return http_build_query($this->parameters);
    }
}
