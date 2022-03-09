<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
      <head>
        <link rel="stylesheet" href="{{ asset('/css/mail.css') }}">
      </head>
    <body>
      <p>{{$name}} 様</p>
      <p>アップロードされたcsvファイルでの、更新及び登録に成功しました。</p>
      <p>■ファイル名 {{ $up_fname }}</p>
      <p>※このメールに返信されても確認はできません。</p>
      <br>
      <p>
        =================================================<br>
        ※このメールは自動送信されていますので、返信はご遠慮ください。<br>
        ※お問い合わせは下記窓口へお願いいたします。<br>
        <br>
        【お問合せ窓口】<br>
        {!! config('app.name') !!} サポート<br>
        Mail ：{!! config('const.contact_mail') !!}<br>
        URL ：{!! config('app.url') !!}<br>
        <br>
        運営：メジェド合同会社<br>
        =================================================
      </p>
    </body>
</html>
