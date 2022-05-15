<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('client_id',null)->get();
        $categoriesClient = Category::where('client_id',Auth::user()->client_id)->get();


        return view('categories.index', compact('categories','categoriesClient'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in cate$category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);
        if (Auth::user()->client_id != null) {
            $category = Category::create([
                'title' => $request->title,
                'client_id' => Auth::user()->client_id,
            ]);
        } else {
            $category = Category::create([
                'title' => $request->title,
            ]);
        }

        if ($category) {
            Alert::success('Success', 'Merchandise Category Successfully Added');
            return back();
        } else {
            Alert::error('Failed', 'Operation failed');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in cate$category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $category = Category::findOrFail($id);

        if ($category->update($request->all())) {
            Alert::success('Success', 'Merchandise Category Updated Successfully');
            return back();
        } else {
            Alert::error('Failed', 'Merchandise Category Not Updated');
            return back();
        }
    }

    /**
     * Remove the specified resource from cate$category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        if($category->products->count() != 0){
            Alert::error('Failed', 'Merchandise Category Cannot be Deleted. Category Has Products');
            return back();
        }else{
            $category->delete();
            Alert::success('Success', 'Merchandise Category Removed Successfully');
            return back();
        }

    }
}
