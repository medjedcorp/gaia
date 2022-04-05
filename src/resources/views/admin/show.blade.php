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
			<div class="breadcrumb-title pe-3">Lands</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item"><a href="/admin/lands">Lands Index</a></li>
						<li class="breadcrumb-item active" aria-current="page">Lands Detail</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->
		<div class="row">
			<div class="col-xl-10 mx-auto">
				<h6 class="mb-0 text-uppercase">売土地詳細(管理者限定)</h6>
				<hr>
				<div class="card bukkendetail">
					<div class="card-body">
						@if (Session::has('notfound'))
						<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
							<div class="text-white">{{ session('notfound') }}</div>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						@endif
						<div class="mb-3">
							<nav class="navbar navbar-expand-lg navbar-dark bg-success rounded">
								<div class="container-fluid"> <a class="navbar-brand" href="#">Details</a>
									<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent5" aria-controls="navbarSupportedContent5" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
									</button>
									<div class="collapse navbar-collapse" id="navbarSupportedContent5">
										<ul class="navbar-nav me-auto mb-2 mb-lg-0">
											<li class="nav-item"> <a class="nav-link active" aria-current="page" href="#"><i class="bx bx-home-alt me-1"></i>基本情報</a>
											</li>
											<li class="nav-item"> <a class="nav-link" href="#contact"><i class="bx bx-user me-1"></i>担当</a>
											</li>
											<li class="nav-item"> <a class="nav-link" href="#regulations"><i class="bx bx-book-content me-1"></i>現況・法規・権利</a>
											</li>
											<li class="nav-item"> <a class="nav-link" href="#environment"><i class="bx bx-map-alt me-1"></i>周辺環境</a>
											</li>
											<li class="nav-item"> <a class="nav-link" href="#images"><i class="bx bx-image me-1"></i>画像・図面</a>
											</li>
											<li class="nav-item"> <a class="nav-link" href="#target"><i class="bx bx-map-pin me-1"></i>地図</a>
											</li>
										</ul>
										<form action="{{route('admin.lands.pdfdownload')}}" name="approvalform" method="get" enctype="multipart/form-data" class="d-flex">
											@csrf
											@method('get')
											<input type="hidden" name="zumen" value="{{$land->bukken_num}}">
											<button type="submit" class="btn btn-light px-4"><i class=" fadeIn animated bx bx-download"></i>図面</button>
										</form>
									</div>
								</div>
							</nav>
						</div>
						<div class="d-flex w-100 justify-content-between">
							<h5 class="card-title"><strong>基本情報</strong></h5>
							<small class="text-muted">Last updated {{$land->updated_at->format('Y-m-d')}}</small>
						</div>
						<dl class="row">
							<dt class="col-sm-3">物件番号</dt>
							<dd class="col-sm-9">{{$land->bukken_num}}</dd>

							<dt class="col-sm-3">登録年月日</dt>
							<dd class="col-sm-9">{{$land->touroku_date}}</dd>
							@if($land->update_date)
							<dt class="col-sm-3">更新年月日</dt>
							<dd class="col-sm-9">{{$land->update_date}}</dd>
							@endif
							@if($land->change_date)
							<dt class="col-sm-3 text-truncate">変更年月日</dt>
							<dd class="col-sm-9">{{$land->change_date}}</dd>
							@endif
							<dt class="col-sm-3">表示設定</dt>
							@if($land->display_flag === 1)
							<dd class="col-sm-9">現在の設定：表示中
								<form action="/admin/lands/{{$land->bukken_num}}" name="approvalform" method="get" enctype="multipart/form-data" class="d-flex">
								@csrf
								@method('get')
								{{-- <input type="hidden" name="zumen" value="{{$land->bukken_num}}"> --}}
								<input type="hidden" name="display_flag" value="0">
								<button type="submit" class="btn btn-sm btn-outline-danger px-3 mt-1"><i class="bx bx-low-vision"></i>非表示にする</button>
								</form>
							</dd>
							@else
							<dd class="col-sm-9">現在の設定：非表示
								<form action="/admin/lands/{{$land->bukken_num}}" name="approvalform" method="get" enctype="multipart/form-data" class="d-flex">
									@csrf
									@method('get')
									<input type="hidden" name="display_flag" value="1">
									<button type="submit" class="btn btn-sm btn-outline-primary px-3 mt-1"><i class="bx bx-show-alt"></i>表示する</button>
									</form>
							</dd>
							@endif
						</dl>
						<hr>
						<h5 class="card-title"><strong>分類</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">物件種目</dt>
							<dd class="col-sm-9">{{$land->bukken_shumoku}}</dd>

							<dt class="col-sm-3">広告転載区分</dt>
							<dd class="col-sm-9">{{$land->ad_kubun}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>取引</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">取引態様</dt>
							<dd class="col-sm-9">{{$land->torihiki_taiyou}}</dd>
							<dt class="col-sm-3">取引状況</dt>
							<dd class="col-sm-9">{{$land->torihiki_jyoukyou}}</dd>
							<dt class="col-sm-3">取引状況の補足</dt>
							<dd class="col-sm-9">{{$land->torihiki_hosoku}}</dd>
						</dl>
						<hr id="contact">
						<h5 class="card-title"><strong>担当</strong></h5>
						<p class="card-text"><strong>会員情報</strong></p>
						<dl class="row">
							<dt class="col-sm-3">商号</dt>
							<dd class="col-sm-9">{{$land->company}}</dd>
							<dt class="col-sm-3">代表電話番号</dt>
							<dd class="col-sm-9">{{$land->company_tel}}</dd>
							<dt class="col-sm-3">問合せ先電話番号</dt>
							<dd class="col-sm-9">{{$land->contact_tel}}</dd>
						</dl>
						<p class="card-text"><strong>物件問合せ担当</strong></p>
						<dl class="row">
							<dt class="col-sm-3">物件問合せ担当者</dt>
							<dd class="col-sm-9">{{$land->pic_name}}</dd>
							<dt class="col-sm-3">物件担当者電話番号</dt>
							<dd class="col-sm-9">{{$land->pic_tel}}</dd>
							<dt class="col-sm-3">Ｅメールアドレス</dt>
							<dd class="col-sm-9">{{$land->contact_tel}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>価格</strong></h5>
						<p class="card-text"><strong>基本情報</strong></p>
						<dl class="row">
							<dt class="col-sm-3">価格</dt>
							@if($land->price == 0)
							<dd class="col-sm-9">-万円</dd>
							@else
							<dd class="col-sm-9">{{ number_format($land->price) }}万円</dd>
							@endif
							<dt class="col-sm-3">変更前価格</dt>
							<dd class="col-sm-9">
								@if($land->mae_price > 0)
								{{ number_format($land->mae_price) }}万円
								@endif
								&nbsp;
							</dd>
							<dt class="col-sm-3">㎡単価</dt>
							<dd class="col-sm-9">{{ number_format($land->heibei_tanka) }}万円</dd>
							<dt class="col-sm-3">坪単価 ※3.30578で換算</dt>
							<dd class="col-sm-9">{{ number_format($land->tsubo_tanka) }}万円</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>面積</strong></h5>
						<p class="card-text"><strong>基本情報</strong></p>
						<dl class="row">
							<dt class="col-sm-3">土地面積（私道を含まず）</dt>
							<dd class="col-sm-9">{{ number_format($land->land_menseki, 2) }}㎡</dd>
							<dt class="col-sm-3">面積計測方式</dt>
							<dd class="col-sm-9">{{$land->keisoku_siki}}</dd>
							<dt class="col-sm-3">セットバック区分</dt>
							<dd class="col-sm-9">{{$land->setback}}</dd>
							<dt class="col-sm-3">私道負担有無</dt>
							<dd class="col-sm-9">{{$land->shidou_futan}}</dd>
							<dt class="col-sm-3">私道面積</dt>
							<dd class="col-sm-9">{{$land->shidou_menseki}}&nbsp;</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>住所</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">都道府県名</dt>
							<dd class="col-sm-9">{{$land->prefecture->name}}</dd>
							<dt class="col-sm-3">所在地名１</dt>
							<dd class="col-sm-9">{{$land->address1}}</dd>
							<dt class="col-sm-3">所在地名２</dt>
							<dd class="col-sm-9">{{$land->address2}}</dd>
							<dt class="col-sm-3">所在地名３</dt>
							<dd class="col-sm-9">{{$land->address3}}&nbsp;</dd>
							<dt class="col-sm-3">その他所在地表示</dt>
							<dd class="col-sm-9">{{$land->other_address}}&nbsp;</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>交通</strong></h5>
						<p class="card-text"><strong>交通１</strong></p>
						<dl class="row">
							@foreach($land->lines as $line)
							@if($line->pivot->level === 1)
							<dt class="col-sm-3">沿線名</dt>
							<dd class="col-sm-9">{{$line->line_name}}</dd>
							<dt class="col-sm-3">駅名</dt>
							<dd class="col-sm-9">{{$line->station_name[0]}}</dd>
							<dt class="col-sm-3">駅より徒歩</dt>
							<dd class="col-sm-9">{{$line->pivot->eki_toho}}</dd>
							<dt class="col-sm-3">駅より車</dt>
							<dd class="col-sm-9">{{$line->pivot->eki_car}}</dd>
							<dt class="col-sm-3">駅よりバス</dt>
							<dd class="col-sm-9">{{$line->pivot->eki_bus}}</dd>
							<dt class="col-sm-3">バス停より徒歩</dt>
							<dd class="col-sm-9">{{$line->pivot->bus_toho}}</dd>
							<dt class="col-sm-3">バス路線名</dt>
							<dd class="col-sm-9">{{$line->pivot->bus_route}}</dd>
							<dt class="col-sm-3">バス停名称</dt>
							<dd class="col-sm-9">{{$line->pivot->bus_stop}}</dd>
							@else
							<p class="card-text">該当なし</p>
							@endif
							@endforeach
						</dl>
						<p class="card-text"><strong>交通２</strong></p>
						<dl class="row">
							@foreach($land->lines as $line)
							@if($line->pivot->level === 2)
							<dt class="col-sm-3">沿線名</dt>
							<dd class="col-sm-9">{{$line->line_name}}</dd>
							<dt class="col-sm-3">駅名</dt>
							<dd class="col-sm-9">{{$line->station_name[0]}}</dd>
							<dt class="col-sm-3">駅より徒歩</dt>
							<dd class="col-sm-9">{{$line->pivot->eki_toho}}</dd>
							<dt class="col-sm-3">駅より車</dt>
							<dd class="col-sm-9">{{$line->pivot->eki_car}}</dd>
							<dt class="col-sm-3">駅よりバス</dt>
							<dd class="col-sm-9">{{$line->pivot->eki_bus}}</dd>
							<dt class="col-sm-3">バス停より徒歩</dt>
							<dd class="col-sm-9">{{$line->pivot->bus_toho}}</dd>
							<dt class="col-sm-3">バス路線名</dt>
							<dd class="col-sm-9">{{$line->pivot->bus_route}}</dd>
							<dt class="col-sm-3">バス停名称</dt>
							<dd class="col-sm-9">{{$line->pivot->bus_stop}}</dd>
							@else
							<p class="card-text">該当なし</p>
							@endif
							@endforeach
						</dl>
						<p class="card-text"><strong>交通３</strong></p>
						<dl class="row">
							@foreach($land->lines as $line)
							@if($line->pivot->level === 3)
							<dt class="col-sm-3">沿線名</dt>
							<dd class="col-sm-9">{{$line->line_name}}</dd>
							<dt class="col-sm-3">駅名</dt>
							<dd class="col-sm-9">{{$line->station_name[0]}}</dd>
							<dt class="col-sm-3">駅より徒歩</dt>
							<dd class="col-sm-9">{{$line->pivot->eki_toho}}</dd>
							<dt class="col-sm-3">駅より車</dt>
							<dd class="col-sm-9">{{$line->pivot->eki_car}}</dd>
							<dt class="col-sm-3">駅よりバス</dt>
							<dd class="col-sm-9">{{$line->pivot->eki_bus}}</dd>
							<dt class="col-sm-3">バス停より徒歩</dt>
							<dd class="col-sm-9">{{$line->pivot->bus_toho}}</dd>
							<dt class="col-sm-3">バス路線名</dt>
							<dd class="col-sm-9">{{$line->pivot->bus_route}}</dd>
							<dt class="col-sm-3">バス停名称</dt>
							<dd class="col-sm-9">{{$line->pivot->bus_stop}}</dd>
							@else
							<p class="card-text">該当なし</p>
							@endif
							@endforeach
						</dl>
						<p class="card-text"><strong>交通その他</strong></p>
						<dl class="row">
							<dt class="col-sm-3">その他交通手段</dt>
							<dd class="col-sm-9">{{$land->other_transportation}}</dd>
							<dt class="col-sm-3">交通</dt>
							<dd class="col-sm-9">{{$land->traffic}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>維持</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">その他一時金なし</dt>
							<dd class="col-sm-9">{{$land->ichijikin}}</dd>
							<dt class="col-sm-3">その他一時金名称１</dt>
							<dd class="col-sm-9">{{$land->ichijikin_name1}}</dd>
							<dt class="col-sm-3">金額１</dt>
							<dd class="col-sm-9">{{$land->ichijikin_price1}}</dd>
							<dt class="col-sm-3">その他一時金名称２</dt>
							<dd class="col-sm-9">{{$land->ichijikin_name2}}&nbsp;</dd>
							<dt class="col-sm-3">金額２</dt>
							<dd class="col-sm-9">{{$land->ichijikin_price2}}&nbsp;</dd>
						</dl>
						<hr id="regulations">
						<h5 class="card-title"><strong>現況</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">現況</dt>
							<dd class="col-sm-9">{{$land->genkyou}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>引渡</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">引渡時期</dt>
							<dd class="col-sm-9">{{$land->hikiwatashi_jiki}}</dd>
							<dt class="col-sm-3">引渡年月</dt>
							<dd class="col-sm-9">{{$land->hikiwatashi_nengetu}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>報酬・負担割合</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">報酬形態</dt>
							<dd class="col-sm-9">{{$land->houshu_keitai}}</dd>
							<dt class="col-sm-3">手数料割合率</dt>
							<dd class="col-sm-9">{{$land->fee_rate}}</dd>
							<dt class="col-sm-3">手数料</dt>
							<dd class="col-sm-9">{{$land->transaction_fee}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>法規</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">都市計画</dt>
							<dd class="col-sm-9">{{$land->city_planning}}</dd>
							<dt class="col-sm-3">登記簿地目</dt>
							<dd class="col-sm-9">{{$land->toukibo_chimoku}}</dd>
							<dt class="col-sm-3">現況地目</dt>
							<dd class="col-sm-9">{{$land->genkyou_chimoku}}</dd>
							<dt class="col-sm-3">用途地域</dt>
							<dd class="col-sm-9">{{$land->youto_chiki}}</dd>
							<dt class="col-sm-3">最適用途</dt>
							<dd class="col-sm-9">{{$land->saiteki_youto}}</dd>
							<dt class="col-sm-3">地域地区</dt>
							<dd class="col-sm-9">{{$land->chiikichiku}}</dd>
							<dt class="col-sm-3">建ぺい率</dt>
							<dd class="col-sm-9">{{$land->kenpei_rate}}</dd>
							<dt class="col-sm-3">容積率</dt>
							<dd class="col-sm-9">{{$land->youseki_rate}}</dd>
							<dt class="col-sm-3">容積率の制限内容</dt>
							<dd class="col-sm-9">{{$land->youseki_seigen}}</dd>
							<dt class="col-sm-3">その他の法令上の制限</dt>
							<dd class="col-sm-9">{{$land->other_seigen}}</dd>
							<dt class="col-sm-3">再建築不可</dt>
							<dd class="col-sm-9">{{$land->saikenchiku_fuka}}</dd>
							<dt class="col-sm-3">国土法届出</dt>
							<dd class="col-sm-9">{{$land->kokudohou_todokede}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>権利</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">借地権種類</dt>
							<dd class="col-sm-9">{{$land->shakuchiken_shurui}}</dd>
							<dt class="col-sm-3">借地料</dt>
							<dd class="col-sm-9">{{$land->shakuchi_ryou}}</dd>
							<dt class="col-sm-3">借地期限</dt>
							<dd class="col-sm-9">{{$land->shakuchi_kigen}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>土地</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">地勢</dt>
							<dd class="col-sm-9">{{$land->chisei}}</dd>
							<dt class="col-sm-3">建築条件</dt>
							<dd class="col-sm-9">{{$land->kenchiku_jyouken}}</dd>
						</dl>
						<hr id="environment">
						<h5 class="card-title"><strong>接道</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">接道状況</dt>
							<dd class="col-sm-9">{{$land->setudou_jyoukyou}}</dd>
							<dt class="col-sm-3">接道舗装</dt>
							<dd class="col-sm-9">{{$land->setudou_hosou}}</dd>
						</dl>
						<p class="card-text"><strong>接道１</strong></p>
						<dl class="row">
							<dt class="col-sm-3">接道種別</dt>
							<dd class="col-sm-9">{{$land->setudou_shubetu1}}</dd>
							<dt class="col-sm-3">接道接面</dt>
							<dd class="col-sm-9">{{$land->setudou_setumen1}}</dd>
							<dt class="col-sm-3">接道位置指定</dt>
							<dd class="col-sm-9">{{$land->setudou_ichi1}}</dd>
							<dt class="col-sm-3">接道方向</dt>
							<dd class="col-sm-9">{{$land->setudou_houkou1}}</dd>
							<dt class="col-sm-3">接道幅員</dt>
							<dd class="col-sm-9">{{$land->setudou_fukuin1}}</dd>
						</dl>
						<p class="card-text"><strong>接道２</strong></p>
						<dl class="row">
							<dt class="col-sm-3">接道種別</dt>
							<dd class="col-sm-9">{{$land->setudou_shubetu2}}</dd>
							<dt class="col-sm-3">接道接面</dt>
							<dd class="col-sm-9">{{$land->setudou_setumen2}}</dd>
							<dt class="col-sm-3">接道位置指定</dt>
							<dd class="col-sm-9">{{$land->setudou_ichi2}}</dd>
							<dt class="col-sm-3">接道方向</dt>
							<dd class="col-sm-9">{{$land->setudou_houkou2}}</dd>
							<dt class="col-sm-3">接道幅員</dt>
							<dd class="col-sm-9">{{$land->setudou_fukuin2}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>環境</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">周辺環境１(フリー)</dt>
							<dd class="col-sm-9">{{$land->shuhenkankyou1}}</dd>
							<dt class="col-sm-3">距離１</dt>
							<dd class="col-sm-9">{{$land->kyori1}}</dd>
							<dt class="col-sm-3">時間１</dt>
							<dd class="col-sm-9">{{$land->jikan1}}</dd>
							<dt class="col-sm-3">周辺環境２(フリー)</dt>
							<dd class="col-sm-9">{{$land->shuhenkankyou2}}</dd>
							<dt class="col-sm-3">距離２</dt>
							<dd class="col-sm-9">{{$land->kyori2}}</dd>
							<dt class="col-sm-3">時間２</dt>
							<dd class="col-sm-9">{{$land->jikan2}}</dd>
							<dt class="col-sm-3">周辺環境３(フリー)</dt>
							<dd class="col-sm-9">{{$land->shuhenkankyou3}}</dd>
							<dt class="col-sm-3">距離３</dt>
							<dd class="col-sm-9">{{$land->kyori3}}</dd>
							<dt class="col-sm-3">時間３</dt>
							<dd class="col-sm-9">{{$land->jikan3}}</dd>
							<dt class="col-sm-3">周辺環境４(フリー)</dt>
							<dd class="col-sm-9">{{$land->shuhenkankyou4}}</dd>
							<dt class="col-sm-3">距離４</dt>
							<dd class="col-sm-9">{{$land->kyori4}}</dd>
							<dt class="col-sm-3">時間４</dt>
							<dd class="col-sm-9">{{$land->jikan4}}</dd>
							<dt class="col-sm-3">周辺環境５(フリー)</dt>
							<dd class="col-sm-9">{{$land->shuhenkankyou5}}</dd>
							<dt class="col-sm-3">距離５</dt>
							<dd class="col-sm-9">{{$land->kyori5}}</dd>
							<dt class="col-sm-3">時間５</dt>
							<dd class="col-sm-9">{{$land->jikan5}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>設備・条件</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">設備・条件</dt>
							<dd class="col-sm-9">{{$land->setubi_jyouken}}</dd>
							<dt class="col-sm-3">設備(フリースペース)</dt>
							<dd class="col-sm-9">{{$land->setubi}}</dd>
							<dt class="col-sm-3">条件(フリースペース)</dt>
							<dd class="col-sm-9">{{$land->jyouken}}</dd>
						</dl>
						<hr>
						<h5 class="card-title"><strong>備考</strong></h5>
						<dl class="row">
							<dt class="col-sm-3">備考１</dt>
							<dd class="col-sm-9">{{$land->bikou1}}</dd>
							<dt class="col-sm-3">備考２</dt>
							<dd class="col-sm-9">{{$land->bikou2}}</dd>
							<dt class="col-sm-3">備考３</dt>
							<dd class="col-sm-9">{{$land->bikou3}}</dd>
							<dt class="col-sm-3">備考４</dt>
							<dd class="col-sm-9">{{$land->bikou4}}</dd>
						</dl>
						<hr id="images">
						<h5 class="card-title"><strong>物件画像</strong></h5>
						<dl class="row">
							@if($land->photo1)
							<div class="col-3">
								<div class="card border-success border-bottom border-3 border-0">
									<a href="/storage/landimages/{{$land->bukken_num}}/{{$land->photo1}}" target="_blank"><img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo1}}" class="card-img-top"></a>
									<div class="card-body">
										<h5 class="card-title text-success">1.ファイル名</h5>
										<p class="card-text">{{$land->photo1}}</p>
									</div>
								</div>
							</div>
							@endif
							@if($land->photo2)
							<div class="col-3">
								<div class="card border-success border-bottom border-3 border-0">
									<a href="/storage/landimages/{{$land->bukken_num}}/{{$land->photo2}}" target="_blank"><img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo2}}" class="card-img-top"></a>
									<div class="card-body">
										<h5 class="card-title text-success">2.ファイル名</h5>
										<p class="card-text">{{$land->photo2}}</p>
									</div>
								</div>
							</div>
							@endif
							@if($land->photo3)
							<div class="col-3">
								<div class="card border-success border-bottom border-3 border-0">
									<a href="/storage/landimages/{{$land->bukken_num}}/{{$land->photo3}}" target="_blank"><img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo3}}" class="card-img-top"></a>
									<div class="card-body">
										<h5 class="card-title text-success">3.ファイル名</h5>
										<p class="card-text">{{$land->photo3}}</p>
									</div>
								</div>
							</div>
							@endif
							@if($land->photo4)
							<div class="col-3">
								<div class="card border-success border-bottom border-3 border-0">
									<a href="/storage/landimages/{{$land->bukken_num}}/{{$land->photo4}}" target="_blank"><img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo4}}" class="card-img-top"></a>
									<div class="card-body">
										<h5 class="card-title text-success">4.ファイル名</h5>
										<p class="card-text">{{$land->photo4}}</p>
									</div>
								</div>
							</div>
							@endif
							@if($land->photo5)
							<div class="col-3">
								<div class="card border-success border-bottom border-3 border-0">
									<a href="/storage/landimages/{{$land->bukken_num}}/{{$land->photo5}}" target="_blank"><img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo5}}" class="card-img-top"></a>
									<div class="card-body">
										<h5 class="card-title text-success">5.ファイル名</h5>
										<p class="card-text">{{$land->photo5}}</p>
									</div>
								</div>
							</div>
							@endif
							@if($land->photo6)
							<div class="col-3">
								<div class="card border-success border-bottom border-3 border-0">
									<a href="/storage/landimages/{{$land->bukken_num}}/{{$land->photo6}}" target="_blank"><img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo6}}" class="card-img-top"></a>
									<div class="card-body">
										<h5 class="card-title text-success">6.ファイル名</h5>
										<p class="card-text">{{$land->photo6}}</p>
									</div>
								</div>
							</div>
							@endif
							@if($land->photo7)
							<div class="col-3">
								<div class="card border-success border-bottom border-3 border-0">
									<a href="/storage/landimages/{{$land->bukken_num}}/{{$land->photo7}}" target="_blank"><img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo7}}" class="card-img-top"></a>
									<div class="card-body">
										<h5 class="card-title text-success">7.ファイル名</h5>
										<p class="card-text">{{$land->photo7}}</p>
									</div>
								</div>
							</div>
							@endif
							@if($land->photo8)
							<div class="col-3">
								<div class="card border-success border-bottom border-3 border-0">
									<a href="/storage/landimages/{{$land->bukken_num}}/{{$land->photo8}}" target="_blank"><img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo8}}" class="card-img-top"></a>
									<div class="card-body">
										<h5 class="card-title text-success">8.ファイル名</h5>
										<p class="card-text">{{$land->photo8}}</p>
									</div>
								</div>
							</div>
							@endif
							@if($land->photo9)
							<div class="col-3">
								<div class="card border-success border-bottom border-3 border-0">
									<a href="/storage/landimages/{{$land->bukken_num}}/{{$land->photo9}}" target="_blank"><img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo9}}" class="card-img-top"></a>
									<div class="card-body">
										<h5 class="card-title text-success">9.ファイル名</h5>
										<p class="card-text">{{$land->photo9}}</p>
									</div>
								</div>
							</div>
							@endif
							@if($land->photo10)
							<div class="col-3">
								<div class="card border-success border-bottom border-3 border-0">
									<a href="/storage/landimages/{{$land->bukken_num}}/{{$land->photo10}}" target="_blank"><img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo10}}" class="card-img-top"></a>
									<div class="card-body">
										<h5 class="card-title text-success">10.ファイル名</h5>
										<p class="card-text">{{$land->photo10}}</p>
									</div>
								</div>
							</div>
							@endif
						</dl>
						<hr>
						<h5 class="card-title"><strong>物件図面</strong></h5>
						<div>
							<form action="{{route('admin.lands.pdfdownload')}}" name="approvalform" method="get" enctype="multipart/form-data">
								@csrf
								@method('get')
								<input type="hidden" name="zumen" value="{{$land->bukken_num}}">
								<button type="submit" class="btn btn-lg btn-success px-5"><i class="fadeIn animated bx bx-download"></i> 図面をダウンロード</button>
							</form>
						</div>
					</div>
				</div>
				<h6 class="mb-0 text-uppercase">物件地図</h6>
				<hr>
				<div class="card">
					<div class="card-body">
						<div id="target" class="gmaps" style="position: relative; overflow: hidden;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end page wrapper -->
@endsection

@section("script")
<script>
	'use strict';
	var map;
	var marker;
	var center = {
		lat: {!! $location->latitude !!}, // 緯度
		lng: {!! $location->longitude !!} // 経度
	};
	
	function initMap() {
		map = new google.maps.Map(document.getElementById('target'), { // #targetに地図を埋め込む
			center: center, // 地図の中心を指定
			zoom: 15 // 地図のズームを指定
		});
		
		marker = new google.maps.Marker({ // マーカーの追加
			position: center, // マーカーを立てる位置を指定
			map: map // マーカーを立てる地図を指定
		});
	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key={{ config('const.map_key') }}&callback=initMap" defer></script>
@endsection