<?php

namespace App\Http\Controllers;

use App\Models\JobInterview;
use App\Models\JobPost;
use App\Models\JobQuestion;
use Illuminate\Http\Request;
use App\Models\JobQuestionAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JobQuestionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$logged_user = auth()->user();
		$JobQuestion = JobQuestion::get();
		$job_interview = JobInterview::get();
		if ($logged_user->can('job_interview_question')) {

			if (request()->ajax()) {
				$JobQuestion = DB::table('job_questions')
					->join('job_interviews', 'job_questions.interview_id', '=', 'job_interviews.id')
					->join('job_posts', 'job_interviews.job_id', '=', 'job_posts.id')
					->select(
						'job_questions.*',
						'job_posts.job_title',
						'job_interviews.interview_place',
						'job_interviews.interview_date'
					)
					->get();

				return datatables()->of($JobQuestion)
					->setRowId(fn($row) => $row->id)

					->addColumn('question', fn($row) => $row->question)

					->addColumn('job_title', function ($row) {
						return "<h5>{$row->job_title}</h5><p>{$row->interview_place} ( {$row->interview_date} )</p>";
					})

					->addColumn('question_type', fn($row) => ucfirst($row->question_type ?? 'N/A'))

					->addColumn('options', function ($row) {
						if (is_array($row->options)) {
							return implode(', ', $row->options);
						}
						return $row->options ?? '-';
					})

					->addColumn('status', function ($row) {
						return $row->status == 1
							? '<div class="badge badge-success">Active</div>'
							: '<div class="badge badge-danger">Inactive</div>';
					})

					->addColumn('action', function ($data) {
						$button = '';

						if (auth()->user()->can('store-job_interview_question')) {
							$button .= '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm"><i class="dripicons-pencil"></i></button>';
							$button .= '&nbsp;&nbsp;';
						}

						if (auth()->user()->can('delete-job_interview_question')) {
							$button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="dripicons-trash"></i></button>';
						}

						return $button;
					})

					->rawColumns(['job_title', 'action', 'status']) // include 'status' for HTML badges
					->make(true);
			}

			return view('recruitment.job_questions.index', compact('JobQuestion', 'job_interview'));
		}

		return abort('403', __('You are not authorized'));
	}
    
	public function create()
	{
		return view('recruitment.job_questions.create');
	}

	public function store(Request $request)
	{
		$logged_user = auth()->user();

		if (request()->ajax()) {

			$questions = $request->input('question');
			// dd($questions = $request->input('question'));
			$interviewId = $request->input('interview_id'); // Get the interview_id

			foreach ($questions as $q) {
				if (isset($q['id']) && $q['id'] != null) {
					// Update existing question
					JobQuestion::where('id', $q['id'])->update([
						'interview_id' => $interviewId,
						'question' => $q['question'],
						'question_type' => $q['question_type'] ?? 'text',
						'options' => $q['options'],
						'status' => 1,
						'created_by' => auth()->user()->getRoleNames()->first(),
					]);
				} else {
					// Create new question
					JobQuestion::create([
						'interview_id' => $interviewId,
						'question' => $q['question'],
						'question_type' => 'text', // default to 'text' if not provided
						'options' => NULL,
						'status' => 1,
						'created_by' => auth()->user()->getRoleNames()->first(),
					]);
				}
			}

			return response()->json(['success' => __('Data Added successfully.')]);
		}

		if ($logged_user->can('store-job_interview_question')) {
			$validator = Validator::make(
				$request->only('question', 'question_type', 'job_id'),
				[
					'question' => 'required',
					'question_type' => 'required',
					'job_id' => 'required'
				]
			);

			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()->all()]);
			}

			$data = [];

			$data['question'] = $request->question;
			$data['question_type'] = $request->question_type;
			$data['options'] = $request->options;
			$data['interview_id'] = $request->job_id;
			$data['status'] = $request->status;
			$data['created_by'] = auth()->user()->getRoleNames()->first();

			JobQuestion::create($data);

			return response()->json(['success' => __('Data Added successfully.')]);
		}
		return response()->json(['success' => __('You are not authorized')]);
	}

	public function edit(string $id)
	{
		if (request()->ajax()) {
			$data = JobQuestion::findOrFail($id);

			// $data = DB::table('job_questions')
			// ->join('job_interviews', 'job_questions.interview_id', '=', 'job_interviews.id')
			// ->join('job_posts', 'job_questions.job_id', '=', 'job_posts.id')
			// ->select('job_questions.*', 'job_posts.job_title')
			// ->where('job_questions.id', $id)
			// ->first();

			$data = DB::table('job_questions')
				->join('job_interviews', 'job_questions.interview_id', '=', 'job_interviews.id')
				->join('job_posts', 'job_interviews.job_id', '=', 'job_posts.id')
				->select(
					'job_questions.*',
					'job_posts.job_title',
					'job_interviews.id as interviewId',
					'job_interviews.interview_place',
					'job_interviews.interview_date'
				)
				->where('job_questions.id', $id)
				->first();

			return response()->json(['data' => $data]);
		}
	}

	public function update(Request $request)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('store-job_interview_question')) {
			$id = $request->hidden_id;
			$validator = Validator::make(
				$request->only('question', 'question_type', 'job_id'),
				[
					'question' => 'required',
					'question_type' => 'required',
					'job_id' => 'required'
				]
			);

			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()->all()]);
			}

			$data = [];

			$data['question'] = $request->question;
			$data['question_type'] = $request->question_type;
			$data['options'] = $request->options;
			$data['interview_id'] = $request->job_id;
			$data['status'] = $request->status;

			JobQuestion::find($id)->update($data);

			return response()->json(['success' => __('Data Updated successfully.')]);
		}
		return response()->json(['success' => __('You are not authorized')]);
	}

	public function destroy($id)
	{
		if (!env('USER_VERIFIED')) {
			return response()->json(['error' => 'This feature is disabled for demo!']);
		}
		$logged_user = auth()->user();

		// if ($logged_user->can('delete-job_interview_question')) {
			JobQuestion::whereId($id)->delete();

			return response()->json(['success' => __('Data is successfully deleted')]);
		// }

		return response()->json(['success' => __('You are not authorized')]);
	}

	public function delete_by_selection(Request $request)
	{
		if (!env('USER_VERIFIED')) {
			return response()->json(['error' => 'This feature is disabled for demo!']);
		}
		$logged_user = auth()->user();

		if ($logged_user->can('delete-job_interview_question')) {
			$job_post_id = $request['job_questionIdArray'];
			$job_post = JobQuestion::whereIntegerInRaw('id', $job_post_id);
			if ($job_post->delete()) {
				return response()->json(['success' => __('Multi Delete', ['key' => __('Job question')])]);
			} else {
				return response()->json(['error' => 'Error, selected Job question can not be deleted']);
			}
		}

		return response()->json(['success' => __('You are not authorized')]);
	}

	public function manageInterview(){

		$user = auth()->user();

		$interviews = JobInterview::whereHas('employees', function ($query) use ($user) {
			$query->where('employee_id', $user->id);
		})->with('InterviewJob', 'jobQuestions', 'candidates','jobPost')->get();
		return view('employee.interview.index',compact('interviews'));

	}

	public function saveAnswers(Request $request)
{
    try {
        $answers = $request->input('answers');
        $candidateId = $request->input('candidate_id');

        foreach ($answers as $questionId => $answerText) {
            JobQuestionAnswer::updateOrCreate(
                [
                    'candidate_id' => $candidateId,
                    'question_id' => $questionId,
                ],
                [
                    'answer' => $answerText,
                ]
            );
        }
		return response()->json(['success' => __('Answers saved successfully')]);

    } catch (\Exception $e) {
        \Log::error('Error saving answers: ' . $e->getMessage(), [
            'exception' => $e
        ]);

		return response()->json(['error' => __('Failed to save answers')]);
    }
}
}
