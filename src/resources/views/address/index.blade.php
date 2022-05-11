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
			<div class="breadcrumb-title pe-3">Address</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Address</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->
		<h6 class="mb-0 text-uppercase"><i class="fadeIn animated bx bx-world mr-1"></i> 地域を選択してください。</h6>
		<hr />
		@foreach ($list_datas as $list_data)
		<div class="card">
			<div class="card-body">
				<h5 class="card-title d-flex align-items-center">
					<form action="{{route('ad.lists')}}" id="preflists" name="preflists" method="get" enctype="multipart/form-data">
						@csrf
						@method('get')
						<input type="hidden" name="pref_id" value="{{$list_data->prefecture_id}}" form="preflists">
						<a href="javascript:preflists.submit()">{{$list_data->name}}</a><span class="fs-6">から探す</span><span class="ms-1 badge bg-primary">{{$list_data->prefecture_id_count}}</span>
					</form>
				</h5>
				<hr />
				<div class="accordion" id="accordionLists{{$loop->index}}">
					@foreach ($list_data['ad1'] as $address1_list)
					<div class="accordion-item">
						<h2 class="accordion-header" id="heading{{$loop->index}}">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$loop->parent->index}}{{$loop->index}}" aria-expanded="true" aria-controls="collapse{{$loop->parent->index}}{{$loop->index}}">
								{{$address1_list->address1}}<span class="ms-1 badge bg-primary">{{$address1_list->address1_count}}</span>
							</button>
						</h2>
						<div id="collapse{{$loop->parent->index}}{{$loop->index}}" class="accordion-collapse collapse" aria-labelledby="heading{{$loop->parent->index}}{{$loop->index}}" data-bs-parent="#accordionLists{{$loop->parent->index}}">
							<div class="accordion-body">
								<div class="row">
									<h5>
									<form action="{{route('ad.lists')}}" id="ad1lists{{$loop->index}}" name="ad1lists{{$loop->index}}" method="get" enctype="multipart/form-data">
										@csrf
										@method('get')
										<input type="hidden" name="pref_id" value="{{$list_data->prefecture_id}}" form="ad1lists{{$loop->index}}">
										<input type="hidden" name="ad1" value="{{$address1_list->address1}}" form="ad1lists{{$loop->index}}">
										<div class="p-3 col-6 col-sm-3 d-flex align-items-center"><a href="javascript:ad1lists{{$loop->index}}.submit()">{{$address1_list->address1}}</a> を全件表示</div>
									</form>
									</h5>
									@foreach ($address1_list['ad2'] as $address2_list)
									<div class="p-3 col-6 col-sm-3 d-flex align-items-center">
										<form action="{{route('ad.lists')}}" id="ad2lists{{$loop->parent->index}}{{$loop->index}}" name="ad2lists{{$loop->parent->index}}{{$loop->index}}" method="get" enctype="multipart/form-data">
											@csrf
											@method('get')
											<input type="hidden" name="pref_id" value="{{$list_data->prefecture_id}}" form="ad2lists{{$loop->parent->index}}{{$loop->index}}">
											<input type="hidden" name="ad1" value="{{$address1_list->address1}}" form="ad2lists{{$loop->parent->index}}{{$loop->index}}">
											<input type="hidden" name="ad2" value="{{$address2_list->address2}}" form="ad2lists{{$loop->parent->index}}{{$loop->index}}">
											<a href="javascript:ad2lists{{$loop->parent->index}}{{$loop->index}}.submit()">{{$address2_list->address2}}</a><span class="ms-1 badge bg-secondary">{{$address2_list->address2_count}}</span>
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
		@endforeach
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