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
						<li class="breadcrumb-item"><a href="/lands">Lands Index</a></li>
						<li class="breadcrumb-item"><a href="/landshow/{{$land->bukken_num}}">Land Detail</a></li>
						<li class="breadcrumb-item active" aria-current="page">Land Contact</li>
					</ol>
				</nav>
			</div>
		</div>
		<!--end breadcrumb-->

		<div class="row">
			<div class="col-xl-9 mx-auto">
				<h6 class="mb-0 text-uppercase">{{$land->prefecture->name}}{{$land->address1}}{{$land->address2}}{{$land->address3}}{{$land->other_address}}に関するお問い合わせ</h6>
				<hr/>
				<div class="card border-top border-0 border-4 border-info">
					<div class="card-body">
						<div class="border p-4 rounded">
							<div class="card-title d-flex align-items-center">
								<div><i class="bx bxs-user me-1 font-22 text-info"></i>
								</div>
								<h5 class="mb-0 text-info">User Registration</h5>
							</div>
							<hr/>
							<div class="row mb-3">
								<label for="inputEnterYourName" class="col-sm-3 col-form-label">Enter Your Name</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="inputEnterYourName" placeholder="Enter Your Name">
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputPhoneNo2" class="col-sm-3 col-form-label">Phone No</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="inputPhoneNo2" placeholder="Phone No">
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputEmailAddress2" class="col-sm-3 col-form-label">Email Address</label>
								<div class="col-sm-9">
									<input type="email" class="form-control" id="inputEmailAddress2" placeholder="Email Address">
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputChoosePassword2" class="col-sm-3 col-form-label">Choose Password</label>
								<div class="col-sm-9">
									<input type="email" class="form-control" id="inputChoosePassword2" placeholder="Choose Password">
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputConfirmPassword2" class="col-sm-3 col-form-label">Confirm Password</label>
								<div class="col-sm-9">
									<input type="email" class="form-control" id="inputConfirmPassword2" placeholder="Confirm Password">
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputAddress4" class="col-sm-3 col-form-label">Address</label>
								<div class="col-sm-9">
									<textarea class="form-control" id="inputAddress4" rows="3" placeholder="Address"></textarea>
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputAddress4" class="col-sm-3 col-form-label"></label>
								<div class="col-sm-9">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="gridCheck4">
										<label class="form-check-label" for="gridCheck4">Check me out</label>
									</div>
								</div>
							</div>
							<div class="row">
								<label class="col-sm-3 col-form-label"></label>
								<div class="col-sm-9">
									<button type="submit" class="btn btn-info px-5">Register</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end row-->


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
@endsection