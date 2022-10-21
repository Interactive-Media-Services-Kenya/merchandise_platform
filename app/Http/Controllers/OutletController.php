<?php

namespace App\Http\Controllers;

use App\Models\County;
use App\Models\Outlet;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OutletController extends Controller
{
    public function index(){
        $outlets  = Outlet::all();

        return view('outlets.index',compact('outlets'));
    }

    public function create(){
        $counties = County::all();

        return view('outlets.create',compact('counties'));
    }


    public function store(Request $request){
        $request->validate([
            'county_id' => 'required|integer',
            'name' => 'required|string',
        ]);
        $county_code = substr(\DB::table('counties')->where('id', $request->county_id)->value('name'), 0, 2);
        $outlet_code =$county_code.'_'.$this->generateCode();

        $outlet = Outlet::create([
            'county_id' => $request->county_id,
            'name' => $request->name,
            'code' => $outlet_code,
            'address_address' => $request->address_address,
            'address_latitude'=>$request->address_latitude,
            'address_longitude'=>$request->address_longitude,
        ]);

        if ($outlet) {
            Alert::success('Success', 'Operation Successful');
            return back();
        } else {
            Alert::error('Failed', 'Operation Successful');
            return back();
        }

    }

    public function edit($id){
        $outlet = Outlet::findOrFail($id);
        $counties = County::all();
        return view('outlets.edit',compact('outlet','counties'));
    }

    public function update(Request $request, $id){
        $outlet = Outlet::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'county_id' => 'required|integer',
        ]);

        $outlet->update($request->only('name','county_id'));
        $county_code = substr(\DB::table('counties')->where('id', $request->county_id)->value('name'), 0, 2);
        $outlet_code =$county_code.'_'.$this->generateCode();

        $outlet->update([
            'code' => $outlet_code,
            'address_address' => $request->address_address,
            'address_latitude'=>$request->address_latitude,
            'address_longitude'=>$request->address_longitude,
        ]);

        Alert::success('Success','Updated Successfully');
        return back();
    }

    public function destroyOutlet($id){
        $outlet = Outlet::findOrFail($id);
        $outlet->delete();
        Alert::success('Success','Outlet Deleted Successfully');
        return back();
    }
    public function generateCode(){
        $code = rand(100000, 999999);

        return $code;
    }

    public function selectSearch(Request $request)
    {
        $outlets = [];

        if($request->has('q')){
            $search = $request->q;
            $outlets =Outlet::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($outlets);
    }
}
