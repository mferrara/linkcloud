<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateSettings(Request $request)
    {
        $link_wrapper_method = $request->get('link-wrapper-method');

        if($request->has('interlinking'))
            setting()->set('interlinking', true);
        else
            setting()->set('interlinking', false);

        setting()->set('link-wrapper-method', $link_wrapper_method);
        setting()->save();

        return redirect(route('user.settings'));
    }
}
