<?php

namespace App\Http\Controllers;

use App\Models\ImgUpload;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Traits\UploadS3Helper;

class ImgUploadController extends Controller
{
    use UploadS3Helper;

    protected $fields = [
        "description", "media_path", "format", "extension"
    ];

    //storing img on local folder
    public function uploadImage(Request $request)
    {
        $dataArray = $request->only(['image','description']);
        // Validate the incoming request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // 2MB max size
        ]);

        // Define the storage directory for images
        $directory = 'uploads/images';

        // Store the image and retrieve its path
        $path = $request->file('image')->store($directory, 'public'); // Stores in 'storage/app/public/uploads/images'
        if ($path) 
        {
            $imgUpload = new ImgUpload();
            $dataArray['extension'] = $request->file('image')->extension();
            $dataArray['format'] = $request->file('image')->getMimeType();
            $dataArray['media_path'] = $path;
            $dataArray['description'] = $dataArray['description']??$request->file('image')->getClientOriginalName();
            $file = $imgUpload->updateFileS3($dataArray);
            if ($file) {
                $response["code"] = 1;
                $response["message"] = "File uploaded successfully.";
                $response["result"] = $file;
                // return $this->sendResponse($response);
                return $response;
            } else {
                $response["code"] = 0;
                $response["message"] = "Failed to upload file.";
                $response["result"] = $file;
                // return $this->sendResponse($response);
                return $response;
            }
        }
        else {
            $response["code"] = 0;
            $response["message"] = "Failed to upload file: " . $path['description'];
            // return $this->sendResponse($response);
            return $response;
        }
    }

     // Update file method
     public function updateImage(Request $request)
     {
        
        $request->validate([
            'media_id' => 'required|exists:img_uploads,id', // Validate that media_id exists in img_uploads table
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // New file
        ]);
        
         $fileModel = ImgUpload::findOrFail($request->media_id);
         
         // Delete the old file
         if (Storage::disk('public')->exists($fileModel->media_path)) {
            Storage::disk('public')->delete($fileModel->media_path);
        }
 
         // Store the new file
        $newFile = $request->file('file');
        $newFilePath = $newFile->store('uploads/images', 'public'); // Store in 'uploads/images'
        
        // Update database record
        $fileModel->description = $newFile->getClientOriginalName();
        $fileModel->media_path = $newFilePath;
        $fileModel->format = $newFile->getMimeType();
        $fileModel->extension =$newFile->extension();
        $fileModel->save();
    
        // Response after update
        return response()->json([
            'code' => 1,
            'message' => 'File updated successfully.',
            'file' => [
                'id' => $fileModel->id,
                'name' => $fileModel->name,
                'path' => $newFilePath,
                'mime_type' => $fileModel->format,
                'size' => $fileModel->size,
            ],
        ]);
     }

    public function uploadFileS3(Request $request)
    {
        try{
            $response = [];
            $dataArray = $request->only(['media','description']);
            $validator = Validator::make($dataArray, [
                'media' => 'required|file',
                'description' => 'string'
            ]);

            if ($validator->fails()) {
                $response["code"] = 0;
                $response["message"] = "Please provide a valid file";
                $response["result"] = $validator->errors();
                return $this->sendResponse($response);
            }      
            // Upload media document to S3
            $path = $this->uploadDocument($request->file('media'));     
            // print_r($path);
            // If upload successful, save media data to database
            if ($path) {
                $imgUpload = new ImgUpload();
                $dataArray['extension'] = $request->file('media')->extension();
                $dataArray['format'] = $request->file('media')->getMimeType();
                $dataArray['media_path'] = $path;
                $dataArray['description'] = $dataArray['description']??$request->file('media')->getClientOriginalName();
                $file = $imgUpload->updateFileS3($dataArray);
                if ($file) {
                    $response["code"] = 1;
                    $response["message"] = "File uploaded successfully.";
                    $response["result"] = $file;
                    // return $this->sendResponse($response);
                    return $response;
                } else {
                    $response["code"] = 0;
                    $response["message"] = "Failed to upload file.";
                    $response["result"] = $file;
                    // return $this->sendResponse($response);
                    return $response;
                }
            } else {
                $response["code"] = 0;
                $response["message"] = "Failed to upload file to S3: " . $path['description'];
                // return $this->sendResponse($response);
                return $response;
            }
        }catch(\Exception $e){ echo $e->getMessage();}
    }

    public function fetchFileS3(Request $request)
    {
        $response = [];
        $dataArray = $request->only(['media_id']);
        // print_r($dataArray);
        // $validator = Validator::make($dataArray, [
        //     'media_id' => ['required', $this->exists('img_uploads', 'id', ['is_Active' => 1])]
        // ]);
        $validator = Validator::make($dataArray, [
            'media_id' => [
                'required',
                'exists:img_uploads,id', // Checks if media_id exists in img_uploads table
                function ($attribute, $value, $fail) {
                    if (!ImgUpload::where('id', $value)->where('is_Active', 1)->exists()) {
                        $fail('The selected media_id is invalid or inactive.');
                    }
                },
            ]
        ]);
        // print_r($validator);
        if ($validator->fails()) {
            $response["code"] = 0;
            $response["message"] = "Please provide valid inputs.";
            $response["result"] = $validator->errors();
            return response()->json($response); // return JSON response
        }
        try {
            // echo "Hi";
            $mediaModel = new ImgUpload();
            $media = $mediaModel->fetchMedia($request->media_id);
            if ($media->isEmpty()) {
                $response["code"] = 1;
                $response["message"] = "Media not found";
                return  $this->sendResponse($response);
            }

            $host = env('APP_HOST');
            $media = $media[0];
            $media['media_path'] =$media['media_path'];
            // echo $media;
            $response["code"] = 1;
            $response["message"] = "Media fetched successfully.";
            $response['result'] =  $media;
            // return  $this->sendResponse($response);
            return response()->json($response);
        } catch (\Exception $e) {
            $response["code"] = 0;
            $response["message"] = $e->getMessage();
            // return $this->sendResponse($response, 500);
            return response()->json($response);
        }
    }
}