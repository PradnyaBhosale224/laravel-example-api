<?php

namespace App\Models;
use App\Models\RestModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentForm extends RestModel
{
    //
    use HasFactory;

    protected $table = "student_forms";
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = [
        "fname",
        "lname",
        "is_active",
        "created_at",
        "updated_at"
    ];

    public function createForm($dataArray)
    {
        // return "Hi";
        // return $dataArray;
        try {
            $data = $this->create($dataArray);
            $data->form_id = $data->id;
            unset($data->id);
            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function fetchForm($formId = null)
    {
        try {
            if ($formId) {
                $data = $this->select(['id as student_form_id', 'fname','lname', 'is_active'])->where('is_active', 1)->where('id', $formId)->get();;
            } else {
                $data = $this->select(['id as student_form_id', 'fname', 'lname','is_active'])->where('is_active', 1)->get();;
            }
            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateForm($dataArray, $isDelete)
    {
        // return $dataArray;
        try {
            $dataArray['id'] = $dataArray['form_id'];
            unset($dataArray['form_id']);
            if ($isDelete) {
                $data = $this->where('id', $dataArray['id'])->update(['is_active' => 0]);
            } else {
                $data = $this->where('id', $dataArray['id'])->update($dataArray);
            }
            $data = $this->find($dataArray['id']);
            $data->form_id = $data->id;
            unset($data->id);
            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }
}
