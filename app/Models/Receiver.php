<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'city', 'mobile', 'lat', 'lon'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
