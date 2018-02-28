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
 */
class Domain extends Model
{
    protected $guarded = ['id', 'created_at'];

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
