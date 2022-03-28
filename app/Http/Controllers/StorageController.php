<?php

namespace App\Http\Controllers;

use App\Models\Storage;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index(){
        $storages = Storage::all();

        return view('storages.index', compact('storages'));
    }

    public function show($id){
        $storage = Storage::findOrFail($id);

        return view('storage.show', compact('storage'));
    }
}
