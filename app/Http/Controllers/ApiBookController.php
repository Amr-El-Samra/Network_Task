<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiBookController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'details' => 'required|string',
            'numberOfPages' => 'required|integer',
            'price' => 'required|numeric',
            'cover' => 'required|image|mimes:jpg,jpeg,png',
            'cat_id' => 'required|exists:cats,id',
            'author_id' => 'required|exists:authors,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
        $imgPath = Storage::putFile("uploads", $request->cover);
        $book = Book::create([
            'name' => $request->name,
            'details' => $request->details,
            'numberOfPages' => $request->numberOfPages,
            'price' => $request->price,
            'cover' => $imgPath,
            'cat_id' => $request->cat_id,
            'author_id' => $request->author_id,
        ]);
        return response()->json([
            'msg' => 'Added Successfully',
            'book' => new BookResource($book)
        ]);
    }

    public function index()
    {
        $books = Book::all();
        return BookResource::collection($books);
    }
}
