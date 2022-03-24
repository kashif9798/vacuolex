<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends ApiController
{

    public function __construct()
    {
        $this->middleware("auth")->except(["microbes"]);
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->flash();
        
        $subcategories = SubCategory::withCount(['microbes'])
        ->with(["category"])
        ->orderBy('microbes_count', 'desc')
        ->when(!empty($request->search), function($q) use ($request){
            $q->where('title','LIKE', '%'.$request->search.'%')
            ->orWhereHas('category', function($query) use ($request){
                $query->where('title','LIKE', '%'.$request->search.'%');
            });
        })
        ->paginate(10);

        $breadcrumbs = [
            ['name' => "Subcategories"],
            ['name' => "Subcategories"]
        ];

        return view('/pages/subcategories/index', [
            'breadcrumbs' => $breadcrumbs,
            "subcategories"  => $subcategories,
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
            ['name' => "Subcategories"],
            ['name' => "Create"]
        ];

        $categories = Category::select("id","title")->get();

        return view('/pages/subcategories/create', [
            'breadcrumbs' => $breadcrumbs,
            "categories"  => $categories,
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
        $this->validate($request,[
            "title"         => ['required', 'string', 'max:255'],
            "category_id"   => ['required', "exists:categories,id"]
        ]);

        SubCategory::create($request->all());

        session()->flash("subcategory_alert", "Subcategory <strong>" . $request->title . "</strong> successfully created.");

        return redirect()->route("subcategories.index");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(SubCategory $subcategory)
    {
        $breadcrumbs = [
            ['name' => "Subcategories"],
            ['link' => "/admin/subcategories", 'name' => "Subcategories"],
            ['name' => "Edit"]
        ];

        $categories = Category::select("id","title")->get();

        return view('/pages/subcategories/edit', [
            'breadcrumbs'   => $breadcrumbs,
            "categories"    => $categories,
            "subcategory"   => $subcategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubCategory $subcategory)
    {
        $this->validate($request,[
            "title"         => ['required', 'string', 'max:255'],
            "category_id"   => ['required', "exists:categories,id"]
        ]);

        $subcategory->update($request->all());

        session()->flash("subcategory_alert", "Subcategory <strong>" . $request->title . "</strong> successfully updated.");

        return redirect()->route("subcategories.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();

        session()->flash("subcategory_alert", "Subcategory <strong>" . $subcategory->title . "</strong> successfully deleted.");

        return redirect()->route("subcategories.index");
    }

   /**
     * Display list of microbes relating with specific sub-category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function microbes(SubCategory $subCategory)
    {
        $microbes = $subCategory->microbes()->paginate(config('paginate.per_page'));

        return $this->showAll($microbes);
    }
}
