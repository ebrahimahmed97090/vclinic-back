@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.doctor.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.doctors.store") }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">{{ trans('cruds.doctor.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', '') }}">
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.doctor.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="email">{{ trans('cruds.doctor.fields.email') }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                           name="email" id="email" value="{{ old('email') }}">
                    @if($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.doctor.fields.email_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="password">{{ trans('cruds.doctor.fields.password') }}</label>
                    <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                           name="password" id="password">
                    @if($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.doctor.fields.password_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="bio">{{ trans('cruds.doctor.fields.bio') }}</label>
                    <input class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}" type="text" name="bio"
                           id="bio" value="{{ old('bio', '') }}">
                    <textarea class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}" name="bio"
                              id="bio"></textarea>
                    @if($errors->has('bio'))
                        <div class="invalid-feedback">
                            {{ $errors->first('bio') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.doctor.fields.bio_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="specialization">{{ trans('cruds.doctor.fields.specialization') }}</label>
                    <input class="form-control {{ $errors->has('specialization') ? 'is-invalid' : '' }}" type="text"
                           name="specialization" id="specialization" value="{{ old('specialization', '') }}">
                    @if($errors->has('specialization'))
                        <div class="invalid-feedback">
                            {{ $errors->first('specialization') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.doctor.fields.specialization_helper') }}</span>
                </div>
                <div class="form-group">
                    <div class="">
                        <label for="start">{{ trans('cruds.doctor.fields.start') }}</label>
                        <input class="form-control date {{ $errors->has('start') ? 'is-invalid' : '' }}" type="text"
                               name="start" id="start" value="{{ old('start', '') }}">
                        @if($errors->has('start'))
                            <div class="invalid-feedback">
                                {{ $errors->first('start') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.doctor.fields.start_helper') }}</span>
                    </div>
                    <div class="">
                        <label for="end">{{ trans('cruds.doctor.fields.end') }}</label>
                        <input class="form-control date {{ $errors->has('end') ? 'is-invalid' : '' }}" type="text"
                               name="end" id="end" value="{{ old('end', '') }}">
                        @if($errors->has('end'))
                            <div class="invalid-feedback">
                                {{ $errors->first('end') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.doctor.fields.end_helper') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check {{ $errors->has('is_first_period') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="is_first_period" value="0">
                        <input class="form-check-input" type="checkbox" name="is_first_period" id="is_first_period"
                               value="1" {{ old('is_first_period', 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="is_first_period">{{ trans('cruds.doctor.fields.is_first_period') }}</label>
                    </div>
                    @if($errors->has('is_first_period'))
                        <span class="text-danger">{{ $errors->first('is_first_period') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.doctor.fields.is_first_period_helper') }}</span>
                </div>

                <div class="form-group">
                    <div class="form-check {{ $errors->has('is_second_period') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="is_second_period" value="0">
                        <input class="form-check-input" type="checkbox" name="is_second_period" id="is_second_period"
                               value="1" {{ old('is_second_period', 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="is_second_period">{{ trans('cruds.doctor.fields.is_second_period') }}</label>
                    </div>
                    @if($errors->has('is_second_period'))
                        <span class="text-danger">{{ $errors->first('is_second_period') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.doctor.fields.is_second_period_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="picture">{{ trans('cruds.doctor.fields.picture') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('picture') ? 'is-invalid' : '' }}"
                         id="picture-dropzone">
                    </div>
                    @if($errors->has('picture'))
                        <div class="invalid-feedback">
                            {{ $errors->first('picture') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.doctor.fields.picture_helper') }}</span>
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
@section('scripts')
    <script>
        Dropzone.options.pictureDropzone = {
            url: '{{ route('admin.configs.storeMedia') }}',
            maxFilesize: 200, // MB
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 200
            },
            success: function (file, response) {
                $('form').find('input[name="picture"]').remove()
                $('form').append('<input type="hidden" name="picture" value="' + response.name + '">')
            },
            removedfile: function (file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="picture"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function () {
                @if(isset($config) && $config->attachment)
                var file = {!! json_encode($config->attachment) !!}
                this.options.addedfile.call(this, file)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="picture" value="' + file.file_name + '">')
                this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function (file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
@endsection
