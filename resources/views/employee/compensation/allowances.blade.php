@extends('layout.main')
@section('content')
    <section>

        <div class="container-fluid"><span id="general_result"></span></div>


        <div class="table-responsive">
            <table id="allowance-table" class="table ">
                <thead>
                <tr>
                    <th>{{__('Month-Year')}}</th>
                    <th>{{__('Allowance Type')}}</th>
                    <th>{{__('Allowance Title')}}</th>
                    @if(config('variable.currency_format')=='suffix')
                        <th>{{__('Allowance Amount')}} ({{config('variable.currency')}})</th>
                    @else
                        <th>({{config('variable.currency')}}) {{__('Allowance Amount')}}</th>
                    @endif
                </tr>
                </thead>

            </table>
        </div>
    </section>
@endsection


@push('scripts')
    <script type="text/javascript">
        (function($) {
            "use strict";

            $(document).ready(function() {

                $('#allowance-table').DataTable().clear().destroy();

    var table_table = $('#allowance-table').DataTable({
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
            url: "{{ route('employees.allowances',$employee->id) }}",
        },

        columns: [

            {
                data: 'month_year',
                name: 'month_year',
            },
            {
                data: 'is_taxable',
                name: 'is_taxable',
                render: function (data, type, row) {
                    if (data == 1) {
                        return "{{trans('file.Taxable')}}";
                    } else {
                        return "{{trans('file.Non-Taxable')}}"
                    }
                }

            },
            {
                data: 'allowance_title',
                name: 'allowance_title'
            },
            {
                data: 'allowance_amount',
                name: 'allowance_amount',
                render: function (data) {
                    if ('{{config('variable.currency_format') =='suffix'}}') {
                        return data + ' {{config('variable.currency')}}';
                    } else {
                        return '{{config('variable.currency')}} ' + data;

                    }
                }
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
                'targets': [0, 2],
            },
        ],


        {{-- 'select': {style: 'multi', selector: 'td:first-child'}, --}}
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
    });
    new $.fn.dataTable.FixedHeader(table_table);
            });



        })(jQuery);
    </script>
@endpush
