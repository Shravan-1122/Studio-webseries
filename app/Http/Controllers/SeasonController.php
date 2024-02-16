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

class SeasonController extends Controller
{
    public function index(Request $request)
    {
        $webid = $request->id;
        $series = Series::where('id', $webid)->where('active', 1)->first();
        $themeName = '';
        if ($series) {
            $themeName = $series->theme->title;
            $webartists = Series::with(['artists'])->where('id', $webid)->get();
            $posts = Season::with(['artists'])->where('web_id', $webid)->get();
            foreach ($posts as $post) {
                foreach ($post->artists as $seasonArtist) {
                    $existingArtist = $webartists->first(function ($item) use ($seasonArtist) {
                        return $item->artists->contains('id', $seasonArtist->id);
                    });
                    if ($existingArtist) {
                        $post->artists()->detach($seasonArtist->id);
                    }
                }
            }

            return view('Season.seasonlist', compact('posts', 'webartists', 'themeName', 'webid'));
        } else {
            return redirect()->route('web.list')->with('error', 'Web series not found.');
        }
    }
    public function add(Request $request)
    {
        $web_id = $request->query('web_id');
        $webSeries = Series::where('id', $web_id)->where('active', 1)->first();
        if ($webSeries) {
            $selectedArtistIds = WebArtist::where('web_id', '=', $web_id)->pluck('artist_id')->toArray();
            $artists = Artist::whereNotIn('id', $selectedArtistIds)->pluck('name', 'id');
            return view('Season/addseason', compact('artists', 'web_id'));
        } else {
            return redirect()->route('web.list')->with('error', 'Web series not found.');
        }
    }
    public function addseason(Request $request)
    {
        $web_id = $request->input('web_id');
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'artist_ids' => 'required|array',
            'artist_ids.*' => 'exists:artists,id',
        ]);
        $webSeries = new Season();
        $webSeries->season_title = $validatedData['title'];
        $webSeries->description = $validatedData['description'];
        $webSeries->web_id = $web_id;
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
        return redirect()->route('season.list', ['id' => $web_id])->with('success', 'Web series added successfully');
    }
    public function edit($id)
    {
        try {
            $season = Season::where('id', $id)->first();
            $web_id = $season->web_id;
            $web = Series::where('id', $web_id)->where('active', 1)->first();
            if ($web && $season) {
                $artists = Artist::pluck('name', 'id') ?? [];
                $selectedArtistIds = SeasonArtist::where('season_id', '=', $id)->pluck('artist_id')->toArray();
                return view('Season/editseason', compact('season', 'artists', 'selectedArtistIds'));
            } else {
                return redirect()->route('web.list')->with('error', 'Web series or season not found.');
            }
        } catch (\Exception $e) {
            return redirect()->route('web.list')->with('error', 'Web series or season not found');
        }
    }

    public function update(Request $request, $id)
    {
        $webSeries = Season::findOrFail($id);
        $web_id = $webSeries->web_id;
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
        return redirect()->route('season.list', ['id' => $web_id])->with('success', 'Web series updated successfully');
    }
    public function delete($id)
    {
        try {
            $season = Season::findOrFail($id);
            $web_id = $season->web_id;
            $web = Series::where('id', $web_id)->where('active', 1)->first();
            if ($web) {
                $season = Season::findOrFail($id);
                $season->artists()->detach();
                $season->delete();
                $season->episodes()->delete();
                return redirect()->route('season.list', ['id' => $web_id])->with('success', 'Web series deleted successfully.');
            } else {
                return redirect()->route('web.list')->with('error', 'Web series  not found.');
            }
        } catch (\Exception $e) {
            return redirect()->route('web.list')->with('error', 'An error occurred while deleting the episode.');
        }
    }
}
