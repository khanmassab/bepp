<?php

namespace App\Http\Controllers\api\v1;

use Throwable;
use App\Constant;
use App\Models\User;
use App\Models\Review;
use App\Models\Artical;
use App\Models\Categories;
use App\Models\deviceToken;
use App\Traits\ReviewTrait;
use Illuminate\Support\Str;
use App\Models\BookProvider;
use Illuminate\Http\Request;
use App\Models\ContactTrader;
use App\Traits\PaginationTrait;
use App\Models\ProviderBuisness;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Services\AdvertisementService;
use Kreait\Firebase\Contract\Messaging;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class CustomerController extends Controller
{
    use PaginationTrait;
    use ReviewTrait;
    public function bookProvider(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone_number' =>'required',
            'address' => 'required',
            'description'=>'required',
            'provider_id'=>'required',
            'start_date' => 'required|after_or_equal:today|date_format:Y-m-d',
        ]);
        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
        }

        if(User::where(['id' => $request->provider_id, 'role_id' => Constant::Provider])->exists())
        {


        do {
            $messaging = app(Messaging::class);

            $rand_nu = mt_rand(100,999);
            $booking_id =Str::random(3).$rand_nu;
            } //check if the booking id already exists and if it does, try again
            while (BookProvider::where(['booking_id' => $booking_id,])->first());

            $booking= BookProvider::create([
                'name' => $request->name,
                'provider_id' => $request->provider_id,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'booking_id' => $booking_id,
            ]);

            Mail::send('mail.customer_booking', ['name' => $request->name, 'booking_id' => $booking_id], function($message) use($request){
                $message->to($request->email);
                $message->subject('Your Booking Id');
            });
            $tokens = deviceToken::where('provider_id',  $request->provider_id)->pluck('fcm_token')->toArray();
            foreach($tokens as $token)
            {
                $providerNotification = Notification::create('appointment',$request->name.' Booked a appointment');
                $providerMessage = CloudMessage::fromArray([
                    'token' =>  $token,
                    'notification' => $providerNotification,
                    'data' => [
                        'name' =>  $request->name,
                        'notification_type' => 'reminder',
                    ],
                ]);
                $providerResult = $messaging->send($providerMessage);
            }
            return response()->json([
                'code' => 200,
                'message' => 'your booking is send to your email',
                'data'  => $booking,
            ]);
        }
        else
        {
            return response()->json([
                'code' => 400,
                'error' => 'Provider could not found',
            ]);
        }

    }
    public function getProviders()
    {
        try{
            $providers = User::where('role_id',Constant::Provider)->whereNotNull('password')

            ->with('reviews', function($q){
                $q->selectRaw('ROUND(AVG(review_count), 1) as total_rate, provider_id');
                $q->groupBy('provider_id');
                $q->orderByDesc('total_rate');
            })
             ->withCount('reviews')
            ->with('providerInfo.category')
            ->paginate(10);


           $this->rviewObject($providers);

            $pagination = $this->pagination($providers);

            return response()->json([
                'code' => 200,
                'message' => 'successfully providrs fetched',
                'data' => [
                    'providers' =>  $providers->items(),
                    'pagination' => $pagination
                ],
            ]);
        }
        catch (\Throwable $th)
        {
            dd($th);
            return response()->json(['code' => 500, 'message' => 'enternal server error.']);
        }
    }



    public function showProviderDetail(Request $request)
    {
        try{

        $validatedData = Validator::make($request->all(), [
            'provider_id' => 'required',
        ]);

        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
        }


        $provider_detail = User::where('id', $request->provider_id)->with('providerInfo.category','services','gallery','reviews','missedApointment')->first();

        // $this->encodeObject($provider_detail);

        // $provider_detail->providerInfo;
        // $provider_detail->providerInfo->category;
        // $provider_detail->services;
        // $provider_detail->gallery;
        // $provider_detail->reviews;
        // $provider_detail->missedApointment;

        $total_reviews =count($provider_detail->reviews);
        $total_rate=$provider_detail->reviews->sum('review_count');

        $missed_apponintment = count($provider_detail->missedApointment);

        if ($total_reviews > 0) {
            $total_rate = $total_rate / $total_reviews;
            $total_rate = number_format((float)$total_rate, 2, '.', '');
        } else {
            $total_rate = 0.0;
        }

        return response()->json([
        'code' => 200,
        'message' => "successfully fetched",
        'data' => [
            'provider_detail' =>$provider_detail,
            'total_review' => $total_reviews,
            'total_rate' => (float)$total_rate,
            'missed_appointment' => $missed_apponintment,
        ],
        ]);

        }
        catch(\Throwable $th)
        {
            return $th->getMessage();
            return response()->json(['code' => 500, 'error' => 'enternal server error.']);
        }
    }

    public function SearchProvider(Request $request)
    {

        $query = $request->input('company_name');

        $providers = User::where('role_id',Constant::Provider)->whereNotNull('password')->whereHas('providerInfo',function($q) use($query){
                $q->Where(DB::raw('lower(company_name)'),'Like', '%' . $query . '%');
                $q->with('category');
        })
        ->with(['reviews' => function($q){
            $q->selectRaw('ROUND(AVG(review_count), 1) as total_rate, provider_id');
            $q->groupBy('provider_id');
            $q->orderByDesc('total_rate');
        }])
        ->withCount('reviews')
            // ->orderByDesc('reviews.total_rate')
            ->with('providerInfo.category')->paginate(10);
            $this->rviewObject($providers);
        $pagination = $this->pagination($providers);

            return response()->json([
                'code' => 200,
                'message' => 'Search results',
                'data' =>[
                    'providers'=> $providers->items(),
                    'pagination' =>  $pagination,
                ],

            ]);

    }
    public function addReview(Request $request)
    {

        try{

            $validatedData = Validator::make($request->all(), [
                'name' => 'required',
                'email'=> 'required',
                'post_code'=> 'required',
                'phon_number'=> 'required',
                'address'=> 'required',
                'city'=> 'required',
                'country'=> 'required',
                'intitil_permation'=> 'nullable|in:1,2,3,4,5',
                'punctuality'=> 'nullable|in:1,2,3,4,5',
                'cleanliness'=> 'nullable|in:1,2,3,4,5',
                'quality'=> 'nullable|in:1,2,3,4,5',
                'value'=> 'nullable|in:1,2,3,4,5',
                'overall_rating'=> 'nullable|in:1,2,3,4,5',
                'is_friend'=> 'required|in:0,1',
                'comment'=> 'required',
                'booking_id'=> 'required',
                'review_type'=> 'required',
            ]);

            if($validatedData->fails()) {
                return response()->json([
                    'code' => 422,
                    'error' => $validatedData->errors()->first(),
                ]);
            }

           $booking =BookProvider::where(['booking_id' => $request->booking_id])->first();
            if($booking)
            {
               if($booking->status == 'incomming')
               {
                    return response()->json([
                        'code' => 403,
                        'error' => 'This booking is not accepted now so please give review after completion work',
                    ]);
               }
               else
               {
                    if(!Review::where('booking_id',$booking->id)->exists())
                    {

                        $initial =$request->intitil_permation;
                        $punctuality= $request->punctuality;
                        $cleanliness = $request->cleanliness;
                        $quality = $request->quality;
                        $value = $request->value;
                        $overall_rating= $request->overall_rating;

                        $total_rating = $initial + $punctuality + $cleanliness + $quality + $value + $overall_rating;
                        $total = $total_rating/6;
                        $total_rate=number_format((float)$total, 1, '.', '');

                        $values = $request->all();
                        unset($values['booking_id'],$values['work_mage']);
                        $values['booking_id'] = $booking->id;
                        $review = Review::create($values);

                        $review->review_count = $total_rate;

                        if($request->hasFile('work_image'))
                        {
                            $file = $request->file('work_image');
                            $filename = time() . '_' . $file->getClientOriginalName();
                            $path = basename($file->storeAs('work_images', $filename));
                            $review->work_image = $path;
                            $review->save();
                        }
                        $review->save();

                        return response()->json([
                            'code' => 200,
                            'message' => 'Thanks for giving review',
                            'data' => $review
                        ]);

                    }
                    else
                    {
                        return response()->json([
                            'code' => 422,
                            'error' => 'You are already give review agianst this booking id',

                        ]);
                    }
                }
           }
          else
            {
                return response()->json([
                    'code' => 400,
                    'error' => 'booking Not found',
                ]);
             }
        }
        catch (\Throwable $th)
        {
            return response()->json(['code' => 500, 'message' => 'enternal server error.']);
        }
    }

    public function addMissedApointment(Request $request)
    {
        try{
            $validatedData = Validator::make($request->all(), [
                'booking_id' => 'required',
                'comment'=> 'required',
                'post_code'=> 'required',
                'name' => 'required',
                'email'=> 'required',
                'post_code'=> 'required',
                'phon_number'=> 'required',
                'address'=> 'required',
                'city'=> 'required',
                'country'=> 'required',
            ]);

            if($validatedData->fails()) {
                return response()->json([
                    'code' => 422,
                    'error' => $validatedData->errors()->first(),
                ]);
            }
            $booking =BookProvider::where(['booking_id' => $request->booking_id])->first();
            if($booking)
            {
               if($booking->status == 'incomming')
               {
                    return response()->json([
                        'code' => 403,
                        'error' => 'This booking is not accepted now so please give review after completion work',
                    ]);
               }
               else
               {
                    if(!Review::where('booking_id',$booking->id)->exists())
                    {       $values = $request->all();
                         $values['booking_id'] = $booking->id;
                         $values['review_type'] = 'missed_appointment';

                        $missed_apointment = Review::create($values);
                        return response()->json([
                            'code' => 200,
                            'message' => 'Recieved your report will be inform soon if possable',
                            'data' => $missed_apointment
                        ]);

                    }
                    else
                    {
                        return response()->json([
                            'code' => 422,
                            'error' => 'You are already give review agianst this booking id',

                        ]);
                    }
                }
           }
          else
            {
                return response()->json([
                    'code' => 400,
                    'error' => 'booking Not found',
                ]);
             }
        }
        catch (\Throwable $th)
        {
            return response()->json(['code' => 500, 'message' => 'enternal server error.']);
        }

    }
    public function addComplaint(Request $request)
    {
        try{
            $validatedData = Validator::make($request->all(), [
                'booking_id' => 'required',
                'comment'=> 'required',
                'post_code'=> 'required',
                'name' => 'required',
                'email'=> 'required',
                'post_code'=> 'required',
                'phon_number'=> 'required',
                'address'=> 'required',
                'city'=> 'required',
                'country'=> 'required',
            ]);

            if($validatedData->fails()) {
                return response()->json([
                    'code' => 422,
                    'error' => $validatedData->errors()->first(),
                ]);
            }
            $booking =BookProvider::where(['booking_id' => $request->booking_id])->first();
            if($booking)
            {
               if($booking->status == 'incomming')
               {
                    return response()->json([
                        'code' => 403,
                        'error' => 'This booking is not accepted now so please give review after completion work',
                    ]);
               }
               else
               {
                    if(!Review::where('booking_id',$booking->id)->exists())
                    {
                          $values = $request->all();
                         $values['booking_id'] = $booking->id;
                         $values['review_type'] = 'complaint';
                        $missed_apointment = Review::create($values);
                        return response()->json([
                            'code' => 200,
                            'message' => 'Recieved your complaint will be inform soon if possable',
                            'data' => $missed_apointment
                        ]);

                    }
                    else
                    {
                        return response()->json([
                            'code' => 422,
                            'error' => 'You are already give review agianst this booking id',

                        ]);
                    }
                }
           }
          else
            {
                return response()->json([
                    'code' => 400,
                    'error' => 'booking Not found',
                ]);
             }
        }
        catch (\Throwable $th)
        {
            return response()->json(['code' => 500, 'message' => 'enternal server error.']);
        }

    }
    public function showProviderReviews(Request $request)
    {
        try
        {

            $validatedData = Validator::make($request->all(), [
                'provider_id' => 'required',
            ]);

            if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
          }
            $user = auth()->user();

            $provider_review = User::where('id', $request->provider_id)->with('reviews')->first();
            return response()->json([
                'code' => 200,
                'message' => "successfully fetched",
                'data' => $provider_review,
            ]);
        }
        catch(\Throwable $th)
        {
            return response()->json(['code' => 500, 'error' => 'enternal server error.']);
        }



    }

    public function searchByLocation(Request $request)
    {
        try{
            $validatedData = Validator::make($request->all(), [
                'company_name' =>'nullable',
                'latitude' => 'required',
                'longitude' => 'required',

            ]);
            if($validatedData->fails()) {
                return response()->json([
                    'code' => 422,
                    'error' => $validatedData->errors()->first(),
                ]);
            }

              $companyName = $request->input('company_name');
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            $radius = 50; // Define the radius in kilometers for the search area

            $company = User::whereHas('providerInfo', function($q) use($companyName,$latitude,$longitude,$radius){
                $q->where(DB::raw('lower(company_name)'),'Like', '%' . $companyName . '%');
                    $q->selectRaw(
                        "(6371 * acos(cos(radians($latitude))
                        * cos(radians(latitude))
                        * cos(radians(longitude) - radians($longitude))
                        + sin(radians($latitude))
                        * sin(radians(latitude))))
                        AS distance"
                    )
                    ->whereRaw("(6371 * acos(cos(radians($latitude))
                        * cos(radians(latitude))
                        * cos(radians(longitude) - radians($longitude))
                        + sin(radians($latitude))
                        * sin(radians(latitude))))
                        <= $radius")
                    ->orderBy('distance');
                })
                ->with('providerInfo')
                ->paginate(10);
                $pagination = $this->pagination($company);
                if($company)
                {
                    return response()->json([
                        'code' => 200,
                        'message' => 'Successfully fetched',
                        'data' => [
                            'companies' => $company->items(),
                            'pagination' =>  $pagination,
                        ],

                      ]);

                }
                else
                {
                    return response()->json([
                        'code' => 400,
                        'error' => 'Trader could not found',
                      ]);
                }



        }
        catch(Throwable $th)
        {

            return response()->json([
                'code' => 500,
                'error' => 'Successfully fitched',
              ]);
        }

    }


    public function getPopularCategories()
    {


       $popularCategories = Categories::where(['status' => 1, 'category_type' => 'trader'])->get();

       return response()->json([
        'code' => 200,
        'message' => 'successfully fetched',
        'popular_categories' => $popularCategories,
       ]);
    }
    public function getTradersByCategory(Request $request)
    {
       $providerbuisness = ProviderBuisness::where('category_id',$request->category_id)->pluck('user_id');
        $Provider = User::whereIn('id',$providerbuisness)
        ->with(['reviews' => function($q){
            $q->selectRaw('ROUND(AVG(review_count), 1) as total_rate, provider_id');
            $q->groupBy('provider_id');
            $q->orderByDesc('total_rate');
        }])

        ->withCount('reviews')
        ->with('providerInfo.category')->paginate(10);


        $this->rviewObject($Provider);
        $pagination = $this->pagination($Provider);
       return response()->json([
        'code' => 200,
        'message' => 'successfully fetched',
        'data' => [
            'providers' =>  $Provider->items(),
            'pagination' =>  $pagination,
        ]
       ]);
    }

    public function contactATrader(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' =>'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required|string',
        ]);

        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
        }

        $contacted = ContactTrader::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'provider_id' => $request->provider_id,
        ]);


        if($contacted){

            $messaging = app(Messaging::class);

            $tokens = deviceToken::where('provider_id',  $request->provider_id)->pluck('fcm_token')->toArray();
            foreach($tokens as $token)
            {
                $providerNotification = Notification::create('Contact Alert!', $request->name.' is trying to contact you');
                $providerMessage = CloudMessage::fromArray([
                    'token' =>  $token,
                    'notification' => $providerNotification,
                    'data' => [
                        'name' =>  $request->name,
                        'message' =>  $request->message,
                        'email' =>  $request->email,
                        'phone' =>  $request->phone,
                        'notification_type' => 'contact_alert',
                    ],
                ]);
                $providerResult = $messaging->send($providerMessage);
            }

            return response()->json(['code' => 200, 'message' => 'The provider will reach out to you soon']);
        }


        return response()->json(['code' => 500, 'message' => 'Something went wrong']);


    }

    public function getAdvertisment()
    {

        $service = new AdvertisementService();
        $advertisements = $service->gitAdvertisement();

        return response()->json([
            'code' => 200,
            'message' => 'Successfully fetched',
            'data' => $advertisements,
        ]);

    }

    public function getProvidersTitles()
    {
        try{
            $providers = ProviderBuisness::select('company_name', 'user_id')
            ->get();

            return response()->json([
                'code' => 200,
                'message' => 'successfully providrs fetched',
                'data' => $providers
            ]);
        }
        catch (\Throwable $th)
        {
            dd($th);
            return response()->json(['code' => 500, 'message' => 'enternal server error.']);
        }
    }
}
