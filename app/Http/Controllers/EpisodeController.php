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

class EpisodeController extends Controller
{
    public function index(Request $request)
    {
        $seasonid = $request->id;
        $season = Season::find($seasonid);
        $webid = $season->web_id;
        $series = Series::findOrFail($webid);
        $themeName = $series->theme->title;
        $webArtists = WebArtist::where('web_id', $webid)->get();
        $seasonArtists = SeasonArtist::where('season_id', $seasonid)->get();
        $webarts = Series::with(['artists'])->where('id', $webid)->get();
        $seasonarts = Season::with(['artists'])->where('id', $seasonid)->get();
        $posts = Episode::where('season_id', $seasonid)->get();
        return view('Episode.episodelist', compact('posts', 'themeName', 'webArtists', 'seasonArtists', 'seasonarts', 'webarts', 'seasonid'));
    }
    public function add(Request $request)
    {
        $season_id = $request->query('season_id');
        $season = Season::where('id', $season_id)->first();
        $web_id = $season->web_id;
        $webSeries = Series::where('id', $web_id)->where('active', 1)->first();
        if ($webSeries && $season) {
            $selectedArtistIds1 = SeasonArtist::where('season_id', '=', $season_id)->pluck('artist_id')->toArray();
            $selectedArtistIds2 = WebArtist::where('web_id', '=', $web_id)->pluck('artist_id')->toArray();
            $selectedArtistIds = array_merge($selectedArtistIds1, $selectedArtistIds2);
            $artists = Artist::whereNotIn('id', $selectedArtistIds)->pluck('name', 'id');
            return view('Episode/addepisode', compact('artists', 'season_id'));
        } else {
            return redirect()->route('web.list')->with('error', 'Web series or season not found.');
        }
    }

    public function addepisode(Request $request)
    {
        $seasonid = $request->input('season_id');
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'artist_ids' => 'required|array',
            'artist_ids.*' => 'exists:artists,id',
        ]);
        $webSeries = new Episode();
        $webSeries->episode_title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
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
        return redirect()->route('episode.list', ['id' => $seasonid])->with('success', 'Web series added successfully');
    }
    public function edit($id)
    {
        try {
            $episode = Episode::find($id);
            $season_id = $episode->season_id;
            $season = Season::find($season_id);
            $web_id = $season->web_id;
            $webSeries = Series::where('id', $web_id)->where('active', 1)->first();
            if ($webSeries && $season && $episode) {
                $artists = Artist::pluck('name', 'id') ?? [];
                $selectedArtistIds = EpisodeArtist::where('episode_id', '=', $id)->pluck('artist_id')->toArray();
                return view('Episode/editepisode', compact('episode', 'artists', 'selectedArtistIds'));
            }
        } catch (\Exception $e) {
            return redirect()->route('web.list')->with('error', 'Web series or season or episode not found');
        }
    }
    public function update(Request $request, $id)
    {

        $webSeries = Episode::findOrFail($id);
        $season_id = $webSeries->season_id;
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'artist_ids' => 'required|array',
            'artist_ids.*' => 'exists:artists,id',
        ]);
        $webSeries->episode_title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $id = $request->session()->get('id');
        $webSeries->updated_by = $id;
        $webSeries->save();
        $webSeries->artists()->sync($validatedData['artist_ids']);
        return redirect()->route('episode.list', ['id' => $season_id])->with('success', 'Web series added successfully');

    }

    public function delete($id)
    {
        try {
            $episode = Episode::findOrFail($id);
            $episode->artists()->detach();
            $episode->delete();
        } catch (\Exception $e) {
            return redirect()->route('web.list')->with('error', 'An error occurred while deleting the episode.');
        }
    }

    public function view(Request $request, $id)
    {
        $episode = Episode::where('id', $id)->first();
        $seasonid = $episode->season_id;
        $season = Season::where('id', $seasonid)->first();
        $webid = $season->web_id;
        $webSeries = Series::where('id', $webid)->where('active', 1)->first();
        if ($webSeries && $season && $episode) {
            return view('Episode/episodeview', compact('webSeries', 'season', 'episode'));
        } else {
            return redirect()->route('web.list')->with('error', 'Web series or season or Episode not found, try again.');
        }
    }
}
