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
        ])->paginate(10);

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
            'movie_logo'       => 'required|image',
            'movie_poster'     => 'required|image',
            'movie_thumbnail'  => 'required|image',
            'movie_trailer' => 'required|mimes:mp4,mov,avi,wmv|max:204800',
            'movie_length'     => 'required|integer|min:1',
            'movie_language'   => 'required|string|max:255',
            'movie_country'    => 'required|string|max:255',
            'movie_release_date' => 'required|date',
            'movie_end_date'     => 'required|date|after_or_equal:movie_release_date',
            'movie_age'        => 'required|integer|min:1',
            'movie_description' => 'required|string',
            'movie_genre'      => 'required|array|min:1',
            'movie_genre.*'    => 'exists:categories,id',
            'movie_actor'      => 'required|array|min:1',
            'movie_actor.*'    => 'exists:actors,id',
            'movie_director'   => 'required|array|min:1',
            'movie_director.*' => 'exists:directors,id',
        ], [
            'required' => ':attribute không được để trống.',
            'movie_trailer.mimes' => 'Trailer phải là file video.',
            'movie_trailer.max' => 'Trailer tối đa 200MB.',
            'movie_end_date.after_or_equal' =>
            'Ngày kết thúc phải lớn hơn hoặc bằng ngày khởi chiếu.',

        ], [
            'movie_name' => 'Tên phim',
            'movie_logo' => 'Logo',
            'movie_poster' => 'Poster',
            'movie_thumbnail' => 'Thumbnail',
            'movie_trailer' => 'Trailer',
            'movie_length' => 'Thời lượng',
            'movie_language' => 'Ngôn ngữ',
            'movie_country' => 'Quốc gia',
            'movie_release_date' => 'Ngày khởi chiếu',
            'movie_end_date' => 'Ngày kết thúc',
            'movie_age' => 'Độ tuổi',
            'movie_description' => 'Mô tả phim',
            'movie_genre' => 'Thể loại',
            'movie_actor' => 'Diễn viên',
            'movie_director' => 'Đạo diễn',
        ]);

        // upload 

        $logo = null;
        $poster = null;
        $thumbnail = null;

        $trailer = null;

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
        if ($request->hasFile('movie_trailer')) {

            $trailer = time() . '_trailer_' .
                $request->file('movie_trailer')->getClientOriginalName();

            Storage::putFileAs(
                'public/video/movie_trailer',
                $request->file('movie_trailer'),
                $trailer
            );
        }
        // create movie 

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
            'trailer'        => $trailer,
        ]);

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
        $movies = Movie::with([
            'categories',
            'actors',
            'directors',
            'schedules'
        ])
            ->whereDate('end_date', '>=', now())
            ->orderBy('release_date', 'desc')
            ->get();

        // phim đang chiếu 
        $movie_show = Movie::whereDate('release_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('release_date', 'desc')
            ->get();

        // phim sắp chiếu
        $upcoming_movies = Movie::whereDate('release_date', '>', now())
            ->orderBy('release_date', 'asc')
            ->take(6)
            ->get();

        // TOP phim
        $top_movies = Movie::whereDate('release_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();

        return view('customer.home', [
            'movie' => $movie,
            'movies' => $movies,
            'movie_show' => $movie_show,
            'upcoming_movies' => $upcoming_movies,
            'top_movies' => $top_movies,
        ]);
    }
    public function loadMoreUpcoming(Request $request)
    {
        $offset = $request->offset ?? 0;

        $movies = Movie::whereDate('release_date', '>', now())
            ->orderBy('release_date', 'asc')
            ->skip($offset)
            ->take(6)
            ->get();

        return response()->json($movies);
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
            'movie_trailer' => 'nullable|mimes:mp4,mov,avi,wmv|max:204800',
            'movie_length'     => 'required|integer|min:1',
            'movie_language'   => 'required|string|max:255',
            'movie_country'    => 'required|string|max:255',
            'movie_release_date' => 'required|date',
            'movie_end_date'     => 'required|date|after_or_equal:movie_release_date',
            'movie_age'        => 'required|integer',
            'movie_description' => 'required',
        ]);

        // old data 

        $logo = $movie->logo;
        $poster = $movie->poster;
        $thumbnail = $movie->thumbnail;
        $trailer = $movie->trailer;

        // upload new

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
        
        if ($request->hasFile('movie_trailer')) {
            // xóa trailer cũ
            if ($movie->trailer) {
                Storage::delete(
                    'public/video/movie_trailer/' . $movie->trailer
                );
            }

            $trailer = time() . '_trailer_' .
                $request->file('movie_trailer')->getClientOriginalName();

            Storage::putFileAs(
                'public/video/movie_trailer',
                $request->file('movie_trailer'),
                $trailer
            );
        }

        // update

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
            'trailer'        => $trailer,
        ]);


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

        if ($movie->logo) {
            Storage::delete('public/img/movie_logo/' . $movie->logo);
        }
        if ($movie->poster) {
            Storage::delete('public/img/movie_poster/' . $movie->poster);
        }
        if ($movie->thumbnail) {
            Storage::delete('public/img/movie_thumbnail/' . $movie->thumbnail);
        }
        if ($movie->trailer) {
        Storage::delete('public/video/movie_trailer/' . $movie->trailer);
        }
        $movie->categories()->detach();
        $movie->actors()->detach();
        $movie->directors()->detach();

        $name = $movie->movie_name;
        $movie->delete();
        return redirect()
            ->route('admin.movies.index')
            ->with(
                'success',
                "Đã xóa phim \"{$name}\"!"
            );
    }
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $genre = (array) $request->genre;
        $release_date = $request->release_date;
        $format = $request->format;

        $query = Movie::with(['categories', 'actors', 'directors']);

        // tìm theo tên phim
        $query->when($keyword, function ($q) use ($keyword) {
            $q->where('movie_name', 'like', "%{$keyword}%");
        });

        // lọc theo thể loại
        $query->when(!empty($genre), function ($q) use ($genre) {
            $q->whereHas('categories', function ($sub) use ($genre) {
                $sub->whereIn('categories.id', $genre);
            });
        });

        // lọc theo ngày chiếu
        $query->when($release_date, function ($q) use ($release_date) {
            $q->whereDate('release_date', '<=', $release_date)
                ->whereDate('end_date', '>=', $release_date);
        });

        // lọc theo format
        $query->when($format, function ($q) use ($format) {
            $q->where('format', $format);
        });

        $movies = $query
            ->orderBy('release_date', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('customer.search', compact(
            'movies',
            'categories',
            'keyword',
            'genre',
            'release_date',
            'format'
        ));
    }
}
