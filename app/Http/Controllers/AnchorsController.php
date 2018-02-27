<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnchorsController extends Controller
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
        $user       = Auth::user();
        $anchors    = $user->anchors()->paginate(25);

        return view('anchors.index')
            ->with('user', $user)
            ->with('anchors', $anchors);
    }
}
