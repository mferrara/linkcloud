<?php

namespace App;

use Laravel\Spark\User as SparkUser;

class User extends SparkUser
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'country_code',
        'phone',
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'uses_two_factor_auth' => 'boolean',
    ];

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function anchors()
    {
        return $this->hasMany(Anchor::class);
    }

    public function importLinks($links_array)
    {
        foreach($links_array as $link_string)
        {
            // Get anchor from string
            $exploded = explode(',', $link_string);

            if(count($exploded) == 2)
            {
                $link_string    = $exploded[0];
                $anchor         = $exploded[1];

                // Get domain name and path from the link
                $uri            = \League\Uri\Http::createFromString($link_string);
                $path           = $uri->getPath();
                $domain_name    = $uri->getHost();
                $domain         = \App\Domain::findOrCreate($domain_name, $this);

                // Determine the anchor
                $anchor         = \App\Anchor::findOrCreate($anchor, $this);

                $link = $this->links()->create([
                    'path'      => $path,
                    'domain_id' => $domain->id,
                    'anchor_id' => $anchor->id
                ]);

                echo 'Added link: '.$link->id.PHP_EOL;
                $array = $link->buildLink();
                echo 'URL: '.$array['href'].PHP_EOL;
                echo 'Anchor: '.$array['anchor'].PHP_EOL;
                echo 'HTML Link: '.$link->buildHTMLLink().PHP_EOL;
            }
            else
            {
                dd('Invalid imported link format.');
            }
        }
    }
}
