<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Insurance;
use Illuminate\Support\Facades\Validator;

class InsuranceController extends Controller
{
    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'product_id'  => 'required',
            'renewal_date' => 'required',
            'title' => 'required',
            'name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'post_code' => 'required',
            'status' => 'nullable',
            'contact_on' => 'nullable',
        ]);
      
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'error' => $validator->errors()->first(),
            ]);
        }

            if(!Insurance::where(['email' => $request->email, 'product_id' => $request->product_id])->exists())
            {
                $values = $request->all();
                $values['contact_on'] =  json_encode($request->contact_on,JSON_FORCE_OBJECT);
               $insurance = Insurance::create($values);
    
               return response()->json([
                'code' => 200,
                'message' => 'Successfully inserted record',
                'data' => $insurance,
               ]);
            }
            else
            {
                return response()->json([
                    'code' => 302,
                    'error' => 'Already exists this record',
                   ]);
            }
          

    }
}
