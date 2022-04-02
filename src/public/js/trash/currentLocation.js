'use strict';

var map;
var marker = [];
var infoWindow = [];
var markerData = [ // マーカーを立てる場所名・緯度・経度
    @foreach($lands as $land) {
        name: {
            {
                $land - > bukken_num
            }
        },
        lat: {
            {
                $land - > latlng - > latitude
            }
        },
        lng: {
            {
                $land - > latlng - > longitude
            }
        },
        // icon: 'tam.png' // TAM 東京のマーカーだけイメージを変更する
    },
    //   {
    //         name: '小川町駅',
    //      lat: 35.6951212,
    //         lng: 139.76610649999998
    //  }, {
    //         name: '淡路町駅',
    //      lat: 35.69496,
    //       lng: 139.76746000000003
    //  }, {
    //         name: '御茶ノ水駅',
    //         lat: 35.6993529,
    //         lng: 139.76526949999993
    //  }, {
    //         name: '神保町駅',
    //      lat: 35.695932,
    //      lng: 139.75762699999996
    //  }, {
    //         name: '新御茶ノ水駅',
    //        lat: 35.696932,
    //      lng: 139.76543200000003
    //  }
    @endforeach
];

function initMap() {

    function success(pos) {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;

        latlng = new google.maps.LatLng(lat, lng);
        map = document.getElementById("map");
        opt = {
            zoom: 15,
            center: latlng,
        };
        // google map 表示
        mapObj = new google.maps.Map(map, opt);
        // マーカーを設定
        marker = new google.maps.Marker({
            position: latlng,
            map: mapObj,
            title: '現在地',
        });

    }

    function fail(error) {
        alert('位置情報の取得に失敗しました。エラーコード：' + error.code);
    }

    navigator.geolocation.getCurrentPosition(success, fail);
}
