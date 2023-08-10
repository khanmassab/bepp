<?php

namespace App\Http\Controllers\admin;

use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class QuestionAnswerController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = QuestionAnswer::orderBy('id', 'DESC')->paginate(10);
         return view('admin.questionAnswer.index',get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.questionAnswer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     
        $validatedData = Validator::make($request->all(), [
            'question'          => 'required',
            'answer'             => 'required',
        ]);
       
    
        if($validatedData->fails()) {
            return back()->with('error', $validatedData->errors()->first());
        }
    
          $createquestion = QuestionAnswer::create($request->all());

          if($request->hasFile('media'))
          {
            $media_file = $request->file('media');
          
            $filename = time() . '_' . $media_file->getClientOriginalName(); 
            $path = basename($media_file->storeAs('public/question_Answer', $filename));
            $createquestion->media =$path;
            $createquestion->save();
          }

          return redirect()->route('get.question')->with('message','Successfully created');
    }


    public function edit($id)
    {
        $id = decrypt($id); 
        $question = QuestionAnswer::where('id',$id)->first();
        return view('admin.questionAnswer.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = decrypt($request->id);

        $artical = QuestionAnswer::where('id',$id)->first();
        $artical->update($request->all());
        if($request->hasFile('media'))
        {
            $this->removeImage('public/question_Answer/'.$artical->media);
          $media_file = $request->file('media');
        
          $filename = time() . '_' . $media_file->getClientOriginalName(); 
          $path = basename($media_file->storeAs('public/question_Answer', $filename));
          $artical->media =$path;
          $artical->save();
        }

        return redirect()->route('get.question')->with('message','Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $id = decrypt($id);
        
        $artical = QuestionAnswer::where('id',$id)->first();
        if($artical->media)
        {
            $this->removeImage('public/question_Answer/'.$artical->media);
        }
        if($artical)
        {

            $artical->delete();

            return back()->with('message','Succefully deleted');

        }
        else
        {
            return back()->with('error','Data not found');  
        }
    }
}
