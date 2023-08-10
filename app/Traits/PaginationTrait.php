<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait PaginationTrait {
    
    public function pagination($value)
    {
      return  $pagination = [
            'current_page' => $value->currentPage(),
            'per_page' => $value->perPage(),
            'total' => $value->total(),
            'last_page' => $value->lastPage(),
            'last_page_url' => $value->url($value->lastPage()),
            'next_page_url' => $value->nextPageUrl(),
            'prev_page_url' => $value->previousPageUrl(),
        ];
    }
}