<?php

namespace App\Http\Requests;

use App\Services\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'articul' => 'required|numeric',
            'name' => 'required|max:255',
            "description" => "required",
            'file' => 'file|mimes:jpg,jpeg,png',
        ];
    }

    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(Response::badResponse(implode(",",$validator->messages()->all())));

    }
}
