<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cars extends Model
{
    protected $guarded = [];
    protected $table = 'cars';
    protected $fillable = ['category_id', 'brand', 'model', 'license_plate', 'year', 'description', 'price_per_day', 'status'];

    public function category()
    {
        return $this->belongsTo(car_categories::class, 'category_id');
    }

    public function categories()
    {
        return $this->belongsToMany(car_categories::class, 'car_category_car', 'car_id', 'car_category_id');
    }


    public function photos()
    {
        return $this->hasMany(car_photos::class, 'car_id');
    }

    public function getThumbnailAttribute()
    {
        return $this->photos()->first()?->file_path;
    }

    public function booking()
    {
        return $this->hasMany(bookings::class, 'car_id');
    }

    public function rentals()
    {
        return $this->hasManyThrough(
            rentals::class,
            bookings::class,
            'car_id',  
            'booking_id', 
            'id',     
            'id'      
        );
    }



    public function ratings()
    {
        return $this->hasMany(rating::class, 'cars_id');
    }

    public function damages()
    {
        return $this->hasMany(damages::class, 'car_id');
    }

    //     public function show($id)
    // {
    //     $car = cars::with(['category', 'photos', 'ratings'])->find($id);

    //     if (!$car) {
    //         return response()->json(['message' => 'Car not found'], 404);
    //     }

    //     return response()->json([
    //         'id' => $car->id,
    //         'category_id' => $car->category_id,
    //         'brand' => $car->brand,
    //         'model' => $car->model,
    //         'license_plate' => $car->license_plate,
    //         'year' => $car->year,
    //         'description' => $car->description,
    //         'price_per_day' => $car->price_per_day,
    //         'status' => $car->status,
    //         'category' => [
    //             'id' => $car->category->id,
    //             'name' => $car->category->name,
    //             'thumbnail' => $car->thumbnail ? asset('storage/' . $car->thumbnail) : null,
    //         ],
    //         'features' => ['Bluetooth', 'Aux', 'Auto'], // kamu bisa ganti dari kolom asli
    //         'average_rating' => round($car->ratings->avg('rating') ?? 4.7, 1),
    //         'owner_name' => 'AP Pemilik mobil ADITYA EKO',
    //         'join_date' => 'Sep 2022',
    //         'owner_location' => 'Jakarta Utara → Bandung',
    //     ]);
    // }
}
