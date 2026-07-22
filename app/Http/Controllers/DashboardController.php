<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        //detect if user has PDS information
        return view('dashboard');
    }
}
