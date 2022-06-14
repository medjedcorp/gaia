<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <title>{{ config('const.app_title') }} / forgot password</title>
</head>

<body class="bg-forgot">
    <!-- wrapper -->
    <div class="wrapper">
        <div class="authentication-forgot d-flex align-items-center justify-content-center">
            <div class="card forgot-box">
                <div class="card-body">
                    <div class="p-4 rounded  border">
                        <div class="text-center">
                            <img src="assets/images/icons/forgot-2.png" width="120" alt="" />
                        </div>
                        <h4 class="mt-5 font-weight-bold">パスワードをお忘れですか?</h4>
                        <p class="text-muted">登録したメールアドレスを入力してください。<br>パスワードリセット用のEmailをお送りします。</p>
                        <div class="my-4">
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4 text-danger" :status="session('status')" />

                            <!-- Validation Errors -->
                            <x-auth-validation-errors class="mb-4 text-danger" :errors="$errors" />
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <x-label class="form-label" for="email" :value="__('Email Address')" />
                                <x-input id="email" class="block mt-1 w-full form-control form-control-lg" type="email" name="email" :value="old('email')"  placeholder="example@user.com" required autofocus />
                        </div>
                        <div class="d-grid gap-2">
                            <x-button class="btn btn-primary btn-lg">
                                <i class="fadeIn animated bx bx-mail-send"></i>{{ __('送信する') }}
                            </x-button>
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg"><i class='bx bx-arrow-back me-1'></i>ログイン画面に戻る</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end wrapper -->
</body>


</html>