<?php

namespace App\Providers;

use Laravel\Spark\Spark;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Your application and company details.
     *
     * @var array
     */
    protected $details = [
        'vendor'    => 'LinkCloud',
        'product'   => 'LinkCloud',
        'street'    => 'PO Box 1111',
        'location'  => 'Your Town, NY 12345',
        'phone'     => '555-555-5555',
    ];

    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = null;

    /**
     * All of the application developer e-mail addresses.
     *
     * @var array
     */
    protected $developers = [
        'mferrara@gmail.com'
    ];

    /**
     * Indicates if the application will expose an API.
     *
     * @var bool
     */
    protected $usesApi = true;

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */
    public function booted()
    {
        Spark::useStripe()->noCardUpFront()->trialDays(30);

        Spark::freePlan()
            ->features([
                'First', 'Second', 'Third'
            ]);

        Spark::plan('Basic', 'linkcloud-1')
            ->price(10)
            ->features([
                'First', 'Second', 'Third'
            ]);

        // Setup API token permissions
        Spark::tokensCan([
            'get-links'     => 'GET Links (For use on your sites)',
            'post-links'    => 'POST Links (For use in link importing scripts)'
        ]);
    }
}
