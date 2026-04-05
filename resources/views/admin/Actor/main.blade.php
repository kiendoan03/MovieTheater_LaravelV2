@extends('layouts.header')
@section('content')

    <!-- Title -->
    <div class="row">
        <div class="col">
            <h2 class="text-light">Actors management site</h2>
        </div>
    </div>

    <!-- Main -->

    <div class="row">
        <div class="col-12">
            <a href="{{route('admin.actors.create')}}" type="button" class="btn btn-outline-light my-4" tabindex="-1" role="button" aria-disabled="true">
                <i class="fa-solid fa-plus"></i> New actor
            </a>
        </div>
    </div>

    <div class="row">

        <div class="col">
            <table id="myTable" class="table my-3 text-light" >
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Actor name</th>
                    <th scope="col">Actor image</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($actors as $actor)

                    <tr>
                        <th scope="row" class="col-1">{{$actor -> id}}</th>
                        <td class="col-5"> {{$actor -> name}} </td>
                        <td class="col-4">
                            <img  class="col-4" src="{{asset(\Illuminate\Support\Facades\Storage::url('img/actor/'). $actor -> image)}}">
                        </td>
                        <td class="col-2">
                            <a href="{{route('admin.actors.edit', $actor)}}" type="button" class="btn btn-outline-light my-1" tabindex="-1" role="button" aria-disabled="true">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form class="d-inline" method="post" action="{{route('admin.actors.destroy', $actor)}}">
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
