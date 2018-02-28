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
            $return = Link::getLinks($user);

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
