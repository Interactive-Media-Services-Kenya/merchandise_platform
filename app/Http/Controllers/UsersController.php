<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\CampaignUser;
use App\Models\Category;
use App\Models\County;
use App\Models\IssueProduct;
use App\Models\Permission;
use App\Models\Productbas;
use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use App\Models\Campaign;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Session;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UpdatePasswordUser;
use Illuminate\Support\Facades\URL;
use App\Services\SendSMSService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\TeamLeadersExport;
use App\Exports\BasExport;
use App\Imports\UsersImport;
use App\Imports\TeamLeadersImport;
use App\Imports\BasImport;
use DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $sendSMSService;

    public function __construct(SendSMSService $sendSMSService)
    {
        $this->sendSMSService = $sendSMSService;
    }
    public function index()
    {
        $users = User::with(['roles', 'county'])->get();
        // dd($users);

        return view('users.index', compact('users'));
    }


    public function getClientID(){
       dd(Auth::user()->client_id);
    }

    // ? get Team leaders all regions

    public function teamleaders()
    {
        $teamleaders = User::with(['roles', 'county'])->where('role_id', 3)->where('client_id',null)->get();
        $salesreps = User::with(['roles', 'county'])->where('role_id', 3)->where('client_id',Auth::user()->client_id)->get();


        return view('teamleaders.index', compact('teamleaders','salesreps'));
    }

    public function teamleadersEdit($id){
        if (Gate::allows('tb_access')){
            $user = User::findOrFail($id);
            //Check if user is a TeamLeader
            if (!($user->role_id == 3)){
                Alert::error('Failed', 'User is Not A TeamLeader');
                return back();
            }
            //Get Campaigns Associated with the Agency
            $campaigns = Campaign::where('client_id',Auth::user()->client_id)->get();
            $campaigns = Campaign::all();


            return view('teamleaders.edit',compact('user','campaigns'));
        }else{
            Alert::error('Failed', 'Unauthorized to Edit Teamleader');
            return back();
        }

    }

    //Update Teamleader Details
    public function teamleadersUpdate(Request $request,$id){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|digits:12',
        ]);
        $user = User::findOrFail($id);
        $campaignIDs = $request->campaign_id;
       //assign teamleaders to campaign while purging campaign not from request.
        $userCampaigns = CampaignUser::select('campaign_id')->where('user_id',$id)->whereNotIn('campaign_id',$campaignIDs)->get();

        foreach ($userCampaigns as $uC){
            $user->campaigns()->detach($uC->campaign_id);
        }
        foreach($request->campaign_id as $campaign){
            $user->campaigns()->detach($campaign);
            $user->campaigns()->attach($campaign);
        }

        if($user->update($request->only(['name','phone','email']))){
            Alert::success('Success','Teamleader Profile Updated Successfully');
            return back();
        }
        Alert::error('Failed','Updated Failed');
        return back();
    }

    public function agencies()
    {
        $agencies = User::with(['roles', 'county'])->where('role_id', 2)->where('client_id',Auth::user()->client_id)->get();
        $salesreps = User::with(['roles', 'county'])->where('role_id', 2)->where('client_id',Auth::user()->client_id)->get();


        return view('agencies.index', compact('agencies','salesreps'));
    }

    public function agencyShow($id){
        $agency = User::findOrFail($id);

        if($agency->role_id!=2){
            Alert::error('Unauthorized!','User Unauthorized to view this page.');
            return back();
        }

        //Show All merchandise types for the client under the agency
        $categories = Category::whereclient_id(Auth::user()->client_id)->get();
        //Products Belonging to the client under an agency
        $products = Product::whereclient_id(Auth::user()->client_id)->whereowner_id($id)->get();
        //Merchandise/Products Issued belonging to client.
        $issuedProducts = IssueProduct::select('product_id')->cursor();
        //Merchandise/Products Issued belonging to client.
        $issuedProductsClient = Product::whereIn('id',$issuedProducts)->whereowner_id($id)->whereclient_id(Auth::user()->client_id)->cursor();

        return view('agencies.show',compact('agency','categories','products','issuedProductsClient'));
    }

    // ? Get Brand Ambassadors for each team leader

    public function brandambassadors()
    {
        $user_id = Auth::user()->id;
        $brandambassadors = User::with(['roles', 'county'])->where('role_id', 4)->where('teamleader_id', $user_id)->get();


        return view('brandambassadors.index', compact('brandambassadors'));
    }

    public function brandambassadorCreate(){
        $roles = Role::pluck('title', 'id');
        $counties = County::pluck('name', 'id');

        return view('brandambassadors.create', compact('roles', 'counties'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('title', 'id');
        $counties = County::pluck('name', 'id');

        return view('users.create', compact('roles', 'counties'));
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'numeric', 'digits:12'],
            'county_id' => ['required', 'integer'],
            'role_id' => ['required', 'integer'],
           // 'password' => ['required', Password::min(8)->mixedCase()->symbols()->uncompromised(), 'confirmed'],
        ]);
        $phone = '254' . substr($request->phone,-9,9);
        $password = rand(1000, 9999);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone,
            'county_id' => $request->county_id,
            'role_id' => $request->role_id,
            'password' => bcrypt($password),
        ]);
        if ($request->has('client_id')) {
            $user->update([
                'client_id' => Auth::user()->client_id,
            ]);
        }

        Activity::create([
            'title' => 'User Added',
            'description' => Auth::user()->name . ' Added User: ' . $user->email,
            'user_id' => Auth::id(),
        ]);
        if ($user) {
            $url_login = URL::to('/');
            $message = "Hello, You have been assigned an account at $url_login . Kindly Use the following details to login to your Account.     Email: $user->email and Password: $request->password ";
                $details = [
                    'title' => 'Mail from '.config('app.name'),
                    'body' => $message,
                ];
            //Send Mail
          //  $mail = Mail::to($user->email)->send(new UpdatePasswordUser($details));

            //Send SMS
            $sms = $this->sendSMSService->sendSMS($message,$phone);
            Alert::success('Success', 'User Successfully Added');
            return back();
        } else {
            Alert::error('Failed', 'Registration failed');
            return back();
        }
    }

    public function BAstore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'numeric', 'digits:12'],
            'county_id' => ['required', 'integer'],
           // 'password' => ['required', Password::min(8)->mixedCase()->symbols()->uncompromised(), 'confirmed'],
        ]);
        $phone = '254' . substr($request->phone,-9,9);
        $password = rand(1000, 9999);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone,
            'county_id' => $request->county_id,
            'role_id' => 4,
            'teamleader_id' =>Auth::id(),
            'password' => bcrypt($password),
        ]);
        if ($request->has('client_id')) {
            $user->update([
                'client_id' => Auth::user()->client_id,
            ]);
        }

        Activity::create([
            'title' => 'User Added',
            'description' => Auth::user()->name . ' Added User: ' . $user->email,
            'user_id' => Auth::id(),
        ]);

        if ($user) {
            $url_login = URL::to('/');
            $message = "Hello, You have been assigned an account at $url_login . Kindly Use the following details to login to your Account.     Email: $user->email and Password: $request->password ";
                $details = [
                    'title' => 'Mail from '.config('app.name'),
                    'body' => $message,
                ];
            //Send Mail
          //  $mail = Mail::to($user->email)->send(new UpdatePasswordUser($details));

            //Send SMS
            $sms = $this->sendSMSService->sendSMS($message,$phone);
            Alert::success('Success', 'User Successfully Added');
            return back();
        } else {
            Alert::error('Failed', 'Registration failed');
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
        $user = User::findOrFail($id);
        $roles = Role::pluck('title', 'id');
        $counties = County::pluck('name', 'id');
        $permissions = Permission::all();
        return view('users.edit', compact('roles', 'user', 'counties','permissions'));
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'numeric', 'digits:12'],
            'county_id' => ['required', 'integer'],
            'role_id' => ['required', 'integer'],
        ]);
        $permissionIDs = $request->permission_id;
        //assign teamleaders to campaign while purging campaign not from request.
        $userPermissions = DB::table('permission_user')->select('permission_id')->where('user_id',$id)->whereNotIn('permission_id',$permissionIDs)->get();
        $user = User::findOrFail($id);
        //Remove Permissions Not in request for the user
        foreach ($userPermissions as $permission){
            $user->permission_users()->detach($permission->permission_id);
        }
        //Add Permissions in the request
        foreach ($permissionIDs as $permission){
            $user->permission_users()->attach($permission);
        }


        if ($user->update($request->only(['name','email','role_id','phone','county_id']))) {
            Activity::create([
                'title' => 'User Updated',
                'description' => Auth::user()->name . ' Updated User: ' . $user->email,
                'user_id' => Auth::id(),
            ]);
            Alert::success('Success', 'User Details Successfully Edited');
            return back();
        } else {
            Alert::error('Failed', 'Details Not Edited');
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
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            Activity::create([
                'title' => 'User Deleted',
                'description' => Auth::user()->name . ' Deleted User: ' . $user->email,
                'user_id' => Auth::id(),
            ]);
            Alert::success('Success', 'User Removed Successfully');
            return back();
        } else {
            Alert::error('Failed', 'User Not Deleted');
            return back();
        }
    }
    public function showBa($id)
    {
        $ba = User::findOrFail($id);
        // ! Get all products assigned to this one brand Ambassador
        $products = Productbas::where('assigned_to', $id)->get();

        $batches = Productbas::select('batch_id')->where('assigned_to', $id)->groupBy('batch_id')->take(5)->get();

        return view('brandambassadors.show', compact('ba', 'products', 'batches'));
    }

    //Get Download Import sample file
    public function getImportAgency(){
        return Excel::download(new UsersExport, 'sampleAgencyUsers.xlsx');
    }
    public function getImportTeamleader(){
        return Excel::download(new TeamLeadersExport, 'sampleTeamLeaderUsers.xlsx');
    }
    public function getImportBas(){
        return Excel::download(new BasExport, 'sampleBasUsers.xlsx');
    }

    public function importUsers(){
        //Exclude Roles SuperAdmin, Client and Other.
        $ids = [1,5,6];
        $roles = Role::whereNotIn('id',$ids)->pluck('title', 'id');
        //Imported Data Count for the day
        if(Auth::user()->role_id == 1){
            $users = User::whereDate('created_at', DB::raw('CURDATE()'))->get();
            $entryCount = $users->count();
        }

        if(Auth::user()->role_id == 2){
            $ids = [1,2];
            //Exclude Roles SuperAdmin,Agency Client and Other.
            $roleids = [1,2,5,6];
            $roles = Role::whereNotIn('id',$roleids)->pluck('title', 'id');
            $users = User::whereNotIn('role_id', $ids)->whereDate('created_at', DB::raw('CURDATE()'))->get();
            $entryCount = $users->count();

        }

        if(Auth::user()->role_id == 3){
            $ids = [1,2,3];
            $users = User::whereNotIn('role_id', $ids)->whereDate('created_at', DB::raw('CURDATE()'))->get();
            $entryCount = $users->count();
        }

        return view('users.import',compact('roles','entryCount','users'));
    }

    public function submitImport(Request $request){
        $request->validate([
            'role_id'=>'required',
        ]);
        try{
            //Import for Agency
            if($request->role_id == 2)
            { //Create User using importfile
                Excel::import(new UsersImport, $request->file);
            }
            //Import for TeamLeaders
            if($request->role_id == 3)
            {
                Excel::import(new TeamLeadersImport, $request->file);
            }
            //Import for BAs
            if($request->role_id == 4)
            {
                Excel::import(new BasImport, $request->file);
            }

            $date= Carbon::today()->toDateString();

            $now = Carbon::now();

            if(Auth::user()->role_id == 1){
                $users = User::whereDate('created_at', DB::raw('CURDATE()'))->get();
                $entryCount = $users->count();
            }

            if(Auth::user()->role_id == 2){
                $ids = [1,2];
                $users = User::whereNotIn('role_id', $ids)->whereDate('created_at', DB::raw('CURDATE()'))->get();
                $entryCount = $users->count();

            }

            if(Auth::user()->role_id == 3){
                $ids = [1,2,3];
                $users = User::whereNotIn('role_id', $ids)->whereDate('created_at', DB::raw('CURDATE()'))->get();
                $entryCount = $users->count();
            }


            if($entryCount != 0)
            {
                foreach($users as $record)
                {
                    $url_login = URL::to('/');
                    $password = rand(1000,9999);
                    // $user = User::create([
                    //     'name' => $record->name,
                    //     'email' => $record->email??$record->name."@app.com",
                    //     'phone' => $record->phone,
                    //     'role_id' => 2,
                    //     'password' => bcrypt($password),
                    //     ]);
                    $user = User::where('phone',$record->phone)->first();
                    $user->update([
                        'password'=>bcrypt($password),
                    ]);

                    //Send Login Details Via SMS
                    $message = "Hello, You have been assigned an account at $url_login . Kindly Use the following details to login to your Account.     Email: $user->email and Password: $password ";
                    $this->sendSMSService->sendSMS($message,$user->phone);

                }
            }


            return back()->with(array('entryCount'=>$entryCount, 'success'=>'Successfully Imported'));

        }   catch (\Exception $e) {

            return redirect()->back()->with("error","Check Document for Formatting Issues.");
        }
    }
}
