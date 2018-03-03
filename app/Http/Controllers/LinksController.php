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
        $links  = $user->links();

        if($request->has('anchor_id'))
            $links->where('anchor_id', $request->get('anchor_id'));

        if($request->has('domain_id'))
            $links->where('domain_id', $request->get('domain_id'));

        $total_count = $links->count();
        $links  = $links->orderBy('id', 'desc')
            ->paginate(25);

        return view('links.index')
            ->with('user', $user)
            ->with('links', $links)
            ->with('total_count', $total_count);
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
