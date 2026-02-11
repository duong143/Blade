<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
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
                <span class="brand-text font-weight-light">TravelLink Admin</span>
            </a>

            <div class="sidebar">
                <nav>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        {{-- User --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>User</p>
                            </a>
                        </li>

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
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings.footer') }}"
                                        class="nav-link {{ request()->is('admin/settings/footer') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cấu hình Footer</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.banners.index') }}"
                                        class="nav-link {{ request()->is('admin/banners*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Ảnh banner & Ưu đãi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.news.index') }}"
                                        class="nav-link {{ request()->is('admin/news*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tin tức du lịch</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
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

    {{-- AdminLTE --}}
    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>

    {{-- Bootstrap File Input --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-fileinput@5.5.2/js/fileinput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-fileinput@5.5.2/themes/fas/theme.min.js"></script>
@stack('scripts')

</body>

</html>