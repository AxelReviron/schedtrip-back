<?php

namespace App\Http\Requests\Item;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Item::class);
    }

    protected function prepareForValidation()
    {
        if ($this->has('luggage')) {
            $luggageIri = $this->input('luggage');
            $luggageId = basename($luggageIri);
            $this->merge([
                'luggage_id' => $luggageId,
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
            'quantity' => 'required|numeric|min:1',
            'luggage_id' => 'required|exists:luggage,id',
        ];
    }
}
