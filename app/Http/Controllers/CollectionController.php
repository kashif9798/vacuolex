<?php

namespace App\Http\Controllers;

use App\Models\Microbe;
use Illuminate\Support\Facades\Auth;

class CollectionController extends ApiController
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    /**
     * Collect microbes
     */
    public function index()
    {
        $user = Auth::guard('sanctum')->user();

        $microbes = $user->microbes_collected;

        foreach ($microbes as $key => $value) {
            if($value->users_collected_microbes->isNotEmpty()){
                $usersID = $value->users_collected_microbes->pluck('id')->toArray();
                if (in_array(Auth::guard('sanctum')->user()->id, $usersID)) {
                    $microbes[$key]->collected = true;
                } else {
                    $microbes[$key]->collected = false;
                }
            }else{
                $microbes[$key]->collected = false;
            }
        }

        return $this->showAll($microbes);
    }

    /**
     * Collect microbes
     */
    public function collect(Microbe $microbe)
    {
        $user = Auth::guard('sanctum')->user();
        $user->microbes_collected()->attach($microbe->id);

        return redirect('/microbes/' . $microbe->id);
    }

    /**
     * Decollect microbes
     */
    public function decollect(Microbe $microbe)
    {
        $user = Auth::guard('sanctum')->user();
        $user->microbes_collected()->detach($microbe->id);
        
        return redirect('/microbes/' . $microbe->id);
    }
}
