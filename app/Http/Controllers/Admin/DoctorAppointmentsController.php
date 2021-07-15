<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDoctorAppointmentRequest;
use App\Http\Requests\StoreDoctorAppointmentRequest;
use App\Http\Requests\UpdateDoctorAppointmentRequest;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use Gate;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DoctorAppointmentsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('doctor_appointment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = Auth::user();
        $role = $user->roles[0]->id;

        $doctorAppointments = DoctorAppointment::with(['doctor']);
        if ($role == 3) {
            $doctorAppointments = $doctorAppointments->where([
                ['doctor_id', '=', $user->doctor_id]
            ]);
        }
        $doctorAppointments = $doctorAppointments->get();

        $doctors = Doctor::get();

        return view('admin.doctorAppointments.index', compact('doctorAppointments', 'doctors'));
    }

    public function create()
    {
        abort_if(Gate::denies('doctor_appointment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctors = Doctor::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.doctorAppointments.create', compact('doctors'));
    }

    public function store(StoreDoctorAppointmentRequest $request)
    {
        $doctorAppointment = DoctorAppointment::create($request->all());

        return redirect()->route('admin.doctor-appointments.index');
    }

    public function edit(DoctorAppointment $doctorAppointment)
    {
        abort_if(Gate::denies('doctor_appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctors = Doctor::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctorAppointment->load('doctor');

        return view('admin.doctorAppointments.edit', compact('doctors', 'doctorAppointment'));
    }

    public function update(UpdateDoctorAppointmentRequest $request, DoctorAppointment $doctorAppointment)
    {
        $doctorAppointment->update($request->all());

        return redirect()->route('admin.doctor-appointments.index');
    }

    public function show(DoctorAppointment $doctorAppointment)
    {
        abort_if(Gate::denies('doctor_appointment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = Auth::user();
        $role = $user->roles[0]->id;
        if ($role == 3) {
            if ($doctorAppointment->doctor_id != $user->doctor_id) {
                return redirect(route('admin.home'));
            }
        }
        $doctorAppointment->load('doctor');

        return view('admin.doctorAppointments.show', compact('doctorAppointment'));
    }

    public function destroy(DoctorAppointment $doctorAppointment)
    {
        abort_if(Gate::denies('doctor_appointment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctorAppointment->delete();

        return back();
    }

    public function massDestroy(MassDestroyDoctorAppointmentRequest $request)
    {
        DoctorAppointment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
