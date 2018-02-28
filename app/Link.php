<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class Link extends Model
{
    protected $guarded = ['id', 'created_at'];

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

    public function buildHTMLLink($target_blank = false)
    {
        $data = $this->buildLink();

        if($target_blank)
            $target = 'target="_blank"';
        else
            $target = '';

        return '<a href="'.$data['href'].'" '.$target.'>'.$data['anchor'].'</a>';
    }

    public function incrementGivenViews()
    {
        DB::table('links')->where('id', $this->id)->increment('given_links');
    }

    /**
     * The User::importLinks method looks for an array of strings (url,anchor) so we create that from uploaded CSV's here
     *
     * @param \SplFileObject $uploaded_file
     * @return array
     */
    public static function convertUploadedFileIntoLinksArray(\SplFileObject $uploaded_file)
    {
        $reader = Reader::createFromFileObject($uploaded_file);

        $return = [];
        foreach($reader as $index => $row)
        {
            $link_count = config('linkcloud.default_link_count');
            if(isset($row[2]))
                $link_count = $row[2];

            // Create a comma delimited string to add to the array
            $row_string = $row[0].','.$row[1].','.$link_count;

            // If the combined string is greater than 2 in length (meaning, essentially, it's not empty)
            // we'll pass it through, for now.
            // TODO: Verification on uploaded link/anchors
            if(mb_strlen($row_string) > 2)
                $return[] = $row_string;
        }

        return $return;
    }
}
