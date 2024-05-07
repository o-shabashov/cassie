<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'  => ['required'],
            'fields' => ['nullable'],
            'url'    => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
