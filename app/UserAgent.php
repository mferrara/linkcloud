<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAgent extends Model
{
    protected $guarded = ['id', 'created_at'];

    /**
     * Find an existing User Agent or create a new entry if one doesn't exist
     *
     * @param $user_agent
     * @return $this|Model|null|object|static
     */
    public static function findOrCreate($user_agent)
    {
        // Does this UA exist already?
        $ua = self::where('text', $user_agent)->first();

        // If not, create it
        if($ua === null)
        {
            $ua = self::create([
                'text' => $user_agent
            ]);
        }

        // And now, return it
        return $ua;
    }
}
