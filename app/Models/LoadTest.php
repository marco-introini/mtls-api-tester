<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoadTest extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Api, $this>
     */
    public function api(): BelongsTo
    {
        return $this->belongsTo(Api::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\LoadTestExecution, $this>
     */
    public function executions(): HasMany
    {
        return $this->hasMany(LoadTestExecution::class);
    }

    protected function casts(): array
    {
        return [
            'failure_responses' => 'array',
        ];
    }
}
