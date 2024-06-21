<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parent = $request->parent;
        $categories = new Category();
        if($parent != '') {            
            $categories = $categories->where('parent_id', $parent);
        }
        $categories = $categories->orderBy('id','DESC')->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        $parent_categories = ['' => "All", 0 => "Parent Only"];
        $parent_categories += Category::forDropdown();
        return view('category.index', compact('categories','parent','parent_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parent_categories = [0 => "Select Parent Category"];
        $parent_categories += Category::forDropdown();
        return view('category.create',compact('parent_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->name = preg_replace('/\s+/', ' ', $request->name);
        $this->validate($request, [
            'name' => ['required']
        ]);
        $data = [
                    'name'   => $request->name,
                    'slug'   => Str::slug($request->name, '-'),
                    'status' => ($request->status) ? true : false,
                    'parent_id' => $request->parent_id
                ];        
        Category::create($data);

        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return redirect()->route('category.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {   
        $parent_categories = [0 => "Select Parent Category"];
        $parent_categories += Category::forDropdown();
        return view('category.edit',['category' => $category, 'parent_categories' => $parent_categories] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->name = preg_replace('/\s+/', ' ', $request->name);
        $this->validate($request, [
            'name' => ['required']
        ]);
        $data = [
            'name' => $request->name,
            // 'slug' => Str::slug($request->name, '-'),
            'status' => ($request->status) ? true : false,
            'parent_id' => $request->parent_id
        ];        
        $category->update($data);

        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('success','Category successfully deleted.');
    }
}
