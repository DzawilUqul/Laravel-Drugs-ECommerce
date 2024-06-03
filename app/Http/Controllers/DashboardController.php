<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        if(!auth()->check() || auth()->user()->roles !== 'User'){
            abort(404);
        };

        return view('dashboard');
    }
}