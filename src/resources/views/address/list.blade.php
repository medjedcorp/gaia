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
						<li class="breadcrumb-item" aria-current="page"><a href="{{ url('address') }}">Address</a></li>
						<li class="breadcrumb-item active" aria-current="page">Address Lists</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->
		<h6 class="mb-0 text-uppercase"><i class="fadeIn animated bx bx-world mr-1"></i> {{$pref_name->name}}の売土地一覧</h6>
		<hr />
		<div class="card">
			<div class="card-body">
				@include('partials.success')
				{{-- @include('partials.danger') --}}
				@if (Session::has('notfound'))
				<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
					<div class="text-white">{{ session('notfound') }}</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
				@endif
				<table id="lnad-list" class="table mb-0 table-striped">
					<thead>
						<tr>
							<th scope="col">イメージ</th>
							<th scope="col">価格</th>
							<th scope="col" class="text-nowrap">用途地域<br>建ぺい率<br>容積率</th>
							<th scope="col" class="text-nowrap">土地面積<br>㎡単価<br>坪単価</th>
							<th scope="col">所在地 / 沿線駅</th>
							<th scope="col">交通</th>
						</tr>
					</thead>
					<tbody>
						@if(count($lands) > 0)
						@foreach($lands as $land)
						<tr>
							@if(empty($land->photo1))
							<td><img src="/images/noimage.png" data-src="/images/noimage.png" class="align-self-start p-1 border lazyload" width="100" height="100" alt="{{$land->bukken_num}}"></td>
							@else
							<td><img src="/images/noimage.png" data-src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo1}}" class="align-self-start p-1 border lazyload" width="100" height="100" alt="{{$land->bukken_num}}"></td>
							@endif
							@if($land->price == 0)
							<td class="text-nowrap text-danger"><strong>-</strong>万円</td>
							@else
							<td class="text-nowrap text-danger"><strong>{{ number_format($land->price) }}</strong>万円</td>
							@endif
							<td>{{$land->youto_chiki}}<br>{{$land->kenpei_rate}}<br>{{$land->youseki_rate}}</td>
							<td class="text-nowrap">{{ number_format($land->land_menseki, 2) }}㎡<br>{{ number_format($land->heibei_tanka) }}万円<br>
								{{ number_format($land->tsubo_tanka) }}万円</td>
							<td>
								{{$land->prefecture->name}}{{$land->address1}}{{$land->address2}}
								{{$land->address3}} <br>
								@if($land->other_address)
								&nbsp;{{$land->other_address}}<br>
								@endif
							</td>
							<td>
								<div class="row row-cols-auto g-3">
									<div class="col-12">
										@foreach($land->lines as $line)
										{{-- @dd($line) --}}
										@if($line->pivot->level === 1)
										<i class="fadeIn animated bx bx-train"></i>{{$line->line_name}}
										{{$line->station_name[0]}}
										@if($line->pivot->eki_toho)
										/ <i class="fadeIn animated bx bx-walk"></i>徒歩{{$line->pivot->eki_toho}}
										@endif
										@if($line->pivot->eki_bus)
										/ <i class="fadeIn animated bx bx-bus"></i>バス{{$line->pivot->eki_bus}}
										@endif
										@endif
										@endforeach
									</div>
									<div class="col">
										<a href="/lands/show/{{$land->bukken_num}}" class="btn btn-sm btn-info px-5">
											<i class="fadeIn animated bx bx-detail"></i>詳細
										</a>
									</div>
									<div class="col">
										<a href="/lands/show/{{$land->bukken_num}}" class="btn btn-sm btn-warning px-5">
											<i class="fadeIn animated bx bx-detail"></i>問合せ
										</a>
									</div>
								</div>
							</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
				<hr>
				{{-- <div style="display: flex;justify-content: center;">{{$lands->appends(request()->query())->links()}}</div> --}}
			</div>


		</div>
	</div>
</div>
<!--end page wrapper -->
@endsection

@section("script")

<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
	'use strict';
	$(document).ready(function() {
		$('#lnad-list').DataTable({
			displayLength: 25,  
			order: [ [4, 'desc'] ]
		});
	});
</script>
<script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
<script>
	$("img.lazyload").lazyload();
</script>
@endsection