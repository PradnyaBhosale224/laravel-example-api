<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RestModel extends Model
{
    use HasFactory;

    /**
     * Format the created_at timestamp attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        if(!$value) return '';
        return \Carbon\Carbon::parse($value)->format($this->dateFormat);
    }

    /**
     * Format the updated_at timestamp attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        if(!$value) return '';
        return \Carbon\Carbon::parse($value)->format($this->dateFormat);
    }

    public function getEntityNameQuery()
    {
        return DB::raw("(CASE 
            WHEN entities.name = 'School' THEN schools_main.school_name
            WHEN entities.name = 'Company' THEN companies_main.name
            WHEN entities.name = 'Student' THEN CONCAT(students_main.first_name,' ',students_main.last_name)
            WHEN entities.name = 'Content' THEN contents_main.title
            WHEN entities.name = 'Channel' THEN channels_main.name
            ELSE NULL
        END) AS entity_name");
    }

    public function entityTableJoin($query, $joinOnColumn) {
        $query = $query->leftJoin('schools as schools_main', function ($join) use($joinOnColumn) {
            $join->on('entities.name', '=', DB::raw("'School'"))
                ->on("$joinOnColumn", '=', 'schools_main.id');
        })
        ->leftJoin('companies as companies_main', function ($join) use($joinOnColumn) {
            $join->on('entities.name', '=', DB::raw("'Company'"))
                ->on("$joinOnColumn", '=', 'companies_main.id');
        })
        ->leftJoin('students as students_main', function ($join) use($joinOnColumn) {
            $join->on('entities.name', '=', DB::raw("'Student'"))
                ->on("$joinOnColumn", '=', 'students_main.id');
        })
        ->leftJoin('channels as channels_main', function ($join) use($joinOnColumn) {
            $join->on('entities.name', '=', DB::raw("'Channel'"))
                ->on("$joinOnColumn", '=', 'channels_main.id');
        })
        ->leftJoin('contents as contents_main', function ($join) use($joinOnColumn) {
            $join->on('entities.name', '=', DB::raw("'Content'"))
                ->on("$joinOnColumn", '=', 'contents_main.id');
        });

        return $query;
    }

    
    public function getEntityName()
    {
        return DB::raw("(CASE 
            WHEN entities.name = 'School' THEN schools.school_name
            WHEN entities.name = 'Company' THEN companies.name
            WHEN entities.name = 'Student' THEN CONCAT(students_main.first_name,' ',students_main.last_name)
            WHEN entities.name = 'Content' THEN contents_main.title
            WHEN entities.name = 'Channel' THEN channels.name
            ELSE NULL
        END) AS entity_name");
    }
}
