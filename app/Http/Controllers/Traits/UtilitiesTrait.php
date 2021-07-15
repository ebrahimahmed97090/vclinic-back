<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Mail;

trait UtilitiesTrait
{

    function VResponse($status = 200, $message = '', $data = [])
    {

        return ['status' => $status, 'message' => $message, 'data' => $data];
    }

    function getYouTubeID($link)
    {
        return ((strpos($link, 'v=')) ? explode('&', last(explode('v=', $link)))[0] : last(explode('/', $link)));
    }

    function send_message($post_body)
    {
        $url = 'https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30';
        $username = 'C832B1E6C090483AAC0B460F9BB4BF9F-02-8';
        $password = 'Y1Ww2suiIjGypzH4HcXC*3u0ziGBq';
        $ch = curl_init();
        $headers = array(
            'Content-Type:application/json',
            'Authorization:Basic ' . base64_encode("$username:$password")
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
        // Allow cUrl functions 20 seconds to execute
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        // Wait 10 seconds while trying to connect
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $output = array();
        $output['server_response'] = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        $output['http_status'] = $curl_info['http_code'];
        $output['error'] = curl_error($ch);
        curl_close($ch);
        return $output;
    }

    function shortenLink($link)
    {
        $short_url = json_decode(file_get_contents("http://api.bit.ly/v3/shorten?login=bitlyusername&apiKey=6da5941dcd12ce461e112ee4218302290ceb5f72&longUrl=" . urlencode("http://example.com") . "&format=json"))->data->url;

    }

    function sendEmail($data)
    {
        $view = $data['view'];
        Mail::send($view, $data, function ($message) use ($data) {
            $message->to([$data['email']])->bcc(['aaa.computerscience@gmail.com', 'm.mahmoud@archmetry.co.uk']);
            if ($data['view'] == 'email.patient') {
                $message->subject('بيانات المراجع رقم .'.$data['patient']['id']);

            } else {
                $message->subject('العيادة الإفتراضية للإقلاع عن التدخين.');

            }
        });
    }

}
