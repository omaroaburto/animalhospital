<?php

namespace App\Http\Requests;
use App\Http\Requests\Traits\ClientTrait;

class UpdateClientRequest extends ApiFormRequest
{
    use ClientTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->clientRules();
    }

    public function messages(): array
    {
        return $this->clientMessages();
    }
}
