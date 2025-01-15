<?php

namespace App\Models;

use App\Helpers\Action;
use App\Helpers\CertificationType;
use App\Helpers\SystemBase;
use App\Helpers\SystemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certification extends Model
{
    Use HasFactory;

    //Fields that can't be assigned
    protected $guarded = [];

    protected $casts = [
        self::type => CertificationType::class,
        self::action => Action::class,
        self::system_base => SystemBase::class,
        self::system_type => SystemType::class,
    ];

    public const type = 'type';

    public const action = 'action';

    public const system_base = 'system_base';
    public const system_type = 'system_type';

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
    public function components(): HasMany
    {
        return $this->hasMany(Component::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }


}
