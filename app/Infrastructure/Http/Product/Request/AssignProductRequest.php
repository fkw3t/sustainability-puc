<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Product\Request;

use Hyperf\Validation\Request\FormRequest;

class AssignProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'         => 'required|uuid',
            'product_barcode' => 'required|string',
            'expire_date'     => 'required|date_format:Y-m-d',
            'quantity'        => 'required|integer',
        ];
    }
}