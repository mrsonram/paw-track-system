<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleMap extends Model
{
    use HasFactory;
    public $timestamps = false;

    //ชื่อตารางในฐานข้อมูล
    protected $table = "animals";
    //ชื่อคอลัมน์ในฐานข้อมูลที่อนุญาติให้แก้ไขข้อมูล
    protected $fillable = ["name", "location", "lat", "lng"];
    //Primary Key
    protected $primaryKey = "id";
}
