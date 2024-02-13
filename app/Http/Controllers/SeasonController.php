<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Theme;
use App\Models\Artist;
use App\Models\WebArtist;
use App\Models\SeasonArtist;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Support\Facades\DB;

class SeasonController extends Controller
{
    public function index(Request $request)
    {
        $webid = $request->session()->get('webid');
        $series = Series::where('id', $webid)->first();
        $themeName = '';
        if ($series) {
            $themeName = $series->theme->title;
        }
        
        $webartists=Series::with(['artists'])->where('id', $webid)->get();
        $posts = Season::with(['artists'])->where('web_id', $webid)->get();
        return view('Season.seasonlist', compact('posts','webartists', 'themeName'));
    }
    public function add()
    {
        $web_id = session()->get('webid');
        $webSeries = Series::where('id', $web_id)->where('active', 1)->first();
        if($webSeries){
     
    $selectedArtistIds = WebArtist::where('web_id', '=', $web_id)->pluck('artist_id')->toArray();

    // Retrieve all artists except the ones that are already selected
    $artists = Artist::whereNotIn('id', $selectedArtistIds)->pluck('name', 'id');

    return view('Season/addseason', compact('artists'));}
    else {
        
        return redirect()->route('web.list')->with('error', 'Web series not found.');
    }
    }
    public function addseason(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'artist_ids' => 'required|array',
            'artist_ids.*' => 'exists:artists,id',
        ]);       
        $webSeries = new Season();
        $webSeries->season_title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $webid = session()->get('webid');
        $webSeries->web_id = $webid;
        $id = $request->session()->get('id');
        $webSeries->created_by = $id;
        $webSeries->updated_by = $id;       
        $webSeries->save();
        foreach ($validatedData['artist_ids'] as $artistId) {
            $seasonArtist = new SeasonArtist();
            $seasonArtist->season_id = $webSeries->id;
            $seasonArtist->artist_id = $artistId;
            $seasonArtist->save();
               }
        return redirect("seasonlist")->with('success', 'Web series added successfully');
    }
    public function edit($id)
    {
        $web_id = session()->get('webid');
        $web = Series::where('id', $web_id)->where('active', 1)->first();
        $season = Season::where('id', $id)->first();
        if($web&& $season) {
        $Season = Season::findOrFail($id);
        $artists = Artist::pluck('name', 'id') ?? [];
        $selectedArtistIds = SeasonArtist::where('season_id', '=', $id)->pluck('artist_id')->toArray();
        return view('Season/editseason', compact('Season', 'artists', 'selectedArtistIds'));
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
            'artist_ids' => 'required|array',
            'artist_ids.*' => 'exists:artists,id',
        ]);
        $webSeries = Season::findOrFail($id);
        $webSeries->season_title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $id = $request->session()->get('id');
        $webSeries->updated_by = $id;
        $webSeries->save();
        $webSeries->artists()->sync($validatedData['artist_ids']);
        return redirect("seasonlist")->with('success', 'Web series updated successfully');
    }
    public function delete($id)
    {
        $web_id = session()->get('webid');
        $web = Series::where('id', $web_id)->where('active', 1)->first();
        if($web){
        $season = Season::findOrFail($id);
        $season->artists()->detach();
        $season->episodes()->delete();
        $season->delete();
        return redirect()->route('season.list')->with('success', 'Web series deleted successfully.');
    }
    else {
    
        return redirect()->route('web.list')->with('error', 'Web series not found.');
    }
    }

    public function view(Request $request, $id)
    {
        $web_id = session()->get('webid');
        $web = Series::where('id', $web_id)->where('active', 1)->first();
        $season = Season::where('id', $id)->first();
        if($web&& $season){
        $request->session()->put('seasonid', $id);
        session()->put('season_id', $id);
        return redirect()->route('episode.list')->with('success', 'episodes open successfully.');
    }
    else {
    
        return redirect()->route('web.list')->with('error', 'Web series not found.');
    }
    }
}
