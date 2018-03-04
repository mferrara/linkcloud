<?php

namespace App;

use App\Jobs\ProcessLinkViewChange;
use Illuminate\Database\Eloquent\Model;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

/**
 * App\Link
 *
 * @property int $id
 * @property int $user_id
 * @property int $domain_id
 * @property int $anchor_id
 * @property string $path
 * @property int $expected_links
 * @property int $given_links
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Anchor $anchor
 * @property-read \App\Domain $domain
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereAnchorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereDomainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereExpectedLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereGivenLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereUserId($value)
 * @mixin \Eloquent
 */
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
        ProcessLinkViewChange::dispatch($this, 1);
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

    /**
     * Get a string of links for this user to show
     *
     * @param User $user
     * @return string
     */
    public static function getLinks(User $user)
    {
        // Replace with links selected from pool
        // TODO: Better/faster distribution method

        // Get eligible users
        $user_ids = User::usersEligibleForLinks();

        // Get links from this collection of users
        $links      = Link::whereIn('user_id', $user_ids)
            ->whereRaw('links.expected_links > links.given_links')
            ->inRandomOrder()
            ->orderBy('id', 'asc')
            ->take(3)
            ->get();

        // Setup return string and user point total
        $return             = '';
        $increment_points   = 0;
        $decrement_points   = [];
        foreach($links as $link)
        {
            // Build the link
            $link_row = $link->buildHTMLLink();

            // Determine how it's to be wrapped
            switch($user->linkMethod())
            {
                case 'br':
                    $link_row .= '<br />';
                    break;

                case 'li':
                    $link_row = '<li>'.$link_row.'</li>';
                    break;
            }

            // Add to the returned string
            $return .= $link_row;
            // Increment the views given to this link
            $link->incrementGivenViews();

            // Increment the user's points (for showing links)
            $increment_points++;

            // Setup array of users to have their points decrement due to their links being shown
            if(isset($decrement_points[$link->user_id]))
                $decrement_points[$link->user_id]++;
            else
                $decrement_points[$link->user_id] = 1;
        }

        // If there are points, increment the user's points
        if($increment_points > 0)
            $user->incrementPoints($increment_points);

        // If there's decrement users, act accordingly
        if(count($decrement_points) > 0)
        {
            // Loop through the $decrement_points array adjusting their score as needed
            foreach($decrement_points as $user_id => $points)
            {
                User::find($user_id)->decrementPoints($points);
            }
        }

        return $return;
    }

}
