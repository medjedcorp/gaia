<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <head>
    <link rel="stylesheet" href="{{ asset('/css/mail.css') }}">
  </head>

<body>
  <p>{{$user['name']}} 様</p>
  <p>この度は、[{!! config('app.name') !!}]にユーザー登録をお申し込みくださいまして、<br>
    誠にありがとうございます。</p>

  <p>入力いただいた情報をもとに登録審査を行なわせていただきます。<br>
    審査の結果につきましては、翌営業日以内にメールにてご案内いたしますので、<br>
    お待ちくださいますようお願い申し上げます。</p>

    <p>お客様のID：{{$user['id']}}</p>
    <p>お客様の名前：{{$user['name']}}</p>

  <br>
  <p>
    =================================================<br>
    ※このメールは自動送信されていますので、返信はご遠慮ください。<br>
    ※お問い合わせは下記窓口へお願いいたします。<br>
    <br>
    【物件へのお問合せ窓口】<br>
    {!! config('const.company_name') !!}<br>
    MAIL ：{!! config('const.contact_mail') !!}<br>
    TEL ：{!! config('const.contact_tel') !!}<br>
    URL ：{!! config('app.url') !!}<br>
    <br>
    【システムへのお問合せ】<br>
    {!! config('const.support_company') !!}<br>
    MAIL ：{!! config('const.support_mail') !!}<br>
    =================================================
  </p>
</body>

</html>