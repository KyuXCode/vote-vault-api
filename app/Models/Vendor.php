<?php

namespace App\Models;

use App\Helpers\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        self::product => ProductType::class,
    ];

    public const product = 'product';
    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class);
    }

}
