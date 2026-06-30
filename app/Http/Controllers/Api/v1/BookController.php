<?php

namespace App\Http\Controllers\Api\v1;

use App\DTO\BookFilterDTO;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Http\Requests\Api\v1\BookFilterRequest;
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

    public function index(BookFilterRequest $request)
    {
        $dto = $request->toDTO();

        $query = $this->repository->getQuery()->with('media');
        $query->withCount('reviews')->withAvg('reviews', 'rating');
        $modifiers = $this->buildModifiers($dto);

        foreach ($modifiers as $modifier) {
            $modifier->apply($query);
        }

        $books = $query->paginate($dto->perPage);

        return BookResource::collection($books);
    }

    public function show($id)
    {
        $book = Book::with(['authors', 'genres', 'media'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->findOrFail($id);
        return new BookResource($book);
    }

    protected function buildModifiers(BookFilterDTO $dto): array
    {
        $modifiers = [];

        if ($dto->sortBy === 'price') {
            $modifiers[] = new SortByPrice($dto->sortDirection);
        } elseif ($dto->sortBy === 'created_at') {
            $modifiers[] = new SortByDate($dto->sortDirection);
        }

        if ($dto->authorId) {
            $modifiers[] = new FilterByAuthor($dto->authorId);
        }

        if ($dto->genreId) {
            $modifiers[] = new FilterByGenre($dto->genreId);
        }

        if ($dto->yearFrom || $dto->yearTo) {
            $modifiers[] = new FilterByYearRange($dto->yearFrom, $dto->yearTo);
        }

        return $modifiers;
    }
}