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
    public function index(Request $request)
    {
        $request->flash();
        
        $microbes = Microbe::select("id", "title", "image", "admin_id", "sub_category_id")
        ->withCount(['comments'])
        ->with(["author:id,name", "subcategory:id,title,category_id", "subcategory.category:id,title"])
        ->when(!empty($request->search), function($query) use ($request){
            $query->where('title','LIKE', '%'.$request->search.'%')
               ->orWhereHas('subcategory', function($q) use ($request){
                    $q->where('title','LIKE', '%'.$request->search.'%');
                })
               ->orWhereHas('subcategory.category', function($q) use ($request){
                    $q->where('title','LIKE', '%'.$request->search.'%');
                })
                ->orWhereHas('author', function($q) use ($request){
                    $q->where('name','LIKE', '%'.$request->search.'%');
                });
        })
        ->orderBy('comments_count', 'desc')
        ->paginate(10);

        $breadcrumbs = [
            ['name' => "Microbes"],
            ['name' => "Microbes"]
        ];

        return view('/pages/microbes/index', [
            'breadcrumbs'   => $breadcrumbs,
            "microbes"      => $microbes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Microbe $microbe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Microbe $microbe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Microbe $microbe)
    {
        //
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexApi()
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
    public function showApi(Microbe $microbe)
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
    public function searchApi(Microbe $microbe, Request $request)
    {
        $microbes = Microbe::where('title','like','%'.$request->search.'%')->select('id','title', 'image')->get();
        
        return $this->showAll($microbes);
    }
}
