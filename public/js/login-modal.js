console.log("LOGIN MODAL JS LOADED");

document.addEventListener("DOMContentLoaded", function () {

    const loginModal = document.getElementById("loginModal");
    const registerModal = document.getElementById("registerModal");

    document.addEventListener("click", function (e) {

        // ===== OPEN LOGIN =====
        if (e.target.id === "openLoginModal") {
            loginModal?.classList.add("active");
        }

        // ===== CLOSE LOGIN =====
        if (e.target.id === "closeLoginModal") {
            loginModal?.classList.remove("active");
        }
        // ===== CLOSE REGISTER =====
        if (e.target.id === "closeRegisterModal") {
            registerModal?.classList.remove("active");
        }

        // ===== SWITCH TO REGISTER =====
        if (e.target.id === "switchToRegister") {
            e.preventDefault();
            loginModal?.classList.remove("active");
            registerModal?.classList.add("active");
        }

        // ===== SWITCH TO LOGIN =====
        if (e.target.id === "switchToLogin") {
            e.preventDefault();
            registerModal?.classList.remove("active");
            loginModal?.classList.add("active");
        }

        // ===== LOGOUT =====
        if (e.target.id === "logoutBtn") {
            fetch("/logout", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .content
                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) location.reload();
                });
        }

        // ===== CHANGE PASSWORD =====
        if (e.target.id === "changePasswordBtn") {
            document.getElementById("changePasswordModal")
                ?.classList.add("active");
        }
        // ===== CLOSE CHANGE PASSWORD MODAL =====
        if (e.target.id === "closeChangePasswordModal") {
            document
                .getElementById("changePasswordModal")
                ?.classList.remove("active");
        }


        // ===== SUBMIT CHANGE PASSWORD =====
        if (e.target.id === "submitChangePassword") {

            const current = document.getElementById("currentPassword")?.value.trim();
            const newPass = document.getElementById("newPassword")?.value.trim();
            const confirm = document.getElementById("confirmNewPassword")?.value.trim();

            if (!current || !newPass || !confirm) {
                alert("Vui lòng nhập đầy đủ thông tin");
                return;
            }

            if (newPass !== confirm) {
                alert("Mật khẩu xác nhận không khớp");
                return;
            }

            fetch("/change-password", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .content
                },
                body: JSON.stringify({
                    current_password: current,
                    new_password: newPass,
                    new_password_confirmation: confirm
                })
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        alert(res.message);

                        // reset form
                        document.getElementById("currentPassword").value = "";
                        document.getElementById("newPassword").value = "";
                        document.getElementById("confirmNewPassword").value = "";

                        // đóng modal
                        document
                            .getElementById("changePasswordModal")
                            ?.classList.remove("active");
                    } else {
                        alert(res.message || "Đổi mật khẩu thất bại");
                    }
                })
                .catch(() => alert("Có lỗi xảy ra"));
        }


    });

    // ===== LOGIN SUBMIT =====
    document.querySelector("#loginModal .login-submit")
        ?.addEventListener("click", (e) => {

            const phone = document.querySelector("#loginModal .phone-input input")?.value.trim();
            const password = document.querySelector("#loginModal .password-input input")?.value.trim();

            if (!phone || !password) {
                alert("Vui lòng nhập đủ thông tin");
                return;
            }

            fetch("/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .content
                },
                body: JSON.stringify({ phone, password })
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) location.reload();
                    else alert(res.message);
                });
        });
    // ===== REGISTER SUBMIT =====
    document.querySelector("#registerModal .login-submit")
        ?.addEventListener("click", (e) => {

            const phone = document.querySelector(
                "#registerModal .phone-input input"
            )?.value.trim();

            const name = document.querySelector(
                "#registerModal .name-input input"
            )?.value.trim();

            const password = document.querySelector(
                "#registerModal .password-input input"
            )?.value.trim();

            if (!phone || !name || !password) {
                alert("Vui lòng nhập đủ thông tin");
                return;
            }

            fetch("/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .content
                },
                body: JSON.stringify({ phone, name, password })
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        alert("Đăng ký thành công, vui lòng đăng nhập");
                        registerModal?.classList.remove("active");
                        loginModal?.classList.add("active");
                        location.reload();
                    } else {
                        alert(res.message || "Đăng ký thất bại");
                    }
                })
                .catch(() => alert("Có lỗi xảy ra"));
        });


});
