<?php

namespace App\Services;

use Illuminate\Http\Request;

final class StationImportService
{
    /**
     * 以下、バリデーションの設定
     */
    public function validationRules()
    {
        return [
            'station_cd'    => 'required|integer|digits_between:1,8',
            'station_g_cd'  => 'required|integer|digits_between:1,8',
            'station_name'  => 'required|string|max:100',
            'line_cd'       => 'required|integer|digits_between:1,6',
            'pref_cd'       => 'required|integer|digits_between:1,2',
            'post'  => 'required|string|max:8',
            'address'  => 'required|string|max:255',
            'lon'  => 'nullable|numeric',
            'lat'  => 'nullable|numeric',
            'display_flag' => 'sometimes|required|boolean', // フィールドが存在するときのみバリデーションsometimes
        ];
    }

    public function validationMessages()
    {
        return [
            'station_cd.required' => 'station_cdを入力してください',
            'station_cd.integer' => 'station_cdは整数で入力してください',
            'station_cd.digits_between' => 'station_cdは8桁以内で入力してください',
            'station_g_cd.required' => 'station_g_cdを入力してください',
            'station_g_cd.integer' => 'station_g_cdは整数で入力してください',
            'station_g_cd.digits_between' => 'station_g_cdは8桁以内で入力してください',
            'station_name.required' => 'station_nameを入力してください',
            'station_name.max' => 'station_nameは100文字以内で入力してください',
            'line_cd.required' => 'line_cdを入力してください',
            'line_cd.integer' => 'line_cdは整数で入力してください',
            'line_cd.digits_between' => 'line_cdは6桁以内で入力してください',
            'pref_cd.required' => 'pref_cdを入力してください',
            'pref_cd.integer' => 'pref_cdは整数で入力してください',
            'pref_cd.digits_between' => 'pref_cdは2桁以内で入力してください',
            'post.required' => 'postを入力してください',
            'post.max' => 'postは8文字ハイフンありで入力してください',
            'address.required' => 'addressを入力してください',
            'address.max' => 'addressは255文字以内で入力してください',
            'lon.max' => 'lonは数値で入力してください',
            'lat.max' => 'latは数値で入力してください',
            'display_flag.boolean' => 'display_flagは0(非公開)または1(公開)を入力してください',
        ];
    }

    public function validationAttributes()
    {
        return [
            'station_cd'    => 'station_cd',
            'station_g_cd'  => 'station_g_cd',
            'station_name'  => 'station_name',
            'line_cd'  => 'line_cd',
            'pref_cd'  => 'pref_cd',
            'post'  => 'post',
            'address'  => 'address',
            'lon'  => 'lon',
            'lat'  => 'lat',
            'display_flag'  => 'display_flag',
        ];
    }
}
