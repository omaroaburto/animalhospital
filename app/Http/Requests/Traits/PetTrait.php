<?php
namespace App\Http\Requests\Traits;
use Illuminate\Validation\Rule;
use App\Enums\Gender;

trait PetTrait{
    public function petRules(): array
    {
        $rules = [
            // Nombre de mascota: solo letras y espacios (por si se llama "Max II" o "Luna Maria")
            'name'       => ['required', 'string', 'min:2', 'max:100', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ\s]+$/u'],

            // Género: Valida contra el Enum de PHP
            'gender'     => ['required', Rule::enum(Gender::class)],

            // Fecha de nacimiento: obligatoria, formato fecha y no puede ser futura
            'birth_date' => ['required', 'date', 'before_or_equal:today'],

            // Fecha de muerte: opcional, formato fecha, no futura y mayor o igual a la de nacimiento
            'death_date' => ['nullable', 'date', 'before_or_equal:today', 'after_or_equal:birth_date'],

            'client_id'  => ['required', 'exists:clients,id'],
            'species_id' => ['required', 'exists:species,id'],
            'breed_id'   => ['nullable', 'exists:breeds,id'], // Puede ser nulo
        ];

        // TRANSFORMAR DE 'required' A 'sometimes' SI LA PETICIÓN ES PATCH
        if (request()->isMethod('patch')) {
            $rules = collect($rules)->map(function ($rule) {
                if (is_array($rule)) {
                    // Filtra de forma segura eliminando 'required' sin romper si hay objetos (como Rule::enum)
                    return collect($rule)
                        ->reject(fn ($value) => $value === 'required')
                        ->prepend('sometimes')
                        ->all();
                }

                if (is_string($rule)) {
                    return str_replace('required', 'sometimes', $rule);
                }

                return $rule;
            })->all();
        }

        return $rules;
    }

    public function petMessages(): array
    {
        return [
            // --- NOMBRE ---
            'name.required' => 'El nombre de la mascota es un campo obligatorio.',
            'name.string'   => 'Por favor, ingresa un formato de texto válido para el nombre de la mascota.',
            'name.min'      => 'El nombre de la mascota debe tener al menos 2 letras.',
            'name.max'      => 'El nombre de la mascota no puede tener más de 100 letras.',
            'name.regex'    => 'Por favor, escribe el nombre usando solo letras. No incluyas números ni símbolos especiales.',

            // --- GÉNERO ---
            'gender.required' => 'El género de la mascota es un campo obligatorio.',
            'gender.enum'     => 'Por favor, selecciona un género válido de la lista.',

            // --- FECHAS ---
            'birth_date.required'        => 'La fecha de nacimiento es un campo obligatorio.',
            'birth_date.date'            => 'Por favor, ingresa una fecha de nacimiento válida.',
            'birth_date.before_or_equal' => 'La fecha de nacimiento no puede ser una fecha futura.',

            'death_date.date'            => 'Por favor, ingresa una fecha de fallecimiento válida.',
            'death_date.before_or_equal' => 'La fecha de fallecimiento no puede ser una fecha futura.',
            'death_date.after_or_equal'  => 'La fecha de fallecimiento no puede ser menor que la fecha de nacimiento de la mascota.',

            // --- RELACIONES ---
            'client_id.required'  => 'El dueño o cliente es un campo obligatorio.',
            'client_id.exists'    => 'El cliente seleccionado no es válido o no existe en nuestros registros.',

            'species_id.required' => 'La especie es un campo obligatorio.',
            'species_id.exists'   => 'La especie seleccionada no es válida o no existe en nuestros registros.',

            'breed_id.exists'     => 'La raza seleccionada no es válida o no existe en nuestros registros.',
        ];
    }
}
