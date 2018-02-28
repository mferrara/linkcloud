<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiV1DocumentationController extends Controller
{

    public function index(Request $request)
    {
        $return = '';

        $return .= "
            <h3>Authentication</h3>
            Go to <a href='https://linkcloud.test/settings/#/api' target='_blank'>API Settings</a> and create a token.
            <h3>Token Permissions</h3>
            GET Links - Tokens with this permission can use the GET endpoint to obtain strings of links to show on their site<br />
            POST Links - Tokens with this permission can use the POST endpoint to send .CSV documents to import into link pool<br />
        ";

        $return .= "
            <h3>To get links...</h3>
            Send a GET request to<br /><br />
            ".route('home')."/api/v1/links<br /><br />
            With the following parameters:<br />
            - api_token (Create in your <a href='https://linkcloud.test/settings/#/api' target='_blank'>API Settings</a> - be sure this token has permission to get links)<br /><br />
            Example:<br />
            ".route('home')."/api/v1/links?api_token=xm1mo3moimio3j1ox3j<br /><br />
            Response:<br />
            The response will be a string containing html formatted links configured as set in your settings.<br /><br />
            ";

        $return .= "
            <h3>To upload links</h3>
            Send a POST request to<br /><br />
            ".route('home')."/api/v1/links<br /><br />
            With the following parameters:<br />
            - api_token (Create in your <a href='https://linkcloud.test/settings/#/api' target='_blank'>API Settings</a> - be sure this token has permission to get links)<br />
            - linksfile (A .CSV document in the format described @ <a href='".route('home')."'>user dashboard</a><br /><br />
            Response:<br />
            JSON array with the following keys:<br /><br />
            - document_rows (# of rows in the POST'd document)<br />
            - links_added (# of those rows added as links)<br />
        ";


        return $return;
    }
}
