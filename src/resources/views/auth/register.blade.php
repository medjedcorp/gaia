<!doctype html>
<html lang="ja">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{asset('assets/images/favicon-32x32.png')}}" type="image/png" />
    @if(config('const.app_env') == 'production')
    @include('taghead')
    @endif
    <!-- loader-->
    <link href="{{asset('assets/css/pace.min.css')}}" rel="stylesheet" />
    <script src="{{asset('assets/js/pace.min.js')}}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet">
    <title>{{ config('const.app_title') }} / ユーザー登録</title>
</head>

<body class="bg-login">
    @if(config('const.app_env') == 'production')
    @include('tagbody')
    @endif
    <!--wrapper-->
    <div class="wrapper">
        <div class="d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                    <div class="col mx-auto">
                        <div class="my-4 text-center">
                            <img src="{{asset('assets/images/logo-img.png')}}" width="180" alt="" />
                        </div>
                        {{-- @include('partials.success') --}}

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
                                        @include('partials.errors')
                                    </div>
                                    <div class="form-body h-adr">
                                        <form class="row g-3" method="POST" action="/auth/thanks" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <span class="p-country-name" style="display:none;">Japan</span>
                                            <div class="col-12">
                                                <label for="inputName" class="form-label">お名前</label>
                                                <div class="input-group">
                                                    @if($errors->has('name'))
                                                    <span class="input-group-text bg-transparent" style="border-color: #fd3550;"><i class="fadeIn animated bx bxs-user"></i></span>
                                                    <input type="text" class="form-control is-invalid border-start-0" name="name" id="inputName" aria-describedby="inputName" value="{{old('name')}}" placeholder="Your Name" required>
                                                    <div class="invalid-feedback">{{$errors->first('name')}}</div>
                                                    @else
                                                    <span class="input-group-text bg-transparent"><i class="fadeIn animated bx bxs-user"></i></span>
                                                    <input type="text" class="form-control border-start-0" name="name" id="inputName" placeholder="Your Name" value="{{old('name')}}" required autofocus>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="inputEmail" class="form-label">Email アドレス</label>
                                                <div class="input-group">
                                                    @if($errors->has('email'))
                                                    <span class="input-group-text bg-transparent" style="border-color: #fd3550;"><i class="fadeIn animated bx bx-envelope"></i></span>
                                                    <input type="email" class="form-control is-invalid border-start-0" name="email" id="inputEmail" aria-describedby="inputEmail" value="{{old('email')}}" placeholder="example@user.com" required>
                                                    <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                                    @else
                                                    <span class="input-group-text bg-transparent"><i class="fadeIn animated bx bx-envelope"></i></span>
                                                    <input type="email" class="form-control border-start-0" name="email" id="inputEmail" placeholder="example@user.com" value="{{old('email')}}" required>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="inputPassword" class="form-label">Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    @if($errors->has('password'))
                                                    <span class="input-group-text bg-transparent" style="border-color: #fd3550;"><i class="fadeIn animated bx bx-lock-open"></i></span>
                                                    <input type="password" class="form-control border-start-0 border-end-0 is-invalid" id="inputPassword" placeholder="Enter Password" name="password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                                    <div class="invalid-feedback">{{$errors->first('password')}}</div>
                                                    @else
                                                    <span class="input-group-text bg-transparent"><i class="fadeIn animated bx bx-lock-open"></i></span>
                                                    <input type="password" class="form-control border-start-0 border-end-0" id="inputPassword" placeholder="Enter Password" name="password"><a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="inputTel" class="form-label">電話番号</label>
                                                <div class="input-group">
                                                    
                                                    @if($errors->has('tel'))
                                                    <span class="input-group-text bg-transparent is-invalid" style="border-color: #fd3550;"><i class="fadeIn animated bx bx-mobile"></i></span>
                                                    <input type="tel" class="form-control is-invalid border-start-0" name="tel" id="inputTel" placeholder="000-0000-0000" value="{{old('tel')}}" required>
                                                    <div class="invalid-feedback">{{$errors->first('tel')}}</div>
                                                    @else
                                                    <span class="input-group-text bg-transparent"><i class="fadeIn animated bx bx-mobile"></i></span>
                                                    <input type="tel" class="form-control border-start-0" id="inputTel" name="tel" placeholder="000-0000-0000" value="{{old('tel')}}" required>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="inputPostcode" class="form-label">郵便番号</label>
                                                @if($errors->has('postcode'))
                                                <input type="text" class="form-control is-invalid p-postal-code" id="inputPostcode" name="postcode" placeholder="000-0000" value="{{old('postcode')}}" required maxlength="8">
                                                <div class="invalid-feedback">{{$errors->first('postcode')}}</div>
                                                @else
                                                <input type="text" class="form-control p-postal-code" id="inputPostcode" name="postcode" placeholder="000-0000" value="{{old('postcode')}}" required maxlength="8">
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label for="inputPref" class="form-label">都道府県名</label>
                                                <select id="inputPref" class="form-select p-region-id" name="prefecture">
                                                    <option value="1" @if(1===(int)old('prefecture')) selected @endif>北海道</option>
                                                    <option value="2" @if(2===(int)old('prefecture')) selected @endif>青森県</option>
                                                    <option value="3" @if(3===(int)old('prefecture')) selected @endif>岩手県</option>
                                                    <option value="4" @if(4===(int)old('prefecture')) selected @endif>宮城県</option>
                                                    <option value="5" @if(5===(int)old('prefecture')) selected @endif>秋田県</option>
                                                    <option value="6" @if(6===(int)old('prefecture')) selected @endif>山形県</option>
                                                    <option value="7" @if(7===(int)old('prefecture')) selected @endif>福島県</option>
                                                    <option value="8" @if(8===(int)old('prefecture')) selected @endif>茨城県</option>
                                                    <option value="9" @if(9===(int)old('prefecture')) selected @endif>栃木県</option>
                                                    <option value="10" @if(10===(int)old('prefecture')) selected @endif>群馬県</option>
                                                    <option value="11" @if(11===(int)old('prefecture')) selected @endif>埼玉県</option>
                                                    <option value="12" @if(12===(int)old('prefecture')) selected @endif>千葉県</option>
                                                    <option value="13" @if(13===(int)old('prefecture')) selected @endif>東京都</option>
                                                    <option value="14" @if(14===(int)old('prefecture')) selected @endif>神奈川県</option>
                                                    <option value="15" @if(15===(int)old('prefecture')) selected @endif>新潟県</option>
                                                    <option value="16" @if(16===(int)old('prefecture')) selected @endif>富山県</option>
                                                    <option value="17" @if(17===(int)old('prefecture')) selected @endif>石川県</option>
                                                    <option value="18" @if(18===(int)old('prefecture')) selected @endif>福井県</option>
                                                    <option value="19" @if(19===(int)old('prefecture')) selected @endif>山梨県</option>
                                                    <option value="20" @if(20===(int)old('prefecture')) selected @endif>長野県</option>
                                                    <option value="21" @if(21===(int)old('prefecture')) selected @endif>岐阜県</option>
                                                    <option value="22" @if(22===(int)old('prefecture')) selected @endif>静岡県</option>
                                                    <option value="23" @if(23===(int)old('prefecture')) selected @endif>愛知県</option>
                                                    <option value="24" @if(24===(int)old('prefecture')) selected @endif>三重県</option>
                                                    <option value="25" @if(25===(int)old('prefecture')) selected @endif>滋賀県</option>
                                                    <option value="26" @if(26===(int)old('prefecture')) selected @endif>京都府</option>
                                                    <option value="27" @if(27===(int)old('prefecture')) selected @endif>大阪府</option>
                                                    <option value="28" @if(28===(int)old('prefecture')) selected @endif>兵庫県</option>
                                                    <option value="29" @if(29===(int)old('prefecture')) selected @endif>奈良県</option>
                                                    <option value="30" @if(30===(int)old('prefecture')) selected @endif>和歌山県</option>
                                                    <option value="31" @if(31===(int)old('prefecture')) selected @endif>鳥取県</option>
                                                    <option value="32" @if(32===(int)old('prefecture')) selected @endif>島根県</option>
                                                    <option value="33" @if(33===(int)old('prefecture')) selected @endif>岡山県</option>
                                                    <option value="34" @if(34===(int)old('prefecture')) selected @endif>広島県</option>
                                                    <option value="35" @if(35===(int)old('prefecture')) selected @endif>山口県</option>
                                                    <option value="36" @if(36===(int)old('prefecture')) selected @endif>徳島県</option>
                                                    <option value="37" @if(37===(int)old('prefecture')) selected @endif>香川県</option>
                                                    <option value="38" @if(38===(int)old('prefecture')) selected @endif>愛媛県</option>
                                                    <option value="39" @if(39===(int)old('prefecture')) selected @endif>高知県</option>
                                                    <option value="40" @if(40===(int)old('prefecture')) selected @endif>福岡県</option>
                                                    <option value="41" @if(41===(int)old('prefecture')) selected @endif>佐賀県</option>
                                                    <option value="42" @if(42===(int)old('prefecture')) selected @endif>長崎県</option>
                                                    <option value="43" @if(43===(int)old('prefecture')) selected @endif>熊本県</option>
                                                    <option value="44" @if(44===(int)old('prefecture')) selected @endif>大分県</option>
                                                    <option value="45" @if(45===(int)old('prefecture')) selected @endif>宮崎県</option>
                                                    <option value="46" @if(46===(int)old('prefecture')) selected @endif>鹿児島県</option>
                                                    <option value="47" @if(47===(int)old('prefecture')) selected @endif>沖縄県</option>
                                                </select>
                                                @if($errors->has('prefecture'))
                                                <div class="text-danger">{{$errors->first('prefecture')}}</div>
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <label for="inputAddress" class="form-label">住所</label>
                                                @if($errors->has('address'))
                                                <textarea class="form-control is-invalid p-locality p-street-address p-extended-address" id="inputAddress" name="address" placeholder="都道府県名以降の住所を入力してください" rows="3" required>{{old('address')}}</textarea>
                                                <div class="invalid-feedback">{{$errors->first('address')}}</div>
                                                @endif
                                                <textarea class="form-control p-locality p-street-address p-extended-address" id="inputAddress" name="address" placeholder="都道府県名以降の住所を入力してください" rows="3" required>{{old('address')}}</textarea>
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
            <p class="mb-0">{{ config('const.copy_right', 'Copyright © Medjed.') }}</p>
        </footer>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    {{-- <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script> --}}
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
        
    $(function(){

        //郵便番号にハイフンを自動挿入するメソッド
        function insertStr(input){
        return input.slice(0, 3) + '-' + input.slice(3,input.length);
        }

        //入力時に郵便番号に自動でハイフンを付けるイベント
        $("#inputPostcode").on('keyup',function(e){
        var input = $(this).val();

        //削除キーではハイフン追加処理が働かないように制御（8がBackspace、46がDelete)
        var key = event.keyCode || event.charCode;
        if( key == 8 || key == 46 ){
            return false;
        }

        //３桁目に値が入ったら発動
        if(input.length === 3){
            $(this).val(insertStr(input));
        }
        });

        //フォーカスが外れた際、本当に4桁目に'-'がついているかチェック。なければ挿入するイベント
        //これでコピペした場合にも反応できるハズ？
        $("#inputPostcode").on('blur',function(e){
        var input = $(this).val();

        //４桁目が'-(ハイフン)’かどうかをチェックし、違ったら挿入
        if(input.length >= 3 && input.substr(3,1) !== '-'){
            $(this).val(insertStr(input));
        }
        });
    });



    </script>
    <!--app JS-->
    <script src="assets/js/app.js"></script>
</body>

</html>