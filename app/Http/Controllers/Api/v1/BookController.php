<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Repositories\Api\v1\BookRepository;
use App\QueryModifiers\SortByPrice;
use App\QueryModifiers\SortByDate;
use App\QueryModifiers\FilterByAuthor;
use App\QueryModifiers\FilterByGenre;
use App\QueryModifiers\FilterByYearRange;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected BookRepository $repository;

    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $query = $this->repository->getQuery();

        $modifiers = $this->buildModifiers($request);

       foreach ($modifiers as $modifier) {
            $modifier->apply($query);
        }

        $books = $query->paginate($request->input('per_page', 15));

        return BookResource::collection($books);
    }

    public function show($id)
    {
        $book = Book::with(['authors', 'genres'])->findOrFail($id);
        return new BookResource($book);
    }

    protected function buildModifiers(Request $request): array
    {
        $modifiers = [];

        if ($request->has('sort_by') && $request->sort_by === 'price') {
            $direction = $request->input('sort_direction', 'asc');
            $modifiers[] = new SortByPrice($direction);
        }

        if ($request->has('sort_by') && $request->sort_by === 'created_at') {
            $direction = $request->input('sort_direction', 'asc');
            $modifiers[] = new SortByDate($direction);
        }

        if ($request->has('author_id') && $request->author_id) {
            $modifiers[] = new FilterByAuthor((int) $request->author_id);
        }

        if ($request->has('genre_id') && $request->genre_id) {
            $modifiers[] = new FilterByGenre((int) $request->genre_id);
        }

        $yearFrom = $request->input('year_from');
        $yearTo = $request->input('year_to');
        if ($yearFrom || $yearTo) {
            $modifiers[] = new FilterByYearRange(
                $yearFrom ? (int) $yearFrom : null,
                $yearTo ? (int) $yearTo : null
            );
        }

        return $modifiers;
    }
}