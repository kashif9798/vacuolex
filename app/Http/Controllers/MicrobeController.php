<?php

namespace App\Http\Controllers;

use App\Models\Microbe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MicrobeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $microbes = Microbe::paginate(config('paginate.per_page'));

        if(Auth::guard('sanctum')->check()){
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
        }

        return $this->showAll($microbes);
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Microbe  $microbe
     * @return \Illuminate\Http\Response
     */
    public function show(Microbe $microbe)
    {
        if($microbe->users_collected_microbes->isNotEmpty()){
            $usersID = $microbe->users_collected_microbes->pluck('id')->toArray();
            if (in_array(Auth::guard('sanctum')->user()->id, $usersID)) {
                $microbe->collected = true;
            } else {
                $microbe->collected = false;
            }
        }else{
            $microbe->collected = false;
        }

        return $this->showOne($microbe);
    }

    /**
     * Search filter
     */
    public function search(Microbe $microbe, Request $request)
    {
        $microbes = Microbe::where('title','like','%'.$request->search.'%')->select('id','title', 'image')->get();
        
        return $this->showAll($microbes);
    }
}
