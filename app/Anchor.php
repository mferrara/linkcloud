<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Anchor
 *
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Link[] $links
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Anchor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Anchor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Anchor whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Anchor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Anchor whereUserId($value)
 * @mixin \Eloquent
 */
class Anchor extends Model
{
    protected $guarded = ['id', 'created_at'];

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
