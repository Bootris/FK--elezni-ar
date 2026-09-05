<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FootballMatch;
use App\Models\Post;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->whereHas('posts', fn ($query) => $query->published())
            ->withCount(['posts' => fn ($query) => $query->published()])
            ->orderBy('name')
            ->get();

        $activeCategory = $request->query('category');

        $posts = Post::published()
            ->with('category')
            ->when($activeCategory, fn ($query) => $query->whereHas(
                'category', fn ($q) => $q->where('slug', $activeCategory),
            ))
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('news.index', compact('posts', 'categories', 'activeCategory'));
    }

    public function show(string $slug)
    {
        $post = Post::published()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $match = FootballMatch::where('post_id', $post->id)->first();

        $related = Post::published()
            ->with('category')
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->orderByRaw('case when category_id = ? then 0 else 1 end', [$post->category_id]))
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('news.show', compact('post', 'related', 'match'));
    }
}
