<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\AddFavoriteRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        $favorites = $user->favoriteBooks()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')    
            ->paginate($request->input('per_page', 15));

        return BookResource::collection($favorites);
    }

    public function store(AddFavoriteRequest $request)
    {
        $dto = $request->toDTO();
        $user = Auth::user();

        if ($user->favoriteBooks()->where('book_id', $dto->bookId)->exists()) {
            return response()->json([
                'message' => 'Эта книга уже в избранном.'
            ], 409);
        }

        $user->favoriteBooks()->attach($dto->bookId);

        return response()->json([
            'message' => 'Книга добавлена в избранное.',
            'book_id' => $dto->bookId
        ], 201);
    }

    public function destroy($bookId)
    {
        $user = Auth::user();

        if (!$user->favoriteBooks()->where('book_id', $bookId)->exists()) {
            return response()->json([
                'message' => 'Книга не найдена в избранном.'
            ], 404);
        }

        $user->favoriteBooks()->detach($bookId);

        return response()->json([
            'message' => 'Книга удалена из избранного.'
        ]);
    }


    public function clear()
    {
        $user = Auth::user();

        $user->favoriteBooks()->detach();

        return response()->json([
            'message' => 'Избранное очищено.'
        ]);
    }
}