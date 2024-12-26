<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_id' => 'required|string|unique:users,user_id',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|in:student,faculty',
            'dept_name' => 'required|string|in:Computer Science,IT/IS,Biology,Mathematics,Psychology'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Adamson email is required',
            'email.email' => 'Adamson Email Only',
            'email.unique' => 'This Adamson email has already been registered',
            'user_id.required' => 'Student/Employee number is required',
            'user_id.unique' => 'This Has Already Been registered',
            'password.required' => 'Password is Required',
            'password.confirmed' => 'Password does not Match',
            'password.min' => 'Minimum of 8 Characters',
            'position.required' => 'Position is required',
            'position.in' => 'Select a valid position',
            'dept_name.required' => 'Department Name is Required',
            'dept_name.in' => 'Select a Valid Department'
        ];
    }
}
