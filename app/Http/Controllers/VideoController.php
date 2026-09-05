<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $activeCategory = $request->query('category');

        $categories = Category::query()
            ->whereHas('posts', fn ($query) => $query->published()->whereNotNull('video_url')->where('video_url', '!=', ''))
            ->withCount(['posts' => fn ($query) => $query->published()->whereNotNull('video_url')->where('video_url', '!=', '')])
            ->orderBy('name')
            ->get();

        $posts = Post::published()
            ->with('category')
            ->whereNotNull('video_url')
            ->where('video_url', '!=', '')
            ->when($activeCategory, fn ($query) => $query->whereHas(
                'category', fn ($q) => $q->where('slug', $activeCategory),
            ))
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('video.index', compact('posts', 'categories', 'activeCategory'));
    }
}
