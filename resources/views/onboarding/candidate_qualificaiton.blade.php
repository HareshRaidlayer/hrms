@extends('layout.main')
@section('content')
    <section>

        <div class="container-fluid"><span id="general_result"></span></div>

    <div class="container-fluid">
            <button type="button" class="btn btn-info" name="create_record" id="create_qualification_record"><i
                        class="fa fa-plus"></i>{{__('Add Qualification')}}</button>
    </div>

        <div class="table-responsive">
            <table id="qualification-table" class="table ">
                <thead>
                    <tr>
                        <th>{{ trans('file.School/University') }}</th>
                        <th>{{ __('Time Period') }}</th>
                        <th>{{ __('Education Level') }}</th>
                        <th class="not-exported">{{ trans('file.action') }}</th>
                    </tr>
                </thead>

            </table>
        </div>
        <div id="QualificationformModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{__('Add Qualification')}}</h5>
                    <button type="button" data-dismiss="modal" id="close" aria-label="Close" class="qualification-close"><i class="dripicons-cross"></i></button>
                </div>

                <div class="modal-body">
                    <span id="qualification_form_result"></span>
                    <form method="post" id="qualification_sample_form" class="form-horizontal" autocomplete="off">

                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>{{trans('file.School/University')}} *</label>
                                <input type="text" name="institution_name" id="institution_name"
                                       placeholder={{trans('file.School/University')}}
                                               required class="form-control">
                            </div>


                            <div class="col-md-6 form-group">
                                <label>{{__('Education Level')}}</label>
                                <select name="education_level_id" id="education_level_id" required
                                        class="form-control selectpicker"
                                        data-live-search="true" data-live-search-style="contains"
                                        title='{{__("Select Education Level...")}}'>
                                    @foreach($education_levels as $education_level)
                                        <option value="{{$education_level->id}}">{{$education_level->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-6 form-group">
                                <label>{{trans('file.From')}} *</label>
                                <input type="text" name="from_date" id="qualification_from_date" required
                                       autocomplete="off" class="form-control date" value="">
                            </div>

                            <div class="col-md-6 form-group">
                                <label>{{trans('file.To')}} *</label>
                                <input type="text" name="to_date" id="qualification_to_date" required autocomplete="off"
                                       class="form-control date" value="">
                            </div>

                            <div class="col-md-6 form-group">
                                <label>{{trans('file.Language')}}</label>
                                <select name="language_skill_id" id="language_skill_id" required
                                        class="form-control selectpicker"
                                        data-live-search="true" data-live-search-style="contains"
                                        title='{{__('Selecting',['key'=>trans('file.Language')])}}...'>
                                    @foreach($language_skills as $language_skill)
                                        <option value="{{$language_skill->id}}">{{$language_skill->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>{{__('Professional Skills')}}</label>
                                <select name="general_skill_id" id="general_skill_id" required
                                        class="form-control selectpicker"
                                        data-live-search="true" data-live-search-style="contains"
                                        title='{{__('Selecting',['key'=>__('Professional Skills')])}}...'>
                                    @foreach($general_skills as $general_skill)
                                        <option value="{{$general_skill->id}}">{{$general_skill->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Description')}}</label>
                                    <textarea class="form-control" name="description" id="qualification_description"
                                              rows="3"></textarea>
                                </div>
                            </div>


                            <div class="container">
                                <div class="form-group" align="center">
                                    <input type="hidden" name="action" id="qualification_action"/>
                                    <input type="hidden" name="hidden_id" id="qualification_hidden_id"/>
                                    <input type="submit" name="action_button" id="qualification_action_button"
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
                    <button type="button" class="qualification-close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h4 align="center" style="margin:0;">{{__('Are you sure you want to remove this data?')}}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" name="ok_button"  class="btn btn-danger qualification-ok">{{trans('file.OK')}}</button>
                    <button type="button" class="qualification-close btn-default" data-dismiss="modal">{{trans('file.Cancel')}}</button>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection


@push('scripts')
    <script type="text/javascript">
        (function($) {
            "use strict";

            $(document).ready(function() {

                $('#qualification-table').DataTable().clear().destroy();
                var date = $('.date');
                date.datepicker({
                    format: '{{ env('Date_Format_JS') }}',
                    autoclose: true,
                    todayHighlight: true
                });


                var table_table = $('#qualification-table').DataTable({
                    initComplete: function() {
                        this.api().columns([0]).every(function() {
                            var column = this;
                            var select = $('<select><option value=""></option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function(d, j) {
                                select.append('<option value="' + d + '">' + d +
                                    '</option>');
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
                        url: "{{ route('candidate_qualification.index', $candidate->id) }}",
                    },

                    columns: [

                        {
                            data: 'institution_name',
                            name: 'institution_name',

                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.from_year + ' to ' + row.to_year;
                            }
                        },
                        {
                            data: 'education_level',
                            name: 'education_level',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false
                        }
                    ],


                    "order": [],
                    'language': {
                        'lengthMenu': '_MENU_ {{ __('records per page') }}',
                        "info": '{{ trans('file.Showing') }} _START_ - _END_ (_TOTAL_)',
                        "search": '{{ trans('file.Search') }}',
                        'paginate': {
                            'previous': '{{ trans('file.Previous') }}',
                            'next': '{{ trans('file.Next') }}'
                        }
                    },
                    'columnDefs': [{
                        "orderable": false,
                        'targets': [0, 3],
                    }, ],


                    'select': {
                        style: 'multi',
                        selector: 'td:first-child'
                    },
                    'lengthMenu': [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                });
                new $.fn.dataTable.FixedHeader(table_table);


                $('#create_qualification_record').click(function() {

                    $('.modal-title').text('{{ __('Add Qualification') }}');
                    $('#qualification_action_button').val('{{ trans('file.Add') }}');
                    $('#qualification_action').val('{{ trans('file.Add') }}');
                    $('#QualificationformModal').modal('show');
                });

                $('#qualification_sample_form').on('submit', function(event) {
                    event.preventDefault();
                    if ($('#qualification_action').val() == '{{ trans('file.Add') }}') {

                        $.ajax({
                            url: "{{ route('candidate.qualificationStore', $candidate->id) }}",
                            method: "POST",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function(data) {
                                var html = '';
                                if (data.errors) {
                                    html = '<div class="alert alert-danger">';
                                    for (var count = 0; count < data.errors
                                        .length; count++) {
                                        html += '<p>' + data.errors[count] + '</p>';
                                    }
                                    html += '</div>';
                                }
                                if (data.success) {
                                    html = '<div class="alert alert-success">' + data
                                        .success + '</div>';
                                    $('#qualification_sample_form')[0].reset();
                                    $('select').selectpicker('refresh');
                                    $('.date').datepicker('update');
                                    $('#qualification-table').DataTable().ajax.reload();
                                }
                                $('#qualification_form_result').html(html).slideDown(300)
                                    .delay(5000).slideUp(300);
                            }

                        });
                    }

                    if ($('#qualification_action').val() == '{{ trans('file.Edit') }}') {
                        $.ajax({
                            url: "{{ route('qualifications.qualificationUpdate') }}",
                            method: "POST",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function(data) {
                                var html = '';
                                if (data.errors) {
                                    html = '<div class="alert alert-danger">';
                                    for (var count = 0; count < data.errors
                                        .length; count++) {
                                        html += '<p>' + data.errors[count] + '</p>';
                                    }
                                    html += '</div>';
                                }
                                if (data.error) {
                                    html = '<div class="alert alert-danger">' + data.error +
                                        '</div>';
                                }

                                if (data.success) {
                                    html = '<div class="alert alert-success">' + data
                                        .success + '</div>';
                                    setTimeout(function() {
                                        $('#QualificationformModal').modal('hide');
                                        $('.date').datepicker('update');
                                        $('select').selectpicker('refresh');
                                        $('#qualification-table').DataTable().ajax
                                            .reload();
                                        $('#qualification_sample_form')[0].reset();
                                    }, 2000);

                                }
                                $('#qualification_form_result').html(html).slideDown(300)
                                    .delay(5000).slideUp(300);
                            }
                        });
                    }
                });


                $(document).on('click', '.qualification_edit', function() {

                    var id = $(this).attr('id');
                    var baseUrl = "{{ route('candidate.qualificationEdit', ['id' => 'ID_PLACEHOLDER']) }}";
                    var target = baseUrl.replace('ID_PLACEHOLDER', id);

                    $.ajax({
                        url: target,
                        dataType: "json",
                        success: function(html) {

                            let id = html.data.id;

                            $('#institution_name').val(html.data.institution_name);
                            $('#qualification_from_date').val(html.data.from_year);
                            $('#qualification_to_date').val(html.data.to_year);
                            $('#qualification_description').val(html.data.description);
                            $('#education_level_id').selectpicker('val', html.data
                                .education_level_id);
                            $('#language_skill_id').selectpicker('val', html.data
                                .language_skill_id);
                            $('#general_skill_id').selectpicker('val', html.data
                                .general_skill_id);



                            $('#qualification_hidden_id').val(html.data.id);
                            $('.modal-title').text('{{ trans('file.Edit') }}');
                            $('#qualification_action_button').val(
                                '{{ trans('file.Edit') }}');
                            $('#qualification_action').val('{{ trans('file.Edit') }}');
                            $('#QualificationformModal').modal('show');
                        }
                    })
                });


                let qualification_delete_id;

                $(document).on('click', '.qualification_delete', function() {
                    qualification_delete_id = $(this).attr('id');
                    $('.confirmModal').modal('show');
                    $('.modal-title').text('{{ __('DELETE Record') }}');
                    $('.qualification-ok').text('{{ trans('file.OK') }}');
                });


                $('.qualification-close').click(function() {
                    $('#qualification_sample_form')[0].reset();
                    $('select').selectpicker('refresh');
                    $('.date').datepicker('update');
                    $('.confirmModal').modal('hide');
                    $('#qualification-table').DataTable().ajax.reload();
                });

                $('.qualification-ok').click(function() {
                    
                        var baseUrl = "{{ route('candidate.qualificationDelete', ['id' => 'ID_PLACEHOLDER']) }}";
                        let target = baseUrl.replace('ID_PLACEHOLDER', qualification_delete_id);
                    $.ajax({
                        url: target,
                        beforeSend: function() {
                            $('.qualification-ok').text('{{ trans('file.Deleting...') }}');
                        },
                        success: function(data) {
                            setTimeout(function() {
                                $('.confirmModal').modal('hide');
                                $('#qualification-table').DataTable().ajax.reload();
                            }, 2000);
                        }
                    })
                });

            });
        })(jQuery);
    </script>
@endpush
