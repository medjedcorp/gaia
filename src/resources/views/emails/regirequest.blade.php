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