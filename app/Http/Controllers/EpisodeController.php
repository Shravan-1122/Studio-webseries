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
        $posts = Episode::where('season_id', $seasonid)->get();
        return view('Episode.episodelist', compact('posts', 'themeName', 'webArtists', 'seasonArtists'));
    }
    public function add()
    {
        $selectedArtistIds = session()->get('selectedArtistIds');
        $artists = Artist::pluck('name', 'id');
        return view('Episode/addepisode', compact('artists', 'selectedArtistIds'));
    }

    public function addepisode(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'artist_ids' => 'required|array',
            'artist_ids.*' => 'exists:artists,id',
        ]);
        $maxId = DB::table('episodes')->max('id');
        $newId = $maxId + 1;
        $webSeries = new Episode();
        $webSeries->id = $newId;
        $webSeries->episode_title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $seasonid = session()->get('seasonid');
        $name = $request->session()->get('name');
        $webSeries->season_id = $seasonid;
        $webSeries->created_by = $name;
        $webSeries->updated_by = $name;
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
        $episode = Episode::findOrFail($id);
        $artists = Artist::pluck('name', 'id') ?? [];
        $selectedArtistIds = EpisodeArtist::where('episode_id', '=', $id)->pluck('artist_id')->toArray();
        return view('Episode/editepisode', compact('episode', 'artists', 'selectedArtistIds'));
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
        $name = $request->session()->get('name');
        $webSeries->updated_by = $name;
        $webSeries->save();
        $webSeries->artists()->sync($validatedData['artist_ids']);
        return redirect("episodelist")->with('success', 'Web series updated successfully');
    }

    public function delete($id)
    {
        $episode = Episode::findOrFail($id);
        $episode->artists()->detach();
        $episode->delete();
        return redirect()->route('episode.list')->with('success', 'Web series deleted successfully.');
    }
    public function view(Request $request, $id)
    {
        $webid = $request->session()->get('webid');
        $seasonid = $request->session()->get('seasonid');
        $webSeries = Series::findOrFail($webid);
        $season = Season::findOrFail($seasonid);
        $episode = Episode::findOrFail($id);
        return view('Episode/episodeview', compact('webSeries', 'season', 'episode'));
    }
}
