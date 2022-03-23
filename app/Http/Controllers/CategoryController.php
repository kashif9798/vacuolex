<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
