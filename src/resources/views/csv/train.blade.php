@extends("layouts.app")
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">鉄道マスタ</li>
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        <li class="breadcrumb-item active" aria-current="page">鉄道会社マスタ</li>
                    </ol>
                </nav>
            </div>
            {{-- <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary">Settings</button>
                    <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item" href="javascript:;">Action</a>
                        <a class="dropdown-item" href="javascript:;">Another action</a>
                        <a class="dropdown-item" href="javascript:;">Something else here</a>
                        <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
                    </div>
                </div>
            </div> --}}
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">鉄道会社csv管理</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        @include('partials.success')
                        @include('partials.danger')
                        <div>
                            <h5 class="card-title">鉄道会社マスタCSVアップロード</h5>
                        </div>
                        <form method="post" action="{{ route('importTrainCSV') }}" class="" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="mb-3">
                                <label for="formFile" class="form-label"><a href="https://www.ekidata.jp/">駅データ.jp</a> よりダウンロードしたcompany.csvをそのままアップロード可能です</label>
                                <input class="form-control" type="file" id="formFile" name="file">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="lni lni-upload"></i> Upload</button>
                        </form>
                    </div>
                </div>
                <h6 class="mb-0 text-uppercase">カテゴリマスタのcsv項目名とルール</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <div>
                            <ul>
                                <li>CSVの項目名はアンダーバーも含め、全て半角です。</li>
                                <li>入力制限欄の(○○)は文字数制限です。半角・全角関係なく1文字ずつカウントします。</li>
                                <li>入力制限欄の「数値(X.Y)」のXは「整数の桁数」 Yは「小数点以下の桁数」を表しています。<br>
                                    例えば「数値(3.2)」と記載してある場合、入力する値の一例は「123.12」です。</li>
                                <li>文字コードは(utf-8)を選択してください。</li>
                                <li>駅データ.jp よりダウンロードしたcompany.csvをそのままアップロード可能です。</li>
                                <li>display_flagは値がない場合、初期値0(表示しない)でアップロードされます。</li>
                            </ul>
                        </div>
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">項目名</th>
                                    <th scope="col">CSV項目名</th>
                                    <th scope="col">説明</th>
                                    <th scope="col">入力制限</th>
                                    <th scope="col">補足</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">鉄道会社コード</th>
                                    <td>company_cd</td>
                                    <td><span class="text-danger">※必須項目</span><br>鉄道会社を識別するためのコードを入力</td>
                                    <td>整数(4)</td>
                                    <td>4桁以内の整数で入力</td>
                                </tr>
                                <tr>
                                    <th scope="row">鉄道会社名</th>
                                    <td>company_name</td>
                                    <td><span class="text-danger">※必須項目</span><br>鉄道会社の名前を入力</td>
                                    <td>文字(40)</td>
                                    <td>40文字以内で鉄道会社名を入力</td>
                                </tr>
                                <tr>
                                    <th scope="row">表示設定</th>
                                    <td>display_flag</td>
                                    <td><span class="text-danger">※項目を設定する場合は必須</span><br>駅や路線を表示する場合に設定</td>
                                    <td>boolean</td>
                                    <td>0：非公開<br>
                                        1：公開</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="card-footer">
                        <form method="get" action="{{ route('exportTrainCSV') }}" enctype="multipart/form-data">
                            @csrf
                            @method('get')
                            <button type="submit" class="btn btn-warning"><i class="lni lni-download"></i> 現在の設定をダウンロード</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
@endsection