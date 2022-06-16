<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <head>
    <link rel="stylesheet" href="{{ asset('/css/mail.css') }}">
  </head>

<body>
  <p>{{$name}} 様</p>
  <p>アップロードされたcsvファイルにエラーがありました。<br>
    エラー内容はダウンロード先のファイルよりご確認ください。</p>
  <p>■ファイル名 {{ $up_fname }}</p>
  <p>■更新結果:エラー<br>
    <a href="{{ $downloadLink }}" download>{{ $downloadLink }}</a></p>
  <p>※ファイルの保存期間は3日間です</p>
  <p>※このメールに心当たりがない場合は、メールの破棄をお願いいたします。</p>
  <br>
  <p>
    =================================================<br>
    ※このメールは自動送信されていますので、返信はご遠慮ください。<br>
    ※お問い合わせは下記窓口へお願いいたします。<br>
    <br>
    【システムへのお問合せ】<br>
    {!! config('const.support_company') !!}<br>
    MAIL ：{!! config('const.support_mail') !!}<br>
    TEL ：{!! config('const.support_tel') !!}<br>
    =================================================
  </p>
</body>

</html>