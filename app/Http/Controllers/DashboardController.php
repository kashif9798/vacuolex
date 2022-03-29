<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Rating;
use App\Models\Comment;
use App\Models\Microbe;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    
    public function index()
    {
        $breadcrumbs = [
            ['name' => "Dashboard"]
        ];

        $categories = Category::count();

        $subcategories = SubCategory::count();

        $microbes = Microbe::count();

        $subscribers = User::count();

        $admins = Admin::whereHas("role", function($q){
           $q->where("level", 1); 
        })->count();

        $collaborators = Admin::whereHas("role", function($q){
            $q->where("level", 2); 
        })->count();

        $comments = Comment::count();

        $ratings = Rating::count();


        return view('/pages/dashboard', [
            'breadcrumbs'       => $breadcrumbs,
            "categories"        => $categories,
            "subcategories"     => $subcategories,
            "microbes"           => $microbes,
            "subscribers"       => $subscribers,
            "admins"            => $admins,
            "collaborators"     => $collaborators,
            "comments"          => $comments,
            "ratings"           => $ratings,
        ]);
    }
}
