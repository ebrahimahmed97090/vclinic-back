@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.doctorAppointment.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.doctor-appointments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.id') }}
                        </th>
                        <td>
                            {{ $doctorAppointment->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.day') }}
                        </th>
                        <td>
                            {{ $doctorAppointment->day }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.time_from') }}
                        </th>
                        <td>
                            {{ $doctorAppointment->time_from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.time_to') }}
                        </th>
                        <td>
                            {{ $doctorAppointment->time_to }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\DoctorAppointment::STATUS_SELECT[$doctorAppointment->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.doctor') }}
                        </th>
                        <td>
                            {{ $doctorAppointment->doctor->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.doctor-appointments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection