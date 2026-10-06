<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Resources\Api\V1\BookResource;
use App\Models\Book;
use Illuminate\Http\Response;

class BookController extends Controller
{
    /**
     * 書籍一覧取得
     */
    public function index()
    {
        $books = Book::with(['user', 'genres'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(10);

        return BookResource::collection($books);
    }

    /**
     * 書籍新規登録
     */
    public function store(BookRequest $request)
    {
        $book = Book::create($request->validated());
        $book->genres()->sync($request->input('genres', []));

        return (new BookResource($book->load(['user', 'genres'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * 書籍詳細取得
     */
    public function show(Book $book)
    {
        $book->load(['user', 'genres', 'reviews']);
        return new BookResource($book);
    }

    /**
     * 書籍更新
     */
    public function update(BookRequest $request, Book $book)
    {
        $book->update($request->validated());
        $book->genres()->sync($request->input('genres', []));

        return new BookResource($book->load(['user', 'genres']));
    }

    /**
     * 書籍削除
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
