<?php
namespace App\Http\Requests\Traits;

use Illuminate\Validation\Rule;

trait ClientTrait{
    public function clientRules(): array
    {
        // 1. Obtener los parámetros de la URL para ignorar los IDs en actualizaciones
        $client = request()->route('client');
        $clientId = is_object($client) ? $client->id : $client;

        // Buscamos el user_id asociado al cliente si el objeto existe
        $userId = is_object($client) ? $client->user_id : null;

        $rules = [
            // Nombres y apellidos
            'first_name'       => ['required', 'string', 'min:2', 'max:100', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ\s]+$/u'],
            'paternal_name'    => ['required', 'string', 'min:2', 'max:100', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ\s]+$/u'],
            'maternal_name'    => ['nullable', 'string', 'min:2', 'max:100', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ\s]+$/u'],

            // Campos Únicos con exclusión dinámica
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone'            => ['required', 'regex:/^9[0-9]{8}$/', Rule::unique('clients', 'phone')->ignore($clientId)],
            'rut'              => ['required', 'string', 'regex:/^(\d{1,2}(\.\d{3}){2}-[\dkK])|(\d{7,8}-[\dkK])$/', Rule::unique('clients', 'rut')->ignore($clientId)],

            // Credenciales y Dirección
            'password'         => ['required', 'string', 'min:8', 'alpha_num', 'confirmed'],
            'street'           => ['required', 'string', 'max:255'],
            'street_number'    => ['nullable', 'integer', 'min:1'],
            'apartment_number' => ['nullable', 'integer', 'min:1'],
            'city_id'          => ['required', 'exists:cities,id'],
        ];

        // TRANSFORMAR DE 'required' A 'sometimes' SI LA PETICIÓN ES DE ACTUALIZACIÓN (PUT o PATCH)
        if (request()->isMethod('patch') || request()->isMethod('put')) {
            foreach ($rules as $field => $rule) {

                // Si estamos actualizando, la contraseña no debería ser obligatoria obligatoriamente
                if ($field === 'password') {
                    $rules[$field] = ['sometimes', 'nullable', 'string', 'min:8', 'alpha_num', 'confirmed'];
                    continue;
                }

                if (is_array($rule)) {
                    $key = array_search('required', $rule);
                    if ($key !== false) {
                        $rules[$field][$key] = 'sometimes';
                    }
                }
            }
        }

        return $rules;

    }

    public function clientMessages(): array
    {
        return [
            // --- NOMBRES Y APELLIDOS ---
            'first_name.required' => 'El nombre es un campo obligatorio.',
            'first_name.string'   => 'Por favor, ingresa un formato de texto válido para el nombre.',
            'first_name.min'      => 'El nombre debe tener al menos 2 letras.',
            'first_name.max'      => 'El nombre no puede tener más de 100 letras.',
            'first_name.regex'    => 'Por favor, escribe el nombre usando solo letras. No incluyas números ni símbolos especiales.',

            'paternal_name.required' => 'El apellido paterno es un campo obligatorio.',
            'paternal_name.string'   => 'Por favor, ingresa un formato de texto válido para el apellido paterno.',
            'paternal_name.min'      => 'El apellido paterno debe tener al menos 2 letras.',
            'paternal_name.max'      => 'El apellido paterno no puede tener más de 100 letras.',
            'paternal_name.regex'    => 'Por favor, escribe el apellido paterno usando solo letras. No incluyas números ni símbolos especiales.',

            'maternal_name.string' => 'Por favor, ingresa un formato de texto válido para el apellido materno.',
            'maternal_name.min'    => 'El apellido materno debe tener al menos 2 letras.',
            'maternal_name.max'    => 'El apellido materno no puede tener más de 100 letras.',
            'maternal_name.regex'  => 'Por favor, escribe el apellido materno usando solo letras. No incluyas números ni símbolos especiales.',

            // --- CONTACTO Y IDENTIFICACIÓN ---
            'email.required' => 'El correo electrónico es un campo obligatorio.',
            'email.email'    => 'Por favor, ingresa una dirección de correo electrónico válida.',
            'email.max'      => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique'   => 'Este correo electrónico ya se encuentra registrado.',

            'phone.required' => 'El número de teléfono es un campo obligatorio.',
            'phone.regex'    => 'Por favor, ingresa un número de celular chileno válido (9 dígitos y comenzar con 9).',
            'phone.unique'   => 'Este número de teléfono ya se encuentra registrado.',

            'rut.required' => 'El RUT es un campo obligatorio.',
            'rut.string'   => 'Por favor, ingresa un formato de texto válido para el RUT.',
            'rut.regex'    => 'Por favor, ingresa un RUT válido con guion (ej: 12345678-9 o 12.345.678-K).',
            'rut.unique'   => 'Este RUT ya se encuentra registrado.',

            // --- CONTRASEÑA ---
            'password.required'  => 'La contraseña es un campo obligatorio.',
            'password.string'    => 'Por favor, ingresa un formato de texto válido para la contraseña.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.alpha_num' => 'Por favor, escribe la contraseña usando solo letras y números sin caracteres especiales.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',

            // --- DIRECCIÓN ---
            'street.required' => 'La calle de la dirección es un campo obligatorio.',
            'street.string'   => 'Por favor, ingresa un formato de texto válido para la calle.',
            'street.max'      => 'La calle no puede tener más de 255 caracteres.',

            'street_number.number'   => 'Por favor, ingresa un valor para el número de la dirección.',
            'street_number.min'      => 'El número de la dirección tiene que ser válido y mayor a 0.',

            'apartment_number.number' => 'Por favor, ingresa un ingresa un valor válido para el número de departamento o block.',
            'apartment_number.min'    => 'El número de departamento o block tiene que ser válido y mayor a 0.',

            'city_id.required' => 'La comuna o ciudad es un campo obligatorio.',
            'city_id.exists'   => 'La comuna o ciudad seleccionada no es válida o no existe en nuestros registros.',
        ];
    }
}
