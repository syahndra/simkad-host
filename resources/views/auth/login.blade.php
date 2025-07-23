<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/images/Lambang_Kabupaten_Brebes.png') }}" type="image/x-icon" />
    <title>Login | Sistem Monitoring Kios Adminduk Desa</title>

    <!-- ========== All CSS files linkup ========= -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lineicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/materialdesignicons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
</head>

<body>
    <!-- ======== Preloader =========== -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <!-- ======== Preloader =========== -->



    <!-- ======== main-wrapper start =========== -->
    <main class="main-login">

        <!-- ========== signin-section start ========== -->
        <section class="signin-section">
            <div class="container-fluid">
                <!-- ========== title-wrapper start ========== -->
                <div class="title-wrapper pt-30">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="title">
                                <h2>Login</h2>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- ========== title-wrapper end ========== -->

                <div class="row g-0 auth-row">
                    <div class="col-lg-6">
                        <div class="auth-cover-wrapper bg-primary-100">
                            <div class="auth-cover">
                                <div class="title text-center">
                                    <h1 class="text-primary mb-10">Sistem Monitoring Kios Adminduk Desa</h1>
                                    <p class="text-medium">
                                        Silahkan masuk ke akun SIMKAD untuk melanjutkan.
                                    </p>
                                </div>
                                <div class="cover-image">
                                    <img src="{{ asset('assets/images/auth/signin-image.svg') }}" alt="" />
                                </div>
                                <div class="shape-image">
                                    <img src="{{ asset('assets/images/auth/shape.svg') }}" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-lg-6">
                        <div class="signin-wrapper">
                            <div class="form-wrapper">
                                <div id="login-text">
                                    <h6 class="mb-15">Form Login</h6>
                                    <p class="text-sm mb-25">
                                        Silahkan masukan email dan password terdaftar.
                                    </p>
                                </div>
                                <form method="POST" action="/login">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-style-1">
                                                <label>Email</label>
                                                <input type="email" name="email" placeholder="Email" />
                                            </div>
                                        </div>
                                        <!-- end col -->
                                        <div class="col-12">
                                            <div class="input-style-1">
                                                <label>Password</label>
                                                <input type="password" name="password" id="password"
                                                placeholder="Password" />
                                            <span class="toggle-password" data-target="password"
                                                style="cursor:pointer; position:absolute; right:10px; top:35px;">👁</span>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                            <div class="text-start text-md-end text-lg-start text-xxl-end mb-30">
                                                <a href="#" id="show-reset-form" class="hover-underline">
                                                    Lupa password?
                                                </a>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                        <div class="col-12">
                                            <div class="button-group d-flex justify-content-center flex-wrap">
                                                <button class="main-btn primary-btn btn-hover w-100 text-center"
                                                    type="submit">
                                                    Sign In
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end row -->
                                </form>

                                <!-- Form Lupa Password -->
                                <div id="forgot-password-form" style="display:none;">
                                    <h6 class="mb-15">Reset Password</h6>
                                    <form id="form-reset-password">
                                        <div class="input-style-1">
                                            <label>Email</label>
                                            <input type="email" name="email" placeholder="Email" required />
                                        </div>

                                        <div class="input-style-1 d-flex">
                                            <div style="flex: 1; margin-right: 5px;">
                                                <label>Kode Verifikasi</label>
                                                <input type="text" name="otp_code" placeholder="Kode OTP" />
                                            </div>
                                            <button type="button" id="send-code-btn"
                                                class="main-btn primary-btn btn-hover mt-30">Kirim Kode</button>
                                        </div>

                                        <div class="input-style-1 position-relative">
                                            <label>Password Baru</label>
                                            <input type="password" name="password" id="password"
                                                placeholder="Password Baru" />
                                            <span class="toggle-password" data-target="password"
                                                style="cursor:pointer; position:absolute; right:10px; top:35px;">👁</span>
                                        </div>

                                        <div class="input-style-1 position-relative">
                                            <label>Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation"
                                                id="password_confirmation" placeholder="Konfirmasi Password" />
                                            <span class="toggle-password" data-target="password_confirmation"
                                                style="cursor:pointer; position:absolute; right:10px; top:35px;">👁</span>
                                        </div>

                                        <div class="button-group d-flex justify-content-center flex-wrap">
                                            <button type="submit" class="main-btn primary-btn btn-hover w-100">Reset
                                                Password</button>
                                        </div>
                                    </form>
                                    <div class="text-center mt-3">
                                        <a href="#" id="back-to-login" class="text-sm hover-underline">←
                                            Kembali ke Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
        </section>
        <!-- ========== signin-section end ========== -->

        <!-- ========== footer start =========== -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 order-last order-md-first">
                        <div class="copyright text-center text-md-start">
                            <p class="text-sm">
                                Designed and Developed by
                                <a href="https://plainadmin.com" rel="nofollow" target="_blank">
                                    PlainAdmin
                                </a>
                            </p>
                        </div>
                    </div>
                    <!-- end col-->
                    <div class="col-md-6">
                        <div class="terms d-flex justify-content-center justify-content-md-end">
                            <a href="#0" class="text-sm">Term & Conditions</a>
                            <a href="#0" class="text-sm ml-15">Privacy & Policy</a>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </footer>
        <!-- ========== footer end =========== -->
    </main>
    <!-- ======== main-wrapper end =========== -->

    <!-- ========= All Javascript files linkup ======== -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/dynamic-pie-chart.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/fullcalendar.js') }}"></script>
    <script src="{{ asset('assets/js/jvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/world-merc.js') }}"></script>
    <script src="{{ asset('assets/js/polyfill.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', () => {
                const target = document.getElementById(btn.dataset.target);
                target.type = (target.type === 'password') ? 'text' : 'password';
            });
        });

        // Ganti form login -> reset password
        document.getElementById('show-reset-form').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('form[action="/login"]').style.display = 'none';
            document.getElementById('login-text').style.display = 'none';
            document.getElementById('forgot-password-form').style.display = 'block';
        });

        // Kirim kode verifikasi
        document.getElementById('send-code-btn').addEventListener('click', function() {
            const email = document.querySelector('#forgot-password-form input[name="email"]').value;
            if (!email) return alert('Email harus diisi');

            fetch('/send-reset-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(res => res.json())
                .then(data => alert(data.message))
                .catch(() => alert('Terjadi kesalahan'));
        });

        // Submit reset password
        document.getElementById('form-reset-password').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch('/submit-reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.status) {
                        location.reload(); // kembali ke login
                    }
                })
                .catch(() => alert('Terjadi kesalahan'));
        });

        // back to login
        document.getElementById('back-to-login').addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah halaman reload saat klik link
            document.querySelector('form[action="/login"]').style.display = 'block'; // Tampilkan form login
            document.getElementById('login-text').style.display = 'block'; // Tampilkan teks Form Login & deskripsi
            document.getElementById('forgot-password-form').style.display = 'none'; // Sembunyikan form reset
        });
    </script>
</body>

</html>

