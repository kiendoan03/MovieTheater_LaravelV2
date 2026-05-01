<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="\bootstrapLib\bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="icon" href="/img/page_logo/download-removebg-preview.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/scroll/hideScrollBar.css">
    <link rel="stylesheet" href="/public/css/home.css">
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="stylesheet" href="/public/css/app.css">
    <link rel="stylesheet" href="/public/css/intro.css">
    <title>Netfnix</title>
</head>

<body class="" style="background-color: black;">

    <div class="container-fluid p-0">

    <header class="row d-flex  p-3  justify-content-between" style="background-color:none; position:fixed ; width: 100% ; z-index:99">
            <section class="col-3">
                <a href="{{route('index')}}">
                    <img class="col-4" src="/img/page_logo/NetFnix Full logo.png" alt="">
                </a>
            </section>

            <section class="col-3 d-flex justify-content-end pe-3">

                <div class=" position-relative border border-0 me-2 rounded-circle text-center" style="height: 3vmax; width: 3vmax; background-color: #ffffff48;">
                    <i class="fa-regular fa-bell position-absolute top-50 start-50 translate-middle" style="font-size: 1.2vmax;color: #ffffff"></i>
                </div>

                <div class="position-relative border border-0 me-2 rounded-circle text-center" style="height: 3vmax; width: 3vmax; background-color: #ffffff48;">
                    <a href="#">
                        <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-50 translate-middle" style="font-size: 1.2vmax;color: #ffffff"></i>                    
                    </a>
                </div>

                @if(isset($user))
                    <div class="dropdown" >
                        <img class="col-12 border  " style="border-radius: 50%;object-fit: cover; overflow: hidden; height: 3vmax; width: 3vmax;" src="{{asset(\Illuminate\Support\Facades\Storage::url('img/user/').$user -> customer_avatar)}}" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" height="" alt="">
                        <ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenuButton1"> 
                            <li><a class="dropdown-item bg-dark text-light" href="{{route('user',$user -> id)}}">Profile</a></li>
                            <li><a class="dropdown-item bg-dark text-light" href="#">Admin site</a></li>
                            <li><a class="dropdown-item bg-dark text-light" href="#">Logout</a></li>
                        </ul>
                    </div>
                @else
                    <div class="dropdown">
                        <img class="col-12 border  " style="border-radius: 50%;object-fit: cover; overflow: hidden;height: 3vmax; width: 3vmax;" src="{{asset(\Illuminate\Support\Facades\Storage::url('img/netfnix/download-removebg-preview.png'))}}" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" height="" alt="">
                            <ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenuButton1"> 
                                <li><a class="dropdown-item bg-dark text-light" href="#">Admin site</a></li>
                                <li><a class="dropdown-item bg-dark text-light" href="#">Login</a></li>
                            </ul>
                    </div>  
                @endif
            </section>

        </header>

        <!-- Overview -->

        <section id="movie__overview" class="row position:relative">

            <!-- Background Trailer -->

 

            <!-- Movie Detail -->
            <section class="position-absolute start-0 end-0 fullscreen-height px-0 col-12" style="background: linear-gradient(to top, rgb(0, 0, 0), rgba(0, 0, 0, 0.444));">

                <!-- Information of movie -->

                <div class="position-relative top-50 start-50 translate-middle col-12">

                    <!-- Overview Tags -->
                    <div class="row mt-5">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="col-1 text-center">
                                <span class="border text-light p-2 rounded-2">{{$movie -> age}}+</span>
                            </div>
                            <div class="col-1 text-center">
                                <span class="text-light p-2">{{$movie -> release_date}}</span>
                            </div>
                            <div class="col-1 text-center">
                                <span class="text-light p-2">{{$movie -> length}} Min</span>
                            </div>
                            <div class="col-1 text-center">
                                <span class="text-light p-2">
                                    @if($movie -> language == 0)
                                        English - VietSub
                                    @elseif($movie -> language == 1)
                                        Vietnamese
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Movie logo -->
                    <div class="row">
                        <div class="col-12 d-block">
                            <img class="col-4 d-block my-5 mx-auto" style="width: 30vmax" src="{{asset('storage/img/movie_logo/' . $movie -> logo)}}" alt="">
                        </div>
                    </div>

                    <div class="row col-12 ps-5">
                        <!-- Movie tags -->
                        <div class="">

                        @foreach($movie_cate as $movie_cate)

                            <span class="border me-2 text-light px-3 py-2 fs-5 rounded-2">{{$movie_cate -> category_name}}</span>
                        
                        @endforeach

                        </div>

                        <div class="d-flex justify-content-between">
                            <!-- Movie Detail -->
                            <div class="col-5 mt-4">
                                <span class="text-light fs-3 movie-title" style="font-family: 'Poppins', sans-serif;">
                                    {{$movie -> movie_name}}
                                </span>
                            </div>

                            <!-- Sound Button -->
                            <div onclick="turnOnSound()" class="col-1">
                                <div class="sound-button d-inline-block text-light position-relative top-50 start-50 translate-middle" style="font-size: 1.5em; cursor: pointer;">
                                    <i class="fa-solid fa-volume-xmark"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Option -->
                        <div class="mt-4">
                            <span class="border rounded-pill text-light text-center px-3 py-2 fs-5" onclick="toDetailedPage()" style="cursor: pointer;">
                                <i class="fa-solid fa-circle-info me-1"></i>  More 
                            </span>
                            <span class="border rounded-pill text-light text-center mx-3 px-3 py-2 fs-5" onclick="toBookTickerPage()" style="cursor: pointer;">
                                <i class="fa-solid fa-ticket" style="color: #ffffff;"></i>  Book Tickets 
                            </span>
                        </div>

                    </div>
                </div>

            </section>

        </section>

        <!-- More Detail Of The Movie -->

        <section id="movie__full--detail" class="row full-height px-5">

            <!-- Back Button -->
            <div class="" style="margin-top: 5vmax;">
                <span onclick="toOverviewPage()" class="border rounded-pill text-light text-center px-3 py-2 fs-5" style="cursor: pointer;">
                    <i class="fa-solid fa-backward"></i>  Back 
                </span>
            </div>

            <!-- Information about the movie -->
            <section class="d-flex">

                <!-- Movie text in4 -->
                <div class="col-7 hide-scrollbar" style="height: 35vmax; overflow-x: hidden; overflow-y: scroll;">

                    <p class="text-light" style="font-size: 2.5vmax; font-family: 'Poppins', sans-serif;">{{$movie -> movie_name}}</p>

                    <span class="border me-2 text-light px-3 py-2 fs-5 rounded-2">
                        
                        <span class="pe-2 fw-bold">IMDb </span>

                    <span class="border-start py-2 ps-2 text-light"> {{$movie -> rating}} / 5 </span>
                    </span>

                    <span class="border rounded-pill text-light text-center mx-2 px-3 py-2 fs-5" onclick="toBookTickerPage()" style="cursor: pointer;">
                            <i class="fa-solid fa-ticket" style="color: #ffffff;"></i>  Book Tickets 
                    </span>
                    <p class="text-light mt-4">
                        {{$movie -> description}}
                    </p>

                    <!-- Actor and Director -->
                    <section>

                        <!-- Actors -->
                        <div>
                            <span class="text-light" style="font-size: 1.7vmax;"> Actors </span>
                            

                                <div class="d-flex my-4">
                                    @foreach($movie_actor as $movie_actor)
                                        <a href="{{route('actor',$movie_actor -> actor_id)}}">
                                            <img class="actor-img me-3" src="{{asset('storage/img/actor/' . $movie_actor -> image)}}" style="object-fit: cover; border-radius: 50%; overflow: hidden; height: 6vmax; width: 6vmax;" alt="">
                                        </a>
                                    @endforeach
                                </div>

                            
                        </div>

                        <!-- Director -->
                        <div>
                            <span class="text-light" style="font-size: 1.7vmax;"> Directors </span>
                            <div class="d-flex my-4 ">
                                @foreach($movie_director as $movie_director)
                                <a href="{{route('director', $movie_director -> director_id)}}">
                                    <img class="d-block  me-3" src="{{asset('storage/img/director/' . $movie_director -> image)}}" style="object-fit: cover; border-radius: 50%; overflow: hidden; height: 6vmax; width: 6vmax;" alt="">
                                </a>
                                @endforeach
                            </div>
                        </div>

                    </section>

                    <!-- Related Movies -->


                </div>

                <!-- Movie Img -->
                <div class="col-5 d-flex justify-content-end">
                    <img class="col-12 border rounded-3 border-0 " src="{{asset('storage/img/movie_poster/' . $movie -> poster)}}" alt="" style="object-fit: cover;height: 35vmax; width: 25vmax;">
                </div>

            </section>

        </section>


        <!-- Ticket -->

        <section id="book__ticket" class="row full-height-ticket px-5 " >

                <section class="mt-5 py-5" >
                    <span onclick="toDetailedPage()" class="border rounded-pill text-light text-center px-3 py-2 fs-5 " style="cursor: pointer;">
                        <i class="fa-solid fa-backward"></i>  Back 
                    </span> 
                    <div class="col-10 d-flex flex-wrap mx-auto hide-scrollbar mt-3" style="height: 40vmax; overflow-x: hidden; overflow-y: scroll;">
                        @foreach($schedules as $schedule)
                            <div class="schedule-card mt-5 text-light col-5 me-5 mx-5 py-3 px-5" style="border-radius: 1vmax;" >
                                <div class="card-header text-danger">
                                    <h2 class="mb-0 fw-bolder">{{ $schedule->room_name }}</h2>
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title mb-3">{{ $schedule->date }}</h4>
                                    <p class="card-text fs-5"><b>Start time:</b> {{ $schedule->start_time }}</p>
                                    <p class="card-text fs-5"><b>End time:</b> {{ $schedule->end_time }}</p>
                                    <a href="{{ route('seat-layout-customer', $schedule->schedule_id) }}" 
                                    class="btn btn-danger mt-3 py-3 px-5 border border-0 rounded-pill">
                                    Booking ticket
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                   
                </section>
                


        </section>

    </div>

    <script>
        function turnOnSound() {
            var trailer = document.getElementById('trailerVideo');
            trailer.muted = !trailer.muted;
            if (trailer.muted) {
                document.querySelector('.sound-button').innerHTML = '<i class="fa-solid fa-volume-xmark"></i>';
            } else {
                document.querySelector('.sound-button').innerHTML = '<i class="fa-solid fa-volume-high"></i>';
            }
        }
    </script>

    <script src="{{asset(\Illuminate\Support\Facades\Storage::url('js/screenProps/setBrowserSize.js'))}}"></script>
    <script src="{{asset(\Illuminate\Support\Facades\Storage::url('js/screenProps/scrollHalf.js'))}}"></script>
    <script src="{{asset(\Illuminate\Support\Facades\Storage::url('js/screenProps/disableScroll.js'))}}"></script>
    <script src="/bootstrapLib/bootstrap.bundle.min.js"></script>

    <script>
function selectSchedule(scheduleId) {
    window.location.href = `/seat-layout/${scheduleId}`;
}
</script>

</body>

</html>