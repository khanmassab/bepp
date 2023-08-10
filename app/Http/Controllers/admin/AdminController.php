<?php

namespace App\Http\Controllers\admin;

use random;
use App\Constant;
use App\Models\User;
use App\Models\Insurance;
use App\Models\Categories;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Services\AdvertisementService;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    
    public function index()
    {
  
     return view('admin.dashboard');
    }

    public function getunverifiedProviders()
    {
        $unverified_providers = User::where(['password' => null , 'role_id' => Constant::Provider])->paginate(10);

        return view('admin.provider.unverfierd',get_defined_vars());
    }

    public function editAccount($id)
    {
        $id = decrypt($id);
        $hashed_random_password = Str::random(5);
        $hashed_random_password .= mt_rand(100, 999);

        do {
        $membership_number= mt_rand(10000,99999);
        $membership_number = 'TT'.$membership_number;
        } //check if the token already exists and if it does, try again
        while (User::where(['id' => $id , 'member_ship_number' =>  $membership_number])->first());
    

        
        $user = User::where(['id' => $id , 'role_id' => Constant::Provider])->first();
        Mail::send('mail.verification', ['password' => $hashed_random_password, 'member_ship_number' => $membership_number ], function($message) use($user){
            $message->to($user->email);
            $message->subject('your account password and member ship number');
        });
       $password=Hash::make($hashed_random_password);
       
                $user->password = $hashed_random_password;
                $user->member_ship_number = $membership_number;
                $user->is_verified=1;
                $user->save();
        return back()->with('message','Successfully verify trader');
    } 

    public function gitCategories()
    {
        $categories = Categories::orderBy('status','DESC')->paginate(5);
        return view('admin.category.show_categories',get_defined_vars());
    }


    public function createCategory(Request $request)
    {

        return view('admin.category.create_category');
    }
    public function storeCategory(Request $request) 
    {
    
        $categories= Categories::create([
            'title' => $request->title,
            'status' => $request->status ? 1:0,
        ]);
        if($request->hasFile('image')) {

            $file = $request->file('image');
            $filename = time() .'_'. $file->getClientOriginalName();
            $path = basename($file->storeAs('public/categories_images', $filename));
            $categories->image = $path;
            
        }
        $categories->save();

        return redirect()->route('git.categories')->with('message', 'successfully category created');
    }

    public function editCategory($id)
    {
        $id = decrypt($id);
        $category = Categories::where('id',$id)->first();
        
        return view('admin.category.update_category',get_defined_vars());
    }

    public function updateCategory(Request $request,$id)
    {
  
            $categories= Categories::where('id',$id)->first();
            $categories->title = $request->title;
        $categories->status = $request->status;
        if($request->hasFile('image')) {

            $file = $request->file('image');
            $filename = time() .'_'. $file->getClientOriginalName();
            $path = basename($file->storeAs('public/categories_images', $filename));
            $categories->image = $path;
            
        }
        $categories->save();

        return redirect()->route('get.categories')->with('message', 'successfully category updated');
    }

    public function gitInsurance()
    {
        $insurances = Insurance::orderBy('id','DESC')->with('product')->paginate(10);
      
       return view('admin.insurance.git_insurance',get_defined_vars());
    }

    public function advertisement()
    {
        $service = new AdvertisementService();
        $advertisements = $service->gitAdvertisement();
  
        return view('admin.Advertisment.show_advertisement', get_defined_vars());
    }
    public function createAdvertisement()
    {
        return view('admin.Advertisment.create');
    }

    public function storeAdvertisment(Request $request)
    {
      
        $service = new AdvertisementService();
        $advertisements = $service->storeAdvertisement($request);

        return redirect()->route('get.advertisement')->with('message','Successfully added advertisment');
    }

    public function editAdvertisment($id)
    {
        $id = decrypt($id);
         $advertisment = Advertisement::where('id',$id)->first();

         return view('admin.Advertisment.edit',get_defined_vars());

    }

    public function updateAdvertisment(Request $request, $id)
    {
     

        $service = new AdvertisementService();
        $advertisements = $service->updateAdvertisement($request,$id);

        return redirect()->route('get.advertisement')->with('message','Succefully updated');

      
    }

    public function deleteAdvertisment($id)
    {
        $service = new AdvertisementService();
        $advertisements = $service->destroyAdevrtisemen($id);
        return back()->with('message','Successfully deleted');
    }

}



