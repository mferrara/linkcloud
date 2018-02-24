<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function anchor()
    {
        return $this->belongsTo(Anchor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buildLink()
    {
        $return = [
            'href' => 'https://'.$this->domain->name.$this->path,
            'anchor' => $this->anchor->text
        ];
        return $return;
    }

    public function buildHTMLLink()
    {
        $data = $this->buildLink();

        return '<a href="'.$data['href'].'">'.$data['anchor'].'</a>';
    }
}
