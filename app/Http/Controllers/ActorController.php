<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ActorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $actors = Actor::all();
//        $admin = Auth::guard('staff')->user();

        return view('admin.actor.main',[
            'actors' => $actors,
//            'admin' => $admin,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
//        $admin = Auth::guard('staff')->user();

        return view('admin.actor.create',[
//            'admin' => $admin,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $actor_img = $request->file('image')-> getClientOriginalName();
        if(!Storage::exists('public/img/actor/'.$actor_img)){
            Storage::putFileAs('public/img/actor/', $request->file('image'), $actor_img);
        }

        $array = [];
        $array = Arr::add($array, 'name', $request->name);
        $array = Arr::add($array, 'image', $actor_img);

        Actor::create($array);
        return redirect()->route('admin.actors.index')->with('success', 'Add actor successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($movie_actor)
    {
        $actor = Actor::find($movie_actor);
        $movie_actor = Movie::join('actor_movies', 'actor_movies.movie_id', '=', 'movies.id')
            ->join('actors', 'actors.id', '=', 'actor_movies.actor_id')
            ->where('actors.id', $actor -> id)
            ->get(['movies.*', 'actor_movies.*', 'actors.*']);

            return view('Customer.actor',[
                'actors' => $actor,
                'movie_actor' => $movie_actor,
            ]);


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Actor $actor)
    {
//        $admin = Auth::guard('staff')->user();

        return view('admin.actor.edit',[
            'actor' => $actor,
//            'admin' => $admin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Actor $actor)
    {
        if($request->hasFile('image')){
            $actor_img = $request->file('image')-> getClientOriginalName();

            if(!Storage::exists('public/img/actor/'.$actor_img)){
                Storage::putFileAs('public/img/actor/', $request->file('image'), $actor_img);
            }
        }else{
            $actor_img = $actor->image;
        }
        $array = [];
        $array = Arr::add($array, 'name', $request->name);
        $array = Arr::add($array, 'image', $actor_img);

        $actor->update($array);

        return redirect()->route('admin.actors.index')->with('success', 'Edit actor successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Actor $actor)
    {
        $actor->delete();

        return redirect()->route('admin.actors.index')->with('success', 'Delete actor successfully!');
    }
}
