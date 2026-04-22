<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Actor;
use App\Models\Category;
use App\Models\Director;
use App\Models\Schedule;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::with([
            'categories',
            'actors',
            'directors'
        ])->get();

        return view('admin.Movie.main', [
            'movies' => $movies,
        ]);
    }

    public function create()
    {
        $actors = Actor::orderBy('name')->get();

        $directors = Director::orderBy('name')->get();

        $categories = Category::orderBy('name')->get();

        return view('admin.Movie.create', [
            'actors' => $actors,
            'directors' => $directors,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_name'       => 'required|string|max:255',

            'movie_logo'       => 'nullable|image',
            'movie_poster'     => 'nullable|image',
            'movie_thumbnail'  => 'nullable|image',

            // trailer giờ là link
            'movie_trailer'    => 'nullable|url',

            'movie_length'     => 'required|integer|min:1',
            'movie_language'   => 'required|string|max:255',
            'movie_country'    => 'required|string|max:255',

            'movie_release_date' => 'required|date',
            'movie_end_date'     => 'required|date|after_or_equal:movie_release_date',

            'movie_age'        => 'required|integer',
            'movie_description' => 'required',

            'movie_genre'      => 'nullable|array',
            'movie_genre.*'    => 'exists:categories,id',

            'movie_actor'      => 'nullable|array',
            'movie_actor.*'    => 'exists:actors,id',

            'movie_director'   => 'nullable|array',
            'movie_director.*' => 'exists:directors,id',
        ]);

        // ================= upload =================

        $logo = null;
        $poster = null;
        $thumbnail = null;

        // trailer là string link
        $trailer = $request->movie_trailer;

        if ($request->hasFile('movie_logo')) {

            $logo = time() . '_logo_' .
                $request->file('movie_logo')->getClientOriginalName();

            Storage::putFileAs(
                'public/img/movie_logo',
                $request->file('movie_logo'),
                $logo
            );
        }

        if ($request->hasFile('movie_poster')) {

            $poster = time() . '_poster_' .
                $request->file('movie_poster')->getClientOriginalName();

            Storage::putFileAs(
                'public/img/movie_poster',
                $request->file('movie_poster'),
                $poster
            );
        }

        if ($request->hasFile('movie_thumbnail')) {

            $thumbnail = time() . '_thumbnail_' .
                $request->file('movie_thumbnail')->getClientOriginalName();

            Storage::putFileAs(
                'public/img/movie_thumbnail',
                $request->file('movie_thumbnail'),
                $thumbnail
            );
        }

        // ================= create movie =================

        $movie = Movie::create([
            'movie_name'     => $request->movie_name,

            'logo'           => $logo,
            'poster'         => $poster,
            'thumbnail'      => $thumbnail,

            'rating'         => 5,

            'synopsis'       => $request->movie_description,
            'language'       => $request->movie_language,
            'country'        => $request->movie_country,

            'length'         => $request->movie_length,

            'release_date'   => $request->movie_release_date,
            'end_date'       => $request->movie_end_date,

            'age_restricted' => $request->movie_age,

            // lưu link youtube
            'trailer'        => $trailer,
        ]);

        // ================= sync relation =================

        $movie->categories()->sync(
            $request->movie_genre ?? []
        );

        $movie->actors()->sync(
            $request->movie_actor ?? []
        );

        $movie->directors()->sync(
            $request->movie_director ?? []
        );

        return redirect()
            ->route('admin.movies.index')
            ->with(
                'success',
                "Tạo phim \"{$movie->movie_name}\" thành công!"
            );
    }

    public function show(Movie $movie)
    {
        $movie->load([
            'categories',
            'actors',
            'directors',
            'schedules'
        ]);

        return view('admin.Movie.show', [
            'movie' => $movie,
        ]);
    }

    public function edit(Movie $movie)
    {
        $actors = Actor::orderBy('name')->get();

        $directors = Director::orderBy('name')->get();

        $categories = Category::orderBy('name')->get();

        $movie->load([
            'categories',
            'actors',
            'directors'
        ]);

        return view('admin.Movie.edit', [
            'movie' => $movie,
            'actors' => $actors,
            'directors' => $directors,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'movie_name'       => 'required|string|max:255',

            'movie_logo'       => 'nullable|image',
            'movie_poster'     => 'nullable|image',
            'movie_thumbnail'  => 'nullable|image',

            // trailer giờ là link
            'movie_trailer'    => 'nullable|url',

            'movie_length'     => 'required|integer|min:1',
            'movie_language'   => 'required|string|max:255',
            'movie_country'    => 'required|string|max:255',

            'movie_release_date' => 'required|date',
            'movie_end_date'     => 'required|date|after_or_equal:movie_release_date',

            'movie_age'        => 'required|integer',
            'movie_description' => 'required',
        ]);

        // ================= old data =================

        $logo = $movie->logo;
        $poster = $movie->poster;
        $thumbnail = $movie->thumbnail;

        // trailer là link
        $trailer = $request->movie_trailer;

        // ================= upload new =================

        if ($request->hasFile('movie_logo')) {

            $logo = time() . '_logo_' .
                $request->file('movie_logo')->getClientOriginalName();

            Storage::putFileAs(
                'public/img/movie_logo',
                $request->file('movie_logo'),
                $logo
            );
        }

        if ($request->hasFile('movie_poster')) {

            $poster = time() . '_poster_' .
                $request->file('movie_poster')->getClientOriginalName();

            Storage::putFileAs(
                'public/img/movie_poster',
                $request->file('movie_poster'),
                $poster
            );
        }

        if ($request->hasFile('movie_thumbnail')) {

            $thumbnail = time() . '_thumbnail_' .
                $request->file('movie_thumbnail')->getClientOriginalName();

            Storage::putFileAs(
                'public/img/movie_thumbnail',
                $request->file('movie_thumbnail'),
                $thumbnail
            );
        }

        // ================= update =================

        $movie->update([
            'movie_name'     => $request->movie_name,

            'logo'           => $logo,
            'poster'         => $poster,
            'thumbnail'      => $thumbnail,

            'synopsis'       => $request->movie_description,
            'language'       => $request->movie_language,
            'country'        => $request->movie_country,

            'length'         => $request->movie_length,

            'release_date'   => $request->movie_release_date,
            'end_date'       => $request->movie_end_date,

            'age_restricted' => $request->movie_age,

            // update link youtube
            'trailer'        => $trailer,
        ]);

        // ================= sync relation =================

        $movie->categories()->sync(
            $request->movie_genre ?? []
        );

        $movie->actors()->sync(
            $request->movie_actor ?? []
        );

        $movie->directors()->sync(
            $request->movie_director ?? []
        );

        return redirect()
            ->route('admin.movies.index')
            ->with(
                'success',
                "Cập nhật phim \"{$movie->movie_name}\" thành công!"
            );
    }

    public function destroy(Movie $movie)
    {
        $hasSchedule = Schedule::where(
            'movie_id',
            $movie->id
        )->exists();

        if ($hasSchedule) {

            return redirect()
                ->route('admin.movies.index')
                ->with(
                    'error',
                    'Không thể xóa phim đã có lịch chiếu!'
                );
        }

        // delete relation

        $movie->categories()->detach();
        $movie->actors()->detach();
        $movie->directors()->detach();

        // delete movie

        $name = $movie->movie_name;

        $movie->delete();

        return redirect()
            ->route('admin.movies.index')
            ->with(
                'success',
                "Đã xóa phim \"{$name}\"!"
            );
    }
}
