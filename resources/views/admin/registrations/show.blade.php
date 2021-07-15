@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.registration.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.registrations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.registration.fields.id') }}
                        </th>
                        <td>
                            {{ $registration->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.registration.fields.patient') }}
                        </th>
                        <td>
                            {{ $registration->patient->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.registration.fields.doctor') }}
                        </th>
                        <td>
                            {{ $registration->doctor->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.registration.fields.zoom_link') }}
                        </th>
                        <td>
                            {{ $registration->zoom_link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.registration.fields.zoom_data') }}
                        </th>
                        <td>
                            {{ $registration->zoom_data }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.registration.fields.date') }}
                        </th>
                        <td>
                            {{ $registration->date }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.registrations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection