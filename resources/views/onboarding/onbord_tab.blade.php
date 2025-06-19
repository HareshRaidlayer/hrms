@extends('layout.main')
@section('content')

    <section>

        <div class="container-fluid"><span id="general_result"></span></div>

        <div class="table-responsive">
            <h2>ONBOARDING</h2>
            <table class="table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Acceptance of the Handbook</th>
                    <th>Specific</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Company Policy</td>
                        <td>Mark as Complete</td>
                        <td>PDF</td>
                        <td>
                            <a class="view btn btn-secondary btn-sm" href="#"><i class="dripicons-preview"></i></a>
                            <a class="edit btn btn-primary btn-sm" href="#"><i class="dripicons-pencil"></i></a>
                            <button type="button" name="delete" class="delete btn btn-danger btn-sm"><i class="dripicons-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Read</td>
                        <td>Mark as Complete</td>
                        <td>video</td>
                        <td>
                            <a class="view btn btn-secondary btn-sm" href="#"><i class="dripicons-preview"></i></a>
                            <a class="edit btn btn-primary btn-sm" href="#"><i class="dripicons-pencil"></i></a>
                            <button type="button" name="delete" class="delete btn btn-danger btn-sm"><i class="dripicons-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Watch video</td>
                        <td>Mark as Complete</td>
                        <td>E-sign</td>
                        <td>
                            <a class="view btn btn-secondary btn-sm" href="#"><i class="dripicons-preview"></i></a>
                            <a class="edit btn btn-primary btn-sm" href="#"><i class="dripicons-pencil"></i></a>
                            <button type="button" name="delete" class="delete btn btn-danger btn-sm"><i class="dripicons-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>


    <div id="confirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">{{trans('file.Confirmation')}}</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h4 align="center">{{__('Are you sure you want to remove this data?')}}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" name="ok_button" id="ok_button"
                        class="btn btn-danger">{{trans('file.OK')}}'</button>
                    <button type="button" class="close btn-default" data-dismiss="modal">{{trans('file.Cancel')}}</button>
                </div>
            </div>
        </div>
    </div>



@endsection


@push('scripts')
    <script type="text/javascript">
        (function ($) {
            "use strict";

            $(document).ready(function () {

                
            });
        })(jQuery);
    </script>
@endpush
