@extends("layouts.app")
@section("style")
<link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
@endsection

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <div class="row row-cols-1 row-cols-xl-2">
            <div class="col">
                <a href="/lands/index" class="text-muted">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">掲載件数</p>
                                    <h4 class="my-1 text-info">{{$lands_count}}<span class="fs-6">件</span></h4>
                                    @if(isset( $update_date->updated_at ))
                                    <p class="mb-0 font-13">最終更新日:{{$update_date->updated_at->format("Y年m月d日")}}</p>
                                    @endif
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-home'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="/lands/new" class="text-muted">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">新着物件</p>
                                    <h4 class="my-1 text-danger">{{$new_count}}<span class="fs-6">件</span></h4>
                                    <p class="mb-0 font-13">１週間 / {{$seven_count}}件</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='bx bx-home-smile'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!--end row-->

        {{-- スマホ用start --}}
        <div class="card radius-10 d-md-none">
            <div class="card-body">
                <h5>■新着物件一覧</h5>
                <div>
                    @if($isMobile)
					<ul class="list-group mb-3">
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
                    @endif
				</div>
                <div class="d-grid gap-2 col-12 mx-auto">
                    <a href="/lands/index" class="btn btn-secondary px-5"><i class="fadeIn animated bx bx-chevron-down-circle"></i>もっと見る</a>
                </div>
            </div>

        </div>
        {{-- スマホ用end --}}

        <div class="card radius-10 d-none d-md-block">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">売土地一覧</h6>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="lnad-list" class="table mb-3 table-striped align-middle">
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
                                            {{-- @dd($line->station_name[0]) --}}
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
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <a href="/lands/index" class="btn btn-secondary px-5"><i class="fadeIn animated bx bx-chevron-down-circle"></i>もっと見る</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header bg-transparent">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">■地図から探す</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="mapcontainer">
                            <div id="gmap" class="col-lg-7 col-xl-8 border-end">
                                <div id="map"></div>
                            </div>
                            <div id="mapscrollcontainer" class="col-lg-5 col-xl-4">
                                <div id="mapscroll">
                                    @isset($maplands)
                                    <div class="mb-4">
                                        @foreach($maplands as $mapland)
                                        <a href="javascript:landclick({{$loop->index}})">
                                            <div class="d-flex align-items-center text-dark">
                                                @if(empty($mapland->photo1))
                                                <img src="/images/noimage.png" data-src="/images/noimage.png" class="align-self-start p-1 border lazyload" width="90" height="90" alt="{{$mapland->bukken_num}}">
                                                @else
                                                <img src="/images/noimage.png" data-src="/storage/landimages/{{$mapland->bukken_num}}/{{$mapland->photo1}}" class="align-self-start p-1 border lazyload" width="90" height="90" alt="{{$mapland->bukken_num}}">
                                                @endif
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="card-title">
                                                        <div class="badge bg-primary" style="display: block; margin-bottom:5px;">{{$mapland->bukken_shumoku}}</div><span>{{$mapland->address1}}{{$mapland->address2}}<br>{{$mapland->address3}}</span>
                                                    </h6>
                                                    @if($mapland->price == 0)
                                                    <h5 class="mt-0 text-danger"><span>-</span>万円</h5>
                                                    @else
                                                    <h5 class="mt-0 text-danger"><span>{{ number_format($mapland->price) }}</span>万円</h5>
                                                    @endif
                                                    <p> 土地面積：{{ number_format($mapland->land_menseki) }}&#13217;<br>
                                                        建ぺい率：{{$mapland->kenpei_rate}}<br>
                                                        容積率：{{ $mapland->youseki_rate }}<br>
                                                        建築条件：{{$mapland->kenchiku_jyouken}}</p>
                                                </div>
                                            </div>
                                        </a>
                                        <hr>
                                        @endforeach
                                    </div>
                                    @endisset
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 col-12 col-md6 mx-auto">
                            <a href="/lands/map" class="btn btn-secondary px-5"><i class="fadeIn animated bx bx-chevron-down-circle"></i>もっと見る</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--end row-->
    </div>
</div>
@endsection

@section("script")
<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="assets/plugins/chartjs/js/Chart.min.js"></script>
<script src="assets/plugins/chartjs/js/Chart.extension.js"></script>
<script src="assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
<script src="assets/js/index.js"></script>

<script>
    'use strict';
        
        var map;
        var marker = [];
        var infoWindow = [];
        // var landlist = "";
        var noimg = `noimage.png`;
        // var noimg = "../images/noimage.png";

        var markerData = [ // 
            @foreach($maplands as $mapland)
            {
                @if(empty($mapland->photo1))
                photo1: noimg,
                @else
                photo1: `{{ $mapland->bukken_num . "/" . $mapland->photo1 }}`,
                @endif
                bukken_num: {{ $mapland->bukken_num }},
                // photo1: `{{ $land->photo1 }}`,
                bukken_shumoku: `{{ $mapland->bukken_shumoku }}`,
                address1: `{{ $mapland->address1 }}`,
                address2: `{{ $mapland->address2 }}`,
                address3: `{{ $mapland->address3 }}`,
                @if($mapland->price == 0)
                price: '-',
                @else
                price: `{{ number_format($mapland->price) }}`,
                @endif
                land_menseki: `{{ number_format($mapland->land_menseki) }}`,
                kenpei_rate: `{{ $mapland->kenpei_rate }}`,
                youseki_rate: `{{ $mapland->youseki_rate }}`,
                kenchiku_jyouken: `{{ $mapland->kenchiku_jyouken }}`,
                lat: {{ $mapland->latitude }},
                lng: {{ $mapland->longitude }},
            },
            @endforeach
        ];
        
        console.log(markerData)
        
        function initMap() {
        
            function success(pos) {
                const latitude = pos.coords.latitude;
                const longitude = pos.coords.longitude;
                const center = {lat:latitude, lng:longitude}; 
        
                map = new google.maps.Map(document.getElementById('map'), { // #sampleに地図を埋め込む
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
        
                    infoWindow[i] = new google.maps.InfoWindow({ // 吹き出しの追加
                         content: '<a href="/lands/show/'+ markerData[i]['bukken_num'] +'" class="container"><div class="row g-0 text-dark"><div class="col-5"><img src="/storage/landimages/'+ markerData[i]['photo1']  +'" alt="' + markerData[i]['bukken_num'] + '" width="110" height="110" class="p-1 border"></div><div class="col"><div class="card-body"><h6 class="card-title"><div class="badge bg-primary" style="display: block; margin-bottom:5px;">' + markerData[i]['bukken_shumoku'] + '</div><span>' + markerData[i]['address1'] + markerData[i]['address2'] + markerData[i]['address3'] + '</span></h6><div class="clearfix"><h6 class="mb-0 float-start text-danger"><span class=" fs-5">' + markerData[i]['price'] + '</span><span class="fs-6">万円</span></h6></div><p class="card-text">土地面積：' + markerData[i]['land_menseki'] + '&#13217;<br>建ぺい率：' + markerData[i]['kenpei_rate'] + '<br>容積率：' + markerData[i]['youseki_rate'] + '<br>建築条件：' + markerData[i]['kenchiku_jyouken'] + '</p></div></div></div><div class="row g-0"><div class="col-12"><div type="button" class="btn btn-success px-5 d-block"><i class="fadeIn animated bx bx-detail"></i>詳細を表示</div></div></div></a>' // 吹き出しに表示する内容
                       });
         
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
        // $(function(){
        // 　　function adjust(){
        //         var h = $(window).height(); //ウィンドウの高さ
        //         var h1= $('.topbar').height(); //他要素の高さ
        //         $('#gmap').css('height', h-h1); //可変部分の高さを適用
        //         // $('#gmap').css('height', h); //可変部分の高さを適用
        //     }
        //     adjust();
        //     $(window).resize(adjust);
        // });
</script>
<style type="text/css">
    #mapcontainer {
        width: 100%;
        padding-top: 60%;
        position: relative;
        /* If you want text inside of it */
        margin-bottom: 20px;
    }

    #gmap {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        height: 100%;
        margin-top: 0px;
        /* padding-top: 60%; */
    }

    #mapscrollcontainer {
        position: absolute;
        bottom: 0;
        right: 0;
        height: 100%;
        margin-top: 0px;
        overflow-y: scroll;
    }

    #mapscroll {
        position: relative;

    }

    @media only screen and (max-width:992px) {
        #mapcontainer {
            padding-top: 200%;
        }

        #gmap {
            height: 50%;
        }

        #mapscrollcontainer {
            height: 50%;
            padding-top: 20px;
        }
    }
</style>
{{-- <style type="text/css">
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
</style> --}}

<script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
<script>
    $("img.lazyload").lazyload();
            var ps = new PerfectScrollbar('#mapscrollcontainer');
</script>

<script src="https://maps.googleapis.com/maps/api/js??language=ja&region=JP&key={{ config('const.map_key') }}&callback=initMap" async defer></script>
@endsection