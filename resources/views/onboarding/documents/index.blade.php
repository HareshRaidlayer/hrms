@extends('layout.main')
@section('page_style')
    <style>

    </style>
@endsection
@section('content')
    <section>

        <span id="document_general_result"></span>


        <div class="container-fluid">
            
                <button type="button" class="btn btn-info" name="create_record" id="create_document_record"><i
                        class="fa fa-plus"></i>{{__('Add Document')}}</button>
           
        </div>


        <div class="table-responsive">
            <table id="document-table" class="table ">
                <thead>
                    <tr>
                        <th>{{__('Document Type')}}</th>
                        <th>{{trans('file.Title')}}</th>
                        <th>{{__('Expired Date')}}</th>
                        <th class="not-exported">{{trans('file.action')}}</th>
                    </tr>
                </thead>

            </table>
        </div>


        <div id="DocumentformModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{__('Add Document')}}</h5>
                        <button type="button" data-dismiss="modal" id="close" aria-label="Close"
                            class="document-close"><span aria-hidden="true">×</span></button>
                    </div>

                    <div class="modal-body">
                        <span id="document_form_result"></span>
                        <form method="post" id="document_sample_form" class="form-horizontal" enctype="multipart/form-data"
                            autocomplete="off">

                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>{{__('Document Type')}}</label>
                                    <select name="document_type_id" id="document_document_type_id" required
                                        class="form-control selectpicker" data-live-search="true"
                                        data-live-search-style="contains"
                                        title='{{__('Selecting', ['key' => __('Document Type')])}}...'>
                                        @foreach($document_types as $document_type)
                                            <option value="{{$document_type->id}}">{{$document_type->document_type}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>{{trans('file.Title')}} *</label>
                                    <input type="text" name="document_title" id="document_title"
                                        placeholder={{trans('file.Title')}} required class="form-control">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>{{__('Expired Date')}} *</label>
                                    <input type="text" name="expiry_date" id="document_expiry_date" required
                                        autocomplete="off" class="form-control date" value="">
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Description')}}</label>
                                        <textarea class="form-control" name="description" id="document_description"
                                            rows="3"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>{{trans('file.Document')}} {{trans('file.File')}} *</label>
                                    <input type="file" name="document_file" id="document_document_file"
                                        class="form-control">
                                    <span id="stored_document_document"></span>
                                </div>

                                <div class="col-md-6 form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="is_notify"
                                            id="document_is_notify" value="1">
                                        <label class="custom-control-label"
                                            for="document_is_notify">{{__('Send Notification?')}}
                                            ({{__('will get notification email before 3 days of expiry date')}})</label>
                                    </div>
                                </div>


                                <div class="container">
                                    <div class="form-group" align="center">
                                        <input type="hidden" name="action" id="document_action" />
                                        <input type="hidden" name="hidden_id" id="document_hidden_id" />
                                        <input type="submit" name="action_button" id="document_action_button"
                                            class="btn btn-warning" value={{trans('file.Add')}} />
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade confirmModal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">{{trans('file.Confirmation')}}</h2>
                        <button type="button" class="bank-close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 align="center" style="margin:0;">{{__('Are you sure you want to remove this data?')}}</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" name="ok_button"
                            class="btn btn-danger document-ok">{{trans('file.OK')}}</button>
                        <button type="button" class="bank-close btn-default"
                            data-dismiss="modal">{{trans('file.Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
@push('scripts')
    <script>
        (function ($) {
            "use strict";
            $('#document-table').DataTable().clear().destroy();
            var date = $('.date');
            date.datepicker({
                format: '{{ env('Date_Format_JS')}}',
                autoclose: true,
                todayHighlight: true
            });


            var table_table = $('#document-table').DataTable({
                initComplete: function () {
                    this.api().columns([0]).every(function () {
                        var column = this;
                        var select = $('<select><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                            $('select').selectpicker('refresh');
                        });
                    });
                },
                responsive: true,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('onboarding.dashboard', $employee->id) }}",
                },

                columns: [

                    {
                        data: 'document',
                        name: 'document',

                    },
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'expiry_date',
                        name: 'expiry_date',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ],


                "order": [],
                'language': {
                    'lengthMenu': '_MENU_ {{__('records per page')}}',
                    "info": '{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)',
                    "search": '{{trans("file.Search")}}',
                    'paginate': {
                        'previous': '{{trans("file.Previous")}}',
                        'next': '{{trans("file.Next")}}'
                    }
                },
                'columnDefs': [
                    {
                        "orderable": false,
                        'targets': [0, 3],
                    },
                ],


                'select': { style: 'multi', selector: 'td:first-child' },
                'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
            });
            new $.fn.dataTable.FixedHeader(table_table);


            $('#create_document_record').click(function () {

                $('.modal-title').text('{{__('Add Document')}}');
                $('#document_action_button').val('{{trans('file.Add')}}');
                $('#document_action').val('{{trans('file.Add')}}');
                $('#DocumentformModal').modal('show');
            });

            $('#document_sample_form').on('submit', function (event) {
                event.preventDefault();
                if ($('#document_action').val() == '{{trans('file.Add')}}') {

                    $.ajax({
                        url: "{{ route('onboarding.documents.store', $employee->id) }}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function (data) {
                            var html = '';
                            if (data.errors) {
                                html = '<div class="alert alert-danger">';
                                for (var count = 0; count < data.errors.length; count++) {
                                    html += '<p>' + data.errors[count] + '</p>';
                                }
                                html += '</div>';
                            }
                            if (data.success) {
                                html = '<div class="alert alert-success">' + data.success + '</div>';
                                $('#document_sample_form')[0].reset();
                                $('select').selectpicker('refresh');
                                $('.date').datepicker('update');
                                $('#document-table').DataTable().ajax.reload();
                            }
                            $('#document_form_result').html(html).slideDown(300).delay(5000).slideUp(300);
                        }

                    });
                }

                if ($('#document_action').val() == '{{trans('file.Edit')}}') {
                    $.ajax({
                        url: "{{ route('onboarding.documents.update') }}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function (data) {
                            var html = '';
                            if (data.errors) {
                                html = '<div class="alert alert-danger">';
                                for (var count = 0; count < data.errors.length; count++) {
                                    html += '<p>' + data.errors[count] + '</p>';
                                }
                                html += '</div>';
                            }
                            if (data.error) {
                                html = '<div class="alert alert-danger">' + data.error + '</div>';
                            }

                            if (data.success) {
                                html = '<div class="alert alert-success">' + data.success + '</div>';
                                setTimeout(function () {
                                    $('#DocumentformModal').modal('hide');
                                    $('.date').datepicker('update');
                                    $('select').selectpicker('refresh');
                                    $('#document-table').DataTable().ajax.reload();
                                    $('#document_sample_form')[0].reset();
                                }, 2000);

                            }
                            $('#document_form_result').html(html).slideDown(300).delay(5000).slideUp(300);
                        }
                    });
                }
            });


            $(document).on('click', '.document_edit', function () {

                var id = $(this).attr('id');

                // var target = `/onboarding/candidate/documents/edit/${id}`;
                var target = "{{ route('onboarding.documents.edit', ['id' => '__ID__']) }}".replace('__ID__', id);

                $.ajax({
                    url: target,
                    method: 'GET',
                    dataType: "json",
                    success: function (html) {

                        let id = html.data.id;

                        $('#document_title').val(html.data.document_title);
                        $('#document_expiry_date').val(html.data.expiry_date);
                        $('#document_description').val(html.data.description);
                        $('#document_document_type_id').selectpicker('val', html.data.document_type_id);
                        if (html.data.is_notify == 1) {
                            $('#document_is_notify').prop('checked', true);
                        } else {
                            $('#document_is_notify').prop('checked', false);
                        }

                        $('#document_hidden_id').val(html.data.id);
                        $('.modal-title').text('{{trans('file.Edit')}}');
                        $('#document_action_button').val('{{trans('file.Edit')}}');
                        $('#document_action').val('{{trans('file.Edit')}}');
                        $('#DocumentformModal').modal('show');
                    }
                })
            });


            let document_delete_id;

            $(document).on('click', '.document_delete', function () {
                document_delete_id = $(this).attr('id');
                $('.confirmModal').modal('show');
                $('.modal-title').text('Delete Record');
                $('.document-ok').text('OK');
            });

            $('.document-close').click(function () {
                $('#document_sample_form')[0].reset();
                $('select').selectpicker('refresh');
                $('.date').datepicker('update');
                $('.confirmModal').modal('hide');
                $('#document-table').DataTable().ajax.reload();
            });

            $('.document-ok').off().on('click', function () {
                // const deleteUrl = `/onboarding/employee/documents/delete/${document_delete_id}`;
                const deleteUrl = "{{ route('onboarding.documents.destroy', ['id' => '__ID__']) }}".replace('__ID__', document_delete_id);

                $.ajax({
                    url: deleteUrl,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () {
                        $('.document-ok').text('Deleting...');
                    },
                    success: function (data) {
                        setTimeout(function () {
                            $('.confirmModal').modal('hide');
                            $('#document-table').DataTable().ajax.reload();
                            $('.document-ok').text('OK');
                        }, 1000);
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText); // View detailed error in console
                        alert('Something went wrong.');
                        $('.document-ok').text('OK');
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
