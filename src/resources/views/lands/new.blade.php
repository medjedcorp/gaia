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
						<li class="breadcrumb-item active" aria-current="page">■新着物件一覧</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->
		<h6 class="mb-0 text-uppercase"><i class="lni lni-users mr-1"></i> 売土地一覧</h6>
		<hr />
		{{-- スマホ用start --}}
		@if($terminal === 'mobile')
		<div class="card d-md-none">
			<div class="card-body">
				<h5>■新着物件一覧</h5>
				<div id="scroll">
					<ul class="list-group">
						@foreach($lands as $land)
						<li class="list-group-item">
							<a href="/lands/show/{{$land->bukken_num}}">
								<div class="d-flex">
									<div class="flex-shrink-0">
										@if(empty($land->photo1))
										<img src="/images/noimage.png" data-src="/images/noimage.png" class="img-thumbnail lazyload mb-2" width="90" height="90" alt="{{$land->bukken_num}}">
										@else
										<img src="/images/noimage.png" data-src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo1}}" class="img-thumbnail lazyload mb-2" width="90" height="90" alt="{{$land->bukken_num}}">
										@endif
									</div>
									<div class="flex-grow-1 ms-3">
										<div class="mb-1 d-flex align-items-bottom">
										<span class="badge bg-primary w-25 me-1">{{$land->bukken_shumoku}}</span>
										<span class="badge bg-gradient-bloody text-white shadow-sm w-25">新着</span>
										</div>
										<span class="h6">{{$land->prefecture->name}}{{$land->address1}}{{$land->address2}}{{$land->address3}}</span>
										@if($land->price == 0)
										<h5 class="mt-0 text-danger"><span>-</span>万円</h5>
										@else
										<h5 class="mt-0 text-danger"><span><strong>{{ number_format($land->price) }}</strong></span>万円</h5>
										@endif
									</div>
								</div>
								<p class="text-secondary">土地面積：{{ number_format($land->land_menseki) }}&#13217; / 建築条件：{{$land->kenchiku_jyouken}}<br>
									建ぺい率：{{$land->kenpei_rate}} / 容積率：{{ $land->youseki_rate }}<br>
								</p>
							</a>
						</li>
						@endforeach
					</ul>
					{{ $lands->links() }}
				</div>
			</div>
		</div>
		@endif
		{{-- スマホ用end --}}
		@if($terminal === 'pc')
		<div class="card d-none d-md-block">
			<div class="card-body">
				@include('partials.success')
				{{-- @include('partials.danger') --}}
				@if (Session::has('notfound'))
				<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
					<div class="text-white">{{ session('notfound') }}</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
				@endif
				<table id="lnad-list" class="table mb-0 table-striped align-middle">
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
							<td class="text-nowrap text-danger">
								@if($land->newflag)
								<span class="badge bg-gradient-bloody text-white shadow-sm w-100 mb-1">New</span><br>
								@endif
								<strong>-</strong>万円
							</td>
							@else
							<td class="text-nowrap text-danger">
								@if($land->newflag)
								<span class="badge bg-gradient-bloody text-white shadow-sm w-100 mb-1">New</span><br>
								@endif
								<strong>{{ number_format($land->price) }}</strong>万円
							</td>
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
										<a href="/lands/show/{{$land->bukken_num}}#inquire" class="btn btn-sm btn-warning px-5">
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
		@endif
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

<script>
	$("img.lazyload").lazyload();
</script>

<script>
	$(document).ready(function() {
    $('ul.pagination').hide();
    $('#scroll').jscroll({
        autoTrigger: true,
        loadingHtml: '<div class="text-center"><div class="spinner-border text-primary text-center" role="status"> <span class="visually-hidden">Loading...</span></div></div>',
        padding: 0,
        nextSelector: '.pagination li.active + li a',
        contentSelector: '#scroll',
        callback: function() {
            $('ul.pagination').remove();
        	}
    	});
	});
</script>
@endsection