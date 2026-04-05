@extends('layouts.header')
@section('content')
    <!-- Title -->
    <div class="row">
        <div class="col">
            <h2 class="text-light mb-4">Edit category</h2>
        </div>
    </div>

    <!-- Main -->
    <div class="row">
        <div class="col-10">
            <form role="form" method="post" action="{{route('admin.categories.update', $category)}}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="category_name" class="form-label text-light">Category name</label>
                    <input type="text" class="form-control bg-dark text-light border-0 shadow-none"
                           id="name" name="name"
                           value="{{$category->name}}" required>
                </div>

                <input type="submit" class="btn btn-danger my-3 col-2" value="Edit" name="submit_btn">

            </form>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

</html>
