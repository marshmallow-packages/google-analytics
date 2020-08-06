<?php

namespace Marshmallow\GoogleAnalytics\Listeners;

use Marshmallow\GoogleAnalytics\GoogleAnalytics;

class GoogleEcommerceTrigger
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $analytics = $event->withAnalytics(
            (new GoogleAnalytics())

                /**
                 * By default we will use version 1 so this is not
                 * mandatory to specify when developers use this.
                 */
                ->version(1)

                /**
                 * Tracking is retreived from the same env variable
                 * that is used bij marshmallow/seoble package.
                 */
                ->trackingId(env('SEO_GA'))

                /**
                 * This will set a random GUID or the Google
                 * Analytics cookie value if it can be retreived.
                 */
                ->anonymousClientId()
        );

        $analytics->sendToGoogle();
    }
}
