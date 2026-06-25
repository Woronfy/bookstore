<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Http\Resources\AuthorResource;
use App\Repositories\Api\v1\AuthorRepository;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $authors = Author::withCount('books')->with(['books'=>function ($query){
            $query->latest('year')->limit(1);
        }])->paginate(15);

        return AuthorResource::collection($authors);
    }
}
