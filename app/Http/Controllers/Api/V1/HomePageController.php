<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UtilitiesTrait;
use App\Http\Controllers\Traits\ZoomMeetingTrait;
use App\Models\Config;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    use UtilitiesTrait;
    use ZoomMeetingTrait;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function ZoomCreate($doctor_id, $time)
    {
        $user_id = Doctor::with([])->where([['id', '=', $doctor_id]])->first();
        $user_id = $user_id->email;
        $time = date('Y-m-d@@H:i:s', $time);
        $time = (string)$time;
        $time = str_replace('@@', 'T', $time);
        $data = ['topic' => 'العيادة الإفتراضية', 'user_id' => $user_id,
            "start_time" => $time, "duration" => "20", "agenda" => "", "host_video" => "1", "participant_video" => "1"];
        return $this->createUserMeeting($data);
    }

    public function getUsersList()
    {
        return $this->getUsers();
    }

    public function HomeVideo()
    {
        try {
            $video = Config::with([])
                ->select(['id', 'title', 'details', 'youtube_link'])
                ->where([
                    ['code', '=', 'HOME-ViDEO']
                ])->first();
            return $this->VResponse(200, 'data retrieved', $video);

        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function AboutUs()
    {
        try {
            $video = Config::with([])
                ->select(['id', 'title', 'details'])
                ->where([
                    ['code', '=', 'ABOUT-US']
                ])->first();
            Header('Access-Control-Allow-Origin', '*');
            Header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
            Header('Access-Control-Allow-Headers', 'Content-Type');
            Header('Access-Control-Allow-Credentials', true);
            return $this->VResponse(200, 'data retrieved', $video);

        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function Intro()
    {
        try {
            $video = Config::with([])
                ->select(['id', 'title', 'details'])
                ->where([
                    ['code', '=', 'intro']
                ])->first();
            Header('Access-Control-Allow-Origin', '*');
            Header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
            Header('Access-Control-Allow-Headers', 'Content-Type');
            Header('Access-Control-Allow-Credentials', true);
            return $this->VResponse(200, 'data retrieved', $video);

        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function Faq()
    {
        try {
            $video = Config::with([])
                ->select(['id', 'title', 'details'])
                ->where([
                    ['code', '=', 'FAQ']
                ])->get();
            return $this->VResponse(200, 'data retrieved', $video);

        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function test()
    {
        $username = 'C832B1E6C090483AAC0B460F9BB4BF9F-02-8';
        $password = 'Y1Ww2suiIjGypzH4HcXC*3u0ziGBq';
        $messages = array(

//            array('to' => '+966507448665', 'body' => 'Welcome to V-Clinic!'),
            array('from' => 'TCPJeddah', 'to' => '+966500947454', 'body' => 'Welcome to V-Clinic!')
        );

        $result = $this->send_message(json_encode($messages));

        if ($result['http_status'] != 201) {
            print "Error sending: " . ($result['error'] ? $result['error'] : "HTTP status " . $result['http_status'] . "; Response was " . $result['server_response']);
        } else {
            print "Response " . $result['server_response'];
            // Use json_decode($result['server_response']) to work with the response further
        }

    }

    public function waiting()
    {
        return [
            ['image' => 'https://img.youtube.com/vi/Lb4g8vzHkww/hqdefault.jpg', 'video' => '<iframe style="width: 100%; height: clamp(200px, 44vh, 510px);" src="https://www.youtube.com/embed/videoseries?list=PLw_YenxRx3XCxgYZCzf3T6nIEe5i2mdXD" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'],
            ['image' => 'https://img.youtube.com/vi/HTz1NGWIiX0/hqdefault.jpg', 'video' => '<iframe style="width: 100%; height: clamp(200px, 44vh, 510px);" src="https://www.youtube.com/embed/videoseries?list=PLw_YenxRx3XDpMAk2txtAHqBlFiuohezG" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'],
            ['image' => 'https://img.youtube.com/vi/gaNvqnxd58M/hqdefault.jpg', 'video' => '<iframe style="width: 100%; height: clamp(200px, 44vh, 510px);" src="https://www.youtube.com/embed/videoseries?list=PLw_YenxRx3XDNHK80IavFO0J4mz_WsNGc" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'],
            ['image' => 'https://img.youtube.com/vi/MyRVc6xMnFk/hqdefault.jpg', 'video' => '<iframe style="width: 100%; height: clamp(200px, 44vh, 510px);" src="https://www.youtube.com/embed/videoseries?list=PLw_YenxRx3XA4M3w1O0-pbmndrqn14IME" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'],
        ];
    }

    public function stats()
    {
        try {
            $appointments = DoctorAppointment::all();
//            return $appointments;
            $times = [
                [
                    "label" => "0",
                    "y" => 0
                ],
                [
                    "label" => "1",
                    "y" => 0
                ],
                [
                    "label" => "2",
                    "y" => 0
                ],
                [
                    "label" => "3",
                    "y" => 0
                ],
                [
                    "label" => "4",
                    "y" => 0
                ],
                [
                    "label" => "5",
                    "y" => 0
                ]
            ];
            $week_number = [];
            foreach ($appointments as $appointment) {
                $found = false;
                $found_week = false;
                for ($i = 0; $i < sizeof($times); $i++) {
                    if ($times[$i]['label'] == $appointment->day_name) {
                        $repeat = $times[$i]['y'] + 1;
                        $times[$i]['y'] = $repeat;
                        $found = true;
                    }
                }
                for ($j = 0; $j < sizeof($week_number); $j++) {
                    $week = ($appointment->week_number);
                    if ($week_number[$j]['label'] == ($week)) {
                        $repeat = $week_number[$j]['y'] + 1;
                        $week_number[$j]['y'] = $repeat;
                        $found_week = true;
                    }
                }
                if (!$found) {
                    array_push($times, ['label' => $appointment->day_name, 'y' => 1]);
                }
                if (!$found_week) {
                    $week = ($appointment->week_number);
                    array_push($week_number, ['label' => $week, 'y' => 1]);
                }
            }
            $steps = DB::table('patients')->select('step', DB::raw('count(*) as sum'))
                ->groupBy('step')->whereNotNull('step')->get();

            $times = [
                'day of week' => ['label' => 'day of week', 'data' => $times],
                'number of week' => ['label' => 'number of week', 'data' => $week_number],
                'user steps' => ['label' => 'user steps', 'data' => $steps],
            ];
            return $this->VResponse(200, 'data retrieved', $times);
        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

}
