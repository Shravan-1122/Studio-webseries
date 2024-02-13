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
use App\Models\EpisodeArtist;

use Illuminate\Support\Facades\DB;

class EpisodeController extends Controller
{
    public function index(Request $request)
    {
        $webid = $request->session()->get('webid');
        $seasonid = $request->session()->get('seasonid');
        $series = Series::findOrFail($webid);
        $themeName = $series->theme->title;


        $webArtists = WebArtist::where('web_id', $webid)->get();
        $seasonArtists = SeasonArtist::where('season_id', $seasonid)->get();

        $webarts=Series::with(['artists'])->where('id', $webid)->get();
        $seasonarts=Season::with(['artists'])->where('id', $seasonid)->get();
        $posts = Episode::where('season_id', $seasonid)->get();
        return view('Episode.episodelist', compact('posts', 'themeName', 'webArtists', 'seasonArtists','seasonarts','webarts'));
    }
    public function add()
    {
        $season_id = session()->get('season_id');
        $web_id = session()->get('webid');
        $webSeries = Series::where('id', $web_id)->where('active', 1)->first();
        $season = Season::where('id', $season_id)->first();
        if($webSeries&&$season){
        $selectedArtistIds1 = SeasonArtist::where('season_id', '=', $season_id)->pluck('artist_id')->toArray();
       
        $selectedArtistIds2 = WebArtist::where('web_id', '=', $web_id)->pluck('artist_id')->toArray();
        $selectedArtistIds = array_merge($selectedArtistIds1, $selectedArtistIds2);
        $artists = Artist::whereNotIn('id', $selectedArtistIds)->pluck('name', 'id');
    
        return view('Episode/addepisode', compact('artists'));
        }
        else {
        
            return redirect()->route('web.list')->with('error', 'Web series or season not found.');
        }
    }

    public function addepisode(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'artist_ids' => 'required|array',
            'artist_ids.*' => 'exists:artists,id',
        ]);
      
        $webSeries = new Episode();
       
        $webSeries->episode_title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $seasonid = session()->get('seasonid');
       
        $webSeries->season_id = $seasonid;
        $id = $request->session()->get('id');
        $webSeries->updated_by = $id;
        $webSeries->created_by = $id;
       
        $webSeries->save();
        foreach ($validatedData['artist_ids'] as $artistId) {
            $seasonArtist = new EpisodeArtist();
            $seasonArtist->episode_id = $webSeries->id;
            $seasonArtist->artist_id = $artistId;
            $seasonArtist->save();
        }
        return redirect("episodelist")->with('success', 'Web series added successfully');
    }
    public function edit($id)
    {
        $season_id = session()->get('season_id');
        $web_id = session()->get('webid');
        $webSeries = Series::where('id', $web_id)->where('active', 1)->first();
        $season = Season::where('id', $season_id)->first();
        $episode = Episode::where('id', $id)->first();
        if($webSeries&&$season&&$episode){
        $episode = Episode::findOrFail($id);
        $artists = Artist::pluck('name', 'id') ?? [];
        $selectedArtistIds = EpisodeArtist::where('episode_id', '=', $id)->pluck('artist_id')->toArray();
        return view('Episode/editepisode', compact('episode', 'artists', 'selectedArtistIds'));
        }
        else {
        
            return redirect()->route('web.list')->with('error', 'Web series or season or episode not found.');
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
        $webSeries = Episode::findOrFail($id);
        $webSeries->episode_title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $id = $request->session()->get('id');
        $webSeries->updated_by = $id;
        $webSeries->save();
        $webSeries->artists()->sync($validatedData['artist_ids']);
        return redirect("episodelist")->with('success', 'Web series updated successfully');
    }

    public function delete($id)
    {
        $season_id = session()->get('season_id');
        $web_id = session()->get('webid');
        $webSeries = Series::where('id', $web_id)->where('active', 1)->first();
        $season = Season::where('id', $season_id)->first();
        if($webSeries&&$season){
        $episode = Episode::findOrFail($id);
        $episode->artists()->detach();
        $episode->delete();
        return redirect()->route('episode.list')->with('success', 'Web series deleted successfully.');
        }
        else {
        
            return redirect()->route('web.list')->with('error', 'Web series or season not found.');
        }
    }
    public function view(Request $request, $id)
    {
        $seasonid = session()->get('season_id');
        $webid = session()->get('webid');
        $webSeries = Series::where('id', $webid)->where('active', 1)->first();
        $season = Season::where('id', $seasonid)->first();
        $episode = Episode::where('id', $id)->first();
        if($webSeries&&$season&&$episode){
        $webSeries = Series::findOrFail($webid);
        $season = Season::findOrFail($seasonid);
        $episode = Episode::findOrFail($id);
        return view('Episode/episodeview', compact('webSeries', 'season', 'episode'));
        }
        else {
        
            return redirect()->route('web.list')->with('error', 'Web series or season or Episode not found, try again.');
        }
        
    }
}
