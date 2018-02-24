<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
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
