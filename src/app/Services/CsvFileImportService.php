<?php

namespace App\Services;

use Illuminate\Http\Request;

final class CsvFileImportService
{
  public function validateUploadFile(Request $request)
  {
    return \Validator::make($request->all(), [
            'file' => 'required|file|mimetypes:text/plain|mimes:csv,txt|max:10480',
        ], [
            'file.required'  => 'ファイルを選択してください',
            'file.file'      => 'ファイルアップロードに失敗しました',
            'file.mimetypes' => 'ファイル形式が不正です',
            'file.mimes'     => 'ファイル拡張子が異なります',
            'file.max'     => 'ファイル容量は最大10MByte以内です',
        ]
    );
  }
}
