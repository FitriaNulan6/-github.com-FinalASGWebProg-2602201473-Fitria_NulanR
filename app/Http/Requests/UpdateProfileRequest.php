<?php
// app/Http/Requests/UpdateProfileRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'mobile_number' => 'required|digits_between:10,15',
            'instagram' => ['required', 'string', 'regex:/^http:\/\/www\.instagram\.com\/[\w\.-]+$/'],
            'hobbies' => 'required|array|min:3',
            'hobbies.*' => 'exists:hobbies,id',
            'password' => 'nullable|min:8|confirmed',
            'profile_image' => 'nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'hobbies.min' => 'Please select at least 3 hobbies.',
            'instagram.regex' => 'Instagram username must be in format: http://www.instagram.com/username',
            'mobile_number.digits_between' => 'Mobile number must be between 10 and 15 digits.',
        ];
    }
}

