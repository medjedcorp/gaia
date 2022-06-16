<?php

namespace App\Services;

class LineNotifyService
{
    public function notify($message)
    {
        // Line送信用
        $token = config('const.line_token');
        // リクエストヘッダを作成します
        // $notify = $message$message;
        $query = http_build_query(['message' => $message]);
        $header = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $token,
            'Content-Length: ' . strlen($query)
        ];
        $ch = curl_init('https://notify-api.line.me/api/notify');
        $options = [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_POST            => true,
            CURLOPT_HTTPHEADER      => $header,
            CURLOPT_POSTFIELDS      => $query
        ];
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        curl_close($ch);
    }
}
