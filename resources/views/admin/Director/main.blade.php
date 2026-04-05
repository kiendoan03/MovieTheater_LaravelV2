@extends('layouts.header')
@section('content')

    <!-- Title -->
    <div class="row">
        <div class="col">
            <h2 class="text-light">Directors management site</h2>
        </div>
    </div>
    <!-- Main -->
    <div class="row">
        <div class="col-12">
            <a href="{{route('admin.directors.create')}}" type="button" class="btn btn-outline-light my-4" tabindex="-1" role="button" aria-disabled="true">
                <i class="fa-solid fa-plus"></i> New director
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table id="myTable" class="table my-3 text-light">
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Director name</th>
                    <th scope="col">Director image</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($directors as $director)
                    <tr>
                        <th scope="row" class="col-1">{{$director -> id}}</th>
                        <td class="col-5">{{$director -> name}}</td>
                        <td class="col-4">
                            <img class="col-4" src="{{asset(\Illuminate\Support\Facades\Storage::url('img/director/'). $director -> image)}}">
                        </td>
                        <td class="col-2">
                            <a href="{{route('admin.directors.edit',$director)}}" type="button" class="btn btn-outline-warning my-1" tabindex="-1" role="button" aria-disabled="true">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form class="d-inline" method="post" action="{{route('admin.directors.destroy', $director)}}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger my-1">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

