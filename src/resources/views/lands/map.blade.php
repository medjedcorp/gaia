@extends("layouts.app")
@section("wrapper")
<div class="page-wrapper">
	<div id="gmap">
		<div id="map">
		</div>
		@if(!$isMobile)
		@isset($lands)
		<div id="mapnavi" class="card mt-2" style="height: 70vh; width: 25rem;">
			<div class="card-body">
				<table class="table float-end">
					<thead class="sticky-top bg-white">
						<tr>
							<th scope="col" class="pt-3 border-bottom border-primary">■物件一覧</th>
						</tr>
					</thead>
					<tbody>
						@foreach($lands as $land)
						<tr>
							<td>
								<a href="javascript:landclick({{$loop->index}})">
									<div class="d-flex align-items-center text-dark">
										@if(empty($land->photo1))
										<img src="/images/noimage.png" data-src="/images/noimage.png" class="align-self-start p-1 border lazyload" width="90" height="90" alt="{{$land->bukken_num}}">
										@else
										<img src="/images/noimage.png" data-src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo1}}" class="align-self-start p-1 border lazyload" width="90" height="90" alt="{{$land->bukken_num}}">
										@endif
										<div class="flex-grow-1 ms-3">
											<h6 class="card-title">
												<div class="badge bg-primary" style="display: block; margin-bottom:5px;">{{$land->bukken_shumoku}}</div><span>{{$land->address1}}{{$land->address2}}{{$land->address3}}</span>
											</h6>
											@if($land->price == 0)
											<h5 class="mt-0 text-danger"><span>-</span>万円</h5>
											@else
											<h5 class="mt-0 text-danger"><span>{{ number_format($land->price) }}</span>万円</h5>
											@endif
											<p> 土地面積：{{ number_format($land->land_menseki) }}&#13217; / 建築条件：{{$land->kenchiku_jyouken}}<br>
												建ぺい率：{{$land->kenpei_rate}} / 容積率：{{ $land->youseki_rate }}<br>
											</p>
										</div>
									</div>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		@endisset
		@endif
	</div>
	@if($isMobile)
	<div id="sp-bottom-menu" 　class="topbar">
		<div class="page-content">
			<div class="row row-cols-1">
				<div class="col">
					{{-- <h6 class="mb-0 text-uppercase">With controls</h6>
					<hr /> --}}
					<div class="card">
						<div class="card-body" style="padding:0;">
							<div id="mapLnadControls" class="carousel slide" data-bs-ride="carousel">
								<div class="carousel-inner">
									@foreach($lands as $land)
									<div class="carousel-item @if($loop->first) active @endif p-3">
										<a href="javascript:landclick({{$loop->index}})">
											<div class="d-flex">
												<div class="flex-shrink-0">
													@if(empty($land->photo1))
													<img src="/images/noimage.png" data-src="/images/noimage.png" class="img-thumbnail lazyload mb-2" width="80" height="80" alt="{{$land->bukken_num}}">
													@else
													<img src="/images/noimage.png" data-src="/storage/landimages/{{$land->bukken_num}}/{{$land->photo1}}" class="img-thumbnail lazyload mb-2" width="80" height="80" alt="{{$land->bukken_num}}" style="height: 80px; width:80px;">
													@endif
												</div>
												<div class="flex-grow-1 ms-3">
													<div class="mb-1 d-flex align-items-bottom">
														<span class="badge bg-primary w-25 me-1">{{$land->bukken_shumoku}}</span>
														<span class="badge bg-gradient-bloody text-white shadow-sm w-25">新着</span>
													</div>
													<span class="h6">{{$land->prefecture->name}}{{$land->address1}}{{$land->address2}}</span>
													@if($land->price == 0)
													<h5 class="mt-0 text-danger"><span>-</span>万円</h5>
													@else
													<h5 class="mt-0 text-danger"><span><strong>{{ number_format($land->price) }}</strong></span>万円</h5>
													@endif

												</div>
												<p class="text-secondary" style="font-size: 90%;">土地面積：{{ number_format($land->land_menseki) }}&#13217; / 建築条件：{{$land->kenchiku_jyouken}}<br>
													建ぺい率：{{$land->kenpei_rate}} / 容積率：{{ $land->youseki_rate }}<br>
												</p>
											</div>

										</a>
									</div>
									@endforeach
								</div>
								<a class="carousel-control-prev text-primary" href="#mapLnadControls" role="button" data-bs-slide="prev" style="font-size: 200%;"> 
									<i class="fadeIn animated bx bx-chevron-left"></i>
									<span class="visually-hidden">Previous</span>
								</a>
								<a class="carousel-control-next text-primary" href="#mapLnadControls" role="button" data-bs-slide="next" style="font-size: 200%;"> 
									<i class="fadeIn animated bx bx-chevron-right"></i>
									<span class="visually-hidden">Next</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
</div>
@endsection


@section("script")

<script>
	'use strict';

var map;
var marker = [];
var infoWindow = [];
// var landlist = "";
var noimg = `noimage.png`;
// var noimg = "../images/noimage.png";

var markerData = [ // マーカーを立てる場所名・緯度・経度
    @foreach($lands as $land)
	{
		@if(empty($land->photo1))
		photo1: noimg,
		@else
		photo1: `{{ $land->bukken_num . "/" . $land->photo1 }}`,
		@endif
        bukken_num: {{ $land->bukken_num }},
        // photo1: `{{ $land->photo1 }}`,
        bukken_shumoku: `{{ $land->bukken_shumoku }}`,
        address1: `{{ $land->address1 }}`,
        address2: `{{ $land->address2 }}`,
        address3: `{{ $land->address3 }}`,
		@if($land->price == 0)
		price: '-',
		@else
		price: `{{ number_format($land->price) }}`,
		@endif
        land_menseki: `{{ number_format($land->land_menseki) }}`,
        kenpei_rate: `{{ $land->kenpei_rate }}`,
        youseki_rate: `{{ $land->youseki_rate }}`,
        kenchiku_jyouken: `{{ $land->kenchiku_jyouken }}`,
        lat: {{ $land->latitude }},
        lng: {{ $land->longitude }},
    },
    @endforeach
];

console.log(markerData)

function initMap() {

    function success(pos) {
        const latitude = pos.coords.latitude;
        const longitude = pos.coords.longitude;
		const center = {lat:latitude, lng:longitude}; 

		map = new google.maps.Map(document.getElementById('map'), { // #mapに地図を埋め込む
			position: center,
			center: center, // 地図の中心を指定
			zoom: 15 // 地図のズームを指定
		});

		for (var i = 0; i < markerData.length; i++) {
			let markerLatLng = new google.maps.LatLng({lat: markerData[i]['lat'], lng: markerData[i]['lng']});
			
        	marker[i] = new google.maps.Marker({ // マーカーの追加
         		position: markerLatLng, // マーカーを立てる位置を指定
            	map: map, // マーカーを立てる地図を指定
       		});

			@if(!$isMobile)
			infoWindow[i] = new google.maps.InfoWindow({ // 吹き出しの追加
         		content: '<a href="/lands/show/'+ markerData[i]['bukken_num'] +'" class="container"><div class="row g-0 text-dark"><div class="col-5"><img src="/storage/landimages/'+ markerData[i]['photo1']  +'" alt="' + markerData[i]['bukken_num'] + '" width="110" height="110" class="p-1 border"></div><div class="col"><div class="card-body"><h6 class="card-title"><div class="badge bg-primary" style="display: block; margin-bottom:5px;">' + markerData[i]['bukken_shumoku'] + '</div><span>' + markerData[i]['address1'] + markerData[i]['address2'] + markerData[i]['address3'] + '</span></h6><div class="clearfix"><h6 class="mb-0 float-start text-danger"><span class=" fs-5">' + markerData[i]['price'] + '</span><span class="fs-6">万円</span></h6></div><p class="card-text">土地面積：' + markerData[i]['land_menseki'] + '&#13217;<br>建ぺい率：' + markerData[i]['kenpei_rate'] + '<br>容積率：' + markerData[i]['youseki_rate'] + '<br>建築条件：' + markerData[i]['kenchiku_jyouken'] + '</p></div></div></div><div class="row g-0"><div class="col-12"><div type="button" class="btn btn-success px-3 d-block"><i class="fadeIn animated bx bx-detail"></i>詳細を表示</div></div></div></a>' // 吹き出しに表示する内容
       		});
			@endif

			@if($isMobile)
			infoWindow[i] = new google.maps.InfoWindow({ // 吹き出しの追加
         		content: '<a href="/lands/show/'+ markerData[i]['bukken_num'] +'" class="container"><div class="row g-0 text-dark"><div class="col-5"><img src="/storage/landimages/'+ markerData[i]['photo1']  +'" alt="' + markerData[i]['bukken_num'] + '" width="90" height="90" class="p-1 border"></div><div class="col"><div class="card-body"><h6 class="card-title"><div class="badge bg-primary" style="display: block; margin-bottom:5px;">' + markerData[i]['bukken_shumoku'] + '</div><span>' + markerData[i]['address1'] + markerData[i]['address2'] + markerData[i]['address3'] + '</span></h6><div class="clearfix"><h6 class="mb-0 float-start text-danger"><span class=" fs-5">' + markerData[i]['price'] + '</span><span class="fs-6">万円</span></h6></div></div></div></div><div class="row g-0 mb-2"><p class="card-text col-12 text-secondary" style="font-size:80%;">土地面積：' + markerData[i]['land_menseki'] + '&nbsp;/&nbsp;建ぺい率：' + markerData[i]['kenpei_rate'] + '<br>容積率：' + markerData[i]['youseki_rate'] + '&nbsp;/&nbsp;建築条件：' + markerData[i]['kenchiku_jyouken'] + '</p></div><div class="row g-0"><div class="col-12"><div type="button" class="btn btn-sm btn-success px-1 d-block"><i class="fadeIn animated bx bx-detail"></i>詳細を表示</div></div></div></a>' // 吹き出しに表示する内容
       		});
			@endif

    		markerEvent(i); // マーカーにクリックイベントを追加
		}


		// マーカーにクリックイベントを追加
		function markerEvent(i) {
			marker[i].addListener('click', function() { // マーカーをクリックしたとき
				infoWindow[i].open(map, marker[i]); // 吹き出しの表示
				map.panTo(this.getPosition());
			});
		}
    }

    function fail(error) {
        alert('位置情報の取得に失敗しました。エラーコード：' + error.code);
    }

    navigator.geolocation.getCurrentPosition(success, fail);
}

var openWindow;
	
function landclick(i) {
	if (openWindow) {
		openWindow.close();
	}
	infoWindow[i].open(map, marker[i]);
	map.panTo(marker[i].getPosition());
	openWindow = infoWindow[i];
}
// ウィンドウの高さを自動調整
$(function(){
　　　function adjust(){
          var h = $(window).height(); //ウィンドウの高さ
          var h1= $('.topbar').height(); //他要素の高さ
          $('#gmap').css('height', h-h1); //可変部分の高さを適用
     }

     adjust();

	 $(window).resize(adjust);
});
</script>
<style type="text/css">
	.page-footer {
		display: none;
	}

	.page-wrapper {
		margin-top: 0px;
		margin-bottom: 0px;
	}

	.card-body {
		padding-top: 0rem;
		padding-bottom: 0.5rem;
	}
</style>

<script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
<script>
	$("img.lazyload").lazyload();
	var ps = new PerfectScrollbar('#mapnavi');
</script>
<!-- google maps api -->
<script src="https://maps.googleapis.com/maps/api/js??language=ja&region=JP&key={{ config('const.map_key') }}&callback=initMap" async defer></script>
@endsection