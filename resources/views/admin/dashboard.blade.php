@extends('layouts.management')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

 <div class="col-9">

                        <!-- Title -->
                        <div class="row">
                            <div class="col">
                                <h2 class="text-light">Dashboard Management site</h2>
                            </div>
                        </div>

                        <!-- Main -->
                        <div class="row mt-5">
                            <div class="col-6 mb-3">
                                <div class="card text-light mb-3 border border-light" style="background-color:black">
                                    <div class="card-body d-flex">
                                        <div class="my-auto ms-5 me-3">
                                            <i class="fa-solid fa-film fa-2xl" style="color: #ff0000;"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title  text-light">Total Movies</h6>
                                            <h3 class="card-text fw-bold text-light">{{$total_movies}}</h3>
                                            
                                        </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card text-light mb-3 border border-light" style="background-color:black">
                                    <div class="card-body d-flex">
                                        <div class="my-auto ms-5 me-3">
                                            <i class="fa-solid fa-video fa-2xl" style="color: #ff0000;"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title text-light">Movie showing</h6>
                                            <h3 class="card-text fw-bold text-light">{{$movie_showing}}</h3>
                                            
                                        </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card text-light mb-3 border border-light" style="background-color:black">
                                    <div class="card-body d-flex">
                                        <div class="my-auto ms-5 me-3">
                                             <i class="fa-solid fa-clapperboard fa-2xl" style="color: #ff0000;"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title text-light">Movie upcomming</h6>
                                            <h3 class="card-text fw-bold text-light">{{$movie_upcomming}}</h3>
                                            
                                        </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card text-light mb-3 border border-light" style="background-color:black">
                                    <div class="card-body d-flex">
                                        <div class="my-auto ms-5 me-3">
                                            <i class="fa-solid fa-hourglass-end fa-2xl" style="color: #ff0000;"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title text-light">Movie ended</h6>
                                            <h3 class="card-text fw-bold text-light">{{$movie_ended}}</h3>
                                            
                                        </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card text-light mb-3 border border-light" style="background-color:black">
                                    <div class="card-body d-flex">
                                        <div class="my-auto ms-5 me-3">
                                            <i class="fa-solid fa-user fa-2xl" style="color: #ff0000;"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title text-light">Total user</h6>
                                            <h3 class="card-text fw-bold text-light">{{$total_user}}</h3>
                                            
                                        </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card text-light mb-3 border border-light" style="background-color:black">
                                    <div class="card-body d-flex">
                                        <div class="my-auto ms-5 me-3">
                                            <i class="fa-solid fa-money-bill fa-2xl" style="color: #ff0000;"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title text-light">Total income</h6>
                                            <h3 class="card-text fw-bold text-light">{{number_format($total_income)}} VND</h3>
                                            
                                        </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card text-light mb-3 border border-light" style="background-color:black">
                                    <div class="card-body d-flex">
                                        <div class="my-auto ms-5 me-3">
                                            <i class="fa-solid fa-ticket fa-2xl" style="color: #ff0000;"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title text-light">Tickets sold</h6>
                                            <h3 class="card-text fw-bold text-light">{{$tickets_sold}}</h3>
                                            
                                        </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="card text-light mb-3 border border-light" style="background-color:black">
                                    <div class="card-body d-flex">
                                        <div class="my-auto ms-5 me-3">
                                            <i class="fa-solid fa-money-check-dollar fa-2xl" style="color: #ff0000;"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title text-light">Month income: {{$month_year}}</h6>
                                            <h3 class="card-text fw-bold text-light">{{number_format($month_income)}} VND</h3>
                                            
                                        </div>
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div>
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels:<?php echo json_encode($labels) ?>,
                datasets: [{
                    label: 'Total income per month(VND)',
                    data: <?php echo json_encode($data) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

@endsection