<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'contacts';
    protected $fillable = ['name', 'email', 'title', 'message'];
    protected $primaryKey = 'id';
    protected $hidden;
}
