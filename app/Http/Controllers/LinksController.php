<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // $this->middleware('subscribed');
    }

    public function index(Request $request)
    {
        $user   = Auth::user();
        $links  = $user->links()->orderBy('id', 'desc')->paginate(25);

        return view('links.index')
            ->with('user', $user)
            ->with('links', $links);
    }

    public function uploadLinks(Request $request)
    {
        $user           = Auth::user();
        // TODO Validation on this posted file
        $links_array    = Link::convertUploadedFileIntoLinksArray($request->file('linksfile')->openFile());
        $import         = $user->importLinks($links_array);
        $links_attempted_count = count($links_array);

        if($import['success'])
        {
            return view('imported-links')
                ->with('new_links', $import['new_links'])
                ->with('links_attempted', $links_attempted_count)
                ->with('user', $user);
        }

        return view('import-failed')
            ->with('user', $user)
            ->with('links_attempted', $links_attempted_count)
            ->with('error_message', $import['error_message']);
    }
}
