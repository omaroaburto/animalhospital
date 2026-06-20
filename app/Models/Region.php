<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use SoftDeletes;
    protected $fillable = ["name"];
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
