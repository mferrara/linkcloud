<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Ip
 *
 * @property int $id
 * @property string $address
 * @property int $v6
 * @property string $host
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domain[] $domains
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ip whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ip whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ip whereV6($value)
 * @mixin \Eloquent
 */
class Ip extends Model
{
    protected $guarded = ['id', 'created_at'];

    /**
     * Domains associated with this ip address
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * Is this IP an IPv6 address
     *
     * @return boolean
     */
    public function isV6()
    {
        return $this->v6;
    }

    /**
     * Find an existing IP or create a new entry if one doesn't exist
     *
     * @param $ip_address
     * @return $this|Model|null|object|static
     */
    public static function findOrCreate($ip_address)
    {
        // Does this IP address exist already?
        $ip = self::where('address', $ip_address)->first();

        // If not, create it
        if($ip === null)
        {
            // Get the IP's host
            $ip_host = gethostbyaddr($ip_address);

            // Create the IP model
            $ip = self::create([
                'address'   => $ip_address,
                'host'      => $ip_host
            ]);
        }

        // And now, return it
        return $ip;
    }

}
