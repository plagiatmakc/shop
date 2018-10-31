<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductAttributeRequest extends FormRequest
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
            'product_id' => 'required|max:255',
            'sku' => ['required',
                        'max:255',
                Rule::unique('product_attributes')->ignore($this->id),],
            'size' => 'required|max:255',
            'stock' => 'required|max:255',
            'price' => "required|regex:/^\d*(\.\d{1,2})?$/",
        ];
    }
}
