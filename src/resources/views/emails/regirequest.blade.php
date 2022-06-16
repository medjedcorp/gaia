<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <head>
    <link rel="stylesheet" href="{{ asset('/css/mail.css') }}">
  </head>

<body>
  <p>{{$user['name']}} 様 よりユーザー登録依頼がありました。</p>

  <p>Admin アカウントでログイン後に、ユーザー情報を確認の上、承認作業をお願い致します。</p>

    <p>お客様のID：{{$user['id']}}</p>
    <p>お客様の名前：{{$user['name']}}</p>
    <p>お客様のメールアドレス：{{$user['email']}}</p>
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