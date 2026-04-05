@extends('layouts.header')
@section('content')

    <!-- Title -->
    <div class="row">
        <div class="col">
            <h2 class="text-light mb-4">Add director</h2>
        </div>
    </div>

    <!-- Main -->
    <div class="row">
        <form role="form" method="post" action="{{route('admin.directors.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-8">
                    <div class="mb-3">
                        <label for="director_name" class="form-label text-light">Director name</label>
                        <input type="text" class="form-control bg-dark border-0 shadow-none text-light" id="name" name="name" required>
                    </div>
                </div>

                <!-- File img -->
                <div class="col-4">

                    <div class="col-12">
                        <div class="row">
                            <label for="image" class="form-label text-light">Director image</label>
                            <input class="form-control bg-dark border-0 shadow-none text-light" type="file" id="image" name="image" accept="image/png, image/jpg, image/jpeg" onchange="show_img()" required>
                            <div class="row my-3" style="width: 15vmax;">
                                <img id="preview" src="" style="max-width:200px; display:none;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="submit" class="btn btn-danger my-2 col-2" value="Add" name="submit_btn">

        </form>
    </div>

    <script>
        function show_img() {
            const input = document.getElementById('image');
            const preview = document.getElementById('preview');

            const file = input.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
