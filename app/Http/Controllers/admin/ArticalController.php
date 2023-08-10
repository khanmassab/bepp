<?php

namespace App\Http\Controllers\admin;

use App\Models\Artical;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ArticalController extends Controller
{
    use ImageTrait;
    public function index()
    {
        $articals = Artical::orderBy('id','DESC')->paginate(10);
        return view('admin.Articals.index',get_defined_vars());
    }
    public function create()
    {
        return view('admin.Articals.create');
    }
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'title'          => 'required',
            'description'    => 'required',
            'media'             => 'required',
        ]);
       
    
        if($validatedData->fails()) {
            return back()->with('error', $validatedData->errors()->first());
        }
    
          $createArtical = Artical::create($request->all());

          if($request->hasFile('media'))
          {
            $media_file = $request->file('media');
          
            $filename = time() . '_' . $media_file->getClientOriginalName(); 
            $path = basename($media_file->storeAs('public/artical', $filename));
            $createArtical->media =$path;
            $createArtical->save();
          }

          return redirect()->route('get.articals')->with('message','Successfully created');
    }

    public function edit($id)
    {
        $id = decrypt($id);
    
        $artical = Artical::where('id',$id)->first(); 
        return view('admin.Articals.edit',get_defined_vars());
    }

    public function update(Request $request,$id)
    {
        $id = decrypt($request->id);

        $artical = Artical::where('id',$id)->first();
        $artical->update($request->all());
        if($request->hasFile('media'))
        {
            $this->removeImage('public/artical/'.$artical->media);
          $media_file = $request->file('media');
        
          $filename = time() . '_' . $media_file->getClientOriginalName(); 
          $path = basename($media_file->storeAs('public/artical/', $filename));
          $artical->media =$path;
          $artical->save();
        }

        return redirect()->route('get.articals')->with('message','Successfully updated');
       
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        
        $artical = Artical::where('id',$id)->first();
        if($artical->media)
        {
            $this->removeImage('public/artical/'.$artical->media);
        }
        if($artical)
        {

            $artical->delete();

            return back()->with('message','Succefully deleted');

        }
        else
        {
            return back()->with('error','This artical not found');  
        }

    }
}
