<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal BEM IT Del - Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Background Lingkaran Animasi */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            animation: float 10s infinite ease-in-out;
        }
        .c1 { width: 200px; height: 200px; top: 20px; left: 20px; animation-delay: 0s; }
        .c2 { width: 300px; height: 300px; top: 100px; right: -150px; animation-delay: 3s; }
        .c3 { width: 150px; height: 150px; bottom: 50px; left: -75px; animation-delay: 6s; }
        .c4 { width: 120px; height: 120px; top: 60%; left: 25%; animation-delay: 1.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Card Login */
        .login-card {
            position: relative;
            background: white;
            border-radius: 26px;
            padding: 40px 30px;
            width: 100%;
            max-width: 380px;
            z-index: 2;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Shake effect */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-10px); }
            40%, 80% { transform: translateX(10px); }
        }
        .shake {
            animation: shake 0.5s;
        }

        /* Logo */
        .logo-box {
            width: 60px;
            height: 60px;
            background: #2563eb;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 15px auto;
            box-shadow: 0 4px 10px rgba(37,99,235,0.4);
        }
        .logo-box span {
            color: white;
            font-weight: bold;
            font-size: 20px;
        }

        /* Judul */
        .welcome-title {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .welcome-subtitle {
            font-size: 15px;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 6px;
        }
        .welcome-description {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.4;
            margin-bottom: 25px;
        }

        /* Input */
        .form-control {
            height: 48px;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            padding-left: 40px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37,99,235,0.2);
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
        }
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 15px;
        }

        /* Tombol */
        .btn-login {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            height: 48px;
            font-size: 15px;
            margin-top: 5px;
            transition: transform 0.2s ease-in-out;
        }
        .btn-login:hover {
            transform: scale(1.02);
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        /* Footer */
        .footer-text {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 25px;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <!-- Background Lingkaran -->
    <div class="circle c1"></div>
    <div class="circle c2"></div>
    <div class="circle c3"></div>
    <div class="circle c4"></div>

    <!-- Card Login -->
    <div class="login-card" id="loginCard">
        <div class="logo-box"><span>DEL</span></div>
        <h2 class="welcome-title">Selamat Datang</h2>
        <p class="welcome-subtitle">Portal BEM IT Del</p>
        <p class="welcome-description">Masuk ke Sistem Informasi Badan<br>Eksekutif Mahasiswa Institut Teknologi Del</p>

        <form id="loginForm">
            <div class="mb-3 position-relative">
                <i class="fas fa-user input-icon"></i>
                <input type="text" id="username" class="form-control" placeholder="Masukkan username BEM Anda">
            </div>
            <div class="mb-3 position-relative">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="password" class="form-control" placeholder="Masukkan password BEM Anda">
                <button type="button" class="password-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Ingat saya</label>
                </div>
                <a href="#" class="forgot-password">Lupa password?</a>
            </div>
            <button type="submit" class="btn btn-login w-100"><i class="fas fa-sign-in-alt me-2"></i>Masuk ke Portal</button>
        </form>

        <div class="footer-text">
            © 2024 BEM Institut Teknologi Del<br>
            Jl. Sisingamangaraja, Sitoluama, Laguboti, Toba Samosir<br>
            Sumatera Utara 22381, Indonesia
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function showToast(message, type = "info") {
            let bgColor = "#2563eb";
            if (type === "success") bgColor = "#16a34a";
            if (type === "error") bgColor = "#dc2626";
            if (type === "warning") bgColor = "#f59e0b";

            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: bgColor,
                close: true,
                stopOnFocus: true
            }).showToast();
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const loginCard = document.getElementById('loginCard');
            const btn = document.querySelector('.btn-login');

            if (!username || !password) {
                showToast("Harap isi username dan password!", "warning");
                loginCard.classList.add("shake");
                setTimeout(() => loginCard.classList.remove("shake"), 500);
                return;
            }

            if (username === "carlo" && password === "carlo") {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                btn.disabled = true;

                setTimeout(() => {
                    showToast("Login berhasil! Selamat datang, Carlo.", "success");
                    btn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Masuk ke Portal';
                    btn.disabled = false;
                }, 1500);
            } else {
                showToast("Username atau password salah!", "error");
                loginCard.classList.add("shake");
                setTimeout(() => loginCard.classList.remove("shake"), 500);
            }
        });
    </script>
</body>
</html>
