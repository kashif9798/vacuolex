<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{

    public function __construct()
    {
        $this->middleware("auth")->except(["indexApi", "microbes"]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->flash();
        
        $categories = Category::withCount(['subCategories', 'microbes'])
        ->orderBy('microbes_count', 'desc')
        ->when(!empty($request->search), function($q) use ($request){
            $q->where('title','LIKE', '%'.$request->search.'%')
               ->orWhere('description','LIKE', '%'.$request->search.'%');
        })
        ->paginate(10);

        $breadcrumbs = [
            ['name' => "Categories"],
            ['name' => "Categories"]
        ];

        return view('/pages/categories/index', [
            'breadcrumbs' => $breadcrumbs,
            "categories"  => $categories,
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
            ['name' => "Categories"],
            ['name' => "Create"]
        ];

        return view('/pages/categories/create', [
            'breadcrumbs' => $breadcrumbs,
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
            "description"   => ['nullable', 'string']
        ]);

        Category::create($request->all());

        session()->flash("category_alert", "Category <strong>" . $request->title . "</strong> successfully created.");

        return redirect()->route("categories.index");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $breadcrumbs = [
            ['name' => "Categories"],
            ['link' => "/admin/categories", 'name' => "Categories"],
            ['name' => "Edit"]
        ];

        return view('/pages/categories/edit', [
            'breadcrumbs'   => $breadcrumbs,
            "category"      => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request,[
            "title"         => ['required', 'string', 'max:255'],
            "description"   => ['nullable', 'string']
        ]);

        $category->update($request->all());

        session()->flash("category_alert", "Category <strong>" . $request->title . "</strong> successfully updated.");

        return redirect()->route("categories.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        session()->flash("category_alert", "Category <strong>" . $category->title . "</strong> successfully deleted.");

        return redirect()->route("categories.index");
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexApi()
    {
        $categories = Category::with('subCategories')->get();
        
        return $this->showAll($categories);
    }

    /**
     * Display list of microbes relating with specific category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function microbes(Category $category)
    {
        $microbes = $category->microbes()->paginate(config('paginate.per_page'));

        return $this->showAll($microbes);
    }
}
