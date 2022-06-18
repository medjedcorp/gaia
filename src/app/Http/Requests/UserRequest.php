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
            'name' => 'required|string|max:85',
            'email' => 'required|email|unique:users,email|max:100',
            'tel' =>  'required|regex:/^[-0-9]+$/|unique:users,tel|between:10,13',
            'password' => 'required|min:8|max:255',
            'postcode' =>  'required|regex:/^[-0-9]+$/|max:8|min:8',
            'prefecture' =>  'required|numeric|integer|min:1|max:47',
            'address' =>  'required|string|max:255',
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
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号は数値とハイフンのみで入力してください',
            'postcode.max' => '郵便番号は８文字(ハイフン込み)で入力してください',
            'postcode.min' => '郵便番号は８文字(ハイフン込み)で入力してください',
            'prefecture.required' => '都道府県名を入力してください',
            'prefecture.numeric' => '他の値を入力しないでください',
            'prefecture.integer' => '他の値を入力しないでください',
            'prefecture.min' => '他の値を入力しないでください',
            'prefecture.max' => '他の値を入力しないでください',
            'address.required' => '住所を入力してください',
            'address.max' => '住所は２５５文字以内で入力してください',
        ];
    }
}
