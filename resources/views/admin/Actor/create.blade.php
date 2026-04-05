<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netfnix - Actor Management - Add actor</title>
    <link rel="icon" href="/img/page_logo/download-removebg-preview.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href=" {{asset(\Illuminate\Support\Facades\Storage::url('css/scroll/hideScrollBar.css'))}}">


    <style>
        input::file-selector-button {
            font-weight: bold;
        }
    </style>
</head>

<body style="background-color: black;">

    <div class="container-fluid">
        <div class="row">
            <!-- Header -->

            <nav class="navbar navbar-expand-lg fixed-top " style="background-color: black;">
                <div class="container  mx-auto p-0">
                    <a class="navbar-brand" href="{{route('admin.dashboard')}}">
                        <img src="/img/page_logo/NetFnix Full logo.png" alt="" height="50" class="d-inline-block align-text-top">
                    </a>
                    @if(isset($admin))
                    <div class="dropdown text-center" >
                            <img class="col-12 border  " style="border-radius: 50%;object-fit: cover; overflow: hidden; height: 3vmax; width: 3vmax;" src="{{asset(\Illuminate\Support\Facades\Storage::url('img/staff/').$admin -> staff_avatar)}}" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" height="" alt="">
                            <p class="my-auto text-light">{{$admin -> name}}</p>  
                        <ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenuButton1"> 
                            <li><a class="dropdown-item bg-dark text-light" href="">Profile</a></li>
                            <li><a class="dropdown-item bg-dark text-light" href="{{route('admin.staffs.logout')}}">Logout</a></li>
                        </ul>
                    </div>
                    @else
                    <div class="dropdown">
                        <img class="col-12 border  " style="border-radius: 50%;object-fit: cover; overflow: hidden;height: 3vmax; width: 3vmax;" src="{{asset(\Illuminate\Support\Facades\Storage::url('img/netfnix/download-removebg-preview.png'))}}" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" height="" alt="">
                            <ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenuButton1"> 
                                <li><a class="dropdown-item bg-dark text-light" href="{{route('admin.staffs.login')}}">Login</a></li>
                            </ul>
                    </div>  
                    @endif
                </div>
            </nav>
        </div>
        <div class="row mt-5">
            <div class="col-10 mx-auto" style="margin-top:5em;">
                <div class="row">
                    <div class="col-3 shadow p-3 bg-dark rounded mb-3 min-vh-100 bg">
                     <div class="btn-group-vertical col-12 " role="group" aria-label="Basic example">
                            <a href="{{route('admin.dashboard')}}" class="btn border-0 rounded text-start text-light shadow-none" tabindex="-1" role="button" aria-disabled="true">Dashboard</a>
                            <a href="{{route('admin.staffs.index')}}" class="btn border-0 rounded text-start text-light shadow-none" tabindex="-1" role="button" aria-disabled="true">Staffs management</a>
                            <a href="{{route('admin.customers.index')}}" class="btn border-0 rounded text-start text-light shadow-none" tabindex="-1" role="button" aria-disabled="true">Customers management</a>
                            <a href="{{route('admin.categories.index')}}" class="btn border-0 rounded text-start text-light shadow-none" tabindex="-1" role="button" aria-disabled="true">Film genre management</a>
                            <a href="{{route('admin.movies.index')}}" class="btn border-0 rounded text-start text-light shadow-none" tabindex="-1" role="button" aria-disabled="true">Movies management</a>
                            <a href="{{route('admin.rooms.index')}}" class="btn border-0 rounded text-start text-light shadow-none" tabindex="-1" role="button" aria-disabled="true">Room management</a>
                            <a href="{{route('admin.schedules.index')}}" class="btn border-0 rounded text-start text-light  shadow-none" tabindex="-1" role="button" aria-disabled="true">Schedules management</a>
                            <a href="{{route('admin.actors.index')}}" class="btn border-0 rounded text-start text-dark bg-danger shadow-none" tabindex="-1" role="button" aria-disabled="true">Actors management</a>
                            <a href="{{route('admin.directors.index')}}" class="btn border-0 rounded text-start text-light shadow-none" tabindex="-1" role="button" aria-disabled="true">Directors management</a>
                        </div>
                    </div>
                    <div class="col-9 ">

                        <!-- Title -->
                        <div class="row">
                            <div class="col">
                                <h2 class="text-light mb-4">Add Actor</h2>
                            </div>
                        </div>

                        <!-- Main -->
                        <div class="row">
                            <form role="form" method="post" action="{{route('admin.actors.store')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mb-3">
                                            <label for="actor_name" class="form-label text-light">Actor name</label>
                                            <input type="text" class="form-control bg-dark border-0 shadow-none text-light" id="actor" name="actor_name" required>
                                        </div>
                                    </div>

                                    <!-- File img -->
                                    <div class="col-4">

                                        <div class="col-12">
                                            <div class="row">
                                                <label for="image" class="form-label text-light">Actor image</label>
                                                <input class="form-control bg-dark border-0 shadow-none text-light" type="file" id="image" name="actor_img" accept="image/png, image/jpg, image/jpeg" onchange="show_img()" required>
                                                <div class="row my-3" style="width: 15vmax;">
                                                    <img id="actor_img" class=" rounded-3 object-fit-cover mx-auto" src="../../../../public/img/poster_film/no_img_poster.jpg" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-danger my-2 col-2" value="Add" name="submit_btn">
                            </form>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        CKEDITOR.replace(movie_description);
    </script>
    <script>
        function show_img() {
            actor_img.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
</body>

</html>