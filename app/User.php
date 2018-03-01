<?php

namespace App;

use App\Jobs\ProcessUserPointChange;
use Laravel\Spark\User as SparkUser;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $photo_url
 * @property bool $uses_two_factor_auth
 * @property string|null $authy_id
 * @property string|null $country_code
 * @property string|null $phone
 * @property string|null $two_factor_reset_code
 * @property int|null $current_team_id
 * @property string|null $stripe_id
 * @property string|null $current_billing_plan
 * @property string|null $card_brand
 * @property string|null $card_last_four
 * @property string|null $card_country
 * @property string|null $billing_address
 * @property string|null $billing_address_line_2
 * @property string|null $billing_city
 * @property string|null $billing_state
 * @property string|null $billing_zip
 * @property string|null $billing_country
 * @property string|null $vat_id
 * @property string|null $extra_billing_information
 * @property \Carbon\Carbon $trial_ends_at
 * @property string|null $last_read_announcements_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $points
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Anchor[] $anchors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domain[] $domains
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Link[] $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Spark\LocalInvoice[] $localInvoices
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Spark\Subscription[] $subscriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Spark\Token[] $tokens
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAuthyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBillingAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBillingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBillingCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBillingState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBillingZip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentBillingPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereExtraBillingInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastReadAnnouncementsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTwoFactorResetCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsesTwoFactorAuth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVatId($value)
 * @mixin \Eloquent
 */
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
                $link_count     = (int) $exploded[2];

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

    public function incrementPoints($value = 1)
    {
        ProcessUserPointChange::dispatch($this, $value);
    }

    public function decrementPoints($value = 1)
    {
        // If the value provided here is positive we'll force it negative to ensure decrement
        if($value > 0)
            $value = $value * -1;
        ProcessUserPointChange::dispatch($this, $value);
    }

}
