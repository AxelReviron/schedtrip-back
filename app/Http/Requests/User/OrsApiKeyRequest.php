<?php

namespace App\Http\Requests\User;

use App\Services\OpenRouteService\Client as OpenRouteServiceClient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrsApiKeyRequest extends FormRequest
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
            'ors_api_key' => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $apiKey = $this->input('ors_api_key');

            $orsClient = new OpenRouteServiceClient($apiKey);

            try {
                $orsClient->testApiKey();
            } catch (RequestException $e) {
                if ($e->response->status() === ResponseAlias::HTTP_FORBIDDEN) {
                    $validator->errors()->add(
                        'ors_api_key',
                        'Invalid API Key.'
                    );
                } else {
                    $validator->errors()->add(
                        'ors_api_key',
                        'Can\'t validate API key, OpenRouteService return an error.'
                    );
                }
            }
        });
    }
}
