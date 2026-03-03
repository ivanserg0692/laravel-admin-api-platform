<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\UI\Forms\NewsFormConfig;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(protected NewsFormConfig $newsFormConfig)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->session()->put('news.index.query', $request->query());

        return view('news.index', [
            'newsCreateFields' => $this->newsFormConfig->fields(),
            'newsUpdateFields' => $this->newsFormConfig->fields(),
            'newsCreateValues' => $this->newsFormConfig->createValues(),
            'newsUpdateValues' => $this->newsFormConfig->modalUpdateValues(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', News::class);

        return view('news.create', [
            'newsFields' => $this->newsFormConfig->fields(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        $canUpdateNews = auth()->user()?->can('update', $news) ?? false;
        $canDeleteNews = auth()->user()?->can('delete', $news) ?? false;

        return view('news.show', [
            'news' => $news,
            'backUrl' => route('news.index', session('news.index.query', [])),
            'canUpdateNews' => $canUpdateNews,
            'canDeleteNews' => $canDeleteNews,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        $this->authorize('update', $news);

        return view('news.update', [
            'news' => $news,
            'backUrl' => route('news.index', session('news.index.query', [])),
        ]);
    }
}
