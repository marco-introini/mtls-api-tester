<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Test extends Model
{
    protected $guarded = [];

    public function api(): ?BelongsTo
    {
        return $this->belongsTo(Api::class);
    }

    protected function casts(): array
    {
        return [
            'request_headers' => 'array',
            'request_date' => 'datetime',
            'response_time' => 'datetime',
        ];
    }
}
