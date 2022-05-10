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
						<li class="breadcrumb-item active" aria-current="page">Lands Index</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->
		<h6 class="mb-0 text-uppercase"><i class="lni lni-users mr-1"></i> 売土地一覧(管理者限定)</h6>
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

				<table id="ad-lnad-list" class="table mb-0 table-striped">
					<thead>
						<tr>
							<th scope="col">物件番号<br>取込日<br>表示設定</th>
							<th scope="col" class="text-nowrap">取引態様<br>取引状況<br>物件種目</th>
							<th scope="col">価格</th>
							<th scope="col" class="text-nowrap">用途地域<br>建ぺい率<br>容積率</th>
							<th scope="col" class="text-nowrap">土地面積<br>㎡単価<br>坪単価</th>
							<th scope="col">所在地 / 沿線駅<br>商号 / 電話番号</th>
							<th scope="col">交通<br>表示 / 非表示<br>図面</th>
						</tr>
					</thead>
					<tbody>
						@if(count($lands) > 0)
						@foreach($lands as $land)
						<tr>
							<td scope="row">{{$land->bukken_num}}<br>
								{{ $land->created_at->format('Y/m/d') }}<br>
								@if($land->display_flag === 1)
								<i class="fadeIn animated bx bx-show-alt"></i>表示中
								@else
								<i class="fadeIn animated bx bx-low-vision"></i>非表示
								@endif
							</td>
							<td>{{$land->torihiki_taiyou}}<br>{{$land->torihiki_jyoukyou}}<br>{{$land->bukken_shumoku}}</td>
							@if($land->price == 0)
							<td class="text-nowrap"><b>-</b>万円</td>
							@else
							<td class="text-nowrap"><b>{{ number_format($land->price) }}</b>万円</td>
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
								{{$land->company}}<br>
								<i class="fadeIn animated bx bx-phone"></i>
								@if($land->contact_tel)
									{{$land->contact_tel}}
								@elseif($land->pic_tel)
									{{$land->pic_tel}}
								@else
									{{$land->company_tel}}
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
									@if(!empty($land->zumen))
									<div class="col">
										<form action="{{route('admin.lands.pdfdownload')}}" name="approvalform" method="get" enctype="multipart/form-data">
											@csrf
											@method('get')
											<input type="hidden" name="zumen" value="{{$land->bukken_num}}">
											<button type="submit" class="btn btn-sm btn-success px-5"><i class="fadeIn animated bx bx-download"></i>図面</button>
										</form>
									</div>
									@endif
									<div class="col">
										<a href="/admin/lands/{{$land->bukken_num}}" class="btn btn-sm btn-info px-5">
											<i class="fadeIn animated bx bx-detail"></i>詳細
										</a>
									</div>
								</div>
							</td>
						</tr>
						@endforeach
						@endif
						<tfoot>
							<tr>
								<th scope="col">物件番号</th>
								<th scope="col" class="text-nowrap">取引態様<br>取引状況<br>物件種目</th>
								<th scope="col">価格</th>
								<th scope="col" class="text-nowrap">用途地域<br>建ぺい率<br>容積率</th>
								<th scope="col" class="text-nowrap">土地面積<br>㎡単価<br>坪単価</th>
								<th scope="col">所在地 / 沿線駅<br>商号 / 電話番号</th>
								<th scope="col">交通<br>図面</th>
							</tr>
						</tfoot>
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
		$('#ad-lnad-list').DataTable({
			displayLength: 25,  
			order: [ [1, 'desc'] ]
		});
	});
</script>
@endsection