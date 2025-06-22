<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FriendActionRequest extends FormRequest
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
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'status' => ['required', Rule::in(['accepted', 'rejected', 'blocked'])],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $friendId = $this->input('user_id');
            $exists = DB::table('user_friend')
                ->where('user_id', Auth::user()->getKey())
                ->where('friend_id', $friendId)
                ->where('status', 'pending')
                ->exists();

            if (!$exists) {
                $validator->errors()->add('user_id', 'No pending friend request from this user.');
            }
        });
    }
}
