<?php
namespace App\Services;

use App\Traits\ImageTrait;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Validator;

class AdvertisementService{

    use ImageTrait;
    public function gitAdvertisement()
    {
        $advertisements = Advertisement::orderBy('id','Desc')->paginate(10);
            return $advertisements;
       
    }

    public function storeAdvertisement($request)
    {
        $validatedData = Validator::make($request->all(), [
            'title'          => 'required',
            'description'         => 'required',
        ]);
       
    
        if($validatedData->fails()) {
            return back()->with('error', $validatedData->errors()->first());
        }
    
        $advertisement = Advertisement::create($request->all());
        if($request->file('media'))
        {  
            $media_file = $request->file('media');
          
            $mimeType = $media_file->getMimeType();
            $fileType = explode('/', $mimeType);
            $filename = time() . '_' . $media_file->getClientOriginalName(); 
            $path = basename($media_file->storeAs('public/adverisment', $filename));
            $advertisement->media =$path;
            $advertisement->type = $mimeType;
            $advertisement->save();
        }
        return $advertisement;
    }

    public function updateAdvertisement($request,$id)
    {
        $advertisement = Advertisement::where('id', $id)-> first();
        if($advertisement->media)
        {
         $this->removeImage('public/adverisment/'.$advertisement->media);
        }
        $updateAdvertisement = $advertisement->update($request->all());
        if($request->file('media'))
        {  
            $media_file = $request->file('media');
          
            $mimeType = $media_file->getMimeType();
            $fileType = explode('/', $mimeType);
            $filename = time() . '_' . $media_file->getClientOriginalName(); 
            $path = basename($media_file->storeAs('public/adverisment', $filename));
            $advertisement->media =$path;
            $advertisement->type = $mimeType;
            $advertisement->save();
        }
        return $advertisement;
    }
    

    public function destroyAdevrtisemen($id)
    {
        $advertisement = Advertisement::where('id', $id)-> first();
        if($advertisement->media)
        {
         $this->removeImage('public/adverisment/'.$advertisement->media);
        }

      return  $advertisement->delete();

       
    }
}