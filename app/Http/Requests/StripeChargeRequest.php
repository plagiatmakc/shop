<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StripeChargeRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'source_id' => 'required|string',
            'description' => 'nullable|string',
            'email' => 'required|email',
            'order_id' => 'required|integer'
        ];
    }
}
