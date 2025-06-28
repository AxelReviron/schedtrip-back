<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'pseudo' => 'required|string|between:2,20|unique:users,pseudo',
            'email' => 'required|string|lowercase|email:strict,dns|unique:users,email',
            'password' => 'required|string|between:8,20|confirmed',
            'consent' => 'required|boolean|accepted',
        ];
    }
}
