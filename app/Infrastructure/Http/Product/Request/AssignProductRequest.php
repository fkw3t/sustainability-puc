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
            'user_id'               => 'required|uuid',
            'product.barcode'       => 'required|string',
            'product.name'          => 'required|string',
            'product.brand'         => 'nullable|string',
            'product.description'   => 'nullable|string',
            'product.average_price' => 'nullable|numeric',
            'product.image_url'     => 'nullable|string',
            'expire_date'           => 'required|date_format:Y-m-d|after:today',
            'quantity'              => 'required|integer',
        ];
    }
}