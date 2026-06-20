<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;
    protected $table = "cities";
    protected $fillable = [
        "name",
        "region_id"
    ];
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    public function region():BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}
