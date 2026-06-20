<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "first_name",
        "paternal_name",
        "maternal_name", 
        "phone",
        "rut",
        "street",
        "street_number",
        "apartment_number",
        "city_id",
        'user_id'
    ];
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
