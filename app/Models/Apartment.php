<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;


    protected $fillable = [
        'property_id',
        'apartment_type_id', // [tl! ++]
        'name',
        'capacity_adults',
        'capacity_children',
        'size', // [tl! ++]
        'bathrooms'
    ];

    public function apartment_type()
    {
        return $this->belongsTo(ApartmentType::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
