<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;

class CategoryController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$selectCategoryWithinLang = Category::select('id', 'name_'.app()->getLocale() .' as name')->get();
        //$category = Category::get();
        //return response()->json($selectCategoryWithinLang);

        $categories = Category::select('id', 'name_'.$this->getCurrentLanguage(). ' as name')->get();

        if ($categories) {
            return $this->responseSuccessData("categories", $categories, "success");
        }

        return $this->responseErrorMessage(404, "Not found");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $category = Category::select('id', 'name_'.$this->getCurrentLanguage(). ' as name')->find($request -> id);

        if($category){
            return $this->responseSuccessData('category', $category, "success");
        }

        return $this->responseErrorMessage(404, "Not found");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
    }
}
