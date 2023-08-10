<?php

namespace App\Http\Controllers\api\v1;

use Throwable;
use App\Constant;
use App\Models\User;
use App\Models\UserInfo;
use App\Rules\TimeRange;
use App\Models\Categories;
use App\Models\CustomerInfo;
use App\Models\ProviderInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\ProviderBuisness;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CallBackTime;
use App\Models\deviceToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {

        $validatedData = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => 'required|email|unique:users',
            'post_code'     =>'required',
            'phone_number'  => 'required',
            'call_date'     =>  'required|after_or_equal:today|date_format:Y-m-d',
            'call_time'     => 'required',
            'address'       => 'required',
            'company_name'  => 'required',
            'buisness_type' => 'required',
            'loc_name'      => 'required',
            'latitude'      => 'required',
            'longitude'     => 'required',
            'category_id'   => 'required',
            'description'   => 'required',
            'marketing_channel' => 'required',
            'marketing_source'  =>'required',
        ]);
    
        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
        }
        $timeParts = explode('-', $request->call_time);

        if (count($timeParts) != 2) {
            return response()->json([
                'code' => 422,
                'error' => 'Time range formate is invalid the valid formate is e.g 9:00-10:00',
            ]);
        }
        $startTime = trim($timeParts[0]);
        $endTime = trim($timeParts[1]);
        
        $validFormat = 'H:i';

        // Check if the start time is before the end time
        if (strtotime($startTime) > strtotime($endTime)) {
            return response()->json([
                'code' => 422,
                'error' => 'End time must be greater than from start time',
            ]);
        }
        if($startTime > 22 || $startTime < 0)
        {
            return response()->json([
                'code' => 422,
                'error' => 'start time must be between 0-22',
            ]);
        }
        if($startTime > 23  || $endTime == $startTime || $startTime < 0)
        {
            return response()->json([
                'code' => 422,
                'error' => 'End  time must be between 0-23 and not equal to start time',
            ]);
        }

        $datetime = Carbon::now(); // Get the current date and time
    
       $call_time= $datetime->setTimeFromTimeString($request->call_time); 
       
     $category= Categories::where('id', $request->category_id)->first();
     if(!$category)
     {
        return response()->json([
            'code' => 400,
            'error' => 'category not found',
          ]);
     }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'post_code'     =>$request->post_code,
            'phone_number'  => $request->phone_number,
            'call_date'     => $request->call_date,
            'call_from_time'     => Carbon::parse($startTime)->format('H:i'),
            'call_to_time' =>Carbon::parse($endTime)->format('H:i'),
            'role_id' => Constant::Provider,
            'marketing_channel' => $request->marketing_channel,
            'marketing_source' => $request->marketing_source,
        ]);
       
        if($user){
          
           $provider_info= ProviderBuisness::create([
            'user_id' => $user->id,
            'company_name'  => $request->company_name,
            'buisness_type' => $request->buisness_type,
            'loc_name'      => $request->loc_name,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'address'       => $request->address,
                
            ]); 
               
            return response()->json([
                'code' => 200,
                'message' => 'please wait for call for your account verifcation',
                'data' => [
                    'user' => $user,
                ],
              ]);
        }
    
        return response()->json([
            'code' => 500,
            'error' => 'Buisness could not be created',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
 

    public function login(Request $request)
    {
        try{
           
            $credentials = $request->only('email', 'password');
            $user= User::where('email', $request->email)->whereNotNull('password')->with('providerInfo')->first();
          
            if ($user && Hash::check($request->password , $user->password) ) {
                
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                $token->save();


                return response()->json([
                    'code' => 200,
                    'message' => 'User logged in successfully',
                    'data' => [
                        'user' => $user,
                        'access_token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
                        // 'notification' =>  $notification,
                    ],
                ]);
            }
          
    
            return response()->json([
                'code' => 401,
                'error' => 'Invalid credentials',
            ]);
        }
        catch (\Throwable $th) {
            return response()->json(['code' => 500, 'message' => 'enternal server error.']);
        }
      
    }

    public function forgotPassword(Request $request)
    {
    												   
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'error' => $validator->errors()->first(),
            ]);
        }

        $otp = random_int(100000, 999999);
        $user=User::where('email', $request->email)->whereNotNull('password')->first();
            if($user)
            {  
                if($user->is_verified == false)
                {
                    return response([
                        "code" => 400, 
                        "error" => "Your account is not verified",

                       ]);
                }
                 Mail::send('mail.forgot_password', ['otp' => $otp], function($message) use($request){
                    $message->to($request->email);
                    $message->subject('Reset Password');
                });
               
                    
                $user= DB::table('password_resets')->where('email',$request->email)->first();
                if($user)
                {
                    
                    DB::table('password_resets')->where('email',$request->email)->update([
                        'otp' => $otp, 
                        'created_at' => Carbon::now(),
                        'expires_at' => Carbon::now()->addMinute(2),
                      ]);
                }
                else
                {
                    DB::table('password_resets')->insert([
                        'email' => $request->email, 
                        'otp' => $otp, 
                        'token' => 'withouttoken',
                        'created_at' => Carbon::now(),
                        'expires_at' => Carbon::now()->addMinute(2),
                      ]);
                }

                return response([
                "code" => 200, 
                "message" => "OTP sent successfully",
                'otp' => $otp,
               ]);

            }
            else
            {
                return response(["code" => 401, 
                 "error" => "This email is not exist",
                ]); 
            }
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validator->errors()->first(),
            ]);
        }

        $user = DB::table('password_resets')->where('email', $request->email)->first();
        if($user->expires_at < Carbon::now()->subMinute(2))
        {
            return response()->json([
                'code' => 400,
                'error' => 'OTP Expired',
            ]);
        }

        if ($user->otp != $request->otp) {
            return response()->json([
                'code' => 400,
                'error' => 'Invalid OTP',
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'OTP verified successfully',
            'user' => $user,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required|confirmed:new_password_confirmation|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validator->errors()->first(),
            ]);
        }

         $user = User::where('email',$request->email)->whereNotNull('password')->update([
            'password' => Hash::make($request->new_password),
        ]);
        return response()->json([
            'code' => 200,
            'message' => 'Your password updated Successfully ',
        ]);
       
    }



    public function changePassword(Request $request){
        try{
            $validator = Validator::make($request->all(),
            [
                'old_password' =>'required',
                'new_password' => 'required|confirmed:new_password_confirmation|min:8',
            ]);
    
            if($validator->fails()){
                return response()->json(['code' => 422, 'error'=>$validator->errors()->first()]);
            }

            $user = auth()->user();
            if(Hash::check($request->old_password, $user->password))
            {
                $pass = User::find($user->id)->update([
                    'password' => Hash::make($request->new_password)
                ]);
                return response()->json([
                'code' => 200, 
                'message' => 'Password Updated'
                ]);
            }
            else
            {
                return response()->json([
                    'code' => 400,
                    'error' => 'Your old password is Incorrect'
                ]);
            }
            } catch (Throwable $e) {
            return response()->json(['code' => 500, 'error' => 'Something Went Wrong']);
        }
    }

    public function addFcmToken(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),
            [
                'fcm_token' => 'required',
            ]);
    
            if($validator->fails()){
                return response()->json(['code' => 422, 'error'=>$validator->errors()->first()]);
            } 
            $user = auth()->user();

            if(!$user){
                return response()->json(['code' => 401, 'message' => 'unauthorized']);
            }
            if(! DeviceToken::where('fcm_token',$request->fcm_token)->exists())
            {
                $create = DeviceToken::create([
                    'provider_id' => $user->id,
                    'fcm_token' => $request->fcm_token,
                ]);
                return response()->json([
                    'code' => 200,
                    'message' => 'Device token successfully registered',
                ]);
            }
            else
            {
                return response()->json([
                    'code' => 302,
                    'error' => 'Already exists device token',
                ]); 
            }
          
            
        }
        catch (\Throwable $th) {
            dd($th);
            throw $th->getMessage();
            return response()->json(['code' => 500, 'message' => 'internal error']);
        }
        
    }

    public function logout(Authenticatable $user)
    {
        $user->token()->revoke();
        // fcm_token id
        $user->save();
        // $user->token()->delete();
        // auth()->guard('api')->logout();
      
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

   

    
}
