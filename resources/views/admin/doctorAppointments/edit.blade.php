@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.doctorAppointment.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.doctor-appointments.update", [$doctorAppointment->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="day">{{ trans('cruds.doctorAppointment.fields.day') }}</label>
                <input class="form-control {{ $errors->has('day') ? 'is-invalid' : '' }}" type="text" name="day" id="day" value="{{ old('day', $doctorAppointment->day) }}" required>
                @if($errors->has('day'))
                    <div class="invalid-feedback">
                        {{ $errors->first('day') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.doctorAppointment.fields.day_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="time_from">{{ trans('cruds.doctorAppointment.fields.time_from') }}</label>
                <input class="form-control timepicker {{ $errors->has('time_from') ? 'is-invalid' : '' }}" type="text" name="time_from" id="time_from" value="{{ old('time_from', $doctorAppointment->time_from) }}" required>
                @if($errors->has('time_from'))
                    <div class="invalid-feedback">
                        {{ $errors->first('time_from') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.doctorAppointment.fields.time_from_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="time_to">{{ trans('cruds.doctorAppointment.fields.time_to') }}</label>
                <input class="form-control timepicker {{ $errors->has('time_to') ? 'is-invalid' : '' }}" type="text" name="time_to" id="time_to" value="{{ old('time_to', $doctorAppointment->time_to) }}" required>
                @if($errors->has('time_to'))
                    <div class="invalid-feedback">
                        {{ $errors->first('time_to') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.doctorAppointment.fields.time_to_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.doctorAppointment.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\DoctorAppointment::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $doctorAppointment->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.doctorAppointment.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="doctor_id">{{ trans('cruds.doctorAppointment.fields.doctor') }}</label>
                <select class="form-control select2 {{ $errors->has('doctor') ? 'is-invalid' : '' }}" name="doctor_id" id="doctor_id" required>
                    @foreach($doctors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('doctor_id') ? old('doctor_id') : $doctorAppointment->doctor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('doctor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.doctorAppointment.fields.doctor_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection