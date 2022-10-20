<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Permission;
use App\Services\PermissionsService;
use App\Services\SendSMSService;
use Illuminate\Http\Request;
use Alert;
use App\Models\Client;
use Gate;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    protected $permissionsService;

    public function __construct(PermissionsService $permissionsService)
    {
        $this->permissionsService = $permissionsService;
    }
    public function index(){
        $campaigns = Campaign::all();

        return view('campaigns.index', compact('campaigns'));
    }

    public function create(){
        //Pass Clients with Associated
        $permissionName = 'Create Campaign';
        $permissions = $this->permissionsService->getPermissions($permissionName);

        abort_unless($permissions, 403);
        if (Gate::allows('admin_access')) {
            $clients = Client::with('brands')->get();

        }
        if (Gate::allows('tb_access')) {
            //Get Clients Associated with each teamleaders and Brands.
            $clients = Client::with('brands')->get();
            //$clients = Client::with('brands')->join('users','users.client_id','clients.id')->where('users.id',Auth::id());
        }
        return view('campaigns.create', compact('clients'));
    }

    public function store(Request $request){

        $request->validate([
            'name'=>'string|required',
            'client_id'=> 'integer|required',
           // 'brand_id' =>'integer',
            'from_date'=> 'required',
            'to_date'=> 'required',
        ]);
        $code = $this->generateCode();
        //Save the campaign details
        $campaign = Campaign::create([
            'name' => $request->name,
            'client_id' => $request->client_id,
            'brand_id' => $request->brand_id,
            'to_date' => $request->to_date,
            'from_date' => $request->from_date,
            'user_id' => Auth::id(),
            'code' => $code,
        ]);

        //Return response feedback to user
        if($campaign){
            Alert::success('Success', 'Campaign Created Successfully');
            return back();
        }else{
            Alert::errot('Failed !', 'Campaign Not Registered');
            return back();
        }
    }

    public function edit($id){
        $permissionName = 'Edit Campaign';
        $permissions = $this->permissionsService->getPermissions($permissionName);
        abort_unless($permissions, 403);
        $campaign = Campaign::findOrFail($id);
         //Pass Clients with Associated Brands
         if (Gate::allows('admin_access')) {
            $clients = Client::with('brands')->get();

        }
        if (Gate::allows('tb_access')) {
            //Get Clients Associated with each teamleaders and Brands.
            $clients = Client::with('brands')->join('users','users.client_id','clients.id')->where('users.id',Auth::id());
        }
        return view('campaigns.edit', compact('clients','campaign'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'string|required',
            'client_id'=> 'integer|required',
           // 'brand_id' =>'integer',
            'from_date'=> 'required',
            'to_date'=> 'required',
        ]);

        $campaign = Campaign::findOrFail($id);

        $campaign->update([
            'name' => $request->name,
            'client_id' => $request->client_id,
            'brand_id' => $request->brand_id,
            'to_date' => $request->to_date,
            'from_date' => $request->from_date,
            'user_id' => Auth::id()
        ]);

        //Return response feedback to user
        if($campaign){
            Alert::success('Success', 'Campaign Updated Successfully');
            return back();
        }else{
            Alert::errot('Failed !', 'Campaign Not Updated');
            return back();
        }

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
    public function generateCode()
    {
        $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $batchcode = 'CAMP-' . mt_rand(100, 999) . substr(str_shuffle($permitted_chars), 0, 3);

        return $batchcode;
    }
}
