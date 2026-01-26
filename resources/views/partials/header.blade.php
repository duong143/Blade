<!-- HEADER -->
<header class="header bs-scope">
    <div class="container header-top">
        <div class="logo"><img src="{{ asset('images/Logo.png') }}" alt=""></div>

        <nav class="menu">
            <a href="/" class="menu-link" data-type="flight">Vé máy bay</a>
            <a href="/" class="menu-link" data-type="hotel">Khách sạn</a>
            <a href="/" class="menu-link" data-type="airport">Xe sân bay</a>
            <a href="#">Săn vé rẻ</a>
            <a href="#">Combo</a>
            <a href="#">Ưu đãi</a>
            <a href="#">Tin tức</a>
        </nav>

        <div class="hotline"> 📞1900 23 23 85 </div>

        @if(session('user'))
        <div class="header-user" id="userMenu">
            <img src="{{ asset('images/avatar.png') }}" class="avatar" id="avatarToggle">

            <div class="user-info">
                <div class="user-name">
                    {{ session('user.name') ?? session('user.phone') }}
                </div>
                <div class="user-point">1200 điểm</div>
            </div>

            <div class="user-dropdown" id="userDropdown">
                <button id="logoutBtn">Đăng xuất</button>
            </div>
        </div>
        @else
        <button class="btn-login" id="openLoginModal">Đăng nhập</button>
        @endif

    </div>
</header>