<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
            'image' => 'image|mimes:jpeg,bmp,png|max:2000',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,bmp,png|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'images.*.uploaded' => ":attribute must be less 2MB",
        ];
    }
}
