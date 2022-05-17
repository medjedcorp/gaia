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
						<li class="breadcrumb-item active" aria-current="page">詳細</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->
		<div class="row">
			<div class="col-xl-10 mx-auto">
				<h6 class="mb-0 text-uppercase">売土地詳細</h6>
				<hr>
				@if($errors)
				<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
					<div class="text-white">※メールの送信に失敗しました。内容をご確認ください。</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
				@endif
				<div class="card bukkendetail">
					<div class="card-header">
						<div class="d-flex w-100 justify-content-between">
							<h4><button type="button" class="btn btn-primary">{{$land->bukken_shumoku}}</button>
								<span style="vertical-align: middle;">
									{{$land->prefecture->name}}{{$land->address1}}{{$land->address2}}{{$land->address3}}{{$land->other_address}}</span>
							</h4>
							<small class="text-muted">{{$land->bukken_num}}</small>
						</div>
					</div>
					<div class="card-body">
						<div class="mb-4 text-center">
							<a href="#inquire" class="btn btn-lg btn-warning px-5 radius-30"><i class="fadeIn animated bx bx-envelope mr-1 fs-4"></i><strong>資料請求</strong><small class="fs-6">する(無料)<br>お問合せもこちら</small></a>
						</div>
						@if (Session::has('notfound'))
						<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
							<div class="text-white">{{ session('notfound') }}</div>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						@endif

						@if($land->photo1)
						<div class="card">
							<div class="card-body">
								<div id="carouselIndicators" class="carousel carousel-dark slide pointer-event" data-bs-ride="carousel">
									<ol class="carousel-indicators">
										<li data-bs-target="#carouselIndicators" data-bs-slide-to="0" class=""></li>
										@if($land->photo2)
										<li data-bs-target="#carouselIndicators" data-bs-slide-to="1" class="active"></li>
										@endif
										@if($land->photo3)
										<li data-bs-target="#carouselIndicators" data-bs-slide-to="2" class="active"></li>
										@endif
										@if($land->photo4)
										<li data-bs-target="#carouselIndicators" data-bs-slide-to="3" class="active"></li>
										@endif
										@if($land->photo5)
										<li data-bs-target="#carouselIndicators" data-bs-slide-to="4" class="active"></li>
										@endif
										@if($land->photo6)
										<li data-bs-target="#carouselIndicators" data-bs-slide-to="5" class="active"></li>
										@endif
										@if($land->photo7)
										<li data-bs-target="#carouselIndicators" data-bs-slide-to="6" class="active"></li>
										@endif
										@if($land->photo8)
										<li data-bs-target="#carouselIndicators" data-bs-slide-to="7" class="active"></li>
										@endif
										@if($land->photo9)
										<li data-bs-target="#carouselIndicators" data-bs-slide-to="8" class="active"></li>
										@endif
										@if($land->photo10)
										<li data-bs-target="#carouselIndicators" data-bs-slide-to="9" class="active"></li>
										@endif
									</ol>
									<div class="carousel-inner ratio ratio-4x3">
										<div class="carousel-item active">
											<img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo1}}" class="d-block w-100" alt="...">
										</div>
										@if($land->photo2)
										<div class="carousel-item">
											<img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo2}}" class="d-block w-100" alt="...">
										</div>
										@endif
										@if($land->photo3)
										<div class="carousel-item">
											<img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo3}}" class="d-block w-100" alt="...">
										</div>
										@endif
										@if($land->photo4)
										<div class="carousel-item">
											<img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo4}}" class="d-block w-100" alt="...">
										</div>
										@endif
										@if($land->photo5)
										<div class="carousel-item">
											<img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo5}}" class="d-block w-100" alt="...">
										</div>
										@endif
										@if($land->photo6)
										<div class="carousel-item">
											<img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo6}}" class="d-block w-100" alt="...">
										</div>
										@endif
										@if($land->photo7)
										<div class="carousel-item">
											<img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo7}}" class="d-block w-100" alt="...">
										</div>
										@endif
										@if($land->photo8)
										<div class="carousel-item">
											<img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo8}}" class="d-block w-100" alt="...">
										</div>
										@endif
										@if($land->photo9)
										<div class="carousel-item">
											<img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo9}}" class="d-block w-100" alt="...">
										</div>
										@endif
										@if($land->photo10)
										<div class="carousel-item">
											<img src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo10}}" class="d-block w-100" alt="...">
										</div>
										@endif
									</div>
									<a class="carousel-control-prev" href="#carouselIndicators" role="button" data-bs-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span>
										<span class="visually-hidden">Previous</span>
									</a>
									<a class="carousel-control-next" href="#carouselIndicators" role="button" data-bs-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span>
										<span class="visually-hidden">Next</span>
									</a>
								</div>
							</div>
						</div>
						@endif
						<table class="table mb-3">
							<tbody>
								<tr>
									<th scope="row" class="h6">価格</th>
									@if($land->price == 0)
									<td class="h2"><span class="text-danger">-万円</span></td>
									@else
									<td class="h2"><span class="text-danger">{{ number_format($land->price) }}万円</span></td>
									@endif
								</tr>
								<tr>
									<th scope="row" class="h6">所在地</th>
									<td class="h5">{{$land->prefecture->name}}{{$land->address1}}{{$land->address2}}{{$land->address3}}{{$land->other_address}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">交通</th>
									<td class="h6">
										@foreach($land->lines as $line)
										@if($line->pivot->level === 1)
										{{$line->line_name}}{{$line->station_name[0]}}駅より徒歩{{$line->pivot->eki_toho}}
										@endif
										@endforeach
									</td>
								</tr>
								<tr>
									<th scope="row" class="h6">土地面積/坪</th>
									<td class="h6">
										{{ number_format($land->land_menseki, 2) }}&#13217;
										@if($land->keisoku_siki)
										({{$land->keisoku_siki}})
										@endif
										&nbsp;/&nbsp; {{ number_format($land->land_menseki * 0.3025, 2) }}坪
									</td>
								</tr>
								<tr>
									<th scope="row" class="h6">建築条件</th>
									<td class="h5">{{$land->kenchiku_jyouken}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">建ぺい率</th>
									<td class="h5">{{$land->kenpei_rate}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">容積率</th>
									<td class="h5">{{$land->youseki_rate}}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card border-start border-0 border-3 border-info">
					<div class="card-header">設備・条件</div>
					<div class="card-body">
						<table class="table mb-3">
							<tbody>
								<tr>
									<th scope="row" class="h6">設備・サービス</th>
									<td class="h5">{{$land->setubi_jyouken}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">その他</th>
									<td class="h5">
										@if($land->setubi && $land->jyouken)
										{{$land->setubi}}<br>{{$land->jyouken}}
										@elseif($land->keisoku_siki && !$land->jyouken)
										{{$land->setubi}}
										@elseif(!$land->keisoku_siki && $land->jyouken)
										{{$land->jyouken}}
										@endif
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card border-start border-0 border-3 border-info">
					<div class="card-header">物件詳細情報</div>
					<div class="card-body">
						<table class="table mb-3">
							<tbody>
								<tr>
									<th scope="row" class="h6">接道状況</th>
									<td class="h5">{{$land->setudou_jyoukyou}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">セットバック</th>
									<td class="h5">{{$land->setback}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">私道負担面積</th>
									<td class="h5">{{$land->shidou_menseki}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">都市計画</th>
									<td class="h5">{{$land->city_planning}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">登記簿地目</th>
									<td class="h5">{{$land->toukibo_chimoku}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">用途地域</th>
									<td class="h5">{{$land->youto_chiki}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">地勢</th>
									<td class="h5">{{$land->chisei}}</td>
								</tr>
								{{-- <tr>
									<th scope="row" class="h6">土地権利</th>
									<td class="h5">{{$land->setudou_jyoukyou}}</td>
								</tr> --}}
								<tr>
									<th scope="row" class="h6">国土法届出</th>
									<td class="h5">{{$land->kokudohou_todokede}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">現況</th>
									<td class="h5">{{$land->genkyou}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">引渡時期</th>
									<td class="h5">{{$land->hikiwatashi_jiki}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">物件番号</th>
									<td class="h5">{{$land->bukken_num}}</td>
								</tr>
								<tr>
									<th scope="row" class="h6">取引態様</th>
									<td class="h5">{{$land->torihiki_taiyou}}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card border-start border-0 border-3 border-info">
					<div class="card-header">物件周辺の生活情報</div>
					<div class="card-body">
						<table class="table mb-3">
							<tbody>
								<tr>
									<th scope="row" class="h6">周辺環境１</th>
									<td class="h6">
										@if($land->shuhenkankyou1 && $land->kyori1 && $land->jikan1)
										{{$land->shuhenkankyou1}} / 距離：{{$land->kyori1}} / {{$land->jikan1}}
										@elseif($land->shuhenkankyou1 && $land->kyori1 && !$land->jikan1)
										{{$land->shuhenkankyou1}} / 距離：{{$land->kyori1}}
										@elseif($land->shuhenkankyou1 && !$land->kyori1 && $land->jikan1)
										{{$land->shuhenkankyou1}} / {{$land->jikan1}}
										@elseif($land->shuhenkankyou1 && !$land->kyori1 && !$land->jikan1)
										{{$land->shuhenkankyou1}}
										@endif
									</td>
								</tr>
								<tr>
									<th scope="row" class="h6">周辺環境２</th>
									<td class="h6">
										@if($land->shuhenkankyou2 && $land->kyori2 && $land->jikan2)
										{{$land->shuhenkankyou2}} / 距離：{{$land->kyori2}} / {{$land->jikan2}}
										@elseif($land->shuhenkankyou2 && $land->kyori2 && !$land->jikan2)
										{{$land->shuhenkankyou2}} / 距離：{{$land->kyori2}}
										@elseif($land->shuhenkankyou2 && !$land->kyori2 && $land->jikan2)
										{{$land->shuhenkankyou2}} / {{$land->jikan2}}
										@elseif($land->shuhenkankyou2 && !$land->kyori2 && !$land->jikan2)
										{{$land->shuhenkankyou2}}
										@endif
									</td>
								</tr>
								<tr>
									<th scope="row" class="h6">周辺環境３</th>
									<td class="h6">
										@if($land->shuhenkankyou3 && $land->kyori3 && $land->jikan3)
										{{$land->shuhenkankyou3}} / 距離：{{$land->kyori3}} / {{$land->jikan3}}
										@elseif($land->shuhenkankyou3 && $land->kyori3 && !$land->jikan3)
										{{$land->shuhenkankyou3}} / 距離：{{$land->kyori3}}
										@elseif($land->shuhenkankyou3 && !$land->kyori3 && $land->jikan3)
										{{$land->shuhenkankyou3}} / {{$land->jikan3}}
										@elseif($land->shuhenkankyou3 && !$land->kyori3 && !$land->jikan3)
										{{$land->shuhenkankyou3}}
										@endif
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card border-start border-0 border-3 border-info">
					<div class="card-header">その他交通情報</div>
					<div class="card-body">
						<table class="table mb-3">
							<tbody>
								<tr>
									<th scope="row" class="h6">交通１</th>
									<td class="h6">
										@foreach($land->lines as $line)
										@if($line->pivot->level === 1)
										@if($line->line_name && $line->station_name && $line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && !$line->pivot->bus_toho && !$line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅より徒歩{{$line->pivot->eki_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && !$line->pivot->bus_toho && !$line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && $line->pivot->eki_car && !$line->pivot->eki_bus && !$line->pivot->bus_toho && !$line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅より車{{$line->pivot->eki_car}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && $line->pivot->eki_bus && $line->pivot->bus_toho && $line->pivot->bus_route && $line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス{{$line->pivot->eki_bus}}分 {{$line->pivot->bus_route}} {{$line->pivot->bus_stop}}より徒歩{{$line->pivot->bus_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && $line->pivot->eki_bus && $line->pivot->bus_toho && !$line->pivot->bus_route && $line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス{{$line->pivot->eki_bus}}分 {{$line->pivot->bus_stop}}より徒歩{{$line->pivot->bus_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && $line->pivot->bus_toho && !$line->pivot->bus_route && $line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス {{$line->pivot->bus_stop}}より徒歩{{$line->pivot->bus_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && $line->pivot->bus_toho && $line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス {{$line->pivot->bus_route}}より徒歩{{$line->pivot->bus_toho}}
										@endif
										@endif
										@endforeach
									</td>
								</tr>
								<tr>
									<th scope="row" class="h6">交通２</th>
									<td class="h6">
										@foreach($land->lines as $line)
										@if($line->pivot->level === 2)
										@if($line->line_name && $line->station_name && $line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && !$line->pivot->bus_toho && !$line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅より徒歩{{$line->pivot->eki_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && !$line->pivot->bus_toho && !$line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && $line->pivot->eki_car && !$line->pivot->eki_bus && !$line->pivot->bus_toho && !$line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅より車{{$line->pivot->eki_car}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && $line->pivot->eki_bus && $line->pivot->bus_toho && $line->pivot->bus_route && $line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス{{$line->pivot->eki_bus}}分 {{$line->pivot->bus_route}} {{$line->pivot->bus_stop}}より徒歩{{$line->pivot->bus_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && $line->pivot->eki_bus && $line->pivot->bus_toho && !$line->pivot->bus_route && $line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス{{$line->pivot->eki_bus}}分 {{$line->pivot->bus_stop}}より徒歩{{$line->pivot->bus_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && $line->pivot->bus_toho && !$line->pivot->bus_route && $line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス {{$line->pivot->bus_stop}}より徒歩{{$line->pivot->bus_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && $line->pivot->bus_toho && $line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス {{$line->pivot->bus_route}}より徒歩{{$line->pivot->bus_toho}}
										@endif
										@endif
										@endforeach
									</td>
								</tr>
								<tr>
									<th scope="row" class="h6">交通３</th>
									<td class="h6">
										@foreach($land->lines as $line)
										@if($line->pivot->level === 3)
										@if($line->line_name && $line->station_name && $line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && !$line->pivot->bus_toho && !$line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅より徒歩{{$line->pivot->eki_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && !$line->pivot->bus_toho && !$line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && $line->pivot->eki_car && !$line->pivot->eki_bus && !$line->pivot->bus_toho && !$line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅より車{{$line->pivot->eki_car}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && $line->pivot->eki_bus && $line->pivot->bus_toho && $line->pivot->bus_route && $line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス{{$line->pivot->eki_bus}}分 {{$line->pivot->bus_route}} {{$line->pivot->bus_stop}}より徒歩{{$line->pivot->bus_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && $line->pivot->eki_bus && $line->pivot->bus_toho && !$line->pivot->bus_route && $line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス{{$line->pivot->eki_bus}}分 {{$line->pivot->bus_stop}}より徒歩{{$line->pivot->bus_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && $line->pivot->bus_toho && !$line->pivot->bus_route && $line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス {{$line->pivot->bus_stop}}より徒歩{{$line->pivot->bus_toho}}
										@elseif($line->line_name && $line->station_name && !$line->pivot->eki_toho && !$line->pivot->eki_car && !$line->pivot->eki_bus && $line->pivot->bus_toho && $line->pivot->bus_route && !$line->pivot->bus_stop)
										{{$line->line_name}}{{$line->station_name[0]}}駅よりバス {{$line->pivot->bus_route}}より徒歩{{$line->pivot->bus_toho}}
										@endif
										@endif
										@endforeach
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="card border-start border-0 border-3 border-info">
					<div class="card-header">物件地図</div>
					<div class="card-body">
						<div id="target" class="gmaps" style="position: relative; overflow: hidden;"></div>
					</div>
				</div>

				<div id="inquire" class="row">
					<div class="col-xl-9 mx-auto">
						<div class="card border-top border-0 border-4 border-warning">
							<div class="card-header">この物件について問合せする</div>
							<div class="card-body">
								<div class="border p-4 rounded">
									<div class="card-title d-flex align-items-center">
										<div><i class="bx bx-envelope me-1 font-22"></i>
										</div>
										<h5 class="mb-0">資料請求・お問合せ<span class="font-14">(無料)</span></h5>
									</div>
									<hr />
									<form action="{{route('users.land.thanks')}}" name="contactform" method="get" enctype="multipart/form-data">
										@csrf
										@method('post')
										<div class="row mb-3">
											<label for="inputBukken" class="col-sm-3 col-form-label">物件番号&nbsp;<span class="badge bg-danger" style="vertical-align: middle;">必須</span></label>
											<div class="col-sm-9">
												@if($errors->has('bukken_num'))
												<input type="text" name="bukken_num" class="form-control is-invalid" id="inputBukken" value="{{$land->bukken_num}}" aria-describedby="inputBukken" readonly style="color:#999;">
												<div class="invalid-feedback">{{$errors->first('bukken_num')}}</div>
												@else
												<input type="text" name="bukken_num" class="form-control" id="inputBukken" value="{{$land->bukken_num}}" readonly style="color:#999;">
												@endif
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputName" class="col-sm-3 col-form-label">お名前&nbsp;<span class="badge bg-danger" style="vertical-align: middle;">必須</span></label>
											<div class="col-sm-9">
												@if($errors->has('name'))
												<input type="text" name="name" class="form-control is-invalid" id="inputName" aria-describedby="inputName" placeholder="Enter Your Name" value="{{$user->name}}">
												<div class="invalid-feedback">{{$errors->first('name')}}</div>
												@else
												<input type="text" name="name" class="form-control" id="inputName" placeholder="Enter Your Name" value="{{$user->name}}">
												@endif
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputPhone" class="col-sm-3 col-form-label">電話番号&nbsp;<span class="badge bg-danger" style="vertical-align: middle;">必須</span></label>
											<div class="col-sm-9">
												@if($errors->has('tel'))
												<input type="text" name="tel" class="form-control is-invalid" aria-describedby="inputPhone" id="inputPhone" placeholder="Phone No" value="{{$user->tel}}">
												<div class="invalid-feedback">{{$errors->first('tel')}}</div>
												@else
												<input type="text" name="tel" class="form-control" id="inputPhone" placeholder="Phone No" value="{{$user->tel}}">
												@endif
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputEmail" class="col-sm-3 col-form-label">メールアドレス&nbsp;<span class="badge bg-danger" style="vertical-align: middle;">必須</span></label>
											<div class="col-sm-9">
												@if($errors->has('email'))
												<input type="email" name="email" class="form-control is-invalid" id="inputEmail" aria-describedby="inputEmail" placeholder="Email Address" value="{{$user->email}}">
												<div class="invalid-feedback">{{$errors->first('email')}}</div>
												@else
												<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email Address" value="{{$user->email}}">
												@endif
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCheckbox" class="col-sm-3 col-form-label">お問合せ内容</label>
											<div class="col-sm-9">
												<div class="form-check">
													<input name="contact[]" class="form-check-input" type="checkbox" value="look" id="contactChecked1">
													<label class="form-check-label" for="contactChecked1">物件を実際に見たい</label>
												</div>
												<div class="form-check">
													<input name="contact[]" class="form-check-input" type="checkbox" value="know" id="contactChecked2">
													<label class="form-check-label" for="contactChecked2">物件の詳しい情報を知りたい</label>
												</div>
												<div class="form-check">
													<input name="contact[]" class="form-check-input" type="checkbox" value="consultant" id="contactChecked3">
													<label class="form-check-label" for="contactChecked3">ローン・購入に関して相談したい</label>
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputOther" class="col-sm-3 col-form-label">その他</label>
											<div class="col-sm-9">
												<textarea name="other" class="form-control" id="inputOther" rows="3" placeholder="その他ご希望、ご要望等があればご記入ください。"></textarea>
											</div>
										</div>
										<div class="row">
											<label class="col-sm-3 col-form-label"></label>
											<div class="col-sm-9">
												<button type="button" class="btn btn-warning px-5" data-bs-toggle="modal" data-bs-target="#submitModal"><i class="fadeIn animated bx bx-mail-send"></i>お問い合せ内容を確認</button>
											</div>

											<!-- Modal -->
											<div class="modal fade" id="submitModal" tabindex="-1" aria-hidden="true">
												<div class="modal-dialog modal-dialog-centered">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">入力内容の確認</h5>
															<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body">
															<div>
																<p class="text-muted">お名前</p>
																<p class="px-2" id="modalName"></p>
															</div>
															<div>
																<p class="text-muted">電話番号</p>
																<p class="px-2" id="modalPhone"></p>
															</div>
															<div>
																<p class="text-muted">メールアドレス</p>
																<p class="px-2" id="modalEmail"></p>
															</div>
															<div>
																<p class="text-muted">お問合せ内容</p>
																<p class="px-2" id="modalCheck"></p>
															</div>
															<div>
																<p class="text-muted">その他</p>
																<p class="px-2" id="modalBody"></p>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
															<button type="submit" class="btn btn-primary">送信</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
				@can('isAdmin')
				<div class="mb-4 text-center">
					<a href="/admin/lands/{{$land->bukken_num}}" class="btn btn-lg btn-danger px-5 radius-30"><i class="fadeIn animated bx bx-lock fs-4"></i><strong>物件詳細</strong><br><small class="fs-6">管理者専用ボタン</small></a>
				</div>
				@endcan
				<d>
			</div>
		</div>
	</div>
</div>
<!--end page wrapper -->
@endsection

@section("script")
<style>
</style>
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
<script>

	$('#submitModal').on('show.bs.modal', function () {

	  var name = $('#inputName').val()
	  var email = $('#inputEmail').val()
	  var phone = $('#inputPhone').val()
	  var body = $('#inputOther').val()
	  var modal = $(this)
	  if(name === ""){
		const mn1 = document.getElementById('modalName');
		mn1.classList.add('text-danger', 'fw-bolder');
		name = "※名前を入力してください";
	  }
	  if(phone === ""){
		const mp1 = document.getElementById('modalPhone');
		mp1.classList.add('text-danger', 'fw-bolder');
		phone = "※電話番号を入力してください";
	  }
	  if(email === ""){
		const me1 = document.getElementById('modalEmail');
		me1.classList.add('text-danger', 'fw-bolder');
		email = "※メールアドレスを入力してください";
	  }
	  modal.find('#modalName').text(name)
	  modal.find('#modalEmail').text(email)
	  modal.find('#modalPhone').text(phone)
	  modal.find('#modalBody').text(body)

	  const arr = [];
      const chk1 = document.getElementsByName("contact[]");

     	if (chk1[0].checked) {
        	arr.push("※物件を実際に見たい");
      	}
		if (chk1[1].checked) {
			arr.push("※物件の詳しい情報を知りたい");
		} 
		if (chk1[2].checked) {
			arr.push("※ローン・購入に関して相談したい");
		}
      document.getElementById("modalCheck").textContent = arr;

	})

</script>
@endsection