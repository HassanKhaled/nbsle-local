<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\universitys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    //

    public function index(Request $request)
    {
        $universities = universitys::all();
        $query = News::with('university');

        if ($request->filled('university_id')) {
            $query->where('university_id', $request->university_id);
        }

        $news = $query->get();

        return view('news.index', compact('news', 'universities'));
    }


        public function create()
    {
        $universities = universitys::all();
        return view('news.create', compact('universities'));
    }

    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'title'         => 'required|string|max:255',
            'desc'          => 'required|string',
            'img'           => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'university_id' => 'required|exists:universitys,id',
        ]);

        $path = $request->file('img')->store('news_images', 'public');

        News::create([
            'title'         => $request->title,
            'desc'          => $request->desc,
            'img_path'      => $path,
            'university_id' => $request->university_id,
        ]);

        return redirect()->route('news.index')->with('success', 'News created successfully.');
    }

    public function edit(News $news)
    {
        $universities = universitys::all();
        return view('news.edit', compact('news', 'universities'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'desc'          => 'required|string',
            'img'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'university_id' => 'required|exists:universitys,id',
        ]);

        $data = $request->only(['title', 'desc', 'university_id']);

        if ($request->hasFile('img')) {
            $data['img_path'] = $request->file('img')->store('news_images', 'public');
        }

        $news->update($data);

        return redirect()->route('news.index')->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        // delete image file from storage if exists
        if ($news->img_path && Storage::disk('public')->exists($news->img_path)) {
            Storage::disk('public')->delete($news->img_path);
        }

        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'News and its image deleted successfully.');
    }

}
