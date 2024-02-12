<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;
use App\Models\Theme;
use App\Models\Artist;
use App\Models\WebArtist;
use App\Models\SeasonArtist;
use App\Models\Season;
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
        $posts = Season::with(['artists'])->where('web_id', $webid)->get();
        return view('Season.seasonlist', compact('posts', 'themeName'));
    }
    public function add()
    {
        $selectedArtistIds = session()->get('selectedArtistIds');
        $artists = Artist::pluck('name', 'id');
        return view('Season/addseason', compact('artists', 'selectedArtistIds'));
    }
    public function addseason(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'artist_ids' => 'required|array',
            'artist_ids.*' => 'exists:artists,id',
        ]);
        $maxId = DB::table('seasons')->max('id');
        $newId = $maxId + 1;
        $webSeries = new Season();
        $webSeries->id = $newId;
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
        $webSeries = Season::findOrFail($id);
        $artists = Artist::pluck('name', 'id') ?? [];
        $selectedArtistIds = SeasonArtist::where('season_id', '=', $id)->pluck('artist_id')->toArray();
        return view('Season/editseason', compact('webSeries', 'artists', 'selectedArtistIds'));
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
        $season = Season::findOrFail($id);
        $season->artists()->detach();
        $season->delete();
        return redirect()->route('season.list')->with('success', 'Web series deleted successfully.');
    }

    public function view(Request $request, $id)
    {
        $request->session()->put('seasonid', $id);
        $selectedArtistIds = SeasonArtist::where('season_id', '=', $id)->pluck('artist_id')->toArray();
        session()->put('selectedArtistIds', $selectedArtistIds);
        return redirect()->route('episode.list')->with('success', 'episodes open successfully.');
    }
}
