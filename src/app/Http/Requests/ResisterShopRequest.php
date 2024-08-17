<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResisterShopRequest extends FormRequest
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
            'name' => 'required',
            'area' => 'required',
            'genre_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '※店名を入力してください。',
            'area.required' => '※地域を選択してください。',
            'genre_id.required' => '※ジャンルを選択してください。',
        ];
    }
}
