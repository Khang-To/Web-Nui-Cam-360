<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class HomeController extends Controller
{
    public function index()
    {
        $danhThangs = Location::latest()->take(3)->get();
        return view('client.home', compact('danhThangs'));
    }
}
