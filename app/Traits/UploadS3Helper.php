<?php

namespace App\Traits;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

trait UploadS3Helper
{
    public function uploadDocument($file)
    {
        // print_r($file);
        $s3 = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);
        // echo "hi";
        // print_r($s3);
        $document_bucket = env('AWS_BUCKET');
        // $document_folder = trim(env('AWS_DOCUMENT_FOLDER'), '/'); 
      
        
        try {
            $result = $s3->putObject([
                'Bucket' => $document_bucket,
                'Key'    => $file->getClientOriginalName(),
                // 'Key'    => $document_folder . '/' . $file->getClientOriginalName(),
                'SourceFile' => $file->path(),
                'ContentType' => $file->getMimeType()
                // 'ContentDisposition' => 'inline' 
            ]);
           
            return $result["ObjectURL"];
        } catch (\AwsException $e) {
            echo "Document Upload Failed: " . $e->getAwsErrorMessage();
            return false;
        }
    }
}
