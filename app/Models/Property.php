<?php

namespace App\Models;

use App\Observers\PropertyObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Property extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use HasEagerLimit;

    protected $fillable = [
        'owner_id',
        'name',
        'city_id',
        'address_street',
        'address_postcode',
        'lat',
        'long',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Apartment::class);
    }

    public static function booted()
    {
        parent::booted();

        self::observe(PropertyObserver::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(800);
    }

    public function address(): Attribute
    {
        return new Attribute(
            get: fn () => $this->address_street
                . ', ' . $this->address_postcode
                . ', ' . $this->city->name
        );
    }
}
