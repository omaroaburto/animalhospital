<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\SpeciesTrait;
use Illuminate\Foundation\Http\FormRequest;


class UpdateSpeciesRequest extends FormRequest
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
