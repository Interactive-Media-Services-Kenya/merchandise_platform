<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Alert;
use Illuminate\Support\Facades\Auth;
use DB;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create');
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
            'email' => 'required|email|max:255',
            'phone'=> 'required|numeric|digits_between:10,12',
            'address'=> 'string'
        ]);
        $created_by = Auth::id();

        $client = Client::create($request->all());

        $client->update([
            'created_by' => $created_by,
        ]);

        if($client){
            Alert::success('Success', 'Client Added Successfully');
            return back();
        }else{
            Alert::error('Failed', 'Client Not Added');
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
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
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
        $request->validate([
            'name'=>'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone'=> 'required|numeric|digits_between:10,12',
            'address'=> 'string'
        ]);

        $client = Client::findOrFail($id);
        $created_by = Auth::id();
        $client->update([
            'created_by' => $created_by,
        ]);

        if($client->update($request->all())){
            Alert::success('Success', 'Client Details Updated Successfully');
            return back();
        }else{
            Alert::error('Failed', 'Client Not Updated');
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
        //
    }

    public function destroyClient($id)
    {
        $client = Client::findOrFail($id);

        if($client->delete()){
            Alert::success('Success', 'Client Removed Successfully');
            return back();
        }else{
            Alert::error('Failed', 'Client Not Deleted');
            return back();
        }

    }

    function fetch(Request $request)
    {
     $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
     $data = DB::table('brands')
       ->where($select, $value)
       ->get();
     $output = '<option value="">SELECT BRAND</option>';
     foreach($data as $row)
     {
      $output .= '<option value="'.$row->id.'">'.strtoupper($row->name).'</option>';
     }
     echo $output;
    }
}
