<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\JobQuestion;
use Illuminate\Http\Request;
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
	$job_posts = JobPost::where('status',1)->get();
    if ($logged_user->can('job_interview_question')) {

        if (request()->ajax()) {
			$JobQuestion = DB::table('job_questions')
			->join('job_posts', 'job_questions.job_id', '=', 'job_posts.id')
			->select(
				'job_questions.*',
				'job_posts.job_title' // or any other columns from job_posts
			)
			->get();

            return datatables()->of($JobQuestion)
                ->setRowId(fn($row) => $row->id)

                ->addColumn('question', fn($row) => $row->question)
				->addColumn('job_title', fn($row) => $row->job_title)

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

                ->rawColumns(['action', 'status']) // include 'status' for HTML badges
                ->make(true);
        }

        return view('recruitment.job_questions.index',compact('JobQuestion','job_posts'));
    }

    return abort('403', __('You are not authorized'));
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recruitment.job_questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
	{
		$logged_user = auth()->user();
        	
		if ($logged_user->can('store-job_interview_question'))
		{
			$validator = Validator::make($request->only('question', 'question_type','job_id'),
				[
					'question' => 'required',
					'question_type' => 'required',
					'job_id' => 'required'
				]
			);

			if ($validator->fails())
			{
				return response()->json(['errors' => $validator->errors()->all()]);
			}

			$data = [];

			$data['question'] = $request->question;
			$data['question_type'] = $request->question_type;
			$data['options'] = $request->options;
			$data['job_id'] = $request->job_id;
			$data['status'] = $request->status;
			$data['created_by'] = auth()->user()->getRoleNames()->first();
            		
			JobQuestion::create($data);

			return response()->json(['success' => __('Data Added successfully.')]);
		}
		return response()->json(['success' => __('You are not authorized')]);
	}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (request()->ajax())
		{
			$data = JobQuestion::findOrFail($id);

			$data = DB::table('job_questions')
			->join('job_posts', 'job_questions.job_id', '=', 'job_posts.id')
			->select('job_questions.*', 'job_posts.job_title') // add more fields if needed
			->where('job_questions.id', $id)
			->first();

			return response()->json(['data' => $data]);
		}
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $logged_user = auth()->user();
        	
		if ($logged_user->can('store-job_interview_question'))
		{
            $id = $request->hidden_id;
			$validator = Validator::make($request->only('question', 'question_type','job_id'),
				[
					'question' => 'required',
					'question_type' => 'required',
					'job_id' => 'required'
				]
			);

			if ($validator->fails())
			{
				return response()->json(['errors' => $validator->errors()->all()]);
			}

			$data = [];

			$data['question'] = $request->question;
			$data['question_type'] = $request->question_type;
			$data['options'] = $request->options;
			$data['job_id'] = $request->job_id;
			$data['status'] = $request->status;
            		
            JobQuestion::find($id)->update($data);

			return response()->json(['success' => __('Data Updated successfully.')]);
		}
		return response()->json(['success' => __('You are not authorized')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
	{
		if(!env('USER_VERIFIED'))
		{
			return response()->json(['error' => 'This feature is disabled for demo!']);
		}
		$logged_user = auth()->user();

		if ($logged_user->can('delete-job_interview_question'))
		{
			JobQuestion::whereId($id)->delete();

			return response()->json(['success' => __('Data is successfully deleted')]);
		}

		return response()->json(['success' => __('You are not authorized')]);
	}

    public function delete_by_selection(Request $request)
	{
		if(!env('USER_VERIFIED'))
		{
			return response()->json(['error' => 'This feature is disabled for demo!']);
		}
		$logged_user = auth()->user();

		if ($logged_user->can('delete-job_interview_question'))
		{
			$job_post_id = $request['job_questionIdArray'];
			$job_post = JobQuestion::whereIntegerInRaw('id', $job_post_id);
			if ($job_post->delete())
			{
				return response()->json(['success' => __('Multi Delete', ['key' => __('Job question')])]);
			} else
			{
				return response()->json(['error' => 'Error, selected Job question can not be deleted']);
			}
		}

		return response()->json(['success' => __('You are not authorized')]);
	}
}
