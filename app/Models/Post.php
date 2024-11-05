<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    public function createForm($dataArray)
    {
        try {
            $data = $this->create($dataArray);
            // $data->id = $data->id;
            // unset($data->id);
            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }
}
