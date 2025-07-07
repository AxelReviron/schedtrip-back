<?php

namespace App\Http\Requests\Trip;

use App\Models\Trip;
use Illuminate\Foundation\Http\FormRequest;

class EditTripViewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $tripId = $this->route()->parameters()['tripId'];
        $trip = Trip::findOrFail($tripId);

        return $this->user()->can('update', $trip);
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
            'tripId' => ['required', 'string', 'exists:trips,id'],
        ];
    }
}
