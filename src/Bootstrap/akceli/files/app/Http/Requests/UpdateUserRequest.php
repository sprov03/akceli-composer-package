<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Documentation: https://laravel.com/docs/6.x/validation#available-validation-rules
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'unique:users,email'],
            'email_verified_at' => ['nullable', 'date'],
            'password' => ['required', 'max:255'],
            'remember_token' => ['nullable', 'max:100'],
            'created_at' => ['nullable', 'date'],
            'updated_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
//            'title.required' => 'A title is required',
//            'body.required'  => 'A message is required',
        ];
    }
}
