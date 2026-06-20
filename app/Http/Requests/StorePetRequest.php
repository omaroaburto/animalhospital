<?php

namespace App\Http\Requests;
use App\Http\Requests\Traits\PetTrait;

class StorePetRequest extends ApiFormRequest
{
    use PetTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->petRules();
    }
    public function messages():array
    {
        return $this->petMessages();
    }
}
