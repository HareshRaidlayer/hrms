
@extends('layout.main')
@section('page_style')
    <style>

    </style>
@endsection
@section('content')

    <section>

        @include('shared.errors')

        <!-- Content -->
        <div class="container-fluid employee-dash">
            <div class="row flex-column-reverse flex-sm-row">
                <div class="col-12 col-md-12">
                    <h2>OFF BOARDING</h2>
                    <div class="timeline">

                        <!-- START OF TIMELINE ITEM -->
                        <div class="timeline-step">
                            <div class="left text-right">
                                <div class="step-title">Acceptance of Resignation / Termination</div>
                            </div>
                            <div class="middle">
                                <div class="dot"></div>
                            </div>
                            <div class="right"></div>
                        </div>

                        <div class="timeline-step">
                            <div class="left text-right">
                                <div class="step-title">Exit Interview Documentation</div>
                            </div>
                            <div class="middle">
                                <div class="dot"></div>
                            </div>
                            <div class="right"></div>
                        </div>

                        <div class="timeline-step">
                            <div class="left text-right">
                                <div class="step-title"> <a href="#" >Asset Recovery</a> </div>
                            </div>
                            <div class="middle">
                                <div class="dot"></div>
                            </div>
                            <div class="right"></div>
                        </div>

                        <div class="timeline-step">
                            <div class="left text-right">
                                <div class="step-title">Accountabilities</div>
                            </div>
                            <div class="middle">
                                <div class="dot"></div>
                            </div>
                            <div class="right"></div>
                        </div>

                        <div class="timeline-step">
                            <div class="left text-right">
                                <div class="step-title">Legal Obligations</div>
                            </div>
                            <div class="middle">
                                <div class="dot"></div>
                            </div>
                            <div class="right"></div>
                        </div>

                        <div class="timeline-step">
                            <div class="left text-right">
                                <div class="step-title"><a href="#" >Payroll & Government Benefits</a></div>
                            </div>
                            <div class="middle">
                                <div class="dot"></div>
                            </div>
                            <div class="right"></div>
                        </div>
                        <div class="timeline-step">
                            <div class="left text-right">
                                <div class="step-title"><a href="#" >System Access Deactivation</a></div>
                            </div>
                            <div class="middle">
                                <div class="dot"></div>
                            </div>
                            <div class="right"></div>
                        </div>
                        <div class="timeline-step">
                            <div class="left text-right">
                                <div class="step-title"><a href="#" >Final Clearance</a></div>
                            </div>
                            <div class="middle">
                                <div class="dot"></div>
                            </div>
                            <div class="right"></div>
                        </div>
                        <div class="timeline-step">
                            <div class="left text-right">
                                <div class="step-title"><a href="#" >Issuance of COE</a></div>
                            </div>
                            <div class="middle">
                                <div class="dot"></div>
                            </div>
                            <div class="right"></div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        
    </section>
@endsection


@push('scripts')
    <script>
        (function($) {
            "use strict";


        })(jQuery);
    </script>
@endpush
