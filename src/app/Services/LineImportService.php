<?php

namespace App\Services;

use Illuminate\Http\Request;

final class LineImportService
{
    /**
     * 以下、バリデーションの設定
     */
    public function validationRules()
    {
        return [
            'line_cd'     => 'required|integer|digits_between:1,6',
            'company_cd'     => 'required|integer|digits_between:1,4',
            'line_name'  => 'required|string|max:40',
            'line_color_c'  => 'nullable|string|max:6',
            'lon'  => 'nullable|numeric',
            'lat'  => 'nullable|numeric',
            'display_flag' => 'sometimes|required|boolean', // フィールドが存在するときのみバリデーションsometimes
        ];
    }

    public function validationMessages()
    {
        return [
            'line_cd.required' => 'line_cdを入力してください',
            'line_cd.integer' => 'line_cdは整数で入力してください',
            'line_cd.digits_between' => 'line_cdは6桁以内で入力してください',
            'company_cd.required' => 'company_cdを入力してください',
            'company_cd.integer' => 'company_cdは整数で入力してください',
            'company_cd.digits_between' => 'company_cdは4桁以内で入力してください',
            'line_name.required' => 'line_nameを入力してください',
            'line_name.max' => 'line_nameは40文字以内で入力してください',
            'line_color_c.max' => 'line_color_cは6文字以内で入力してください',
            'lon.max' => 'lonは数値で入力してください',
            'lat.max' => 'latは数値で入力してください',
            'display_flag.boolean' => 'display_flagは0(非公開)または1(公開)を入力してください',
        ];
    }

    public function validationAttributes()
    {
        return [
            'line_cd'     => 'line_cd',
            'company_cd'     => 'company_cd',
            'line_name'  => 'line_name',
            'line_color_c'  => 'line_color_c',
            'lon'  => 'lon',
            'lat'  => 'lat',
            'display_flag'  => 'display_flag',
        ];
    }
}
