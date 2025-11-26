<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
use App\Models\BookLog;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function upload(Request $request) {
        $documentItem = $request->validate([
            "book_name" => "required",
            "book_file" => "required|file",
            "subject" => "required",
        ]);

        $documentFile = $request->file("book_file")->store("books", "public");

        $document = Books::create([
            "book_name" => $documentItem["book_name"],
            "book_file" => $documentFile,
            "subject" => $documentItem["subject"],
        ]); 

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request) {
        $book = Books::find($request->id);

        $book->update([
            "book_name" => $request->book_name,
            "subject" => $request->subject,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Book has been updated',
        ]);
    }

    public function fetchBooks() {
        $bookList = Books::get();

        return response()->json([
            "books" => $bookList
        ]);
    }

    public function fetchBookDetails($book_id) {
        $book = Books::where("id", $book_id)->first();

        return response()->json([
            "bookDetails" => $book
        ]);
    }

    public function deleteBook(Request $request) {
        $book = Books::find($request->book_id);

        $book->delete();

        return response()->json([
            "status" => "success",
        ]);
    }

    public function book_record_open(Request $request) {
        $loginRecord = BookLog::create([
            "username" => $request->username,
            "book_name" => $request->book_name,
            "subject" => $request->subject,
            "opened_at" => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User create successfully',
        ]);
    }

    public function fetchBookLogRecords() {
        $bookLogs = BookLog::get();

        return response()->json([
            "BookLogs" => $bookLogs
        ]);
    }
}
