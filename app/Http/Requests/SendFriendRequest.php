<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class SendFriendRequest extends FormRequest
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
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $targetUserId = $this->input('user_id');
            $connectedUserId = Auth::user()->getKey();

            if ($targetUserId === $connectedUserId) {
                $validator->errors()->add('user_id', 'You cannot send a friend request to yourself.');
            }

            $existing = DB::table('user_friend')
                ->where('user_id', $targetUserId)
                ->where('friend_id', $connectedUserId)
                ->first();

            if ($existing) {
                switch ($existing->status) {
                    case 'blocked':
                        $validator->errors()->add('user_id', 'The selected user id is invalid.');
                        break;

                    case 'accepted':
                        $validator->errors()->add('user_id', 'You are already friends with this user.');
                        break;

                    case 'pending':
                        $validator->errors()->add('user_id', 'A friend request is already pending.');
                        break;

                    case 'rejected':
                        // User can resend a friend request
                        break;

                    default:
                        $validator->errors()->add('user_id', 'Cannot send a friend request.');
                        break;
                }
            }
        });
    }

    public function canResend(): bool
    {
        $targetUserId = $this->input('user_id');
        $currentUserId = Auth::user()->getKey();

        $existing = DB::table('user_friend')
            ->where('user_id', $targetUserId)
            ->where('friend_id', $currentUserId)
            ->first();

        return $existing && $existing->status === 'rejected';
    }
}
