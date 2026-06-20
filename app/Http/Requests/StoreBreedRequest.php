<?php

namespace App\Http\Requests;
use App\Http\Requests\Traits\BreedTrait;

class StoreBreedRequest extends ApiFormRequest
{

    use BreedTrait;
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return $this->breedRules();
    }

    public function messages(): array
    {
        return $this->breedMessages();
    }
}
