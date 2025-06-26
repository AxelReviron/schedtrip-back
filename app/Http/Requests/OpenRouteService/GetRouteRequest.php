<?php

namespace App\Http\Requests\OpenRouteService;

use App\Rules\Latitude;
use App\Rules\Longitude;
use Illuminate\Foundation\Http\FormRequest;

class GetRouteRequest extends FormRequest
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
            'coordinates' => 'required|array|min:2', // should have at least two coordinates
            'coordinates.*' => 'required|array|size:2',// Should be a pair
            'coordinates.*.0' => ['required', new Longitude()],
            'coordinates.*.1' => ['required', new Latitude()],
        ];
    }
}
