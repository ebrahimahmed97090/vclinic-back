<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UtilitiesTrait;
use App\Http\Controllers\Traits\ZoomMeetingTrait;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use App\Models\Patient;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DoctorsController extends Controller
{
    use UtilitiesTrait;
    use ZoomMeetingTrait;

    public function list(Request $request): array
    {
        try {
            $doctors = Doctor::with(['ratings'])->select([
                'id',
                'name',
                'email',
                'bio',
                'specialization',
                'start',
                'end',
                'is_first_period',
                'is_second_period',
            ])->whereNotNull(['start', 'end']);
            if ($request->has('key')) {
                $doctors = $doctors->where('name', 'like', "%" . $request->input('key') . "%");
            }
            $doctors = $doctors->orderBy('start', 'asc')
                ->orderBy('is_first_period', 'desc')->get();

            $results = [];
            foreach ($doctors as $doctor) {
                $today = new \DateTime();
                $days = $request->has('today') ? 1 : 30;
                $times = [];
                for ($i = 0; $i < $days; $i++) {
                    $day_times = [];
                    if ($i > 0) $today->modify("+1 day");
                    $start = $doctor->start;
                    $start = new  \DateTime($start);
                    $end = $doctor->end;
                    $end = new \DateTime($end);
                    $end->modify('+1 day');

//echo json_encode([$today,$start,$end])."<br>";
//                    if ($today < $start || $today > $end) {
//                        continue;
//                    }
                    if (($today > $start && $today < $end)) {
                        $final_time = new \DateTime();
                        $final_time->setDate($today->format('Y'), $today->format('m'), $today->format('d'));
//                     echo json_encode($final_time)."<br>";
                        if ($doctor->is_first_period) {
                            $today->setTime(17, 0, 0);
                            if ($doctor->is_second_period) {
                                $final_time->setTime(22, 40, 0);
                            } else {
                                $final_time->setTime(19, 40, 0);
                            }
                        } else {
                            $today->setTime(20, 0, 0);
                            $final_time->setTime(22, 40, 0);
                        }

                        while ($today <= $final_time) {
                            if ($today > now()) {
                                $reserved = DoctorAppointment::with([])->where([
                                    ['doctor_id', '=', $doctor->id], ['date_time', '=', $today]
                                ])->first();
                                $dateTime = $today->getTimestamp();//->format('Y-m-d H:i:s');
//                                                        echo json_encode($dateTime) . "<br>";
                                array_push($day_times, ['time' => $dateTime, 'reserved' => ($reserved == null) ? 0 : 1]);
//                                                        echo json_encode($times) . "<br>";

                            }
                            $today->modify('+20 minutes');
                        }
                        if (!empty($day_times)) array_push($times, $day_times);

                    }
                }
                $ratings_avg = $doctor->ratings_avg;
                $rating = 0.0;
                if ($ratings_avg['count'] > 0) {
                    $rating = $ratings_avg['sum'] / $ratings_avg['count'];
                }
                if (!empty($times))
                    array_push($results, [
                        'id' => $doctor->id,
                        'name' => $doctor->name,
                        'email' => $doctor->email,
                        'bio' => $doctor->bio,
                        'specialization' => $doctor->specialization,
                        'picture' => $doctor->picture,
                        'times' => $times,
                        'rating' => round($rating, 1),
                        'rating_count' => $ratings_avg['count'],
                        'start' => $doctor->start,
                        'end' => $doctor->end,
                    ]);
            }
            return $this->VResponse(200, 'data retrieved', $results);
        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function saveAppointment(Request $request): array
    {
        try {
            if (!$request->has('step')) {
                return $this->VResponse(400, ['step is required'], null);
            }
            $rules = [];
            $step = $request->input('step');
            switch ($step) {
                case 1:
                    $rules = [
                        'first_name' => 'required',
                        'second_name' => 'required',
                        'last_name' => 'required',
                        'national_id' => 'required',
                        'mobile' => 'required',
                    ];
                    break;
                case 2:
                    $rules = [
                        'patient_id' => 'required|exists:patients,id',
                        'province' => 'required',
                        'district' => 'required',
                        'age' => 'required',
                        'gender' => 'required',
                        'start_age' => 'required',
                    ];
                    break;
                case 3:
                    $rules = [
                        'patient_id' => 'required|exists:patients,id',
                        'tobaco_type' => 'required',
                        'daily_cigarettes' => 'required',
                        'weekly_hookah' => 'required',
                    ];
                    break;
                case 4:
                    $rules = [
                        'patient_id' => 'required|exists:patients,id',
                        'marital_status' => 'required',
                        'education' => 'required',
                        'job' => 'required',
                    ];
                    break;
                case 5:
                    $rules = [
                        'patient_id' => 'required|exists:patients,id',
                        'date_time' => 'required',
                        'doctor_id' => 'required',
                        'zoom_id' => 'required'
                    ];
                    break;

                default:
                    return $this->VResponse(400, 'worng step value', []);
                    break;
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->VResponse(400, $validator->messages(), null);
            } else {
                if ($step == 1) {
                    $mobile = $request->input('mobile');
                    if (substr($mobile, 0, 2) == '05') {
                        $mobile = '+966' . substr($mobile, 1);
                    }
                    if (substr($mobile, 0, 1) == '5') {
                        $mobile = '+966' . $mobile;
                    }

                    $patient = Patient::firstOrCreate([
                        'first_name' => $request->input('first_name'),
                        'second_name' => $request->input('second_name'),
                        'last_name' => $request->input('last_name'),
                        'email' => $request->input('email'),
                        'mobile' => $mobile,
                        'national_id' => $request->input('national_id'),
                        'step' => $request->input('step'),
                    ]);
                    return $this->VResponse(200, 'step 1 saved', $patient);
                } else {
                    $patient = Patient::with([])->where('id', '=', $request->input('patient_id'))->first();
                }
                if ($step == 2) {
                    $patient->update($request->all());
                    return $this->VResponse(200, 'step 2 saved', $patient);
                }
                if ($step == 3) {
                    $patient->update($request->all());
                    return $this->VResponse(200, 'step 3 saved', $patient);
                }
                if ($step == 4) {
                    $patient->update($request->all());
                    return $this->VResponse(200, 'step 4 saved', $patient);
                }
                if ($step == 5) {
                    $first_name = explode(' ', $patient->first_name)[0];
                    $link = 'https://meeting.tcp-jeddah.com/#/meeting?nickname=' . $first_name . '&meetingId=' . $request->input('zoom_id');

                    $message = 'مرحباً بكم يمكنكم الدخول لموعدكم من خلال الرابط   ' . $link;
                    $body = [
                        'from' => 'TCPJeddah', 'to' => $patient->mobile, 'body' => $message
                    ];
                    $body = json_encode($body);
                    $sms = $this->send_message($body);
                    $this->sendEmail(['link' => $link, 'email' => $patient->email, 'view' => 'email.registration']);
                    $date = (string)date('Y-m-d H:i:s', $request->input('date_time'));
                    $doctor = $request->input('doctor_id');
                    $check_appointment = DoctorAppointment::with([])->where([
                        ['date_time', '=', $date],
                        ['doctor_id', '=', $doctor],
                    ])->first();
                    if ($check_appointment != null) {
                        return $this->VResponse(400, 'Appointment Booked', []);

                    }
                    $patient = Patient::with([])->where('id', '=', $request->input('patient_id'))->first();
                    $patient->step = $request->input('step');
                    $patient->save();
                    $appointment = DoctorAppointment::create([
                        'date_time' => $date,
                        'doctor_id' => $doctor,
                        'patient_id' => $patient->id,
                        'zoom_id' => $request->input('zoom_id'),
                        'sms' => $sms['http_status']]);
                    $doctor = Doctor::with([])->where('id', '=', $doctor)->first();
                    $patient = $patient->toArray();
                    $patient['doctor'] = $doctor->name;
                    $this->sendEmail(['patient' => $patient, 'email' => 'jeddahvcc@gmail.com', 'view' => 'email.patient']);

                    return $this->VResponse(200, 'appointment saved', $appointment);
                }
            }

        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function saveRating(Request $request): array
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'rating' => 'required',
                'national_id' => 'required',
                'doctor_id' => 'required|exists:doctors,id'
            ]);
            if ($validator->fails()) {
                return $this->VResponse(400, $validator->messages(), null);
            } else {
                $rating = Rating::with([])->where([
                    ['national_id', '=', $request->input('national_id')],
                    ['email', '=', $request->input('email')],
                    ['ratingable_id', '=', $request->input('doctor_id')],
                    'ratingable_type' => Doctor::class
                ])->first();
                if ($rating == null) {
                    $rating = Rating::create([
                        'name' => $request->input('name'),
                        'email' => $request->input('email'),
                        'rating' => $request->input('rating'),
                        'ratingable_id' => $request->input('doctor_id'),
                        'ratingable_type' => Doctor::class
                    ]);
                }
                return $this->VResponse(200, 'appointment saved', $rating);
            }

        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function done(Request $request): array
    {
        try {
            $validator = Validator::make($request->all(), [
                'appointment_id' => 'required|exists:doctor_appointments,id'
            ]);
            if ($validator->fails()) {
                return $this->VResponse(400, $validator->messages(), null);
            } else {
                $doctor_id = $request->user()->doctor_id;
                $appointment = DoctorAppointment::with([])->where([
                    ['doctor_id', '=', $doctor_id],
                    ['id', '=', $request->input('appointment_id')]
                ])->first();
                if ($appointment == null) {
                    return $this->VResponse(400, 'not related doctor', null);
                }
                $appointment->done = 1;
                $appointment->save();
                return $this->VResponse(200, 'appointment saved', $appointment);
            }

        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->VResponse(400, $validator->messages(), null);
            } else {
                if (!auth()->attempt($request->toArray())) {
                    return $this->VResponse(401, 'Invalid Username or Password', []);
                }

                $user = auth()->user();
                $role = $user->roles[0]->id;
                $user->is_admin = ($role == 1);
                $accessToken = $user->createToken('authToken')->accessToken;
                return $this->VResponse(200, 'Logged in successfully', ['user' => $user, 'access_token' => $accessToken]);

            }
        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function postProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->VResponse(400, $validator->messages(), null);
            } else {
                $user_id = $request->user()->id;
                $user = User::with([])->where('id', '=', $user_id)->first();
                $user->password = Hash::make($request->input('password'));
                $user->save();
                return $this->VResponse(200, 'Profile Changed Successfully', $user);
            }
        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function forgetPassowrd(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
            ]);
            if ($validator->fails()) {
                return $this->VResponse(400, $validator->messages(), null);
            } else {
                $user = \App\Models\User::with([])->where('email', '=', $request->input('email'))->first();
                $password = Str::random(8);
                $user->password = Hash::make($password);
                $user->save();
                $data = ['password' => $password, 'email' => $request->input('email'), 'view' => 'email.forget_password', 'subject' => 'العيادة الإفتراضية'];
                Mail::send('email.forget_password', $data, function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['subject']);
                });

                return $this->VResponse(200, 'password sent successfully', []);

            }
        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function getAppointments(Request $request)
    {
        try {
            $doctor_id = $request->user()->doctor_id;
            if ($request->has('today')) {
                $today_start = new \DateTime();
                $today_start->setTime(0, 0, 0);

                $today_end = new \DateTime();
                $today_end->modify('+1 day');
                $today_end->setTime(0, 0, 0);
                $appointments = DoctorAppointment::with(['doctor', 'patient'])->where([
                    ['doctor_id', '=', $doctor_id],
                    ['date_time', '>', $today_start],
                    ['date_time', '<=', $today_end]
                ])->get();
                $data = [['day' => $today_start->getTimestamp(),
                    'appointments' => $appointments]];
                $data = (array)$data;
                return $this->VResponse(200, 'data retrieved',
                    $data);
            }
            $doctor = Doctor::with([])->where('id', '=', $doctor_id)->first();
            $today = \DateTime::createFromFormat('Y-m-d',$doctor->start);
            $today->setTime(0, 0, 0);
            $times = [];
            $days = 6;
            for ($i = 0; $i < $days; $i++) {
                if ($i > 0) $today->modify("+1 day");
                $final_time = new \DateTime();
                $final_time->setDate($today->format('Y'), $today->format('m'), $today->format('d'));
                $final_time->setTime(22, 40, 0);
                $appointments = DoctorAppointment::with(['doctor', 'patient'])->where([
                    ['doctor_id', '=', $doctor_id],
                    ['date_time', '>', $today],
                    ['date_time', '<=', $final_time]
                ])->get();
                array_push($times, ['day' => $today->getTimestamp(), 'appointments' => $appointments]);
            }


//            $appointments = DoctorAppointment::with(['doctor', 'patient'])->where([
//                ['doctor_id', '=', $doctor_id],
//            ])->get();
//            foreach ($appointments as $appointment) {
//                $date = new \DateTime($appointment->date_time);
//                $date->setTime(0, 0, 0);
//                $date = $date->getTimestamp();
//                $filled = false;
//                for ($i = 0; $i < sizeof($times); $i++) {
//                    if ($date == $times[$i]['day']) {
//                        $day_appointments = $times[$i]['appointments'];
//                        array_push($day_appointments, $appointment);
//                        $times[$i]['appointments'] = $day_appointments;
//                        $filled = true;
//                    }
//                }
//                if (!$filled) {
//                    array_push($times, ['day' => $date, 'appointments' => [$appointment]]);
//                }
//            }

            return $this->VResponse(200, 'data retrieved', $times);
        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }


    }

    public function getProfile(Request $request)
    {
        try {
            $doctor_id = $request->user()->doctor_id;
            $doctor = Doctor::with([])->where('id', '=', $doctor_id)->first();
            return $this->VResponse(200, 'data retrieved', $doctor);
        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function adminList(Request $request)
    {
        try {
            $doctors = Doctor::with(['appointments' => function ($q) {
                return $q->orderBy('date_time', 'asc')->with(['patient']);
            }])->orderBy('start')->orderBy('is_first_period', 'desc')->get();
            return $this->VResponse(200, 'data retrieved', $doctors);
        } catch (\Exception $e) {
            return $this->VResponse($e->getCode(), $e->getMessage(), $e->getTrace());
        }
    }

    public function mail()
    {
        $patients = Patient::with([])->whereBetween('id', [194, 206])->get();
        $patients = $patients->toArray();
        foreach ($patients as $patient) {
            $patient = (array)$patient;
            $this->sendEmail(['patient' => $patient, 'email' => 'jeddahvcc@gmail.com', 'view' => 'email.patient']);
        }

    }

}
