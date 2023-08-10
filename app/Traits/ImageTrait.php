<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageTrait {

    public function removeImage($path)
{  
  if(Storage::exists($path)){
    Storage::delete($path);
  }
}

}