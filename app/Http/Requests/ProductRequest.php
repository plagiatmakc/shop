<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    protected $type_of_currency = ['usd', 'eur', 'uah'];

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
        $rules = [
            'name' => 'required|max:255',
            'price' => "required|regex:/^\d*(\.\d{1,2})?$/",
            'currency' => [
                            'required',
                            Rule::in($this->type_of_currency),
                            ],
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,bmp,png|max:2000',
            'categories' => 'array|',
            'categories.*' => 'exists:categories,id',
        ];

        return $rules;
    }
}
