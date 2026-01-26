// hiển thị nút đăng xuất
document.addEventListener("DOMContentLoaded", function () {
        const avatar = document.getElementById("avatarToggle");
        const dropdown = document.getElementById("userDropdown");

        if (!avatar || !dropdown) return;

        avatar.addEventListener("click", function (e) {
            e.stopPropagation();
            dropdown.classList.toggle("show");
        });

        document.addEventListener("click", function () {
            dropdown.classList.remove("show");
        });
    });