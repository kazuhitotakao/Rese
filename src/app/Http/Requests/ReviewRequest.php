<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'rating' => ['required'],
            'comment' => ['required', 'string', 'max:400'],
            'images.*' => ['nullable', 'file', 'mimes:jpeg,png'],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => 'ご自身に合う星★の数を選択してください。',
            'comment.required' => '口コミを入力してください。',
            'comment.string' => '口コミは文字列で入力してください。',
            'comment.max' => '口コミは400文字以内で入力してください。',
            'images.*.mimes' => 'jpegまたはpng形式でアップロードをお願いします。',
        ];
    }
}
