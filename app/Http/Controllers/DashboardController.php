<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // home
    public function index()
    {
        $breadcrumbs = [
            ['name' => "Dashboard"]
        ];
        return view('/pages/dashboard', ['breadcrumbs' => $breadcrumbs]);
    }
}
