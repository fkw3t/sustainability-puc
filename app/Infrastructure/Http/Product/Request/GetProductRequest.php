<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Product\Request;

use Hyperf\Validation\Request\FormRequest;

class GetProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'searchable_field' => 'required|in:barcode,name',
            'value'            => 'required',
        ];
    }
}
