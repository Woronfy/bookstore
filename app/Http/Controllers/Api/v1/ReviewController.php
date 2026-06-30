<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\ReviewsRequest;
use App\Http\Requests\Api\v1\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Book;
use App\Repositories\Api\v1\ReviewRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\v1\VoteReviewRequest;
use App\DTO\Review\VoteReviewDTO;


class ReviewController extends Controller
{
    protected ReviewRepository $repository;

    public function __construct(ReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Book $book, ReviewsRequest $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $dto = $request->toDTO();
        $query = $this->repository->getQueryForBook($book->id);
        $query->withCount([
            'votes as likes_count' => fn($q) => $q->where('vote', 1),
            'votes as dislikes_count' => fn($q) => $q->where('vote', -1),
        ])
        ->withSum('votes', 'vote');
        
        if (auth()->check()) {
            $query->with(['userVote']); 
        }

        $this->repository->applySort($query, $dto->sortBy, $dto->sortDirection);
        $reviews = $this->repository->paginate($query, $dto->perPage);

        return ReviewResource::collection($reviews);
    }

    public function stats(Book $book): JsonResponse
    {
        $stats = $this->repository->getStats($book->id);
        return response()->json($stats);
    }

    public function store(Book $book, StoreReviewRequest $request): JsonResponse
    {
        $dto = $request->toDTO();

        $data = [
            'book_id' => $book->id,
            'author_id' => Auth::id(),
            'rating' => $dto->rating,
            'comment' => $dto->comment,
            'pros' => $dto->pros,
            'cons' => $dto->cons,
            'is_anonymous' => $dto->isAnonymous,
        ];

        $review = $this->repository->create($data);

        return response()->json([
            'message' => trans('reviews.created'),
            'review' => new ReviewResource($review),
        ], 201);
    }

    public function vote(Review $review, VoteReviewRequest $request): JsonResponse
    {
        $dto = $request->toDTO();
        $authorId = Auth::id();

        $result = $this->repository->toggleVote($review->id, $authorId, $dto->vote);

        
        $stats = $this->repository->getVoteStats($review->id);

        return response()->json([
            'message' => $result ? trans('reviews.vote_updated') : trans('reviews.vote_removed'),
            'rating' => $stats['rating'],
            'likes' => $stats['likes'],
            'dislikes' => $stats['dislikes'],
            'user_vote' => $stats['user_vote'],
        ]);
    }
}