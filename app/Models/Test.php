<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Test extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function api(): ?BelongsTo
    {
        return $this->belongsTo(Api::class);
    }

    protected function casts(): array
    {
        return [
            'request_raw' => 'array',
            'response_raw' => 'array',
            'request_timestamp' => 'datetime',
            'response_timestamp' => 'datetime',
        ];
    }

    public function executionTime(): Attribute
    {
        return new Attribute(
          get: fn() => $this->response_timestamp->diff($this->request_timestamp)
        );
    }
}
