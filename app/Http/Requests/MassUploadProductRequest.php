<?php

namespace App\Http\Requests;

use App\Rules\Columns;
use Illuminate\Foundation\Http\FormRequest;

class MassUploadProductRequest extends FormRequest
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
            "catalog_id" => "numeric",
            "columns" => ["required", new Columns()],
            'file' => 'file|mimes:xls,xlsx',
        ];
    }
}
