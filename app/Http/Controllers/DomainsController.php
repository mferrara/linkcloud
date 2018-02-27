<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainsController extends Controller
{
    public function index(Request $request)
    {
        $user       = Auth::user();
        $domains    = $user->domains()->paginate(25);

        return view('domains.index')
            ->with('user', $user)
            ->with('domains', $domains);
    }
}
