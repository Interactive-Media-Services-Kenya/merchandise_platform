<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\UserCode;
use App\Models\User;
//use App\Utility\SendSMS;

class OTPController extends Controller
{
    public function index()
    {
        return view('auth.otp');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required',
        ]);

        $find = UserCode::where('user_id', auth()->user()->id)
                        ->where('code', $request->code)
                        ->where('updated_at', '>=', now()->subMinutes(2))
                        ->first();

        if (!is_null($find)) {
            Session::put('user_otp', auth()->user()->id);
            return redirect()->route('home');
        }

        return back()->with('error', 'You entered wrong code.');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function resend()
    {
        auth()->user()->generateCode();

        return back()->with('success', 'We sent you code on your mobile number.');
    }
}
