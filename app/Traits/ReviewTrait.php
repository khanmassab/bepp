<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ReviewTrait {

    public function rviewObject($value)
    {
        $value->getCollection()->transform(function ($item) {
            $item->setRelation('reviews', (object) $item->reviews->first());
            return json_decode($item);
        });
    }

    public function encodeObject($value)
    {
        if (is_array($value) || $value instanceof Countable) {
            if (count($value) > 0) {
                $value->setRelation('reviews', (object) $value->reviews->first());
            }
        }

        $json = json_encode($value);
        return $json;
    }
}
