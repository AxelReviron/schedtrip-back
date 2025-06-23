<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class SearchUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = User::where('pseudo', $this->pseudo)->first();
        return $user && $this->user()->can('view', $user);
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
            'pseudo' => ['required', 'string', 'exists:users,pseudo'],
        ];
    }

    public function getPseudo(): string
    {
        return $this->route('pseudo');
    }

}
