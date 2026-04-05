@extends('layouts.header')
@section('content')

    <!-- Title -->
    <div class="row">
        <div class="col">
            <h2 class="text-light">Category management site</h2>
        </div>
    </div>

    <!-- Main -->
    <div class="row">
        <div class="container-fluid"></div>
    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{route('admin.categories.create')}}" class="btn btn-outline-danger my-4">
                <i class="fa-solid fa-plus"></i> New category
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table id="myTable" class="table my-3 text-light">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($categories as $category)
                    <tr>
                        <th>{{$category->id}}</th>
                        <td>{{$category->name}}</td>
                        <td>
                            <a href="{{route('admin.categories.edit', $category)}}" class="btn btn-outline-light my-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <form class="d-inline" method="post" action="{{route('admin.categories.destroy', $category)}}">
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
