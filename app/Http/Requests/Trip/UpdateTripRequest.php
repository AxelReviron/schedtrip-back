<?php

namespace App\Http\Requests\Trip;

use App\Models\Trip;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $trip = Trip::find($this->route('id'));
        return $this->user()->can('update', $trip);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'label' => 'nullable|string',
            'description' => 'nullable|string',
            'distance' => 'nullable|integer',
            'duration' => 'nullable|integer',
            'is_public' => 'nullable|boolean',
            'geojson' => 'nullable|json',
            'author_id' => 'prohibited',
            'author' => 'prohibited'
        ];
    }
}
