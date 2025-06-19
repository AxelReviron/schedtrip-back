<?php

namespace App\Http\Requests;

use App\Models\Stop;
use App\Models\Trip;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $stop = Stop::find($this->route('id'));
        return $this->user()->can('update', $stop);
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
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'arrivalDate' => 'nullable|date',
            'departureDate' => 'nullable|date',
            'orderIndex' => 'nullable|integer|min:0',
            'trip_id' => 'prohibited'
        ];
    }
}
