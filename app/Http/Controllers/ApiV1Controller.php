<?php

namespace App\Http\Controllers;

use App\Link;
use App\Linkcloud\BotDetection;
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
        // If there's no ip provided, return 500 code
        if($request->has('ip') === false || $request->get('ip') == '')
            return response("", 500);

        // If there's no user agent provided, return 500 code
        if($request->has('ua') === false || $request->get('ua') == '')
            return response("", 500);

        // Determine if this is a bot/debug request
        $ip_address = $request->get('ip');
        $user_agent = $request->get('ua');
        $bot        = BotDetection::isThisABot($ip_address, $user_agent);

        if($bot)
        {
            // Get the user
            $user = Auth::user();

            // Confirm their provided token can get links
            if($user->token()->can('get-links'))
            {
                // Get the link string to return
                $return = Link::getLinks($user);

                return $return;
            }

            return response("", 500);
        }

        return '';
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
            // Get posted document and import via the same process as the form upload import
            $import         = $user->importCSVLinks($request->file('linksfile')->openFile());
            $new_links      = $import['new_links'];

            return json_encode([
                'links_added'   => $new_links
            ]);
        }

        return response("You don't have permission to access that resource", 500);
    }
}
