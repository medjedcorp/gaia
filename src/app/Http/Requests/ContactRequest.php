<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bukken_num' => 'required',
            'name' => 'required|string|max:85',
            'email' => 'required|email|max:100',
            'tel' =>  'required|regex:/^[-0-9]+$/|between:10,13',
        ];
    }

    public function messages()
    {
        return [
            'bukken_num.required' => '物件番号は必須です',
            'name.required' => '名前を入力してください',
            'name.max' => '名前は８５文字以内で入力してください',
            'email.required' => 'emailは必須です',
            'email.max' => '名前は２５５文字以内で入力してください',
            'email.email' => 'emailの形式で入力してください',
            'tel.required' => '電話番号は必須です',
            'tel.digits_between' => '電話番号は10~13文字以内で入力してください',
            'tel.regex' => '電話番号は数値とハイフンのみで入力してください',
        ];
    }
}
