<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoadTest extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Api, \App\Models\LoadTest>
     */
    public function url(): BelongsTo
    {
        return $this->belongsTo(Api::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\LoadTestExecution>
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
