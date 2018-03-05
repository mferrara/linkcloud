<?php


namespace App\Observers;

use App\Link;

class LinkObserver
{
    /**
     * Listen to the Link created event.
     *
     * @param  \App\Link  $link
     * @return void
     */
    public function created(Link $link)
    {
        // Create a redis entry for this link
        $link->cacheLinkData();

        // Add this links representations to the link pool
        $link->addToLinkPool();
    }

    public function deleted(Link $link)
    {
        // Remove this link's redis entry
        $link->purgeLinkCachedData();
    }
}