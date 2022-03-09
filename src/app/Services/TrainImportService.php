<?php

namespace App\Services;

use Illuminate\Http\Request;

final class TrainImportService
{
    /**
     * 以下、バリデーションの設定
     */
    public function validationRules()
    {
        return [
            'company_cd'     => 'required|integer|digits_between:1,4',
            'company_name'  => 'required|string|max:40',
        ];
    }

    public function validationMessages()
    {
        return [
            'company_cd.required' => 'company_cdを入力してください',
            'company_cd.integer' => 'company_cdは整数で入力してください',
            'company_cd.digits' => 'company_cdは4桁以内で入力してください',
            'company_name.required' => 'company_nameを入力してください',
            'company_name.max' => 'company_nameは40文字以内で入力してください',
        ];
    }

    public function validationAttributes()
    {
        return [
            'company_cd'     => 'company_cd',
            'company_name'  => 'company_name',
        ];
    }
}
