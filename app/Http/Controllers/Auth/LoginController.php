<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserCode;
use Illuminate\Http\Client\Response;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    protected $maxAttempts = 3; // Default is 5
    protected $decayMinutes = 1; // Default is 1

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'logoutApi']);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user_id = auth()->user()->id;
            $user = User::where('id', $user_id)->first();
            $user->generateCode($user);

            return redirect()->route('otp.index');
        }

        return redirect("login")->withSuccess('Opps! You have entered invalid credentials');
    }

    public function LoginApi(Request $request)
    {

        // Todo: Check If phone number exists in the user table??

        // Todo: If the phone number exists, Authenticate then send otp

        //Todo: Confirm OTP and assign user token.
        $phoneNumber = '254' . substr($request->phone, -9, 9);
        $user = User::where('phone', $phoneNumber)->first();
        if ($user) {
            $user->generateCode($user);
            return  \Response::json([
                'message' => "OTP Code Sent Use it to generate Auth Token",
                'status' => 1,
            ]);
        } else {
            return \Response::json([
                'message' => "Unauthorised ! Phone number not registered in the System.",
                'status' => 0,
            ]);
        };
    }

    public function verifyOTPApi(Request $request)
    {
        //confirm otp exist and find user_id
        $find = UserCode::where('code', $request->code)
            //->where('updated_at', '>=', now()->subMinutes(1440))
            ->first();

        if (!is_null($find)) {
            $user = User::where('id', $find->user_id)->first();

            $token = 'token';
            $token = $user->createToken($token);
            return \Response::json([
                'message' => "Token Generated Successfull",
                'token' => $token->plainTextToken,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->title,
                'status' => 1,
            ]);
        } else {
            return response()->json([
                'message' => "OTP Code does not exist or is expired",
                'status' => 0,
            ]);
        }
    }

    public function logoutApi()
    {

        auth()->user()->tokens()->delete();

        return \Response::json([
            'message' => 'Successfully Logged Out',
            'status' => 0,
        ]);
    }
}
