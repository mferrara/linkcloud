<?php

namespace App\Http\Controllers;

use App\Link;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiV1Controller extends Controller
{

    /**
     * Handle GET request for showing links
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     */
    public function getLinks(Request $request)
    {
        $user = Auth::user();

        if($user->token()->can('get-links'))
        {
            // Replace with links selected from pool
            // TODO: Better/faster distribution method
            $users      = User::where('points', '>', 0)->get();
            $user_ids   = $users->pluck('id')->all();

            // Get links from this collection of users
            $links      = Link::whereIn('user_id', $user_ids)
                ->whereRaw('links.expected_links > links.given_links')
                //->where('user_id', '!=', $user->id)
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
                $return .= $link->buildHTMLLink().'<br />';
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
                foreach($decrement_points as $user_id => $points)
                {
                    User::find($user_id)->decrementPoints($points);
                }
            }

            return $return;
        }

        return response("You don't have permission to access that resource", 500);
    }

    /**
     * Handle POST request with CSV for import
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     */
    public function postLinks(Request $request)
    {
        $user = Auth::user();

        if($user->token()->can('post-links'))
        {
            // TODO Validation on this posted file

            // Get posted document and import via the same process as the form upload import
            $links_array    = Link::convertUploadedFileIntoLinksArray($request->file('linksfile')->openFile());
            $import         = $user->importLinks($links_array);
            $links_attempted_count = count($links_array);
            $new_links      = $import['new_links'];

            return json_encode([
                'document_rows' => $links_attempted_count,
                'links_added'   => $new_links
            ]);
        }

        return response("You don't have permission to access that resource", 500);
    }
}
