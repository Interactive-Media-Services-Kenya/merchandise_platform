<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Alert;
use App\Models\Client;
use Gate;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index(){
        $campaigns = Campaign::all();

        return view('campaigns.index', compact('campaigns'));
    }

    public function create(){
        //Pass Clients with Associated Brands
        if (Gate::allows('admin_access')) {
            $clients = Client::with('brands')->get();

        }
        if (Gate::allows('tb_access')) {
            //Get Clients Associated with each teamleaders and Brands.
            $clients = Client::with('brands')->join('users','users.client_id','clients.id')->where('users.id',Auth::id());
        }
        return view('campaigns.create', compact('clients'));
    }


    public function destroyCampaign($id)
    {
        $campaign = Campaign::findOrFail($id);

        if($campaign->delete()){
            Alert::success('Success', 'Campaign Removed Successfully');
            return back();
        }else{
            Alert::error('Failed', 'Campaign Not Deleted');
            return back();
        }

    }
}
