<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Microbe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends ApiController
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Microbe $microbe)
    {
        $rules = [
            "rating" => ["required", "numeric", "max:255"],
        ];

        $this->validate($request, $rules);


        Rating::updateOrCreate(
            [
                "microbe_id" => $microbe->id,
                "user_id" => Auth::guard('sanctum')->user()->id,
            ],
            [
                "rating" => $request->rating
            ]
        );

        $rating= Rating::avg('rating');
       
        $rating = round($rating);

        return $this->showMessage([
            "rating" => $rating,
        ]);
    }
}
