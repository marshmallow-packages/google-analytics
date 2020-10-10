<?php

namespace Marshmallow\GoogleAnalytics;

use Marshmallow\GoogleAnalytics\Types\EcommerceTracking;
use Marshmallow\GoogleAnalytics\Types\Event;
use Marshmallow\GoogleAnalytics\Types\ItemHit;
use Marshmallow\GoogleAnalytics\Types\Pageview;
use Marshmallow\HelperFunctions\Facades\Str;

class GoogleAnalytics
{
    protected $params = [];

    protected $requests = [];

    protected $version;

    protected $trackingId;

    protected $anonymousClientId;

    protected $path = 'https://www.google-analytics.com/collect';

    public function version($version)
    {
        $this->version = $version;

        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function trackingId($trackingId)
    {
        $this->trackingId = $trackingId;

        return $this;
    }

    public function getTrackingId()
    {
        return $this->trackingId;
    }

    /**
     * If there is no $anonymousClientId specified, then we will
     * try to get if from the _ga cookies or generate one ourselfs.
     */
    public function anonymousClientId($anonymousClientId = null)
    {
        /**
         * If there is no client id provided, we will try to get
         * it from the Google Analytics cookie.
         */
        if (! $anonymousClientId) {
            $anonymousClientId = self::getClientIdFromCookies();
        }

        /**
         * If there is no id provided and no cookie available,
         * we generate a uuid as client id.
         */
        if (! $anonymousClientId) {
            $anonymousClientId = Str::uuid();
        }

        $this->anonymousClientId = $anonymousClientId;

        return $this;
    }

    public static function getClientIdFromCookies()
    {
        if (! isset($_COOKIE) || ! isset($_COOKIE['_ga'])) {
            return null;
        }
        if ($client_id = preg_replace("/^.+\.(.+?\..+?)$/", "\\1", $_COOKIE['_ga'])) {
            return $client_id;
        }

        return null;
    }

    public function getAnonymousClientId()
    {
        return $this->anonymousClientId;
    }

    public function pageview(Pageview $pageview)
    {
        $pageview->validate();

        return $this->addRequest([
            't' => 'pageview',
            'dh' => $pageview->getHostname(),
            'dp' => $pageview->getPage(),
            'dt' => $pageview->getTitle(),
        ]);
    }

    public function event(Event $event)
    {
        $event->validate();

        return $this->addRequest([
            't' => 'event',
            'ec' => $event->getCategory(),
            'ea' => $event->getAction(),
            'el' => $event->getLabel(),
            'ev' => $event->getValue(),
        ]);
    }

    public function ecommerceTracking(EcommerceTracking $tracking)
    {
        $tracking->validate();

        return $this->addRequest([
            't' => 'transaction',
            'ti' => $tracking->getId(),
            'ta' => $tracking->getAffiliation(),
            'tr' => $tracking->getRevenue(),
            'ts' => $tracking->getShipping(),
            'tt' => $tracking->getTax(),
            'cu' => $tracking->getCurrency(),
        ]);
    }

    public function itemHit(ItemHit $item)
    {
        $item->validate();

        return $this->addRequest([
            't' => 'item',
            'ti' => $item->getId(),
            'in' => $item->getName(),
            'ip' => $item->getPrice(),
            'iq' => $item->getQuantity(),
            'ic' => $item->getCode(),
            'iv' => $item->getVariation(),
            'cu' => $item->getCurrency(),
        ]);
    }

    public function sendToGoogle()
    {
        foreach ($this->requests as $key => $request) {

            /**
             * Add the default parameters when we are going to do the post
             */
            $request->addParameter('v', $this->getVersion());
            $request->addParameter('tid', $this->getTrackingId());
            $request->addParameter('cid', $this->getAnonymousClientId());


            /**
             * Post the request with Curl because it couldnt make
             * it work with the Laravel HTTP client.
             */
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $this->path . '?',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $request->getCurlPostFields(),
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/x-www-form-urlencoded",
                ],
            ]);

            curl_exec($curl);
            curl_close($curl);

            unset($this->requests[$key]);
        }

        return $this;
    }

    protected function addRequest($parameters)
    {
        $request = new GoogleAnalyticsRequest;
        foreach ($parameters as $key => $value) {
            $request->addParameter($key, $value);
        }
        $this->requests[] = $request;

        return $this;
    }
}
