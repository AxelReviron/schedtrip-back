<?php

namespace App\Http\Requests\Trip;

use App\Models\Trip;
use App\Rules\MustBeCurrentUser;
use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Trip::class);
    }

    protected function prepareForValidation()
    {
        if ($this->has('author')) {
            $authorIri = $this->input('author');
            $authorId = basename($authorIri);
            $this->merge([
                'author_id' => $authorId,
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
            'distance' => 'nullable|numeric',
            'duration' => 'nullable|numeric',
            'is_public' => 'boolean',
            'geojson' => 'nullable|json',
            'author_id' => ['required', 'exists:users,id', new MustBeCurrentUser()]
        ];
    }
}
