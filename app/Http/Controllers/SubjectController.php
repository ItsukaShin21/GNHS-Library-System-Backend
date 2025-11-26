<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    public function fetchSubjects() {
        $subjectList = Subject::get();

        return response()->json([
            "subjects" => $subjectList
        ]);
    }

    public function fetchDocumentInfo($file_id) {
        $document = Documents::where('id', $file_id)->first();

        $documentInfo = [
            "id" => $document->id,
            "file_name" => $document->file_name,
            "uploaded_by" => $document->uploaded_by,
            "file_type" => $document->file_type,
            "uploaded_at" => $document->uploaded_at,
        ];

        return response()->json([
            "document_info" => $documentInfo,
        ]);
    }

    public function fetchPendingDocuments() {
        $document = Documents::where("status", "Pending")
            ->orderBy("uploaded_at", "desc")->get();

        return response()->json([
            "documentList" => $document,
        ]);
    }

    public function updateDocument(Request $request) {
        $validation = $request->validate([
            "file_id" => "required",
            "file_name" => "required",
            "file_type" => "required",
        ]);

        $documentInfo = Documents::find($validation["file_id"]);

        $documentInfo->update([
            "file_name" => $request->file_name,
            "file_type" => $request->file_type,
        ]);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function deleteDocument(Request $request) {
        $document = Documents::find($request->id);

        $document->delete();

        return response()->json([
            "status" => "success",
        ]);
    }

    public function acceptDocument(Request $request) {
        $document = Documents::find($request->id);

        $document->update([
            "status" => "Accepted",
        ]);

        return response()->json([
            "status" => "success",
        ]);
    }

    public function rejectDocument(Request $request) {
        $document = Documents::find($request->id);

        $document->update([
            "status" => "Rejected",
        ]);

        return response()->json([
            "status" => "success",
        ]);
    }

    public function fetchPendingCount() {
        $pendingCount = Documents::where("status", "pending")->count();

        return response()->json([
            "pending_count" => $pendingCount
        ]);
    }
}
