@extends("layouts.app")


@section("wrapper")
<div class="page-wrapper">
	<div style="position: relative; overflow: hidden;width: 100%;height: 0;height:100vmin;">
		<div id="map" class="gmaps" style="position: absolute; height:100vmin; width:100%;top:0; left:0; ">
		</div>
		<form action="{{route('result.currentLocation')}}" method="get" enctype="multipart/form-data">
			@csrf
			@method('get')
			<input type="hidden" name="lat" class="lat_input" value="">
			<input type="hidden" name="lng" class="lng_input" value="">
			<button type="submit" class="btn btn-sm btn-success px-5" disabled>周辺を表示</button>
		</form>
		<div class="row row-cols-1 row-cols-lg-3" style="position: fixed; bottom:5%;">
			<div class="col" style=" display: flex;">
				<div class="card" style="justify-content: center;">
					<div class="row g-0">
						<div class="col-md-4">
							<img src="assets/images/products/16.png" class="img-fluid" alt="...">
						</div>
						<div class="col-md-8">
							<div class="card-body">
								<h6 class="card-title">Light Grey Headphone</h6>
								<div class="cursor-pointer my-2">
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-secondary"></i>
								</div>
								<div class="clearfix">
									<p class="mb-0 float-start fw-bold"><span class="me-2 text-decoration-line-through text-secondary">$240</span><span>$199</span></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card">
					<div class="row g-0">
						<div class="col-md-4">
							<img src="assets/images/products/17.png" class="img-fluid" alt="...">
						</div>
						<div class="col-md-8">
							<div class="card-body">
								<h6 class="card-title">Black Cover iPhone 8</h6>
								<div class="cursor-pointer my-2">
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-warning"></i>
								</div>
								<div class="clearfix">
									<p class="mb-0 float-start fw-bold"><span class="me-2 text-decoration-line-through text-secondary">$179</span><span>$110</span></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card">
					<div class="row g-0">
						<div class="col-md-4">
							<img src="assets/images/products/19.png" class="img-fluid" alt="...">
						</div>
						<div class="col-md-8">
							<div class="card-body">
								<h6 class="card-title">Men Hand Watch</h6>
								<div class="cursor-pointer my-2">
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-warning"></i>
									<i class="bx bxs-star text-secondary"></i>
									<i class="bx bxs-star text-secondary"></i>
								</div>
								<div class="clearfix">
									<p class="mb-0 float-start fw-bold"><span class="me-2 text-decoration-line-through text-secondary">$150</span><span>$120</span></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- <div class="page-content"> --}}
		{{-- <div id="simple-map" class="gmaps"></div> --}}
		<!--breadcrumb-->
		{{-- <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="breadcrumb-title pe-3">Maps</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Google Maps</li>
					</ol>
				</nav>
			</div>
			<div class="ms-auto">
				<div class="btn-group">
					<button type="button" class="btn btn-primary">Settings</button>
					<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
					</button>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item" href="javascript:;">Action</a>
						<a class="dropdown-item" href="javascript:;">Another action</a>
						<a class="dropdown-item" href="javascript:;">Something else here</a>
						<div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
					</div>
				</div>
			</div>
		</div> --}}
		<!--end breadcrumb-->
		{{-- <div class="row">
			<div class="col-xl-9 mx-auto">
				<h6 class="text-uppercase">Simple Basic Map</h6>
				<hr />
				<div class="card">
					<div class="card-body">
						<div id="simple-map" class="gmaps"></div>
					</div>
				</div>
				<h6 class="text-uppercase">Map With Marker</h6>
				<hr />
				<div class="card">
					<div class="card-body">
						<div id="marker-map" class="gmaps"></div>
					</div>
				</div>
				<h6 class="text-uppercase">Over Layer Map</h6>
				<hr />
				<div class="card">
					<div class="card-body">
						<div id="overlay-map" class="gmaps"></div>
					</div>
				</div>
				<h6 class="text-uppercase">Polygonal Map</h6>
				<hr />
				<div class="card">
					<div class="card-body">
						<div id="polygons-map" class="gmaps"></div>
					</div>
				</div>
				<h6 class="text-uppercase">Styled Map</h6>
				<hr />
				<div class="card">
					<div class="card-body">
						<div id="style-map" class="gmaps"></div>
					</div>
				</div>
			</div>
		</div> --}}
		<!--end row-->
		{{--
	</div> --}}
</div>
@endsection


@section("script")
<script>
	// currentLocation.jsで使用する定数latに、controllerで定義した$latをいれて、currentLocation.jsに渡す
	const lat = {{ $lat }};

	// currentLocation.jsで使用する定数lngに、controllerで定義した$lngをいれて、currentLocation.jsに渡す
	const lng = {{ $lng }};
</script>
<script src="{{ asset('js/setLocation.js') }}"></script>
<script src="{{ asset('/js/currentLocation.js') }}"></script>
<!-- google maps api -->
<script src="https://maps.googleapis.com/maps/api/js??language=ja&region=JP&key={{ config('const.map_key') }}&callback=initMap" async defer></script>
@endsection