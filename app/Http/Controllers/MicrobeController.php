<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Microbe;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MicrobeController extends ApiController
{
    public function __construct()
    {
        $this->middleware("auth")->except(["indexApi", "repoImages", "showApi", "searchApi"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->flash();
        
        $microbes = Microbe::select("id", "title", "image", "admin_id", "sub_category_id", "created_at")
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
        ->orderBy('updated_at', 'desc')
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
        $breadcrumbs = [
            ['name' => "Microbes"],
            ['name' => "Create"]
        ];
        

        $subcategories = SubCategory::select("id","title")->get();

        return view('/pages/microbes/create', [
            'breadcrumbs' => $breadcrumbs,
            "subcategories"  => $subcategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "title" => ["required","string"],
            "sub_category_id" => ["required","exists:sub_categories,id"],
            "image" => ["required", "image", "max:10000"],
            "excerpt" => ["required", "string", "max:70"],
            "description" => ["required", "string"],
        ];

        $this->validate($request,$rules);

        $create = [
            "title"             => $request->title,
            "excerpt"           => $request->excerpt,
            "description"       => $request->description,
            "admin_id"          => auth()->user()->id,
            "sub_category_id"   => $request->sub_category_id,
        ];

        $path = $request->file('image')->store('microbe-images', "public");
        $create["image"] = asset("storage") . "/" . $path;

        Microbe::create($create);

        session()->flash("microbe_alert", "Microbe <strong>" . $request->title . "</strong> successfully created.");

        return redirect()->route("microbes.index");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Microbe $microbe)
    {
        $breadcrumbs = [
            ['name' => "Microbes"],
            ['link' => "/admin/microbes", 'name' => "Microbes"],
            ['name' => "Edit"]
        ];

        $subcategories = SubCategory::select("id","title")->get();

        return view('/pages/microbes/edit', [
            'breadcrumbs'       => $breadcrumbs,
            "subcategories"     => $subcategories,
            "microbe"           => $microbe,
        ]);
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
        $rules = [
            "title" => ["required","string"],
            "sub_category_id" => ["required","exists:sub_categories,id"],
            "image" => ["nullable", "image", "max:10000"],
            "excerpt" => ["required", "string", "max:70"],
            "description" => ["required", "string"],
        ];

        $this->validate($request,$rules);

        $update = [
            "title"             => $request->title,
            "excerpt"           => $request->excerpt,
            "description"       => $request->description,
            "admin_id"          => auth()->user()->id,
            "sub_category_id"   => $request->sub_category_id,
        ];

        if(!empty($request->image)){
            $path = $request->file('image')->store('microbe-images', "public");
            $update["image"] = asset("storage") . "/" . $path;
        }

        $microbe->update($update);

        session()->flash("microbe_alert", "Microbe <strong>" . $request->title . "</strong> successfully updated.");

        return redirect()->route("microbes.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Microbe $microbe)
    {
        $microbe->delete();

        session()->flash("microbe_alert", "Microbe <strong>" . $microbe->title . "</strong> successfully deleted.");

        return redirect()->route("microbes.index");
    }

    /**
     * store microbe repository images.
     *
     * @return \Illuminate\Http\Response
     */
    public function repoImages(Request $request)
    {
        $path = $request->file('file')->store('microbe-repo-images', "public");
        return response()->json(['location'=> asset("storage") . "/" . $path]); 
        
        /*$imgpath = request()->file('file')->store('uploads', 'public'); 
        return response()->json(['location' => "/storage/$imgpath"]);*/

        // $path=$request->file('file')->storeAs('uploads', $fileName, 'public');

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexApi()
    {
        $microbes = Microbe::select("id","title","excerpt","image","admin_id","sub_category_id","created_at")
        ->with(["author:id,name", "subcategory:id,title,category_id", "subcategory.category:id,title"])
        ->withCount(["comments"])
        ->paginate(config('paginate.per_page'));

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
    public function showApi($id)
    {
        $microbe = Microbe::where("id", $id)
        ->with(["author:id,name", "subcategory:id,title,category_id", "subcategory.category:id,title", "comments", "comments.user:id,name,image"])
        ->withCount(["comments"])
        ->first();

        if(Auth::guard('sanctum')->check()){
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

            $ratedByUser = Rating::where("microbe_id", $microbe->id)->where("user_id", Auth::guard('sanctum')->user()->id)->count();

            if($ratedByUser > 0){
                $microbe->rated_by_user = true;
            } else {
                $microbe->rated_by_user = false;
            }
        }

        return $this->showOne($microbe);
    }

    /**
     * Search filter
     */
    public function searchApi(Request $request)
    {
        $microbes = Microbe::where('title','like','%'.$request->search.'%')->select('id','title', 'image')->get();
        
        return $this->showAll($microbes);
    }
}
