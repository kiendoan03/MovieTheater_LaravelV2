<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" href="/img/page_logo/download-removebg-preview.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="{{asset(\Illuminate\Support\Facades\Storage::url('css/scroll/hideScrollBar.css'))}}">
</head>
<style>
    #sidebar a:hover {
        background-color: #f8f9fa; /* bg-light */
        color: #000 !important;
    }
    #myTable tbody tr:hover {
        background-color: #f8f9fa; /* sáng lên */
        color: #000; /* chữ đen cho dễ nhìn */
        cursor: pointer;
    }

</style>
<body style="background-color: black;" class="text-light">

<div class="container-fluid mb-5">
    <div class="row">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg fixed-top" style="background-color: black;">
            <div class="container mx-auto p-0">
                                    <a class="navbar-brand" href="">
                <img src="/img/page_logo/NetFnix Full logo.png" height="50">
                </a>
{{--                                    @if(isset($admin))--}}
                                    <div class="dropdown text-center" >
                                            <img class="col-12 border" style="border-radius: 50%; height: 3vmax; width: 3vmax;" src="" type="button" data-bs-toggle="dropdown">
{{--                                            <p>{{$admin -> name}}</p>--}}
                                        <ul class="dropdown-menu bg-dark">
                                            <li><a class="dropdown-item text-light" href="">Profile</a></li>
                                            <li><a class="dropdown-item text-light" href="">Logout</a></li>
                                        </ul>
                                    </div>
{{--                                    @else--}}
                                    <div class="dropdown">
                                        <img class="col-12 border" style="border-radius: 50%; height: 3vmax; width: 3vmax;" src="" type="button" data-bs-toggle="dropdown">
                                            <ul class="dropdown-menu bg-dark">
                                                <li><a class="dropdown-item text-light" href="">Login</a></li>
                                            </ul>
                                    </div>
{{--                                    @endif--}}
            </div>
        </nav>
    </div>

    <div class="row mt-5">
        <div class="col-10 mx-auto" style="margin-top:5em;">
            <div class="row">
                <div class="col-3 shadow p-3 bg-dark rounded mb-3 min-vh-100" id="sidebar">
                    <div class="btn-group-vertical col-12">
                        <a href="" class="btn text-light" data-id="dashboard">Dashboard</a>
                        <a href="" class="btn text-light" data-id="staffs">Staffs</a>
                        <a href="" class="btn text-light" data-id="customers">Customers</a>
                        <a href="" class="btn text-light" data-id="genre">Film genre</a>
                        <a href="" class="btn text-light" data-id="movies">Movies</a>
                        <a href="" class="btn text-light" data-id="rooms">Rooms</a>
                        <a href="" class="btn text-light" data-id="schedules">Schedules</a>
                        <a href="" class="btn text-light" data-id="actors">Actors</a>
                        <a href="" class="btn text-light" data-id="directors">Directors</a>
                    </div>
                </div>

                <div class="col-9">
                    @yield('content')
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const links = document.querySelectorAll('#sidebar a');

    links.forEach(link => {
        link.addEventListener('click', function () {
            localStorage.setItem('activeMenu', this.dataset.id);
        });
    });

    // Khi load lại trang
    const active = localStorage.getItem('activeMenu');

    if (active) {
        links.forEach(link => {
            if (link.dataset.id === active) {
                link.classList.add('bg-light', 'text-dark');
            }
        });
    }
</script>
</body>
</html>
