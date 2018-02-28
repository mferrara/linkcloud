<?php

namespace App;

use Illuminate\Support\Facades\App;
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
        $new_links = [];
        $error = false;
        foreach($links_array as $link_string)
        {
            // Get anchor from string
            $exploded = explode(',', $link_string);

            if(count($exploded) > 1 && mb_strlen($exploded[0]) > 1)
            {
                $link_string    = $exploded[0];
                $anchor         = $exploded[1];
                $link_count     = $exploded[2];

                if( ! mb_stristr($link_string, 'http'))
                    $link_string = 'https://'.$link_string;

                // Get domain name and path from the link
                $uri            = \League\Uri\Http::createFromString($link_string);
                $path           = $uri->getPath();
                $domain_name    = $uri->getHost();
                $domain         = \App\Domain::findOrCreate($domain_name, $this);

                // Determine the anchor
                $anchor         = \App\Anchor::findOrCreate($anchor, $this);

                $create = [
                    'path'              => $path,
                    'domain_id'         => $domain->id,
                    'anchor_id'         => $anchor->id,
                    'expected_links'    => $link_count
                ];

                $link = $this->links()->create($create);

                if($link)
                    $new_links[] = $link;
            }
            else
            {
                // There's an error in the link, break from the loop
                $error = 'Invalid imported link format.';
                break;
            }
        }

        // Setup return array
        $return['success']          = false;
        $return['error_message']    = false;
        $return['new_links']        = $new_links;

        if($new_links > 0)
            $return['success'] = true;
        elseif($error)
            $return['error_message'] = $error;
        else
            $return['error_message'] = 'Generic error';

        return $return;
    }
}
