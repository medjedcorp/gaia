<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
	<!-- loader-->
	<link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
	<title>Rocker - Multipurpose Bootstrap5 Admin Template</title>
</head>

<body>
<!-- wrapper -->
<div class="wrapper">
    <div class="authentication-reset-password d-flex align-items-center justify-content-center">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-lg-5 border-end">
                            <div class="card-body">
                                <div class="p-5">
                                    <div class="text-start">
                                        <img src="assets/images/logo-img.png" width="180" alt="">
                                    </div>
                                    
                                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                                    <h4 class="mt-5 font-weight-bold">新しいパスワードを生成</h4>
                                    <p class="text-muted">パスワード再設定のリクエストを受け取りました。新しいパスワードを入力してください。</p>
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                    <div class="mb-3 mt-5">
                                        <x-label for="email" :value="__('Email')" />
                                        <x-input id="email" type="email" name="email" class="form-control" :value="old('email', $request->email)" required autofocus />
                                    </div>
                                    <div class="mb-3">
                                        <x-label for="password" :value="__('Password')" />

                                        <x-input id="password" type="password" class="form-control" name="password" required />
                                    </div>
                                    <div class="mb-3">
                                        <x-label for="password_confirmation" :value="__('Confirm Password')" />

                                        <x-input id="password_confirmation" class="form-control"
                                                            type="password"
                                                            name="password_confirmation" required />
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">パスワードを変更する</button>
                                        </form>
                                     <a href="{{ route('login') }}" class="btn btn-light"><i class='bx bx-arrow-back mr-1'></i>ログイン画面に戻る</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <img src="{{ asset('assets/images/login-images/forgot-password-frent-img.jpg') }}" class="card-img login-img h-100" alt="...">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end wrapper -->
</body>

</html>
