<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\User\Request;

use Hyperf\Validation\Request\FormRequest;

class AuthenticateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'     => 'required|email',
            'password'  => 'required|min:5|max:32',
        ];
    }
}
