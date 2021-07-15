@extends('layouts.admin')
@section('content')
    @can('doctor_appointment_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.doctor-appointments.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.doctorAppointment.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.doctorAppointment.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-DoctorAppointment">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.day') }}
                        </th>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.time_from') }}
                        </th>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.time_to') }}
                        </th>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.doctorAppointment.fields.doctor') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\DoctorAppointment::STATUS_SELECT as $key => $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="search">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($doctors as $key => $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($doctorAppointments as $key => $doctorAppointment)
                        <tr data-entry-id="{{ $doctorAppointment->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $doctorAppointment->id ?? '' }}
                            </td>
                            <td>
                                {{ $doctorAppointment->day ?? '' }}
                            </td>
                            <td>
                                {{ $doctorAppointment->time_from ?? '' }}
                            </td>
                            <td>
                                {{ $doctorAppointment->time_to ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\DoctorAppointment::STATUS_SELECT[$doctorAppointment->status] ?? '' }}
                            </td>
                            <td>
                                {{ $doctorAppointment->doctor->name ?? '' }}
                            </td>
                            <td>
                                @can('doctor_appointment_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.doctor-appointments.show', $doctorAppointment->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('doctor_appointment_show')
                                    <a class="btn btn-xs btn-dark" target="_blank"
                                       href="https://zoom.us/s/{{$doctorAppointment->zoom_id}}">
                                        {{ trans('global.meeting') }}
                                    </a>
                                @endcan

                                @can('doctor_appointment_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.doctor-appointments.edit', $doctorAppointment->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('doctor_appointment_delete')
                                    <form
                                        action="{{ route('admin.doctor-appointments.destroy', $doctorAppointment->id) }}"
                                        method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                        style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger"
                                               value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('doctor_appointment_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.doctor-appointments.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({selected: true}).nodes(), function (entry) {
                        return $(entry).data('entry-id')
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')

                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: {ids: ids, _method: 'DELETE'}
                        })
                            .done(function () {
                                location.reload()
                            })
                    }
                }
            }
            dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [[1, 'desc']],
                pageLength: 100,
            });
            let table = $('.datatable-DoctorAppointment:not(.ajaxTable)').DataTable({buttons: dtButtons})
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
            $('div#sidebar').on('transitionend', function (e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            })

            let visibleColumnsIndexes = null;
            $('.datatable thead').on('input', '.search', function () {
                let strict = $(this).attr('strict') || false
                let value = strict && this.value ? "^" + this.value + "$" : this.value

                let index = $(this).parent().index()
                if (visibleColumnsIndexes !== null) {
                    index = visibleColumnsIndexes[index]
                }

                table
                    .column(index)
                    .search(value, strict)
                    .draw()
            });
            table.on('column-visibility.dt', function (e, settings, column, state) {
                visibleColumnsIndexes = []
                table.columns(":visible").every(function (colIdx) {
                    visibleColumnsIndexes.push(colIdx);
                });
            })
        })

    </script>
@endsection
