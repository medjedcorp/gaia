'use strict';
// 検索時に先に緯度経度情報取得

function getPosition() {
    return new Promise((res, rej) => {
      navigator.geolocation.getCurrentPosition(res, rej);
      // ↓これがないと位置情報確認画面で止まらない
      event.preventDefault();
    //   event.preventDefault();
    });
  }

function searchBox() {
    getPosition()
        .then(function (value) {
            var lat = value.coords.latitude;
            var lng = value.coords.longitude;
            console.log(lat);
            console.log(lng);
            $('<input>').attr({
                'type': 'hidden',
                'name': 'lat',
                'id': 'lat',
                'value': lat
            }).appendTo(document.form1);
            $('<input>').attr({
                'type': 'hidden',
                'name': 'lng',
                'id': 'lng',
                'value': lng
            }).appendTo(document.form1);
            document.form1.submit();
        })
        .catch(function (value) {
            // 非同期処理が失敗した場合
            window.alert("位置情報の取得に失敗しました。位置情報の利用を許可してください。");
        });
}

