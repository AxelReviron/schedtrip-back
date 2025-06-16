<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->trip);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'label' => 'required|string',
            'description' => 'nullable|string',
            'distance' => 'nullable|integer',
            'duration' => 'nullable|integer',
            'is_public' => 'boolean',
            'geojson' => 'nullable|json',
            'author_id' => 'exists:App\Models\User,id'
        ];
    }
}
