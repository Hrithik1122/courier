<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'city', 'mobile', 'lat', 'lon'];

    public function receivers()
    {
        return $this->hasMany(Receiver::class);
    }
}
