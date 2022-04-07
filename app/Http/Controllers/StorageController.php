<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class StorageController extends Controller
{
    public function index()
    {
        $storages = Storage::where('client_id',null)->get();
        $storagesClient = Storage::where('client_id', Auth::user()->client_id)->get();

        return view('storages.index', compact('storages','storagesClient'));
    }

    public function create()
    {
        return view('storages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        if (Auth::user()->client_id != null) {
            $storage = Storage::create([
                'title' => $request->title,
                'client_id' => Auth::user()->client_id,
            ]);
        } else {
            $storage = Storage::create([
                'title' => $request->title,
            ]);
        }

        if ($storage) {
            Alert::success('Success', 'Storage Created Successfully');
            return back();
        } else {
            Alert::error('Failed', 'Storage Not Created');
            return back();
        }
    }



    public function edit($id)
    {
        $storage = Storage::findOrFail($id);
        return view('storages.edit', compact('storage'));
    }

    public function update(Request $request,$id){

        $storage = Storage::findOrFail($id);

        $storage->update([
            'title' => $request->title,
        ]);

        if ($storage) {
            Alert::success('Success', 'Storage Updated Successfully');
            return back();
        } else {
            Alert::error('Failed', 'Storage Not Updated');
            return back();
        }
    }

    public function show($id)
    {
        $storage = Storage::findOrFail($id);
        $batches = Batch::where('storage_id', $id)->get();

        return view('storages.show', compact('storage','batches'));
    }


    public function destroyStorage($id)
    {
        $storage = Storage::findOrFail($id);

        if ($storage->delete()) {
            Alert::success('Success', 'Storage Removed Successfully');
            return back();
        } else {
            Alert::error('Failed', 'Storage Not Deleted');
            return back();
        }
    }
}
