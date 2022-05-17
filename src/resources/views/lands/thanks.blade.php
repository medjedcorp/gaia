@extends("layouts.app")

@section("style")
<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/common.css') }}" rel="stylesheet" />
@endsection

@section("wrapper")
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="breadcrumb-title pe-3">売土地</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item"><a href="{{ url('lands/index') }}">一覧</a></li>
						<li class="breadcrumb-item active" aria-current="page">送信完了</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->
		<div class="row">
			<div class="col-xl-10 mx-auto">
				<div class="row">
					<div class="col-xl-9 mx-auto">
						<div class="card border-top border-0 border-4 border-warning">
							<div class="card-header">送信完了のお知らせ</div>
							<div class="card-body">
								<div class="border p-4 rounded">
									<div class="card-title d-flex align-items-center">
										<div><i class="fadeIn animated bx bx-smile font-22"></i>
										</div>
										<h5 class="mb-0">お問合せありがとうございました。</h5>
									</div>
									<hr />
									<ul class="list-group list-group-flush">
										<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
											<span class="text-secondary">物件番号</span>
											<h6 class="mb-0">{{$bukken_num}}</h6>
										</li>
										<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
											<span class="text-secondary">お名前</span>
											<h6 class="mb-0">{{$name}} 様</h6>
										</li>
										<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
											<span class="text-secondary">電話番号</span>
											<h6 class="mb-0">{{$tel}}</h6>
										</li>
										<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
											<span class="text-secondary">メールアドレス</span>
											<h6 class="mb-0">{{$to}}</h6>
										</li>
										<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
											<span class="text-secondary">お問合せ内容</span>
											<h6 class="mb-0">{{$value}}</h6>
										</li>
										<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
											<span class="text-secondary">その他</span>
											<h6 class="mb-0">{{$other}}</h6>
										</li>
									</ul>
								</div>
							</div>
							<div class="card-footer">確認後担当者よりご連絡させて頂きます。ご連絡のない場合は、{!! config('const.contact_tel') !!}まで、お問い合わせ下さい。</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
</div>
<!--end page wrapper -->
@endsection

@section("script")
@endsection