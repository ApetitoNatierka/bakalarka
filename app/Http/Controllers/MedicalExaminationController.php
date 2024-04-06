<?php

namespace App\Http\Controllers;

use App\Models\MedicalExamination;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicalExaminationController extends Controller
{
    public function get_medical_examinations() {
        $medical_examinations = MedicalExamination::all();

        return view('medical_examinations', ['medical_examinations' => $medical_examinations]);
    }

    public function add_medical_examination(Request $request) {
        $validate_data = $request->validate([
            'medical_examination' => ['required', Rule::unique('medical_examinations', 'medical_examination')],
            'description' => ['required'],
        ]);

        $medical_examination = MedicalExamination::create($validate_data);

        return response()->json(['message' => 'Medical examination created successfully', 'medical_examination' => $medical_examination]);
    }

    public function delete_medical_examination(Request $request) {
        $validate_data = $request->validate([
            'medical_examination_id' => ['required'],
        ]);

        $medical_examination = MedicalExamination::find($validate_data['medical_examination_id']);

        $medical_examination->delete();

        return response()->json(['message' => 'Medical examination deleted successfully']);
    }

    public function modify_medical_examination(Request $request) {
        $validate_data = $request->validate([
            'medical_examination_id' => ['required'],
            'medical_examination' => ['required'],
            'description' => ['required'],
        ]);

        $medical_examination = MedicalExamination::find($validate_data['medical_examination_id']);

        unset($validate_data['medical_examination_id']);

        $medical_examination->update($validate_data);

        return response()->json(['message' => 'Medical examination modified successfully']);
    }

    public function get_search_medical_examinations(Request $request) {
        $medical_examination_id = $request->input('medical_examination_id', null);
        $medical_examination = $request->input('medical_examination', null);
        $description = $request->input('description', null);

        $medical_examinationQuery = MedicalExamination::query();

        if ($medical_examination_id) {
            $medical_examinationQuery->where(function ($query) use ($medical_examination_id) {
                $query->where('id', 'like', '%' . $medical_examination_id . '%');
            });
        }

        if ($medical_examination) {
            $medical_examinationQuery->where(function ($query) use ($medical_examination) {
                $query->where('medical_examination', 'like', '%' . $medical_examination . '%');
            });
        }

        if ($description) {
            $medical_examinationQuery->where(function ($query) use ($description) {
                $query->where('description', 'like', '%' . $description . '%');
            });
        }

        $medical_examinations = $medical_examinationQuery->get();

        return response()->json([
            'message' => 'Medical examinations returned successfully',
            'medical_examinations' => $medical_examinations,
        ]);
    }

    public function select_medical_examinations(Request $request) {
        $search_term = $request->search_term;
        $medical_examinations = MedicalExamination::where('medical_examination', 'like', '%' . $search_term . '%')->get();

        return response()->json(['medical_examinations' => $medical_examinations]);
    }

}
