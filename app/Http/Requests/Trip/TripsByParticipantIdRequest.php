<?php

namespace App\Http\Requests\Trip;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class TripsByParticipantIdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = User::findOrFail($this->route('participantId'));
        return $this->user()->can('view', $user);
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
            'participantId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
