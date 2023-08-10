<?php

namespace App\Http\Controllers\api\v1;

use App\Constant;
use App\Models\Buisness;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BookProvider;
use App\Models\Categories;
use App\Models\ProviderBuisness;
use App\Models\ProviderGallery;
use App\Models\ProviderServices;
use App\Models\Services;
use App\Models\User;
use App\Traits\PaginationTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    use ImageTrait;
    use PaginationTrait;
  
    public function updateBuisness(Request $request)
    {

        $provider = auth()->user();

        $values = array_filter($request->all());
        unset($values['logo'],$values['media']);
        if($provider->role_id == Constant::Provider)
        {
            ProviderBuisness::where('user_id', $provider->id)->update($values);
            $buisness = ProviderBuisness::where('user_id', $provider->id)->first();

            if($buisness)
            {
                if($request->hasFile('logo')) {
                    $this->removeImage('public/buisness_logo/'.$buisness->logo);

                    $file = $request->file('logo');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    
                    $path = basename($file->storeAs('public/buisness_logo', $filename));
    
                    $buisness->logo = $path;
                    $buisness->save();
                   
                  
                } 
                 
               
                    $gallery=[];
                    if($request->hasFile('media'))
                    {
                    foreach ($request->file('media') as $media_file) {
                        
                      $mimeType = $media_file->getMimeType();
                      $fileType = explode('/', $mimeType)[0];
                   
                      $filename = time() . '_' . $media_file->getClientOriginalName();
                      
                      $path = basename($media_file->storeAs('public/media', $filename));
                    
                        $gallery = ProviderGallery::create([
                        'provider_id' => $provider->id,
                        'media' => $path,
                        'media_type' => $fileType,
                      ]);
                    }
                    
                  }
                    $provider = User::where('id',$provider->id)->with('providerInfo.category','gallery',)->first();
                return response()->json([
                    'code' => 200,
                    'error' => 'Successfully updated',
                    'data' => [
                        'provider' => $provider,
                        

                    ],
                ]);
            }
            
        }
        else
        {
            return response()->json([
                'code' => 403,
                'error' => "You can not access becouse you are customer",
                
            ]); 
        }
    }

    public function getIncommingBookings()
    {
           $user = auth()->user();
            if($user)
            {
                $bookings = BookProvider::where('provider_id',$user->id);

                $incomming_booking = $bookings->where('status','incomming')->paginate(10);
                $pagination = $this->pagination($incomming_booking);
                 return response()->json([
                    'code' => 200,
                    'message' => "Successfully fitched your bookings",
                    'data'  => [
                        'incomming_bookings' => $incomming_booking->items(),
                        'pagination' => $pagination,
                    ]
                ]); 
            }
            else
            {
                return response()->json([
                    'code' => 400,
                    'error' => "Booking  could not found",
                    
                ]); 
            }
          
     }

    public function getPendingBookings()
    {
        $user = auth()->user();
        if($user)
        {
            $bookings = BookProvider::where('provider_id',$user->id);
            $pending_bookings = $bookings->where('status','pending')->paginate(10);
            $pagination = $this->pagination($pending_bookings);
            return response()->json([
                'code' => 200,
                'message' => "Successfully fitched pending booking",
                'data'  => [
                    'pending' => $pending_bookings->items(),
                    'pagination' => $pagination,
                ] 
            ]); 
        }
        else
        {
            return response()->json([
                'code' => 400,
                'error' => "Booking  could not found",
                
            ]); 
        }
    }
    public function getCompletedBookings()
    {
        $user = auth()->user();
        if($user)
        {
            $bookings = BookProvider::where('provider_id',$user->id);
            $completed_bookings = $bookings->where('status','completed')->paginate(10);
            $pagination = $this->pagination($completed_bookings);
            return response()->json([
                'code' => 200,
                'message' => "Successfully fitched completed booking",
                'data'  => [
                    'pending' => $$completed_bookings->items(),
                    'pagination' => $pagination,
                ] 
            ]); 
        }
        else
        {
            return response()->json([
                'code' => 400,
                'error' => "Booking  could not found",
                
            ]); 
        }
    }

     public function deleteBooking(Request $request)
     {
        $validatedData = Validator::make($request->all(), [
            'id' =>'required'
        ]);

        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
        }

        $user = auth()->user();
          $booking = BookProvider::where(['id' =>$request->id, 'provider_id' => $user->id, 'status' => 'incomming'])->first();
         
          if($booking)
          {
            $booking->delete();

            return response()->json([
                'code' => 200,
                'message' => "Successfully deleted this booking",
                
            ]); 
          }
          else
          {
            return response()->json([
                'code' => 400,
                'error' => "could not found incomming booking",
                
            ]); 
          }
    }
    
    public function deleteMedia(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'media_id' =>'required'
        ]);
        
        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
        }

        $provider = auth()->user();

        $provider_media= ProviderGallery::where(['id' =>$request->media_id,'provider_id' => $provider->id])->first();
        if($provider_media)
        {
                $provider_media->delete();
                return response()->json([
                    'code' => 200,
                    'message' => "Successfully deleted file",
                    
                ]); 
        }
        else
        {
            return response()->json([
                'code' => 400,
                'error' => "File not found",
                
            ]); 
        }
    }

    public function addServices(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'title' =>'required'
        ]);
               
        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
        }

        $provider = auth()->user();

        if($provider)
        {
            $services = ProviderServices::create([
                'title' => $request->title,
                'provider_id' => $provider->id,
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'Successfully service added',
                'data' => $services,
            ]);
        }
        else{
            return response()->json([
                'code' => 400,
                'error' =>'Could not found Providers',
            ]);
        }
        
    }
    public function deleteService(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'service_id' =>'required'
        ]);
               
        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
        }

        $provider = auth()->user();
        $service = ProviderServices::where([ 'id' => $request->service_id,'provider_id' => $provider->id])->first();
        if($service)
        {
            $service->delete();
            return response()->json([
                'code' => 200,
                'message' => 'Successfully service deleted',
                
            ]);
        }
        else{
            return response()->json([
                'code' => 400,
                'error' =>'Could not found Service',
            ]);
        }
    }

    public function addBookingToPinding(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'booking_id' =>'required',
        ]);
        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
        }
        $booking = BookProvider::where('id',$request->booking_id)->first();

        if($booking)
        { 
           if($booking->status == 'pending')
           {
            return response()->json([
                'code' => 403,
                'error' => 'Already in pending please  complete the booking',
            ]);  
           }
           elseif($booking->status == 'completed')
           {
            return response()->json([
                'code' => 403,
                'error' => 'This Booking already completed and you can not add in pinding',
            ]); 
           }

            $booking->status= 'pending';
             $booking->save();

            return response()->json([
                'code' => 200,
                'error' => 'Your booking is now in pending after work done then please completed the status',
            ]);  

            
        }
        else
        {
            return response()->json([
                'code' => 400,
                'error' =>'Could not found booking',
            ]);
        }
               
        
    }
    public function addBookingToCompleted(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'booking_id' =>'required',
        ]);
        if($validatedData->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validatedData->errors()->first(),
            ]);
        }
        $booking = BookProvider::where('id',$request->booking_id)->first();

        if($booking)
        { 
            if($booking->status == 'completed')
            {
                return response()->json([
                    'code' => 403,
                    'error' => 'Already Completed',
                ]);  
            }
            elseif($request->status == 'incomming')
            {
                return response()->json([
                    'code' => 403,
                    'error' => 'This Booking already in incomming and you can not add into completed',
                ]); 
            }
           
            
                $booking->status='completed';
                $booking->save();
                return response()->json([
                    'code' => 200,
                    'error' => 'Successfully completed your booking',
                ]);
        }
        else
        {
            return response()->json([
                'code' => 400,
                'error' =>'Could not found booking',
            ]);
        }
               
     
    }

    
}

