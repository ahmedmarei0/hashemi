<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\User;
use App\Models\Warnings;
use Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use GeneralTrait;

    public function login(Request $request)
    {

        $request->validate([
            'mac_address' =>"required|string",
            'username' =>"required|string",
            'password' =>"required|string",
            'notification_token' =>"required|string",
        ]);

        $user = User::where('username', $request->username)->first();
        if($user === null){
            return $this->returnErrorApi('100008', 'user is not exist');
        }
        else if($user->blocked == 1 ){
            return $this->returnErrorApi('100002', 'this user is blocked');
        }
        else if($user->mac_address == null || $user->mac_address == ''){
            $user->tokens()->delete();
            $token = $user->createToken('myapptoken')->plainTextToken;
            $user->password = Hash::make($request->password);
            $user->mac_address = $request->mac_address;
            $user->remember_token = $token;
            $user->notification_token = $request->notification_token;
            $user->save();
            return $this -> returnSuccessMessageApi($user );
        }
        if(Hash::check($request->password, $user->password)){

            // return $user;
            if($user->mac_address == $request->mac_address || $user->mac_address === null || empty($user->mac_address) || $user->username == "admin1"){
                $user->tokens()->delete();
                $token = $user->createToken('myapptoken')->plainTextToken;
                $user->remember_token = $token;
                $user->notification_token = $request->notification_token;
                $user->save();
                return $this -> returnSuccessMessageApi( $user);
            }
            else{

                $userWarning = User::select(['id', 'username'])->where('username', $request->username)
                        ->with(['warning' => function($query) {
                            $query->where('type', 'share_account')->where('state', 0);
                        }])->first();
                if($userWarning->warning !== null && count($userWarning->warning) > 3){
                    Warnings::create([
                        'user_id' => $user->id,
                        'reason' => 'تم حظر هذا الحساب لتجاوز الحد الاقصى لمحاولة الدخول من جهاز اخر',
                        'type' => 'share_account'
                    ]);
                    $user->blocked = 1;
                    $user->save();
                }else{
                    Warnings::create([
                        'user_id' => $user->id,
                        'reason' => 'محاولة تسجيل دخول من جهاز اخر',
                        'type' => 'share_account'
                    ]);
                }
              return $this->returnErrorApi('100016', 'another device');

            }
        }
        else{
            Warnings::create([
                'user_id' => $user->id,
                'reason' => 'محاولة تسجيل دخول فاشلة',
                'type' => 'login'
            ]);

            $userWarning = User::select(['id', 'username'])->where('username', $request->username)
                        ->with(['warning' => function($query) {
                            $query->where('type', 'login')->where('state', 0)
                            ->whereDate('created_at', Carbon::today());
                        }])->first();
            if($userWarning->warning !== null && count($userWarning->warning) > 3){
                $user->blocked = 1;
                $user->save();
            }

            return $this->returnErrorApi('100004', 'username or password wrong');
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return $this -> returnSuccessMessageApi(['logout_time' => Carbon::now()]);
    }
}
