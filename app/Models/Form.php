<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    public function createForm($dataArray)
    {
        return $dataArray;
        // try {
        //     $data = $this->create($dataArray);
        //     $data->school_type_id = $data->id;
        //     unset($data->id);
        //     return $data;
        // } catch (\Exception $e) {
        //     return false;
        // }
    }
}

