<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:6'],
            'active' => ['required', 'boolean'],
            'locale' => ['required', Rule::in(User::LOCALES)],
            'roles' => 'array', // Додаємо валідацію для ролей
            'roles.*' => 'exists:roles,id', // Переконуємося, що передані ролі існують
        ];
    }
}
