<?php

namespace Marshmallow\GoogleAnalytics\Types;

use Marshmallow\GoogleAnalytics\Contracts\GoogleAnalyticsType;

class Pageview implements GoogleAnalyticsType
{
    protected $page;
    protected $title;
    protected $hostname;

    public function hostname($hostname)
    {
        $this->hostname = $hostname;
        return $this;
    }

    public function getHostname()
    {
        return $this->hostname;
    }

    public function page($page)
    {
        $this->page = $page;
        return $this;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function validate(): void
    {
        /**
         * No required fields by Google but the method
         * is required by the interface.
         */
    }
}
