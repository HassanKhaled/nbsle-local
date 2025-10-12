<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Universitys;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // Show all news
    public function index()
    {
        $user = auth()->user();
        if (!$user->has_role('admin')) {

            $news = News::where('university_id', $user->uni_id)->with('university')->latest()->paginate(10);

        } else {
            $news = News::with('university')->latest()->paginate(10);
        }

        return view('news.index', compact('news'));
    }

    // Show form to create news
    public function create()
    {
        $universities = Universitys::all();
        return view('news.create', compact('universities'));
    }

    // Store new news
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'desc'          => 'required|string',
            'img_path'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'news_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'university_id' => 'nullable|exists:universitys,id',
            'publish_date'  => 'nullable|date',
            'time'          => 'nullable|string|max:100',
            'location'      => 'nullable|string|max:255',
            'is_active'     => 'boolean',
        ]);

        if ($request->hasFile('img_path')) {
            $validated['img_path'] = $request->file('img_path')->store('news', 'public');
        }

        $news = News::create($validated);

        // Save multiple images
        if ($request->hasFile('news_images')) {
            foreach ($request->file('news_images') as $image) {
                $path = $image->store('news_images', 'public');
                $news->newsImages()->create(['image_url' => $path]);
            }
        }

        return redirect()->route('news.index')->with('success', 'News created successfully.');
    }

    // Show single news
    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    // Show edit form
    public function edit(News $news)
    {
        $universities = Universitys::all();
        return view('news.edit', compact('news', 'universities'));
    }

    // Update news
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'desc'          => 'required|string',
            'img_path'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'news_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'university_id' => 'nullable|exists:universitys,id',
            'publish_date'  => 'nullable|date',
            'time'          => 'nullable|string|max:100',
            'location'      => 'nullable|string|max:255',
            'is_active'     => 'boolean',
        ]);

        if ($request->hasFile('img_path')) {
            $validated['img_path'] = $request->file('img_path')->store('news', 'public');
        }

        $news->update($validated);

        // Add new images
        if ($request->hasFile('news_images')) {
            foreach ($request->file('news_images') as $image) {
                $path = $image->store('news_images', 'public');
                $news->newsImages()->create(['image_url' => $path]);
            }
        }

        return redirect()->route('news.edit', $news)->with('success', 'News updated successfully.');
    }


    // Delete news
    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('news.index')->with('success', 'News deleted successfully.');
    }

    public function destroyImage($id)
    {
        $image = \App\Models\NewsImage::findOrFail($id);
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }

    public function publicDetails($id)
    {
        $news = News::with('university', 'newsImages')->where('is_active', true)->findOrFail($id);
        $news->increment('views');
        return view('templ.news', compact('news'));
    }

    public function addLike($id)
    {
        $news = News::findOrFail($id);
        $news->increment('likes'); // يزيد العدد +1
        return response()->json(['likes' => $news->likes]);
    }

}
