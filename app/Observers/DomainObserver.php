<?php


namespace App\Observers;

use App\Domain;
use App\Jobs\DomainDNSLookup;

class DomainObserver
{
    /**
     * Listen to the Domain created event.
     *
     * @param  \App\Domain  $domain
     * @return void
     */
    public function created(Domain $domain)
    {
        // Queue task to run reverse DNS on the domain
        DomainDNSLookup::dispatch($domain);
    }
}
