<?php
namespace App\Http\Requests\Traits;
trait BreedTrait{

    public function breedRules():array
    {
        return [
            'name' => 'required|string|min:2|max:100|regex:/^[\pL\s\-]+$/u',
            'species_id' => 'required|integer|exists:species,id',
        ];
    }

    public function breedMessages():array
    {
        return [
            'name.required' => 'Por favor, escribe el nombre de la raza.',
            'name.string'   => 'Por favor, ingresa un formato de texto válido para el nombre de la raza.',
            'name.min'      => 'El nombre de la raza debe tener al menos 2 letras.',
            'name.max'      => 'El nombre de la raza no puede tener más de 100 letras.',
            'name.regex'    => 'El nombre de la raza solo puede tener letras, espacios y guiones.',
            'species_id.required' => 'Por favor, selecciona una especie de la lista.',
            'species_id.integer'  => 'El id especie seleccionada no es válida no es numérico.',
            'species_id.exists'   => 'La especie elegida no está disponible o no existe.',
        ];
    }
}
