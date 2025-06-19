<?php

namespace App\Http\Requests;

use App\Models\Stop;
use App\Rules\MustBeCurrentUser;
use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $stopId = basename($this->input('stop'));
        $stop = Stop::findOrFail($stopId);

        return $this->user()->can('update', $stop->trip);
    }

    protected function prepareForValidation()
    {
        if ($this->has('user')) {
            $userIri = $this->input('user');
            $userId = basename($userIri);
            $this->merge([
                'user_id' => $userId,
            ]);
        }

        if ($this->has('stop')) {
            $stopIri = $this->input('stop');
            $stopId = basename($stopIri);
            $this->merge([
                'stop_id' => $stopId,
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
            'user_id' => ['required', 'exists:users,id', new MustBeCurrentUser()],
            'stop_id' => 'required|exists:stops,id',
            'content' => 'required|string',
        ];
    }
}
