<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <head>
    <link rel="stylesheet" href="{{ asset('/css/mail.css') }}">
  </head>

<body>
  <p>{{$name}}  様</p>
  <p>この度は、[{!! config('app.name') !!}]よりお問い合わせ頂き、<br>
    誠にありがとうございます。</p>

    <p>入力内容を確認後、担当者よりお客様へご連絡させて頂きます。<br>
    この度は誠にありがとうございました。</p>

    <p>物件番号：{{$bukken_num}}<br>
    お問合せ内容：{{$value}}<br>
    その他：{{$other}}</p>

  <br>
  <p>
    =================================================<br>
    ※このメールは自動送信されていますので、返信はご遠慮ください。<br>
    ※お問い合わせは下記窓口へお願いいたします。<br>
    <br>
    【物件へのお問合せ窓口】<br>
    {!! config('const.company_name') !!}<br>
    Mail ：{!! config('const.contact_mail') !!}<br>
    Tel ：{!! config('const.contact_tel') !!}<br>
    Url ：{!! config('app.url') !!}<br>
    <br>
    【システムへのお問合せ】<br>
    {!! config('const.support_company') !!}<br>
    Mail ：{!! config('const.support_mail') !!}<br>
    =================================================
  </p>
</body>

</html>