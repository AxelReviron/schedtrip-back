<?php

namespace App\Http\Requests\OpenRouteService;

use App\Rules\Latitude;
use App\Rules\Longitude;
use Illuminate\Foundation\Http\FormRequest;

class ReverseSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lon' => ['required', new Longitude()],
            'lat' => ['required', new Latitude()],
        ];
    }
}
