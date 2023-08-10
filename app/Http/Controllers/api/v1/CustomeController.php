<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Categories;
use App\Models\CallBackTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CustomeController extends Controller
{


    public function getCategories(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'category_type' =>'required',
        ]);

        if($validator->fails()){
            return response()->json(['code' => 422, 'error'=>$validator->errors()->first()]);
        }
        $categories = Categories::orderBy('title','ASC')->where('category_type', $request->category_type)->select('id','title','category_type')->get();

        if($request->category_type == 'garage')
        {
            return response()->json([
                'code' => 200,
                'message' => 'Successfully fetched',
                'garage_categories'  => $categories,
            ]);
        }
        else
        {
            return response()->json([
                'code' => 200,
                'message' => 'Successfully fetched',
                'trader_categories'  => $categories,
            ]);
        }
        
    } 

    public function  getCallbackTimes()
    {
        $callTime = CallBackTime::orderBy('id', 'ASC')->select('call_time')->get();
        $timeRange=[];
        foreach($callTime as $callTime)
        {
                $timeRange[] = $callTime->call_time;
        }

        return response()->json([
            'code' => 200,
            'message' => 'Successfully fetched',
            'call_times' => $timeRange,
        ]);
    }
}
