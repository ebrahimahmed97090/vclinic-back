<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorAppointmentRequest;
use App\Http\Requests\UpdateDoctorAppointmentRequest;
use App\Http\Resources\Admin\DoctorAppointmentResource;
use App\Models\DoctorAppointment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorAppointmentsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('doctor_appointment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DoctorAppointmentResource(DoctorAppointment::with(['doctor'])->get());
    }

    public function store(StoreDoctorAppointmentRequest $request)
    {
        $doctorAppointment = DoctorAppointment::create($request->all());

        return (new DoctorAppointmentResource($doctorAppointment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DoctorAppointment $doctorAppointment)
    {
        abort_if(Gate::denies('doctor_appointment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DoctorAppointmentResource($doctorAppointment->load(['doctor']));
    }

    public function update(UpdateDoctorAppointmentRequest $request, DoctorAppointment $doctorAppointment)
    {
        $doctorAppointment->update($request->all());

        return (new DoctorAppointmentResource($doctorAppointment))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DoctorAppointment $doctorAppointment)
    {
        abort_if(Gate::denies('doctor_appointment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctorAppointment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
