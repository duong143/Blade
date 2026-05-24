<header class="header bs-scope">
    <div class="container header-top">
        <div class="logo">
            <a href="/" style="text-decoration: none; color: inherit;">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo">
            </a>
        </div>

        <button class="menu-toggle" id="menuToggleBtn" aria-label="Toggle Navigation">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <nav class="menu" id="headerMenuNav">
            @if(false)
            <a href="/" class="menu-link" data-type="flight">Vé máy bay</a>
            <a href="/" class="menu-link" data-type="hotel">Khách sạn</a>
            <a href="/" class="menu-link" data-type="airport">Xe sân bay</a>
            @endif
            <a href="{{ route('combo.index') }}" class="menu-link">Combo</a>
            <a href="{{ route('frontend.destinations') }}" class="menu-link">Điểm đến hot</a> 
            <a href="{{ route('blogs.index') }}" class="menu-link">Cẩm nang du lịch</a>
            <a href="{{ route('contact.index') }}" class="menu-link">Liên hệ</a>
            
            <div class="hotline mobile-only">📞 0387542417</div>
        </nav>

        <div class="hotline desktop-only"> 📞0387542417 </div>

        <div class="header-actions">
            @if(session('user'))
            <div class="header-user" id="userMenu">
                <img src="{{ asset('images/avatar.png') }}" class="avatar" id="avatarToggle" alt="Avatar">

                <div class="user-info">
                    <div class="user-name">
                        {{ session('user.name') ?? session('user.phone') }}
                    </div>
                    <div class="user-point">1200 điểm</div>
                </div>

                <div class="user-dropdown" id="userDropdown">
                    <button id="changePasswordBtn" class="dropdown-item">Đổi mật khẩu</button>
                    <button id="logoutBtn" class="dropdown-item logout">Đăng xuất</button>
                </div>
            </div>
            @else
            <button class="btn-login" id="openLoginModal">Đăng nhập</button>
            @endif
        </div>

        @include('partials.change-password-modal')
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('menuToggleBtn');
        const menuNav = document.getElementById('headerMenuNav');

        if (toggleBtn && menuNav) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleBtn.classList.toggle('is-active');
                menuNav.classList.toggle('is-open');
            });

            // Click ra ngoài thì tự động đóng menu mobile lại
            document.addEventListener('click', function(e) {
                if (!menuNav.contains(e.target) && !toggleBtn.contains(e.target)) {
                    toggleBtn.classList.remove('is-active');
                    menuNav.classList.remove('is-open');
                }
            });
        }
    });
</script>