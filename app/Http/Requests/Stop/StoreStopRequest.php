<?php

namespace App\Http\Requests\Stop;

use App\Models\Trip;
use Illuminate\Foundation\Http\FormRequest;

class StoreStopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $tripId = basename($this->input('trip'));
        $trip = Trip::findOrFail($tripId);

        return $this->user()->can('update', $trip);
    }

    protected function prepareForValidation()
    {
        if ($this->has('trip')) {
            $tripIri = $this->input('trip');
            $tripId = basename($tripIri);
            $this->merge([
                'trip_id' => $tripId,
            ]);
        }
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
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'arrivalDate' => 'nullable|date',
            'departureDate' => 'nullable|date',
            'orderIndex' => 'nullable|integer|min:0',
            'trip_id' => ['required', 'exists:trips,id']
        ];
    }
}
