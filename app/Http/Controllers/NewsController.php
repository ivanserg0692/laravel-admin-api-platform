<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
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

        $news = News::query()
            ->latest('published_at')
            ->latest('id')
            ->paginate(10);

        return view('news.index', [
            'news' => $news,
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
        return view('news.create', [
            'newsFields' => $this->newsFormConfig->fields(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return view('news.show', [
            'news' => $news,
            'backUrl' => route('news.index', session('news.index.query', [])),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        return view('news.update', [
            'news' => $news,
            'newsFields' => $this->newsFormConfig->fields(),
            'newsValues' => $this->newsFormConfig->updateValues($news),
            'backUrl' => route('news.index', session('news.index.query', [])),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
    }
}
