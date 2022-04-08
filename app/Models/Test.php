<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Test extends Model
{
    protected $guarded = [];

    public function url(): ?BelongsTo
    {
        return $this->belongsTo(Url::class);
    }

}