<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    public function root()
    {
//        var_dump(Auth::user()->hasVerifiedEmail());
        return view('pages.root');
    }
}
