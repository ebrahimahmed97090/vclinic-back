@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.config.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.configs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.config.fields.id') }}
                        </th>
                        <td>
                            {{ $config->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.config.fields.title') }}
                        </th>
                        <td>
                            {{ $config->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.config.fields.attachment') }}
                        </th>
                        <td>
                            @if($config->attachment)
                                <a href="{{ $config->attachment->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.config.fields.details') }}
                        </th>
                        <td>
                            {{ $config->details }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.config.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Config::STATUS_SELECT[$config->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.configs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection