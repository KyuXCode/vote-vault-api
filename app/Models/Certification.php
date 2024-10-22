<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    Use HasFactory;

    //Fields that can't be assigned
    protected $guarded = [];
}
