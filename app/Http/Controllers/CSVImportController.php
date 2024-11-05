<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CSVImportController extends Controller
{
    public function importCSV(Request $request)
    {
        // Validate that a file is provided and is a CSV
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // Retrieve the uploaded file
        $file = $request->file('file');
       
        // Open and read the file
        $path = $file->getRealPath();
        $csvData = array_map('str_getcsv', file($path));
        
        // Process the CSV rows (assuming the first row contains headers)
        $header = $csvData[0];
        unset($csvData[0]); // Remove header row
        $productModel = new Product();
        foreach ($csvData as $row) {
            $rowData = array_combine($header, $row);

            $dataArray['name']= $rowData['name'];
            $dataArray['price']= $rowData['price'];
            $dataArray['quantity']= $rowData['quantity'];

            $form = $productModel->importData($dataArray);
            // Insert or update the database
            // Product::updateOrCreate(
            //     ['name' => $rowData['name']], // Match by product name
            //     [
            //         'price' => $rowData['price'],
            //         'quantity' => $rowData['quantity'],
            //     ]
            // );
        }

        return response()->json([
            'code' => 1,
            'message' => 'CSV data imported successfully.',
        ], 200);
    }
}
