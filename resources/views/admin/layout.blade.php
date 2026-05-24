<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin')</title>

    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-fileinput@5.5.2/css/fileinput.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form action="{{ route('admin.logout') }}" method="POST" class="mb-0">
                        @csrf
                        <button type="submit"
                            class="btn btn-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="/admin" class="brand-link">
                <span class="brand-text font-weight-light">TourLink Admin</span>
            </a>

            <div class="sidebar">
                <nav>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        {{-- User --}}
                        @can('users.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>User</p>
                            </a>
                        </li>
                        @endcan
                        {{-- Roles --}}
                        @can('roles.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}"
                                class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endcan
                        @php
                        $canSeeSystemMenu =
                        auth()->check() && (
                        auth()->user()->can('footer.view') ||
                        auth()->user()->can('banners.view') ||
                        auth()->user()->can('news.view')
                        );
                        @endphp

                        <li class="nav-item">
                            <a href="{{ route('admin.combos.index') }}"
                                class="nav-link {{ request()->is('admin/combos*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-suitcase-rolling"></i>
                                <p>Combos</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.discount-codes.index') }}"
                                class="nav-link {{ request()->is('admin/discount-codes*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-ticket-alt"></i>
                                <p>Mã giảm giá</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.combo-bookings.index') }}"
                                class="nav-link {{ request()->is('admin/combo-bookings*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>Đơn hàng combo</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.destinations.index') }}"
                                class="nav-link {{ request()->is('admin/destinations*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-map-marked-alt "></i>
                                <p>Điểm đến Hot</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.attractions.index') }}" class="nav-link {{ request()->is('admin/attractions*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-camera-retro"></i>
                                <p>Quản lý điểm đến</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.blogs.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Cẩm nang du lịch</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->is('admin/contacts*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>
                                    Liên hệ khách hàng
                                    {{-- Bonus: Hiện số tin nhắn chưa đọc (nếu muốn) --}}
                                    @php
                                    $unreadCount = \App\Models\Contact::where('is_read', false)->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                    <span class="right badge badge-danger">{{ $unreadCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>

                        @if(false)

                        <li class="nav-item">
                            <a href="{{ route('admin.airlines.index') }}"
                                class="nav-link {{ request()->is('admin/airlines*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-plane"></i>
                                <p>Hãng bay</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.airports.index') }}"
                                class="nav-link {{ request()->is('admin/airports*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-map-marker-alt"></i>
                                <p>Sân bay</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.flights.index') }}"
                                class="nav-link {{ request()->is('admin/flights*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-plane-departure"></i>
                                <p>Vé máy bay</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.flight-bookings.index') }}"
                                class="nav-link {{ request()->is('admin/flight-bookings*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>Đơn đặt vé máy bay</p>
                            </a>
                        </li>
                        @endif



                        @if ($canSeeSystemMenu)
                        <li class="nav-item has-treeview {{ request()->is('admin/settings*') || request()->is('admin/banners*') || request()->is('admin/news*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->is('admin/settings*') || request()->is('admin/banners*') || request()->is('admin/news*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    Cấu hình hệ thống
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                @can('footer.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings.footer') }}"
                                        class="nav-link {{ request()->is('admin/settings/footer') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cấu hình Footer</p>
                                    </a>
                                </li>
                                @endcan
                                <!-- @can('banners.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.banners.index') }}"
                                        class="nav-link {{ request()->is('admin/banners*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Ảnh banner & Ưu đãi</p>
                                    </a>
                                </li>
                                @endcan
                                @can('news.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.news.index') }}"
                                        class="nav-link {{ request()->is('admin/news*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tin tức du lịch</p>
                                    </a>
                                </li>
                                @endcan -->

                            </ul>
                        </li>
                        @endif
                    </ul>

                </nav>
            </div>
        </aside>

        {{-- Content --}}
        <div class="content-wrapper p-3">
            @yield('content')
        </div>

    </div>
    {{-- jQuery --}}
    <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>

    {{-- Bootstrap --}}
    <script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    </script>
    {{-- AdminLTE --}}
    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>

    {{-- Bootstrap File Input --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-fileinput@5.5.2/js/fileinput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-fileinput@5.5.2/themes/fas/theme.min.js"></script>
    @stack('scripts')

</body>

</html>