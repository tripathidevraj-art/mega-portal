<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use App\Models\NewsLike;
use Illuminate\Http\Request;
use App\Mail\NewsPublishedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
public function index()
{
    $news = News::latest()->paginate(15); // Show all (drafts + published)
    return view('admin.news.index', compact('news')); // ← admin view
}

public function show($id)
{
    $news = News::where('is_published', true)
        ->where(fn($q) => $q->where('published_at', '<=', now())->orWhereNull('published_at'))
        ->findOrFail($id);

    // Only increment views once per session (optional but recommended)
    if (!session()->has("news_viewed_{$news->id}")) {
        $news->increment('views');
        session(["news_viewed_{$news->id}" => true]);
    }
    if (request()->route()->getPrefix() === 'admin') {
        return view('admin.news.show', compact('news'));
    }
    return view('news.show', compact('news'));
}

public function publicIndex(Request $request)
{
    $view = $request->get('view', 'grid');
    $search = $request->get('search');
    $sort = $request->get('sort', 'latest');

    $query = News::where('is_published', true)
        ->where(fn($q) => $q->where('published_at', '<=', now())->orWhereNull('published_at'));

    // Search by title or excerpt
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('excerpt', 'LIKE', "%{$search}%");
        });
    }

    // Sorting
    switch ($sort) {
        case 'oldest':
            $query->oldest();
            break;
        case 'title_asc':
            $query->orderBy('title', 'asc');
            break;
        case 'title_desc':
            $query->orderBy('title', 'desc');
            break;
        default: // latest
            $query->latest();
    }

    $news = $query->withCount('likes')->paginate(12);

    return view('news.index', compact('news', 'view', 'search', 'sort'));
}

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'admin_id' => auth()->id(),
            'is_published' => $request->boolean('is_published'),
            'published_at' => $validated['published_at'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        // ✅ CREATE ONLY ONCE — store the result in $news
        $news = News::create($data);

        // Now safely use $news for emails, logging, etc.
        if ($news->is_published) {
            $users = User::where('role', 'user')->get();
            foreach ($users as $user) {
                try {
                    if (!empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                        Mail::to($user->email)->send(new NewsPublishedMail($news));
                    }
                } catch (\Exception $e) {
                    \Log::warning('Email failed for: ' . $user->email, ['error' => $e->getMessage()]);
                }
            }
        }

        return redirect()->route('admin.news.index')
            ->with('success', 'News article created successfully!');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'is_published' => $request->boolean('is_published'),
            'published_at' => $validated['published_at'] ?? null,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'News article updated successfully!');
    }

    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'News article deleted successfully!');
    }

    public function toggleLike(Request $request, News $news)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Login required'], 401);
        }

        $userId = auth()->id();
        $exists = NewsLike::where('news_id', $news->id)
                        ->where('user_id', $userId)
                        ->exists();

        if ($exists) {
            NewsLike::where('news_id', $news->id)
                    ->where('user_id', $userId)
                    ->delete();
            $liked = false;
        } else {
            NewsLike::create([
                'news_id' => $news->id,
                'user_id' => $userId,
            ]);
            $liked = true;
        }

        $newCount = NewsLike::where('news_id', $news->id)->count();

        return response()->json([
            'liked' => $liked,
            'count' => $newCount
        ]);
    }
}