<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TellerCategory extends Model
{
    protected $fillable = [
        'name',
        'prefix',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'type');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'category_id');
    }
}
