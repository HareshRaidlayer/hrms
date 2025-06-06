@extends('layout.main')
@section('content')


    <section>


        <div class="container-fluid">
            <h1>{{__('Transfer History')}}</h1>
            @can('store-transfer')
                <button type="button" class="btn btn-info" name="create_record" id="create_record"><i
                            class="fa fa-tumblr"></i> {{__('Add Transfer')}}</button>
            @endcan
            @can('delete-transfer')
                <button type="button" class="btn btn-outline-secondary float-right" name="trans_index" id="trans_index">
                    <i
                            class="fa fa-balance-scale"></i><a
                            href="{{route('transactions.index')}}">{{__('Transaction History')}}</a></button>
            @endcan
        </div>


        <div class="table-responsive">
            <table id="transfer-table" class="table ">
                <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{__('Date')}}</th>
                    <th>{{__('From Account')}}</th>
                    <th>{{__('To Account')}}</th>
                    @if(config('variable.currency_format')=='suffix')
                        <th>{{trans('file.Amount')}} ({{config('variable.currency')}})</th>
                    @else
                        <th>({{config('variable.currency')}}) {{trans('file.Amount')}}</th>
                    @endif
                    <th>{{__('Payment Mode')}}</th>
                    <th>{{__('Reference No')}}</th>
                </tr>
                </thead>

            </table>
        </div>
    </section>



    <div id="formModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{__('Add Transfer')}}</h5>
                    <button type="button" data-dismiss="modal" id="close" aria-label="Close" class="close"><i class="dripicons-cross"></i></button>
                </div>

                <div class="modal-body">
                    <span id="form_result"></span>
                    <form method="post" id="sample_form" class="form-horizontal">

                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('From Account')}}*</label>&nbsp;<span
                                            id="balance"></span>
                                    <select name="from_account_id" id="from_account_id"
                                            class="form-control selectpicker dynamic"
                                            data-live-search="true" data-live-search-style="contains"
                                            data-dependent="account_balance"
                                            title='{{__('Selecting',['key'=>trans('file.Account')])}}...'>
                                        @foreach($accounts as $account)
                                            <option value="{{$account->id}}">{{$account->account_name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('To Account')}}*</label>&nbsp;<span
                                            id="balance"></span>
                                    <select name="to_account_id" id="to_account_id" class="form-control selectpicker"
                                            data-live-search="true" data-live-search-style="contains"
                                            title='{{__('Selecting',['key'=>trans('file.Account')])}}...'>
                                        @foreach($accounts as $account)
                                            <option value="{{$account->id}}">{{$account->account_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6 form-group">
                                @if(config('variable.currency_format')=='suffix')
                                    <label>{{trans('file.Amount')}} ({{config('variable.currency')}}) *</label>
                                @else
                                    <label>({{config('variable.currency')}}) {{trans('file.Amount')}} *</label>
                                @endif
                                <input type="text" name="amount" id="amount" required class="form-control"
                                       placeholder="{{trans('file.Amount')}}">
                            </div>


                            <div class="col-md-6 form-group">
                                <label>{{trans('file.Date')}} *</label>
                                <input type="text" name="date" id="date" class="form-control date" autocomplete="off"
                                       value="">
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('Payment Mode')}} </label>
                                    <select name="payment_method_id" id="payment_method_id"
                                            class="form-control selectpicker"
                                            data-live-search="true" data-live-search-style="contains"
                                            title='{{__('Selecting',['key'=>__('Payment Mode')])}}...'>
                                        @foreach($payment_methods as $payment_method)
                                            <option value="{{$payment_method->id}}">{{$payment_method->method_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6 form-group">
                                <label>{{__('Reference No')}} *</label>
                                <input type="text" name="reference" id="reference" required class="form-control"
                                       placeholder="{{__('Reference No')}}">
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Description')}}</label>
                                    <textarea class="form-control" id="description" name="description"
                                              rows="3"></textarea>
                                </div>
                            </div>


                            <div class="container">
                                <div class="form-group" align="center">
                                    <input type="hidden" name="action" id="action"/>
                                    <input type="hidden" name="hidden_id" id="hidden_id"/>
                                    <input type="submit" name="action_button" id="action_button" class="btn btn-warning"
                                           value={{trans('file.Add')}}>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>



@endsection

@push('scripts')
<script type="text/javascript">
    (function($) {
        "use strict";

        $(document).ready(function () {

            var date = $('.date');
            date.datepicker({
                format: '{{ env('Date_Format_JS')}}',
                autoclose: true,
                todayHighlight: true
            });


            var table_table = $('#transfer-table').DataTable({
                initComplete: function () {
                    this.api().columns([1]).every(function () {
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
                    url: "{{ route('finance_transfer.index') }}",
                },

                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'from_account',
                        name: 'from_account',
                    },
                    {
                        data: 'to_account',
                        name: 'to_account',
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function (data) {
                            if ('{{config('variable.currency_format') =='suffix'}}') {
                                return data + ' {{config('variable.currency')}}';
                            } else {
                                return '{{config('variable.currency')}} ' + data;

                            }
                        }
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method',
                    },
                    {
                        data: 'reference',
                        name: 'reference',
                    },

                ],


                "order": [],
                'language': {
                    'lengthMenu': '_MENU_ {{__("records per page")}}',
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
                        'targets': [0, 6],
                    },
                    {
                        'render': function (data, type, row, meta) {
                            if (type == 'display') {
                                data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                            }

                            return data;
                        },
                        'checkboxes': {
                            'selectRow': true,
                            'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                        },
                        'targets': [0]
                    }
                ],


                'select': {style: 'multi', selector: 'td:first-child'},
                'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
                dom: '<"row"lfB>rtip',
                buttons: [
                    {
                        extend: 'pdf',
                        text: '<i title="export to pdf" class="fa fa-file-pdf-o"></i>',
                        exportOptions: {
                            columns: ':visible:Not(.not-exported)',
                            rows: ':visible'
                        },
                    },
                    {
                        extend: 'csv',
                        text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                        exportOptions: {
                            columns: ':visible:Not(.not-exported)',
                            rows: ':visible'
                        },
                    },
                    {
                        extend: 'print',
                        text: '<i title="print" class="fa fa-print"></i>',
                        exportOptions: {
                            columns: ':visible:Not(.not-exported)',
                            rows: ':visible'
                        },
                    },
                    {
                        extend: 'colvis',
                        text: '<i title="column visibility" class="fa fa-eye"></i>',
                        columns: ':gt(0)'
                    },
                ],
            });
            new $.fn.dataTable.FixedHeader(table_table);
        });

        $('#create_record').on('click', function () {

            $('.modal-title').text('{{__('Transfer For')}}');
            $('#action_button').val('{{trans("file.Add")}}');
            $('#action').val('{{trans("file.Add")}}');
            $('#formModal').modal('show');
        });

        $('#sample_form').on('submit', function (event) {
            event.preventDefault();
            if ($('#action').val() == '{{trans('file.Add')}}') {

                $.ajax({
                    url: "{{ route('finance_transfer.store') }}",
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
                            $('#sample_form')[0].reset();
                            $('select').selectpicker('refresh');
                            $('.date').datepicker('update');
                            $('#transfer-table').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html).slideDown(300).delay(5000).slideUp(300);
                    }
                })
            }
        });

        $('.dynamic').change(function () {
            if ($(this).val() !== '') {
                let value = $(this).val();
                let dependent = $(this).data('dependent');
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dynamic_balance') }}",
                    method: "POST",
                    data: {value: value, _token: _token, dependent: dependent},
                    success: function (result) {

                        $('#balance').html(result);
                        $('select').selectpicker();
                    }
                });
            }
        });

    })(jQuery);
</script>
@endpush
