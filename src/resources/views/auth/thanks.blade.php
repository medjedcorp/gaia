<!doctype html>
<html lang="ja">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <title>[{!! config('app.name') !!}]  ユーザー登録申請への受付完了</title>
</head>

<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
        <div class="d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                    <div class="col mx-auto">
                        <div class="my-4 text-center">
                            <img src="assets/images/logo-img.png" width="180" alt="" />
                        </div>
                        @include('partials.success')
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class=""> ユーザー登録申請への受付完了</h3>
                                        <p>この度は、[{!! config('app.name') !!}]のユーザー登録にお申し込み頂きまして、誠にありがとうございます。
                                            入力いただいた情報をもとに登録を行なわせていただきます。
                                            登録の結果につきましては、２営業日以内にメールにてご案内いたしますので、
                                            お待ちくださいますようお願いいたします。
                                        </p>
                                    </div>
                                    <div class="login-separater text-center mb-4"> <span>REGISTRATION DETAILS</span>
                                        <hr />
                                    </div>
                                    <div class="form-body">
                                        <div class="col-12">
                                            <dl>
                                                <dt>お名前</dt>
                                                <dd>{{ $user->name }}</dd>
                                            </dl>
                                        </div>
                                        <div class="col-12">
                                            <dl>
                                                <dt>Email アドレス</dt>
                                                <dd>{{ $user->email }}</dd>
                                            </dl>
                                        </div>
                                        <div class="col-12">
                                            <div>
                                                <dl>
                                                    <dt>Password</dt>
                                                    <dd>Secret</dd>
                                                </dl>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <dl>
                                                <dt>電話番号</dt>
                                                <dd>{{ $user->tel }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                以上の内容で受け付けました。ありがとうございました。
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
        <footer class="bg-white shadow-sm border-top p-2 text-center fixed-bottom">
            <p class="mb-0">{!! config('const.footer') !!}</p>
        </footer>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <!--app JS-->
    <script src="assets/js/app.js"></script>
</body>

</html>