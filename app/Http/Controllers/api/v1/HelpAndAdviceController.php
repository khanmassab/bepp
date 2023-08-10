<?php

namespace App\Http\Controllers\api\v1;

use Throwable;
use App\Models\Artical;
use Illuminate\Http\Request;
use App\Traits\PaginationTrait;
use App\Http\Controllers\Controller;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Question\Question;

class HelpAndAdviceController extends Controller
{
    use PaginationTrait;

    public function getArticalsAndQuestions()
    {
        try {
            $articals = Artical::orderBy('id','DESC')->take(2)->latest()->get();
            $questions = QuestionAnswer::orderBy('id','DESC')->select('question')->take(2)->latest()->get();

            return response()->json([
                'code' => 200,
                'message' => 'successfully fetched',
                'data' =>
                [
                    'articals' => $articals,
                    'question' => $questions,
                ]
            ]);

            } catch (\Throwable $th) {
                throw $th;
            }
    }

    public function getArticals()
    {
         $articals = Artical::orderBy('id','DESC')->paginate(10);
        $paginiation= $this->pagination($articals);
        return response()->json([
         'code' => 200,
         'message' => 'successfully fetched',
         'data' =>[
            'articals' => $articals->items(),
            'pagination' => $paginiation,
         ]
        ]);
    }

    public function getArticalDeatail(Request $request)
    {
            try{
                $validatedData = Validator::make($request->all(), [
                    'id' => 'required',
                ]);

                if($validatedData->fails()) {
                    return response()->json([
                        'code' => 422,
                        'error' => $validatedData->errors()->first(),
                    ]);
                }

                $artical = Artical::where('id',$request->id)->first();

                return response()->json([
                    'code' => 200,
                    'message' => 'Successfully fetched',
                    'artical' => $artical,
                ]);

            }
            catch(Throwable $th)
            {
                return response()->json(['code' => 500, 'error' => 'Enternal server error']);
            }
    }


    public function getQuestions()
    {
        $questions = QuestionAnswer::orderBy('id','DESC')->select('id','question','question_detail', 'answer', 'media')->paginate(10);
        $paginiation= $this->pagination($questions);
        return response()->json([
         'code' => 200,
         'message' => 'successfully fetched',
         'data' =>[
            'questions' => $questions->items(),
            'pagination' => $paginiation,
         ]
        ]);
    }
    public function getQuestionAnswerDetail(Request $request)
    {
            try{
                $validatedData = Validator::make($request->all(), [
                    'id' => 'required',
                ]);

                if($validatedData->fails()) {
                    return response()->json([
                        'code' => 422,
                        'error' => $validatedData->errors()->first(),
                    ]);
                }

                $question = QuestionAnswer::where('id',$request->id)->first();

                return response()->json([
                    'code' => 200,
                    'message' => 'Successfully fetched',
                    'question' => $question,
                ]);

            }
            catch(Throwable $th)
            {
                return response()->json(['code' => 500, 'error' => 'Enternal server error']);
            }
    }
}
