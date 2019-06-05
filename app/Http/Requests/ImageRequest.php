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
            'image' => 'image|mimes:jpeg,bmp,png|max:2048',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,bmp,png|max:2048',
        ];
    }

    public function messages()
    {
        $validationArray = [];
        foreach ($this->file('images') as $key => $file) {
            $validationArray['images.'.$key.'.uploaded'] = 'The ' .  $file->getClientOriginalName() . ' must be less than 2048 kilobytes.';
            $validationArray['images.'.$key.'.image'] = 'The ' .  $file->getClientOriginalName() . ' must be image.';
            $validationArray['images.'.$key.'.mimes'] = 'The ' .  $file->getClientOriginalName() . ' must be a file of type: jpeg, bmp, png.';
        }

        return $validationArray;
//        return [
//            'images.*.uploaded' => ":attribute must be less 2MB",
//        ];
    }
}
