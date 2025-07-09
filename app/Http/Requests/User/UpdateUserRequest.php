<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        return $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'pseudo' => [
                'nullable', 'string', 'between:2,20', Rule::unique('users', 'pseudo')->ignore($userId),
            ],
            'email' => [
                'nullable', 'string', 'lowercase', 'email:strict,dns', Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'required|string|between:8,20|confirmed',
        ];
    }
}
