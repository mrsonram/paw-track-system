<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapLocation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = "map_locations";
    protected $fillable = ["name", "lat", "lng"];
    protected $primaryKey = "id";
}
