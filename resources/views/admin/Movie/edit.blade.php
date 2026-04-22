@extends('layouts.admin')

@section('content')
<h2>Sửa phim</h2>

<form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="text" name="movie_name" value="{{ $movie->movie_name }}"><br>

    <input type="number" name="movie_length" value="{{ $movie->length }}"><br>

    <input type="date" name="movie_release_date" value="{{ $movie->release_date }}"><br>
    <input type="date" name="movie_end_date" value="{{ $movie->end_date }}"><br>

    <input type="number" name="movie_age" value="{{ $movie->age_restricted }}"><br>

    <input type="text" name="movie_language" value="{{ $movie->language }}"><br>
    <input type="text" name="movie_country" value="{{ $movie->country }}"><br>

    <textarea name="movie_description">{{ $movie->synopsis }}</textarea><br>

    Logo: <input type="file" name="movie_logo"><br>
    Poster: <input type="file" name="movie_poster"><br>
    Thumbnail: <input type="file" name="movie_thumbnail"><br>
    Trailer: <input type="file" name="movie_trailer"><br>

    <button type="submit">Cập nhật</button>
</form>
@endsection