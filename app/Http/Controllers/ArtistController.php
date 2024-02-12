<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistController extends Controller
{
    public function index()
    {
        $userModel = new Artist();
        $data = [
            'posts' => $userModel->all(),
        ];
        return view('Artist/ArtistList', $data);
    }
    public function add()
    {
        return view('Artist/addartist');
    }
    public function addartist(Request $request)
    {
        $storeData = $request->validate([
            'name' => 'required|max:255',
            'age' => 'required',
        ]);
        $artist = Artist::create($storeData);
        if ($artist) {
            return redirect("artistlist")->with('success', 'Artist added successfully');
        } 
        else {
            return back()->withInput()->with('error', 'Failed to add artist');
        }
    }
    public function edit($id)
    {
        $artist = Artist::findOrFail($id);
        return view('Artist/editartist', compact('artist'));
    }
    public function update(Request $request, $id)
    {
        $artist = Artist::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'age' => 'required',
        ]);
        $artist->update($validatedData);
        return redirect()->route('StudioController.artistlist')->with('success', 'Artist updated successfully');
    }
    public function delete($id)
    {
        $artist = Artist::find($id);
        if ($artist) {
            $artist->delete();
            return redirect()->route('StudioController.artistlist')->with('success', 'Artist deleted successfully.');
        } else {
            return redirect()->route('StudioController.artistlist')->with('error', 'Failed to delete artist.');
        }
    }
}
