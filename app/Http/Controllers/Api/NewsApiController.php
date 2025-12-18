<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class NewsApiController extends Controller
{
    public function index(Request $request)
    {
        $news = News::query()
            ->where('is_published', true)
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->latest('published_at')
            ->paginate(20);

        return NewsResource::collection($news);
    }

    public function show(string $slug)
    {
        $news = News::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return new NewsResource($news);
    }
}
