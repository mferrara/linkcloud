<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function show()
    {
        return view('home');
    }

    public function uploadLinks(Request $request)
    {
        $user           = Auth::user();
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
