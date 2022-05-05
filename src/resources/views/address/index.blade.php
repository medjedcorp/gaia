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
						<li class="breadcrumb-item active" aria-current="page">Address Index</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->
		<h6 class="mb-0 text-uppercase"><i class="lni lni-users mr-1"></i> 住所から探す</h6>
		<hr />
		<div class="card">
			<div class="card-header bg-transparent">
				<div class="d-flex align-items-center">
					<div>
						<h6 class="mb-0">地域を選択してください。<span class="fs-6"> /（複数選択できます）</span></h6>
					</div>
				</div>
			</div>
			<div class="card-body">
				@include('partials.success')
				{{-- @include('partials.danger') --}}
				@if (Session::has('notfound'))
				<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
					<div class="text-white">{{ session('notfound') }}</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
				@endif
				<div class="notices">
					<div>NOTICE:</div>
					<div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
				</div>
				@foreach($prefs as $pref)
					{{$pref->name}}
				@endforeach
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