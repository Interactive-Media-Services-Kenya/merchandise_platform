<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function county()
    {
        return $this->belongsTo(County::class, 'county_id');
    }
    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function generateCode()
    {
        $code = rand(1000, 9999);

        UserCode::updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['code' => $code]
        );



        $receiverNumber = auth()->user()->phone;
        $message = "OTP login code is " . $code;
        $this->sendCode($receiverNumber,$message);

    }
    public function generateCodeApi($user)
    {
        $code = rand(1000, 9999);

        UserCode::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $code]
        );



        $receiverNumber = $user->phone;
        $message = "OTP login code is " . $code;
        $this->sendCode($receiverNumber,$message);

    }
    public function sendCode($receiverNumber,$message){

        try {


            $headers = [
                'Cookie: ci_session=ttdhpf95lap45hq8t3h255af90npbb3ql'
            ];

            $encodMessage = rawurlencode($message);

            $url = 'https://3.229.54.57/expresssms/Api/send_bulk_api?action=send-sms&api_key=Snh2SGFQT0dIZmFtcRGU9ZXBlcEQ=&to=' . $receiverNumber . '&from=IMS&sms=' . $encodMessage . '&response=json&unicode=0&bulkbalanceuser=voucher';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true,);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

            $response = curl_exec($ch);
            $res = json_decode($response);
            date_default_timezone_set('Africa/Nairobi');
            $date = date('m/d/Y h:i:s a', time());
            // if($res)
            // {
            //     print_r( "This is test number:".$mobile.", ".$date." \r\n");
            // }
            curl_close($ch);
        } catch (\Exception $e) {

            return redirect()->back()->with("error", $e);
        }
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // public static function getClientID(){
    //     return Auth::user()->client_id;
    // }
}
