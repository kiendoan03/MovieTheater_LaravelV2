@extends('layouts.header')
@section('content')
    <!-- Title -->
    <div class="row">
        <div class="col">
            <h2 class="text-light mb-4">Add category</h2>
        </div>
    </div>

    <!-- Main -->
    <div class="row">
        <div class="col-10">
            <form role="form" method="post" action="{{route('admin.categories.store')}}">
                @csrf
                <div class="mb-3">
                    <label for="category_name" class="form-label text-light">Category name</label>
                    <input type="text" class="form-control bg-dark text-light border-0 shadow-none" id="category_name" name="name" required>
                </div>
                <input type="submit" class="btn btn-danger my-3 col-2" value="Add" name="submit_btn">
            </form>
        </div>
    </div>
@endsection
