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
    <title>{!! config('app.name') !!} / ユーザー登録</title>
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
                        {{-- @include('partials.success') --}}
                        @include('partials.errors')
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">登録お問い合わせ</h3>
                                        <p>アカウントをお持ちですか？ <a href="{{ route('login') }}">ログインはこちら</a><br>
                                            ご利用をご希望の場合は、登録申請をお願い致します。<br>確認後１～３営業日以内にご連絡させて頂きます。
                                        </p>
                                    </div>
                                    <div class="login-separater text-center mb-4"> <span>SIGN UP WITH EMAIL</span>
                                        <hr />
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" method="POST" action="{{route('users.store')}}" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <div class="col-12">
                                                <label for="inputName" class="form-label">お名前</label>
                                                @if($errors->has('name'))
                                                <input type="text" class="form-control is-invalid" name="name" id="inputName" aria-describedby="inputName" value="{{old('name')}}" placeholder="Your Name" required>
                                                <div class="invalid-feedback">{{$errors->first('name')}}</div>
                                                @else
                                                <input type="text" class="form-control" name="name" id="inputName" placeholder="Your Name" value="{{old('name')}}" required autofocus>
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <label for="inputEmail" class="form-label">Email アドレス</label>
                                                @if($errors->has('email'))
                                                <input type="email" class="form-control is-invalid" name="email" id="inputEmail" aria-describedby="inputEmail" value="{{old('email')}}" placeholder="example@user.com" required>
                                                <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                                @else
                                                <input type="email" class="form-control" name="email" id="inputEmail" placeholder="example@user.com" value="{{old('email')}}" required>
                                                @endif

                                            </div>
                                            <div class="col-12">
                                                <label for="inputPassword" class="form-label">Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    @if($errors->has('password'))
                                                    <input type="password" class="form-control border-end-0 is-invalid" id="inputPassword" placeholder="Enter Password" name="password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                                    <div class="invalid-feedback">{{$errors->first('password')}}</div>
                                                    @else
                                                    <input type="password" class="form-control border-end-0" id="inputPassword" placeholder="Enter Password" name="password"><a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputTel" class="form-label">電話番号</label>
                                                @if($errors->has('tel'))
                                                <input type="tel" class="form-control is-invalid" name="tel" id="inputTel" placeholder="00000000000" value="{{old('tel')}}" required>
                                                <div class="invalid-feedback">{{$errors->first('tel')}}</div>
                                                @else
                                                <input type="tel" class="form-control" id="inputTel" name="tel" placeholder="00000000000" value="{{old('tel')}}" required>
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i class='bx bx-user'></i>登録申請を送信する</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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