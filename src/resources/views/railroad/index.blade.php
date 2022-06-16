@extends("layouts.app")

@section("style")
<link href="{{ asset('css/common.css') }}" rel="stylesheet" />
@endsection

@section("wrapper")
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="breadcrumb-title pe-3">RailLoad</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Lines</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->
		<h6 class="mb-0 text-uppercase"><i class="fadeIn animated bx bx-train mr-1"></i> 沿線を選択してください。</h6>
		<hr />
		@foreach ($list_datas as $list_data)
		<div class="card">
			<div class="card-body">
				<h5 class="card-title d-flex align-items-center">
					<form action="{{ route('users.lands.index') }}" id="preflists{{$loop->index}}" name="preflists{{$loop->index}}" method="get" enctype="multipart/form-data">
						@csrf
						@method('get')
						<input type="hidden" name="keyword" value="{{$list_data->name}}" form="preflists{{$loop->index}}">
						<a href="javascript:preflists{{$loop->index}}.submit()">{{$list_data->name}}</a><span class="fs-6">から探す</span><span class="ms-1 badge bg-primary rounded-pill">{{$list_data->prefecture_id_count}}</span>
					</form>
				</h5>
				<hr />
				<div class="accordion" id="accordionTrain{{$loop->index}}">
					@foreach($list_data->company as $company)
					<div class="accordion-item">
						<h2 class="accordion-header" id="heading{{$loop->parent->index}}{{$loop->index}}">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$loop->parent->index}}{{$loop->index}}" aria-expanded="false" aria-controls="collapse{{$loop->parent->index}}{{$loop->index}}">
								<h5 class="d-flex align-items-center">{{$company['company_name']}}<span class="ms-1 badge bg-primary rounded-pill">{{$company['company_cd_count']}}</span></h5>
							</button>
						</h2>
						<div id="collapse{{$loop->parent->index}}{{$loop->index}}" class="accordion-collapse collapse" aria-labelledby="heading{{$loop->parent->index}}{{$loop->index}}" data-bs-parent="#accordionTrain{{$loop->parent->index}}">
							<div class="accordion-body">
								<div class="accordion accordion-flush" id="accordionLine{{$loop->parent->index}}{{$loop->index}}">
									@foreach($company['lines'] as $line)
									<div class="accordion-item">
										<h2 class="accordion-header" id="heading{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}">
											<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}" aria-expanded="false" aria-controls="collapse{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}">
												<h6 class="d-flex align-items-center">{{$line['line_name']}}<span class="ms-1 badge bg-primary rounded-pill">{{$line['line_id_count']}}</span></h6>
											</button>
										</h2>
										<div id="collapse{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}" class="accordion-collapse collapse" aria-labelledby="heading{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}" data-bs-parent="#accordionLine{{$loop->parent->parent->index}}{{$loop->parent->index}}">
											<div class="accordion-body">
												<div class="row">
													@foreach($line['stations'] as $station)
													<div class="p-3 col-12 col-md-4">
														{{-- バグ対策index0のときだけフォームが作成されない…これしたら作成される --}}
														@if($loop->index === 0)
														<form></form>
														@endif
														{{-- バグ対策終了 --}}
														<form action="{{route('rail.lists')}}" id="raillists{{$loop->parent->parent->parent->index}}{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}" name="raillists{{$loop->parent->parent->parent->index}}{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}" method="get" enctype="multipart/form-data">
															@csrf
															@method('get')
															<input type="hidden" name="pref_id" value="{{$list_data->prefecture_id}}" form="raillists{{$loop->parent->parent->parent->index}}{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}">
															<input type="hidden" name="line_cd" value="{{$line['line_cd']}}" form="raillists{{$loop->parent->parent->parent->index}}{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}">
															<input type="hidden" name="station_cd" value="{{$station['station_cd']}}" form="raillists{{$loop->parent->parent->parent->index}}{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}">
															<div class="d-flex align-items-center">
															<a href="javascript:raillists{{$loop->parent->parent->parent->index}}{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}.submit()" class="fs-6">{{$station['station_name']}}</a><span class="ms-1 badge bg-primary rounded-pill">{{$station['station_cd_count']}}</span>
															</div>
														</form>
													</div>
													@endforeach
												</div>
											</div>
										</div>
									</div>
									@endforeach
								</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
		@endforeach
	</div>
</div>
<!--end page wrapper -->
@endsection

@section("script")
@endsection