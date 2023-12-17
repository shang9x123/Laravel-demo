<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'email' =>['required','email','unique:users'],
            'password' =>['required'],
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Email must cần thiết',
            'email.email' => 'Email phải đúng định dạng',
            'email.unique:users' => 'Email đã tồn tại',
            'password.required' => 'Password must be at least',
        ];
    }


}
