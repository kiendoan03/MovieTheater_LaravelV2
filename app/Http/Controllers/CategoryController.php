<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
//        $admin = Auth::guard('staff')->user();


        return view('admin.category.main',[
                'categories' => $categories,
//                'admin' => $admin,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
//        $admin = Auth::guard('staff')->user();

        return view('admin.category.create',[
//            'admin' => $admin,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $array = [];
        $array = Arr::add($array, 'name', $request->name);

        Category::create($array);

        return redirect()->route('admin.categories.index')->with('success', 'Add category successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
//        $admin = Auth::guard('staff')->user();

        return view('admin.category.edit',[
            'category' => $category,
//            'admin' => $admin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $category->update($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Update category successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
//        CategoryMovieController::where('category_id', $category->id)->delete();

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Delete category successfully!');
    }
}
