<?php

namespace App\Models;

use App\Models\Division;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{

    use HasFactory;
   
    protected $fillable = [
        'name', 'phone', 'position', 'image','division_id'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    public function getImageAttribute($value)
    {
        return env('APP_URL').Storage::url("images/".$value);
    }
     /**
     * Get the division that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
