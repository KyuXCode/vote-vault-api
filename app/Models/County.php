<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class County extends Model
{
    Use HasFactory;

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
    protected $guarded = [];
}
