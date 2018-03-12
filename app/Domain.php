<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Domain
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Link[] $links
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain whereUserId($value)
 * @mixin \Eloquent
 * @property int|null $ip_id
 * @property-read \App\Ip|null $ip
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain whereIpId($value)
 */
class Domain extends Model
{
    protected $guarded = ['id', 'created_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ip()
    {
        return $this->belongsTo(Ip::class);
    }

    /**
     * Lookup and store this domain's IP address and host
     *
     * @return $this|Model|null|object|static
     */
    public function lookupDNS()
    {
        // Get the IP
        $ip_string = gethostbyname($this->name);

        // Store the IP & determine it's host
        $ip = Ip::findOrCreate($ip_string);

        // Attach this IP to the domain if it's not already
        if($this->ip_id !== $ip->id)
        {
            $this->ip_id = $ip->id;
            $this->save();
        }

        return $ip;
    }

    /**
     * Find an existing domain name or create a new entry if one doesn't exist
     *
     * @param $domain_name
     * @param User $user
     * @return Model|null|object|static
     */
    public static function findOrCreate($domain_name, User $user)
    {
        $domain = $user->domains()->where('name', $domain_name)->first();

        if($domain)
            return $domain;

        $domain = $user->domains()->create([
            'name' => $domain_name
        ]);

        return $domain;
    }
}
