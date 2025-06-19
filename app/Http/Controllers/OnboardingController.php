<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobInterview;
use App\Models\EmployeeDocument;
use App\Models\Employee;
use App\Models\DocumentType;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OnboardingController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->ajax()) {
            $onboarding = JobInterview::whereHas('employees', function ($query) use ($user) {
                $query->where('employee_id', $user->id);
            })
                ->with('InterviewJob', 'candidates')
                ->get();

            $candidates = collect();
            foreach ($onboarding as $interview) {
                foreach ($interview->candidates as $candidate) {
                    $candidate->interview_date = $interview->interview_date;
                    $candidate->interview_time = $interview->interview_time;
                    $candidate->interview_id = $interview->id;
                    $candidates->push($candidate);
                }
            }

            return datatables()->of($candidates)
                ->addColumn('name', function ($row) {
                    return $row->full_name ?? 'N/A';
                })
                ->addColumn('contact', function ($row) {
                    return $row->phone ?? 'N/A';
                })
                ->addColumn('schedule', function ($row) {
                    return $row->interview_date . ', ' . $row->interview_time;
                })
                ->addColumn('status', function ($row) {
                    return $row->status ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $url = route('onboarding.dashboard', ['id' => $row->id]);
                    $button = '<a href="' . $url . '" name="show" class="btn btn-light btn-sm">' . __('Onboarding') . '</a>';
                    return $button;
                })



                ->rawColumns(['action'])
                ->make(true);
        }

        return view('onboarding.index');
    }
    public function dashboard($id)
    {

        $user = auth()->user();

        $employee = Employee::find($user->id);
        $document_types = DocumentType::get();
        // print_r($employee);
        // exit;

        if (request()->ajax()) {
            return datatables()->of(EmployeeDocument::with('DocumentType')->where('employee_id', $employee->id)->get())
                ->setRowId(function ($document) {
                    return $document->id;
                })
                ->addColumn('document', function ($row) {
                    return $row->DocumentType->document_type;
                })
                ->addColumn('expiry_date', function ($row) {
                    return $row->expiry_date;
                })
                ->addColumn('title', function ($row) {
                    if ($row->document_file) {
                        return $row->document_title . '<br><h6><a href="' . route('documents_document.download', $row->id) . '">' . trans('file.File') . '</a></h6>';
                    } else {
                        return $row->document_title;
                    }
                })
                ->addColumn('action', function ($data) use ($user) {

                    $button = '<button type="button" name="edit" id="' . $data->id . '" class="document_edit btn btn-primary btn-sm"><i class="dripicons-pencil"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" class="document_delete btn btn-danger btn-sm"><i class="dripicons-trash"></i></button>';

                    return $button;

                })
                ->rawColumns(['action', 'title'])
                ->make(true);
        }
        return view('onboarding.documents.index', compact('employee', 'document_types'));
    }
    public function update(Request $request)
    {
        $id = $request->hidden_id;
        $logged_user = auth()->user();
        $validator = Validator::make(
            $request->only(
                'document_title',
                'document_type_id',
                'expiry_date',
                'description',
                'document_file',
                'is_notify'
            ),
            [
                'document_title' => 'required',
                'document_type_id' => 'required',
                'expiry_date' => 'required',
                'document_file' => 'nullable|file|max:10240|mimes:jpeg,png,jpg,gif,ppt,pptx,doc,docx,pdf',
            ]
        );


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }


        $data = [];

        $data['document_title'] = $request->document_title;
        $data['document_type_id'] = $request->document_type_id;
        $data['expiry_date'] = $request->expiry_date;
        $data['description'] = $request->description;
        $data['is_notify'] = $request->is_notify;


        $file = $request->document_file;

        $file_name = null;

        if (isset($file)) {
            $this->unlink($id);
            $file_name = $data['document_title'];
            if ($file->isValid()) {
                $file_name = $file_name . '.' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('document_documents', $file_name);
                $data['document_file'] = $file_name;
            }
        }

        EmployeeDocument::find($id)->update($data);

        return response()->json(['success' => __('Data is successfully updated')]);
    }
    public function unlink($id)
    {

        $document = EmployeeDocument::findOrFail($id);
        $file_path = $document->document_file;

        if ($file_path) {
            $file_path = public_path('uploads/document_documents/' . $file_path);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
    public function destroy($id)
    {
        $logged_user = auth()->user();

        $this->unlink($id); // Custom method to delete file if needed
        EmployeeDocument::destroy($id); // Or use ::whereId($id)->delete();

        return response()->json(['success' => __('Data is successfully deleted')]);
    }

}
