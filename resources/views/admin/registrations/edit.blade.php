@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.registration.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.registrations.update", [$registration->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="patient_id">{{ trans('cruds.registration.fields.patient') }}</label>
                <select class="form-control select2 {{ $errors->has('patient') ? 'is-invalid' : '' }}" name="patient_id" id="patient_id" required>
                    @foreach($patients as $id => $entry)
                        <option value="{{ $id }}" {{ (old('patient_id') ? old('patient_id') : $registration->patient->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('patient'))
                    <div class="invalid-feedback">
                        {{ $errors->first('patient') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.registration.fields.patient_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="doctor_id">{{ trans('cruds.registration.fields.doctor') }}</label>
                <select class="form-control select2 {{ $errors->has('doctor') ? 'is-invalid' : '' }}" name="doctor_id" id="doctor_id" required>
                    @foreach($doctors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('doctor_id') ? old('doctor_id') : $registration->doctor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('doctor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.registration.fields.doctor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="zoom_link">{{ trans('cruds.registration.fields.zoom_link') }}</label>
                <input class="form-control {{ $errors->has('zoom_link') ? 'is-invalid' : '' }}" type="text" name="zoom_link" id="zoom_link" value="{{ old('zoom_link', $registration->zoom_link) }}" required>
                @if($errors->has('zoom_link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('zoom_link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.registration.fields.zoom_link_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="zoom_data">{{ trans('cruds.registration.fields.zoom_data') }}</label>
                <input class="form-control {{ $errors->has('zoom_data') ? 'is-invalid' : '' }}" type="text" name="zoom_data" id="zoom_data" value="{{ old('zoom_data', $registration->zoom_data) }}">
                @if($errors->has('zoom_data'))
                    <div class="invalid-feedback">
                        {{ $errors->first('zoom_data') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.registration.fields.zoom_data_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="date">{{ trans('cruds.registration.fields.date') }}</label>
                <input class="form-control datetime {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $registration->date) }}">
                @if($errors->has('date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.registration.fields.date_helper') }}</span>
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