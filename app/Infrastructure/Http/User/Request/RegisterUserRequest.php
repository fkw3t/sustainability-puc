<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\User\Request;

use Hyperf\Validation\Request\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|max:255',
            'document'  => 'required|digits:11',
            'email'     => 'required|email',
            'password'  => 'required|min:5|max:32',
            'birthdate' => 'required|date_format:Y-m-d',
        ];
    }
}
