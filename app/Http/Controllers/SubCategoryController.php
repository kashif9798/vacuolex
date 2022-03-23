<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends ApiController
{
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
