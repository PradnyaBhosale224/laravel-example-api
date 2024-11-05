<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Table name (optional, if the table name doesn't follow Laravel's convention)
    protected $table = 'products';

    // Allow mass assignment on these fields
    protected $fillable = [
        'name',
        'price',
        'quantity',
    ];

    public function importData($dataArray)
    {
        try {
            $data = $this->create($dataArray);
            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }
}
