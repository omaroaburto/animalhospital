<?php
namespace App\Http\Requests\Traits;
trait SpeciesTrait{

    public function speciesRules():array
    {
        return [
            'name' => 'required|string|min:2|max:190|regex:/^[\pL\s\-]+$/u'
        ];
    }

    public function speciesMessages():array
    {
        return[
            'name.required' => 'El nombre de la especie es un campo obligatorio',
            'name.string' => 'Por favor, ingresa un formato de texto válido para el nombre de la especie',
            'name.min' => 'El nombre de la especie debe tener al menos 2 letras.',
            'name.max' => 'El nombre de la especie no puede tener más de 100 letras.',
            'name.regex' => 'Por favor, escribe el nombre usando solo letras. No incluyas números ni símbolos especiales'
        ];
    }
}

