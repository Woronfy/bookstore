<?php

namespace App\Repositories\Api\v1;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\ReviewVote;

class ReviewRepository
{
    public function getQueryForBook(int $bookId): Builder
    {
        return Review::where('book_id', $bookId);
    }

    public function paginate(Builder $query, int $perPage = 15): LengthAwarePaginator
    {
        return $query->with('author')->paginate($perPage);
    }

    public function applySort(Builder $query, string $sortBy, string $sortDirection): void
    {
        $direction = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'desc';
        $query->orderBy($sortBy, $direction);
    }

    public function getStats(int $bookId): array
    {
        $stats = Review::where('book_id', $bookId)
            ->selectRaw('COUNT(*) as total, AVG(rating) as average, COUNT(DISTINCT author_id) as unique_authors')
            ->first();

        $distribution = Review::where('book_id', $bookId)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        return [
            'total' => (int) ($stats->total ?? 0),
            'average' => $stats->average ? round($stats->average, 1) : null,
            'unique_authors' => (int) ($stats->unique_authors ?? 0),
            'distribution' => $distribution,
        ];
    }

    

    public function create(array $data): Review
    {
        return Review::create($data);
    }

    public function toggleVote(int $reviewId, int $authorId, int $vote): ?ReviewVote
    {
        $existing = ReviewVote::where('review_id', $reviewId)
            ->where('author_id', $authorId)
            ->first();

        if ($existing) {
            if ($existing->vote === $vote) {
                $existing->delete();
                return null;
            } else {
                $existing->update(['vote' => $vote]);
                return $existing;
            }
        }

        return ReviewVote::create([
            'review_id' => $reviewId,
            'author_id' => $authorId,
            'vote' => $vote,
        ]);
    }

    public function getVoteStats(int $reviewId): array
    {
        $review = Review::withCount([
                'votes as likes_count' => fn($q) => $q->where('vote', 1),
                'votes as dislikes_count' => fn($q) => $q->where('vote', -1),
            ])
            ->withSum('votes', 'vote')
            ->find($reviewId);

        $userVote = null;
        if (auth()->check()) {
            $vote = ReviewVote::where('review_id', $reviewId)
                ->where('author_id', auth()->id())
                ->first();
            $userVote = $vote ? $vote->vote : null;
        }

        return [
            'rating' => $review->votes_sum_vote ?? 0,
            'likes' => $review->likes_count ?? 0,
            'dislikes' => $review->dislikes_count ?? 0,
            'user_vote' => $userVote,
        ];
    }
}