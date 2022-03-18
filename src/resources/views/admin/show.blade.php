@extends("layouts.app")

@section("style")
<link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
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
						<li class="breadcrumb-item active" aria-current="page">Lands Detail</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->
		
		
		<div class="card">

			<div class="card-body">
				@include('partials.success')
				@include('partials.danger')
				<div class="card-title">
					<h6 class="mb-0 text-uppercase"><i class="lni lni-users mr-1"></i> 売土地詳細(管理者限定)</h6>
				</div>
				<hr />
				<table class="table mb-0 table-striped">
					<thead>
						<tr>
							<th scope="col">物件番号</th>
							<th scope="col">取引態様<br>取引状況<br>物件種目</th>
							<th scope="col">価格</th>
							<th scope="col">用途地域<br>建ぺい率<br>容積率</th>
							<th scope="col">土地面積<br>㎡単価<br>坪単価</th>
							<th scope="col">所在地 / 沿線駅<br>商号 / 電話番号</th>
							<th scope="col">交通<br>図面</th>
						</tr>
					</thead>
					<tbody>
						@if(count($lands) > 0)
						@foreach($lands as $land)
						<tr>
							<td scope="row">{{$land->bukken_num}}</td>
							<td>{{$land->torihiki_taiyou}}<br>{{$land->torihiki_jyoukyou}}<br>{{$land->bukken_shumoku}}</td>
							<td>{{$land->price}}万円</td>
							<td>{{$land->youto_chiki}}<br>{{$land->kenpei_rate}}<br>{{$land->youseki_rate}}</td>
							<td>{{$land->land_menseki}}㎡<br>{{$land->heibei_tanka}}万円<br>{{$land->tsubo_tanka}}万円</td>
							<td>
								{{-- $land->prefecture->name --}}{{$land->address1}}{{$land->address2}}{{$land->address3}}&nbsp;{{$land->other_address}}<br>{{$land->line_cd1->line_name}}{{$land->station_cd1}}<br>{{$land->company}}<br>{{$land->contact_tel}}
							</td>
							<td>{{$land->eki_toho1}}
								<div class="row row-cols-auto g-3">
									<div class="col">
										<form action="{{route('admin.lands.pdfdownload')}}" name="approvalform" method="get" enctype="multipart/form-data">
											@csrf
											@method('get')
											<input type="hidden" name="zumen" value="{{$land->bukken_num}}">
										<button type="submit" class="btn btn-success px-5">図面</button>
										</form>
									</div>
									<div class="col">
										<a href="/admin/lands/{{$land->bukken_num}}" class="btn btn-info px-5">
										詳細
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
				<div style="display: flex;justify-content: center;">{{$lands->appends(request()->query())->links()}}</div>
			</div>


		</div>
	</div>
</div>
<!--end page wrapper -->
@endsection

@section("script")
<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
	'use strict';
	$(document).ready(function() {
		$('#landstable').DataTable({
			displayLength: 25,  
			order: [ [1, 'desc'] ]
		});
	});
</script>
@endsection