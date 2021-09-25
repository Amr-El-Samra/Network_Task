<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthDetailsResource;
use App\Http\Resources\BookResource;
use App\Http\Resources\DetailsResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiBookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return BookResource::collection($books);
    }

    public function catdetails()
    {
        $cats = Cat::all();
        return DetailsResource::collection($cats);
    }

    public function authdetails(){
        $auth = Author::all();
        return AuthDetailsResource::collection($auth);
    }

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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'details' => 'required|string',
            'numberOfPages' => 'required|integer',
            'price' => 'required|numeric',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png',
            'cat_id' => 'required|exists:cats,id',
            'author_id' => 'required|exists:authors,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $book = Book::find($id);
        if ($book == null) {
            return response()->json([
                'msg' => '404 Not Found'
            ]);
        }
        $imgPath = $book->cover;
        if ($request->hasFile('cover')) {
            Storage::delete($imgPath);
            $imgPath = Storage::putFile("uploads", $request->cover);
        }
        $book->update([
            'name' => $request->name,
            'details' => $request->details,
            'numberOfPages' => $request->numberOfPages,
            'price' => $request->price,
            'cover' => $imgPath,
            'cat_id' => $request->cat_id,
            'author_id' => $request->author_id,
        ]);
        return response()->json([
            'msg' => 'Updated Successfully',
            'book' => new BookResource($book)
        ]);
    }

    public function show($id)
    {
        $book = Book::find($id);
        if ($book == null) {
            return response()->json([
                'msg' => '404 Not Found'
            ]);
        }
        return new BookResource($book);
    }

    public function delete($id)
    {
        $book = Book::find($id);
        if ($book == null) {
            return response()->json([
                'msg' => '404 Not Found'
            ]);
        }
        $book->delete();
        return response()->json([
            'msg' => 'Deleted Successfully',
        ]);
    }

    public function archive()
    {
        $book = Book::onlyTrashed()->get();
        return BookResource::collection($book);
    }

    public function restore($id)
    {
        $book = Book::where('id', $id)->where('deleted_at', '<>', 'NULL')->withTrashed()->first();
        if ($book == null) {
            return response()->json([
                'msg' => '404 Not Found'
            ]);
        }
        $book->restore();
        return response()->json([
            'msg' => 'Restored Successfully',
        ]);
    }
}
