{{-- @php
    use App\Models\JobPost;
@endphp --}}
@extends('layout.main')
@section('page_style')
    <style>

    </style>
@endsection
@section('content')
    <section>

        @include('shared.errors')

        <div class="container">
            <div class="container-fluid"><span id="general_result"></span></div>
            <div class="card-body">

                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link py-1 px-3 active" data-toggle="pill" href="#job-details">Job
                            Details</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link py-1 px-3" data-toggle="pill" href="#applicants">Applicants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1 px-3" data-toggle="pill" href="#for-interview">For Interview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1 px-3" data-toggle="pill" href="#manage-interview">Manage
                            Interview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1 px-3" data-toggle="pill" href="#Selected">Selected</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane container active" id="job-details">
                        <div class="accordion" id="accordionExample">
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($interviews as $interview)
                                <div class="card mt-2">
                                    <div class="card-header p-2" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button"
                                                data-toggle="collapse"
                                                data-target="#{{ $interview->jobPost->job_title }}{{ $count }}"
                                                aria-expanded="true"
                                                aria-controls="{{ $interview->jobPost->job_title }}{{ $count }}">
                                                {{ $interview->jobPost->job_title ?? '' }}
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="{{ $interview->jobPost->job_title }}{{ $count }}" class="collapse"
                                        aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Job Type</label>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $interview->jobPost->job_type }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>No Of Vacancy</label>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $interview->jobPost->no_of_vacancy }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Min. Experience</label>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $interview->jobPost->min_experience }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Gender</label>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $interview->jobPost->gender }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Closing Date</label>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $interview->jobPost->closing_date }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Jod Description</label>
                                                        <textarea type="text" readonly class="form-control">{{ $interview->jobPost->short_description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $count++;
                                @endphp
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane container fade" id="applicants">
                        <div class="table-responsive mt-3">
                            <table id="applicantsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Schedule</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($interviews as $interview)
                                        @foreach ($interview->candidates as $candidate)
                                            <tr>
                                                <td>{{ $candidate->full_name ?? 'N/A' }}</td>
                                                <td>{{ $candidate->phone ?? 'N/A' }}</td>
                                                <td>{{ $interview->interview_date ?? '' }},
                                                    {{ $interview->interview_time ?? '' }}</td>
                                                <td>{{ $candidate->status ?? '-' }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                            type="button" id="actionMenu{{ $candidate->id }}"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu"
                                                            aria-labelledby="actionMenu{{ $candidate->id }}">
                                                            <a class="dropdown-item interview-link" href="#"
                                                                data-candidate-id="{{ $candidate->id }}"
                                                                data-interview-id="{{ $interview->id }}">
                                                                Interview
                                                                </a>
                                                            <a class="dropdown-item" href="#">Generate Job Offer</a>
                                                            <a class="dropdown-item" href="{{route('onboarding.dashboard',$candidate->id)}}">Upload Requirements</a>
                                                            <a class="dropdown-item" href="{{ route('onboarding.onboardCandadite',$candidate->id) }}">Onboarding</a>
                                                            
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane container fade" id="for-interview">
                        <label for="candidateSelect">Agent Name:</label>
                        <select class="form-control candidate-select" id="candidateSelect">
                            <option value="">-- Select Agent --</option>
                            @foreach ($interviews as $interview)
                                @foreach ($interview->candidates as $candidate)
                                    <option value="{{ $candidate->id }}" data-interview-id="{{ $interview->id }}">
                                        {{ $candidate->full_name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        {{-- All candidate question blocks (hidden by default) --}}
                        @foreach ($interviews as $interview)
                            @foreach ($interview->candidates as $candidate)
                                <div class="candidate-questions mt-3" data-interview-id="{{ $interview->id }}"
                                    data-candidate-id="{{ $candidate->id }}" style="display: none;">

                                    @if ($interview->jobQuestions->count())
                                        {{-- <form class="answer-form"> --}}
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Question</th>
                                                    <th>Answer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $count = 1; @endphp
                                                @foreach ($interview->jobQuestions as $question)
                                                    <tr>
                                                        <td>{{ $count++ }}</td>
                                                        <td>{{ $question->question }}</td>
                                                        <td>
                                                            <textarea name="answers[{{ $candidate->id }}][{{ $question->id }}]" class="form-control">{{ $question->questionAnswer->answer ?? '' }}</textarea>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-primary save-answers"
                                            data-candidate-id="{{ $candidate->id }}"
                                            data-interview-id="{{ $interview->id }}"
                                            data-job-id={{ $interview->job_id }}>
                                            Save Data
                                        </button>
                                        </form>
                                    @else
                                        <p>No questions found.</p>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                    <div class="tab-pane container fade" id="manage-interview">
                        <table id="interviewTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Schedule</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($interviews as $interview)
                                    <tr>
                                        <td>{{ $interview->jobPost->job_title ?? '' }} ({{$interview->interview_place ?? ''}})</td>
                                        <td>{{ $interview->interview_date ?? '' }},
                                            {{ $interview->interview_time ?? '' }}</td>

                                        <td>
                                            <!-- Example action buttons -->
                                            <button href="#" data-id="{{ $interview->id }}"
                                                class="btn btn-sm btn-info manage-interview">Manage</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane container fade" id="Selected">Selected</div>
                </div>
            </div>
            <div class="modal fade" id="manageInterviewModal" tabindex="-1" role="dialog"
                aria-labelledby="manageInterviewModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title-int" id="manageInterviewModalLabel">Manage Interview</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="manageInterviewContent">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>QUESTION</th>
                                        <th><button type="button" id="addQuestion" class="btn btn-success btn-sm">ADD
                                                QUESTION</button></th>
                                    </tr>
                                </thead>
                                <tbody id="questionList">
                                    <!-- Dynamic rows will be added here -->
                                </tbody>
                            </table>
                            <button type="button" id="saveQuestions" class="btn btn-primary">Save Questions</button>
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

            var jobQuestions = [];

            function renderQuestions() {
                var tbody = $('#questionList');
                tbody.empty(); // Clear table body before rendering

                jobQuestions.forEach(function(item, index) {
                    var row = `
                        <tr data-index="${index}">
                            <td>${index + 1}.</td>
                            <td><textarea class="form-control question" rows="2">${item.question}</textarea></td>
                            <td><button type="button" class="btn btn-link text-danger remove-question">Remove</button></td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            }

            $('#view-interview-answer').on('click', function() {
                $('#interviewAnswers').modal('show');
            });

            $(document).ready(function() {
                // Toggle candidate questions on select
                $('#candidateSelect').on('change', function() {
                    let selectedCandidateId = $(this).val();
                    let selectedInterviewId = $(this).find('option:selected').data('interview-id');

                    $('.candidate-questions').hide(); // Hide all
                    $(`.candidate-questions[data-candidate-id="${selectedCandidateId}"][data-interview-id="${selectedInterviewId}"]`)
                        .show();
                });

                // Save answers on button click
                $('.save-answers').on('click', function() {
                    let interviewId = $(this).data('interview-id');
                    let candidateId = $(this).data('candidate-id');
                    // let jobId = $(this).data('job-id');

                    // Collect answers
                    let answers = {};
                    $(`.candidate-questions[data-candidate-id="${candidateId}"][data-interview-id="${interviewId}"] textarea`)
                        .each(function() {
                            let name = $(this).attr('name');
                            let questionId = name.match(/\[(\d+)\]\[(\d+)\]/)[2];
                            answers[questionId] = $(this).val();
                        });

                    // Send via AJAX
                    $.ajax({
                        url: "{{ route('job_questions.answers.save') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            candidate_id: candidateId,
                            interview_id: interviewId,
                            // job_id:jobId,
                            answers: answers
                        },
                        success: function(res) {
                            $('#view-interview-answer').modal('hide');
                            $('#candidateSelect').val('');
                            // Hide all question blocks again
                            $('.candidate-questions').hide();

                            var html = '<div class="alert alert-success">' + res.success +
                            '</div>';
                        $('#general_result').html(html).slideDown(300).delay(5000).slideUp(300);

                        },
                        error: function(err) {
                            var html =
                            '<div class="aalert alert-danger">Something went wrong while saving.</div>';
                        $('#general_result').html(html).slideDown(300).delay(5000).slideUp(300);
                        }
                    });
                });
            });

            $(document).on('click', '.manage-interview', function() {
                var interviewId = $(this).data('id');

                // Open the modal
                $('#manageInterviewModal').modal('show');

                // Send AJAX request

                $.ajax({
                    url: '/recruitment/interview/manage/' + interviewId, // Laravel route
                    type: 'GET',
                    success: function(response) {
                        jobQuestions = response.data
                            .job_questions; // Assign job_questions to variable
                        renderQuestions(); // Render on page
                        $('#saveQuestions').data('id', response.data.job_id);
                        $('#saveQuestions').data('interview-id', response.data.id);
                    },
                    error: function(xhr) {
                        $('#manageInterviewContent').html(
                            '<div class="alert alert-danger">Something went wrong!</div>');
                    }
                });
            });

            //  Add new question
            $('#addQuestion').on('click', function() {
                jobQuestions.push({
                    id: null,
                    interview_id: null,
                    question: '',
                    question_type: 'text',
                    options: null,
                    status: true,
                    created_by: 'admin'
                });
                renderQuestions();
            });

            //  Remove question
            $(document).on('click', '.remove-question', function() {
                var rowIndex = $(this).closest('tr').data('index');
                jobQuestions.splice(rowIndex, 1);
                renderQuestions();
            });

            // Save questions via AJAX
            $('#saveQuestions').on('click', function() {

                $('#questionList tr').each(function(index) {
                    var updatedQuestion = $(this).find('.question').val();
                    jobQuestions[index].question = updatedQuestion;
                });
                var interviewId = $(this).data('interview-id');

                $.ajax({
                    url: "{{ route('job_questions.store') }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        interview_id: interviewId,
                        question: jobQuestions,
                    },
                    success: function(response) {
                        $('#manageInterviewModal').modal('hide');
                        renderQuestions();
                        var html = '<div class="alert alert-success">' + response.success +
                            '</div>';
                        $('#general_result').html(html).slideDown(300).delay(5000).slideUp(300);

                    },
                    error: function(xhr) {

                        var html =
                            '<div class="aalert alert-danger">Something went wrong while saving.</div>';
                        $('#general_result').html(html).slideDown(300).delay(5000).slideUp(300);
                    }
                });
            });

            $('#questionList').on('click', '.remove-question', function() {
                var row = $(this).closest('tr');
                var index = row.data('index');
                var questionId = jobQuestions[index].id;

                if (questionId !== null && questionId !== undefined) {
                    // Existing question, needs to be deleted from database
                    $.ajax({
                        url: '/recruitment/job-questions/' + questionId + '/delete',
                        type: 'get',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Remove from jobQuestions array
                            jobQuestions.splice(index, 1);
                        },
                        error: function(xhr) {
                            alert('Something went wrong while deleting.');
                        }
                    });
                } else {
                    // New question not saved yet, just remove from the array
                    jobQuestions.splice(index, 1);
                    renderQuestions();
                }
            });

            $(document).on('click', '.interview-link', function (e) {
                e.preventDefault();
                
                let selectedCandidateId = $(this).data('candidate-id');
                let selectedInterviewId = $(this).data('interview-id');
                $('#candidateSelect option').each(function () {
                    if ($(this).val() == selectedCandidateId && $(this).data('interview-id') == selectedInterviewId) {
                        $(this).prop('selected', true);
                    } else {
                        $(this).prop('selected', false);
                    }
                });
                $('#candidateSelect').trigger('change');

                // Activate the #for-interview tab (Bootstrap tabs)
                $('a[href="#for-interview"]').tab('show');

                // Show only the matching candidate-questions
                $('.candidate-questions').hide();
                $(`.candidate-questions[data-candidate-id="${selectedCandidateId}"][data-interview-id="${selectedInterviewId}"]`)
                    .show();
            });

        })(jQuery);
    </script>
@endpush
