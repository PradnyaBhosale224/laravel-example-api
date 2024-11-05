<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    protected $fields = [
        "product_id","name", "price", "quantity"
    ];

    public function exportProducts()
    {
        // Define the headers for the CSV file
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ];

        // Use StreamedResponse to generate the CSV as a stream
        return new StreamedResponse(function () {
            // Open output stream to write CSV
            $file = fopen('php://output', 'w');

            // Write CSV headers
            fputcsv($file, ['ID', 'Name', 'Price', 'Quantity', 'Created At']);

            // Fetch data and write each row to the CSV
            $products = Product::all();
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->price,
                    $product->quantity,
                    $product->created_at,
                ]);
            }

            fclose($file); // Close the file stream
        }, 200, $headers); // Return response with CSV headers and 200 status code
    }

    public function exportProductById(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id' // Validate that media_id exists in img_uploads table
        ]);
        // Fetch the product by ID
        $product = Product::findOrFail($request->product_id);
        
        // Check if the product exists
        if (!$product) {
            return response()->json([
                'code' => 0,
                'message' => 'Product not found.',
            ], 404);
        }

        $product_id = $request->product_id;
       
        // Define headers for CSV file download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="product_' . $product_id . '.csv"',
        ];
        
        // Create a streamed response for CSV output
        return new StreamedResponse(function () use ($product) {
            $file = fopen('php://output', 'w');

            // Write CSV headers (column names)
            fputcsv($file, ['ID', 'Name', 'Price', 'Quantity', 'Created At', 'Updated At']);

            // Write the product data as a row in the CSV
            fputcsv($file, [
                $product->id,
                $product->name,
                $product->price,
                $product->quantity,
                $product->created_at,
                $product->updated_at,
            ]);

            fclose($file); // Close the file stream
        }, 200, $headers); // Return response with CSV headers and 200 status code
    }
}
