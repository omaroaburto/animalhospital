<?php

namespace App\Http\Requests;
use App\Http\Requests\Traits\SpeciesTrait;


class StoreSpeciesRequest extends ApiFormRequest
{
    use SpeciesTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->speciesRules();
    }
    public function messages():array
    {
        return $this->speciesMessages();
    }
}
