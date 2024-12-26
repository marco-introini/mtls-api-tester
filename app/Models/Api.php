<?php

namespace App\Models;

use App\Enum\MethodEnum;
use App\Enum\APITypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Api extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function certificate(): ?BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function tests(): ?HasMany
    {
        return $this->hasMany(Test::class);
    }

    protected function casts(): array
    {
        return [
            'headers' => 'json',
            'method' => MethodEnum::class,
            'service_type' => APITypeEnum::class,
        ];
    }
}
