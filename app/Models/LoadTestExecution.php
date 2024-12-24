<?php

namespace App\Models;

use App\Enum\ExecutionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoadTestExecution extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\LoadTest, $this>
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(LoadTest::class);
    }

    protected function casts(): array
    {
        return [
            'executed_at' => 'datetime',
            'status' => ExecutionStatusEnum::class,
        ];
    }

}
