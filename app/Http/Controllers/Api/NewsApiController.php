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
        $perpage = $request->integer('per_page', 20);
        $news = News::where('is_published', true)
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->latest('published_at')
            ->paginate($perpage);

        return NewsResource::collection($news);
    }

    public function show(string $slug)
    {
        $news = News::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return new NewsResource($news);
    }
}
