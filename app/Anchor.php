<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anchor extends Model
{

    public static function findOrCreate($anchor_text, User $user)
    {
        $anchor = $user->anchors()->where('text', $anchor_text)->first();

        if($anchor)
            return $anchor;

        $anchor = $user->anchors()->create([
            'text' => $anchor_text
        ]);

        return $anchor;
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
