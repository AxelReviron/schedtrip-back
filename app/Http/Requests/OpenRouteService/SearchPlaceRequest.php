<?php

namespace App\Http\Requests\OpenRouteService;

use Illuminate\Foundation\Http\FormRequest;

class SearchPlaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Add route parameter to validation
     *
     * @return array
     */
    public function validationData(): array
    {
        return array_merge($this->all(), $this->route()->parameters());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'place' => 'required|string',
        ];
    }
}
