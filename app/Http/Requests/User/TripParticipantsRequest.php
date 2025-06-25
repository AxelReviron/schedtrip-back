<?php

namespace App\Http\Requests\User;

use App\Models\Trip;
use Illuminate\Foundation\Http\FormRequest;

class TripParticipantsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $trip = Trip::find($this->route('id'));
        return $this->user()->can('manageParticipants', $trip);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'participants' => ['required', 'array', 'min:1'],
            'participants.*.user_id' => ['required', 'uuid', 'exists:users,id'],
        ];

        if (in_array($this->method(), ['POST', 'PATCH'])) {
            $rules['participants.*.permission'] = ['required', 'in:view,update'];
        }

        return $rules;
    }
}
