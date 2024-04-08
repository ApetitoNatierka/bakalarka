<?php

namespace App\Http\Controllers;

use App\Models\MedicalExamination;
use App\Models\MedicalTreatment;
use Illuminate\Http\Request;

class MedicalTreatmentController extends Controller
{
    public function add_medical_treatment(Request $request) {
        $validate_data = $request->validate([
            'medical_examination_id' => ['required', 'exists:medical_examinations,id'],
            'animal_id' => ['required', 'exists:animals,id'],
            'note' => ['required'],
            'start' => ['required'],
            'end' => ['required'],
        ]);

        $medical_treatment = MedicalTreatment::create($validate_data);

        $medical_examinations = MedicalExamination::all();

        return response()->json(['message' => 'Medical treatment created successfully', 'medical_treatment' => $medical_treatment, 'medical_examinations' => $medical_examinations]);
    }

    public function delete_medical_treatment(Request $request) {
        $validate_data = $request->validate([
            'medical_treatment_id' => ['required'],
        ]);

        $medical_treatment = MedicalTreatment::find($validate_data['medical_treatment_id']);

        $medical_treatment->delete();

        return response()->json(['message' => 'Medical treatment deleted successfully']);
    }

    public function modify_medical_treatment(Request $request) {
        $validate_data = $request->validate([
            'medical_treatment_id' => ['required'],
            'note' => ['required'],
            'start' => ['required'],
            'end' => ['required'],
        ]);

        $medical_treatment = MedicalTreatment::find($validate_data['medical_treatment_id']);

        unset($validate_data['medical_treatment_id']);

        $medical_treatment->update($validate_data);

        return response()->json(['message' => 'Medical treatment modified successfully']);
    }

}

