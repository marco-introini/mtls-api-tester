<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certificate extends Model
{
    protected $guarded = [];

    public function urls(): ?HasMany
    {
        return $this->hasMany(Api::class);
    }
}
