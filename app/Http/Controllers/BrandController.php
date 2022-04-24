<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::where('client_id',Auth::user()->client_id)->get();

        return view('brands.index',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
        ]);

        $brand = Brand::create([
            'name' => $request->name,
            'client_id' => Auth::user()->client_id ? Auth::user()->client_id : null,
        ]);
        if ($brand) {
            Alert::success('Success','Brand Added Successfully');
            return redirect()->route('brands.index');
        } else {
            Alert::error('Failed','Brand Not Added');
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
        $brand = Brand::findOrFail($id);

        return view('brands.show',compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);

        return view('brands.edit',compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name'=>'required|string|max:255',
        ]);

        $brand->update([
            'name' => $request->name,
        ]);

        if ($brand) {
            Alert::success('Success','Brand Updated Successfully');
            return redirect()->route('brands.index');
        } else {
            Alert::error('Failed','Brand Not Updated');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $deleteBrand = $brand->delete();

        if ($deleteBrand) {
            Alert::success('Success','Brand Deleted Successfully');
            return redirect()->route('brands.index');
        } else {
            Alert::error('Failed','Brand is Not Deleted');
            return back();
        }
    }
}
