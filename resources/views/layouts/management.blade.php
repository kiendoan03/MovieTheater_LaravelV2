<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Site')</title>
    <link rel="icon" href="/favicon.ico">

    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />

    <style>
        :root {
            --bg: #0d0f14;
            --surface: #13161e;
            --card: #1a1e28;
            --border: rgba(255, 255, 255, 0.07);
            --border-h: rgba(255, 255, 255, 0.15);
            --text: #e8eaf0;
            --muted: #6b7280;
            --accent: #e8c96a;
            --accent-bg: rgba(232, 201, 106, 0.10);
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Sora', sans-serif;
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--card);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--border-h);
        }

        /* Header */
        .navbar {
            background-color: rgba(13, 15, 20, 0.8) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 0;
        }

        /* Sidebar */
        #sidebar {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.25rem;
            position: sticky;
            top: 100px;
            height: calc(100vh - 140px);
        }

        #sidebar .nav-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 1.5rem 0 0.75rem 0.75rem;
        }

        #sidebar .nav-link {
            color: var(--muted);
            font-size: 14px;
            font-weight: 500;
            padding: 10px 16px;
            border-radius: 10px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 4px;
        }

        #sidebar .nav-link:hover {
            color: var(--text);
            background: rgba(255, 255, 255, 0.03);
            transform: translateX(4px);
        }

        #sidebar .nav-link.active {
            color: var(--accent);
            background: var(--accent-bg);
            border: 1px solid rgba(232, 201, 106, 0.2);
        }

        #sidebar .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* DataTables Custom */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: var(--muted) !important;
            font-size: 13px;
            margin-top: 1rem;
        }

        table.dataTable {
            border-color: var(--border) !important;
        }

        table.dataTable thead th {
            border-bottom: 1px solid var(--border) !important;
        }

        .dropdown-menu {
            background: var(--card);
            border: 1px solid var(--border);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            border-radius: 12px;
            padding: 8px;
        }

        .dropdown-item {
            color: var(--text);
            border-radius: 8px;
            font-size: 14px;
            padding: 8px 12px;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--accent);
        }

        .user-avatar {
            border-radius: 50%;
            height: 38px;
            width: 38px;
            border: 2px solid var(--border);
            object-fit: cover;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .user-avatar:hover {
            border-color: var(--accent);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="/img/logo/logo.png" height="40">
            </a>

            <div class="dropdown">
                <img class="user-avatar" src="https://ui-avatars.com/api/?name=Admin&background=1a1e28&color=e8c96a" data-bs-toggle="dropdown">
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user me-2"></i> Profile</a></li>
                    <li>
                        <hr class="dropdown-divider border-secondary">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        <div class="row g-4">
            <div class="col-lg-3 d-none d-lg-block">
                <div id="sidebar">
                    <div class="nav-label">Core</div>
                    <a href="#" class="nav-link" data-id="dashboard"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>

                    <div class="nav-label">Management</div>
                    <a href="#" class="nav-link" data-id="staffs"><i class="fa-solid fa-user-shield"></i> Staffs</a>
                    <a href="#" class="nav-link" data-id="customers"><i class="fa-solid fa-users"></i> Customers</a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-link" data-id="genre"><i class="fa-solid fa-tags"></i> Film Genre</a>
                    <a href="{{ route('admin.movies.index') }}" class="nav-link" data-id="movies"><i class="fa-solid fa-film"></i></i> Movies</a>
                    <a href="{{ route('admin.rooms.index') }}" class="nav-link" data-id="rooms"><i class="fa-solid fa-door-open"></i> Rooms</a>
                    <a href="{{ route('admin.schedules.index') }}" class="nav-link" data-id="schedules"><i class="fa-solid fa-calendar-days"></i> Schedules</a>

                    <div class="nav-label">Data</div>
                    <a href="{{ route('admin.room_types.index') }}" class="nav-link" data-id="room-types"><i class="fa-solid fa-door-closed"></i> Room Types</a>
                    <a href="{{ route('admin.seat_types.index') }}" class="nav-link" data-id="seat-types"><i class="fa-solid fa-chair"></i> Seat Types</a>
                    <a href="{{ route('admin.actors.index') }}" class="nav-link" data-id="actors"><i class="fa-solid fa-masks-theater"></i> Actors</a>
                    <a href="{{ route('admin.directors.index') }}" class="nav-link" data-id="directors"><i class="fa-solid fa-clapperboard"></i> Directors</a>
                </div>
            </div>

            <div class="col-lg-9">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Khởi tạo DataTable với style dark
            if ($('#myTable').length) {
                $('#myTable').DataTable({
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search records..."
                    }
                });
            }

            // Xử lý Active Menu
            const links = document.querySelectorAll('#sidebar .nav-link');
            const activeId = localStorage.getItem('activeMenu');

            links.forEach(link => {
                // Set active khi click
                link.addEventListener('click', function() {
                    localStorage.setItem('activeMenu', this.dataset.id);
                });

                // Restore active state
                if (link.dataset.id === activeId) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>