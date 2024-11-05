<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImgUpload extends Model
{
    use HasFactory;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = [
        "description",
        "media_path",
        "format",
        "extension",
        "is_active"
    ];

    protected $hidden = [ "created_at" , "updated_at" ];

    public function updateFileS3($dataArray)
    {
        try {
            $newRecord = $this->create($dataArray);
            $newRecord['media_id'] = $newRecord['id'];
            unset($newRecord['id']);
            return $newRecord;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function fetchMedia($mediaId)
    {
        try {
            // echo $mediaId;
            $data = null;
            if ($mediaId) {
                $data = $this->select([...$this->fillable, 'id as media_id'])->where('id', $mediaId)->where('is_active', 1)->get();
            }
            // print_r($data);
            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateMedia($dataArray, $isDelete)
    {
        try {
            $dataArray['id'] = $dataArray['media_id'];
            unset($dataArray['media_id']);
            if ($isDelete) {
                $data = $this->where('id', $dataArray['id'])->update(['is_active' => 0]);
            } else {
                $data = $this->where('id', $dataArray['id'])->update($dataArray);
            }
            $data = $this->find($dataArray['id']);
            $data['media_id'] = $data['id'];
            unset($data['id']);
            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }
}
