<?php

namespace App\Models;

use App\Models\Traits\HasFlexibleDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute; // 🌟 Asegúrate de incluir este import
use Illuminate\Support\Carbon;

class Pet extends Model
{
    use HasFactory, SoftDeletes, HasFlexibleDates;
    protected $fillable = [
        "name",
        "birth_date",
        "death_date",
        "gender",
        "client_id",
        "species_id",
        "breed_id"
    ];
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function species():BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    /**
     * Mutador y Accesor inteligente para 'birth_date'
     */
    protected function birthDate(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->parseFlexibleDate($value),
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }

    /**
     * Mutador y Accesor inteligente para 'death_date'
     */
    protected function deathDate(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->parseFlexibleDate($value),
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }
}
