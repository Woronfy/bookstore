<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GenreResource;
use App\Repositories\Api\v1\GenreRepository;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    protected GenreRepository $repository;

    public function __construct(GenreRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $genres = $this->repository->getPaginated($request->input('per_page', 15));
        return GenreResource::collection($genres);
    }
}