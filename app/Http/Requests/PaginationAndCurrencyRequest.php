<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaginationAndCurrencyRequest extends FormRequest
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
        return [
            'pagination' => 'integer|min:1|nullable',
            'type' => [
                'string',
                Rule::in($this->type_of_currency),
                'nullable',
            ],
            'category' => 'exists:categories,id|nullable',
        ];
    }
}
