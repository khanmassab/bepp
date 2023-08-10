<?php

namespace App\Http\Controllers\admin\links;

use App\Models\News;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::orderBy('id', 'DESC')->paginate(10);
        return view('admin.useful-links.news.index', get_defined_vars());
    }

    public function getNews()
    {
        $news = News::all();
        return response()->json(['code' => 200, 'message' => 'News Fectched Successfully', 'data' => $news]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.useful-links.news.create');
    }

public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'news_details' => 'required',
        'image' => 'required',
        'image_title' => 'required',
    ]);

    $news = News::create($request->all());

    if ($request->hasFile('image')) {
        $media_file = $request->file('image');
        $filename = time() . '_' . $media_file->getClientOriginalName();
        $path = basename($media_file->storeAs('public/news', $filename));
        $news->image = $path;
        $news->save();
    }

    return redirect()->route('news.index')->with('message', 'Successfully created');
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($news)
    {
        $id = $news;
        $news = News::where('id',$id)->first();
        return view('admin.useful-links.news.edit',get_defined_vars());
    }

public function update(Request $request, $news)
{
    $id = decrypt($request->id);

    $news = News::findOrFail($id);
    $news->update($request->all());

    if ($request->hasFile('image')) {
        $this->removeImage('public/news/' . $news->image);
        $media_file = $request->file('image');
        $filename = time() . '_' . $media_file->getClientOriginalName();
        $path = basename($media_file->storeAs('public/news', $filename));
        $news->image = $path;
        $news->save();
    }

    return redirect()->route('news.index')->with('message', 'Successfully updated');
}

public function destroy($id)
{
    $news = News::find($id);

    if ($news) {
        if ($news->image) {
            $this->removeImage('public/news/' . $news->image);
        }

        $news->delete();
        return back()->with('message', 'Successfully deleted');
    }

    return back()->with('error', 'Data not found');
}
}
