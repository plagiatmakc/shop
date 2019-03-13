<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\User;

class ClientUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'phone' => ['required', 'phone:AUTO',
                Rule::unique('users')->ignore($this->id),
            ],
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->id),
            ],
            'avatar' => 'nullable|mimes:jpeg,bmp,png'
        ];
    }
}
