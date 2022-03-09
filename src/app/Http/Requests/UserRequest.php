<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:85',
            'email' => 'required|email|unique:users,email|max:100',
            'tel' =>  'required|regex:/^[-0-9]+$/|unique:users,tel|between:10,13',
            'password' => 'required|min:8|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'name.max' => '名前は８５文字以内で入力してください',
            'email.required' => 'emailは必須です',
            'email.max' => '名前は２５５文字以内で入力してください',
            'email.email' => 'emailの形式で入力してください',
            'email.unique' => '同じemailでの登録があります',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは８文字以上で入力してください',
            'password.max' => 'パスワードは１００文字以下で入力してください',
            'tel.required' => '電話番号は必須です',
            'tel.digits_between' => '電話番号は10~13文字以内で入力してください',
            'tel.regex' => '電話番号は数値とハイフンのみで入力してください',
            'tel.unique' => '同じ電話番号での登録があります',
        ];
    }
}
