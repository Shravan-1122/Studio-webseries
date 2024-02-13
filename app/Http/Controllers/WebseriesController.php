<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Theme;
use App\Models\Artist;
use App\Models\WebArtist;

class WebseriesController extends Controller
{
    public function index()
    {
        $userModel = Series::with(['theme', 'artists'])->where('active', 1)->get();
        $data = [
            'posts' => $userModel->all(),
        ];
        return view('Web/weblist', $data);
    }
    public function add()
    {
        $themes = Theme::pluck('title', 'id');
        $artists = Artist::pluck('name', 'id');
        return view('Web/addweb', compact('themes', 'artists'));
    }
    public function addweb(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'theme_id' => 'required|exists:themes,id',
            'artist_ids' => 'required|array',
            'artist_ids.*' => 'exists:artists,id',
        ]);
        $webSeries = new Series();
        $webSeries->id = 'web' . str_pad(Series::count() + 1, 4, '0', STR_PAD_LEFT);
        $webSeries->title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $webSeries->theme_id = $validatedData['theme_id'];
        $webSeries->status = 'active';
        $webSeries->active = 1;
        $id = $request->session()->get('id');        
        $webSeries->created_by = $id;
        $webSeries->updated_by = $id;
        $webSeries->save();
        foreach ($validatedData['artist_ids'] as $artistId) {
            $webArtist = new WebArtist();
            $webArtist->web_id = $webSeries->id;
            $webArtist->artist_id = $artistId;
            $webArtist->save();
        }
        return redirect("weblist")->with('success', 'Web series added successfully');
    }
    public function edit($id)
    { 
        $web = Series::where('id', $id)->where('active', 1)->first();
        if($web){
        $webSeries = Series::findOrFail($id);
        $themes = Theme::pluck('title', 'id') ?? [];
        $artists = Artist::pluck('name', 'id') ?? [];
        $selectedArtistIds = WebArtist::where('web_id', '=', $id)->pluck('artist_id')->toArray();
        return view('web/editweb', compact('webSeries', 'themes', 'artists', 'selectedArtistIds'));
    }
    else {
    
        return redirect()->route('web.list')->with('error', 'Web series not found.');
    }
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'theme_id' => 'required|exists:themes,id',
            'artist_ids' => 'required|array',
            'artist_ids.*' => 'exists:artists,id',
        ]);
        $webSeries = Series::findOrFail($id);
        $webSeries->title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $webSeries->theme_id = $validatedData['theme_id'];
        $id = $request->session()->get('id');
        $webSeries->updated_by = $id;
        $webSeries->save();
        $webSeries->artists()->sync($validatedData['artist_ids']);
        return redirect("weblist")->with('success', 'Web series updated successfully');
    }

    public function delete($id)
    {
        $webSeries = Series::findOrFail($id);
        if ($webSeries) {
            $webSeries->active = 0;
            $webSeries->save();
            return redirect()->route('web.list')->with('success', 'web series deleted successfully.');
        } else {
            return redirect()->route('web.list')->with('error', 'Failed to delete web series.');
        }
    }
    public function updatestatus(Request $request, $id)
    {
        $post = Series::findOrFail($id);
        $post->status = $request->status;
        $post->save();
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
    public function getStatus($id)
    {
        $post = Series::findOrFail($id);
        return response()->json(['status' => $post->status]);
    }
    public function view(Request $request, $id)
    {
        $web = Series::where('id', $id)->where('active', 1)->first();
        if($web){
        $request->session()->put('webid', $id);    
        return redirect()->route('season.list')->with('success', 'web series seasons open successfully.');
    }
    else {
    
        return redirect()->route('web.list')->with('error', 'Web series not found.');
    }
    }

}
