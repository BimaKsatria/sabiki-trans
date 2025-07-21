<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sabiki Trans</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-1.png') }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    <div class="container-fluid topbar bg-secondary d-none d-xl-block w-100">
        <div class="container">
            <div class="row gx-0 align-items-center" style="height: 45px;">
                <div class="col-lg-6 text-center text-lg-start mb-lg-0">
                    <div class="d-flex flex-wrap align-items-center small">
                        <a href="https://maps.app.goo.gl/QSUdn1N4JLMed3C87" class="text-muted me-4"><i
                                class="fas fa-map-marker-alt text-primary me-2"></i>Jl. Panglima Sudirman Gg. XI No.
                            74</a>
                        <div class="d-flex gap-4">
                            <a href="tel:+6285749264940" class="text-muted">
                                <i class="fas fa-phone-alt text-primary me-2"></i>+6285749264940
                            </a>
                            <a href="mailto:sabikitrans@gmail.com" class="text-muted">
                                <i class="fas fa-envelope text-primary me-2"></i>sabikitrans@gmail.com
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center text-lg-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <a href="https://www.tiktok.com/@sabikitrans"
                            class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-tiktok"></i></a>
                        <a href="https://www.instagram.com/sabikitransindonesia/"
                            class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid nav-bar sticky-top px-0 px-lg-4 py-2 py-lg-0">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a href="" class="navbar-brand p-0">

                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto py-0">
                        <a href="#home" class="nav-item nav-link active">Beranda</a>
                        <a href="#features" class="nav-item nav-link">Fitur</a>
                        <a href="#about" class="nav-item nav-link">Tentang Kami</a>
                        <a href="#services" class="nav-item nav-link">Layanan</a>
                        <a href="#contact" class="nav-item nav-link">Kontak</a>
                    </div>
                    <a href="#" class="btn btn-primary rounded-pill py-2 px-4">Mulai Sekarang</a>

                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar & Hero End -->

    <!-- Carousel Start -->
    <div class="header-carousel">
        <div id="carouselId" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
            <ol class="carousel-indicators">
                <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active"></li>
                <li data-bs-target="#carouselId" data-bs-slide-to="1"></li>
            </ol>
            <div class="carousel-inner" role="listbox">

                <!-- Slide 1: Download App -->
                <div class="carousel-item active">
                    <img src="{{ asset('images/carousel-2.jpg') }}" class="img-fluid w-100" alt="Download App">
                    <div class="carousel-caption d-flex align-items-center h-100">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div
                                        class="bg-dark bg-opacity-75 rounded p-5 shadow-lg text-center animate__animated animate__fadeInLeft">
                                        <h3 class="text-white fw-bold mb-4"><i class="fas fa-mobile-alt me-2"></i>
                                            Unduh Aplikasi Sabiki</h3>
                                        <p class="text-white mb-4 fs-6">
                                            <i class="fas fa-check-circle text-success me-2"></i> Booking cepat &
                                            mudah<br>
                                            <i class="fas fa-check-circle text-success me-2"></i> Promo menarik setiap
                                            bulan
                                        </p>
                                        <div class="d-flex gap-3 justify-content-center">
                                            <a href="https://6e3d-2001-448a-5102-e65-9dad-16d8-ecbb-f8ae.ngrok-free.app/files/app-release.apk"
                                                class="btn btn-outline-light rounded-pill px-4 d-flex align-items-center">
                                                <i class="fab fa-google-play me-2"></i> <span>Android</span>
                                            </a>
                                            <a class="btn btn-outline-light rounded-pill px-4 d-flex align-items-center disabled"
                                                aria-disabled="true" tabindex="-1">
                                                <i class="fab fa-apple me-2"></i> <span>IOS</span>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 d-none d-lg-block animate__animated animate__fadeInRight">
                                    <h1 class="display-5 fw-bold text-white">Rental Lebih Mudah<br> Dengan Aplikasi
                                        Kami</h1>
                                    <p class="lead text-white-50">Pesan kapan saja, di mana saja, lebih simpel.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2: App Features -->
                <div class="carousel-item" id="home">
                    <img src="{{ asset('images/carousel-1.jpg') }}" class="img-fluid w-100" alt="App Features">
                    <div class="carousel-caption d-flex align-items-center h-100">
                        <div class="container">
                            <div class="row align-items-center">
                                <!-- Judul Besar di Kiri -->
                                <div class="col-lg-6 animate__animated animate__fadeInLeft">
                                    <div class="text-center text-lg-start">
                                        <h1 class="display-5 fw-bold text-white">Semua Kemudahan<br> Dalam Genggaman
                                        </h1>
                                        <p class="lead text-white-50">Pesan cepat, bayar mudah, layanan aman.</p>
                                    </div>
                                </div>

                                <!-- Card Fitur di Kanan -->
                                <div class="col-lg-6 animate__animated animate__fadeInRight">
                                    <div class="bg-dark bg-opacity-75 rounded p-5 shadow-lg text-center">
                                        <h3 class="text-white fw-bold mb-4">
                                            <i class="fas fa-star text-warning me-2"></i> Fitur Aplikasi Kami
                                        </h3>
                                        <div class="text-white-50 mb-4">
                                            <div class="d-flex justify-content-center align-items-center mb-3">
                                                <i class="fas fa-clock fa-2x text-primary me-3"></i>
                                                <div class="text-start">
                                                    <h5 class="mb-1 text-white">Booking 24 Jam</h5>
                                                    <p class="small mb-0">Pesan kendaraan kapan saja tanpa batas waktu.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center mb-3">
                                                <i class="fas fa-credit-card fa-2x text-primary me-3"></i>
                                                <div class="text-start">
                                                    <h5 class="mb-1 text-white">Pembayaran Fleksibel</h5>
                                                    <p class="small mb-0">Transfer bank, e-wallet, hingga bayar di
                                                        tempat.</p>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center mb-3">
                                                <i class="fas fa-headset fa-2x text-primary me-3"></i>
                                                <div class="text-start">
                                                    <h5 class="mb-1 text-white">Bantuan 24/7</h5>
                                                    <p class="small mb-0">Layanan pelanggan selalu siap membantu Anda.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="https://6e3d-2001-448a-5102-e65-9dad-16d8-ecbb-f8ae.ngrok-free.app/files/app-release.apk" class="btn btn-outline-light rounded-pill w-75 py-2">
                                            <i class="fas fa-download me-2"></i> Download Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Features Start -->
    <div class="container-fluid feature py-5" id="features">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-5 text-capitalize mb-3">Fitur <span class="text-primary">Unggulan</span></h1>
                <p class="mb-0">Sabiki Trans Rental memberikan kemudahan bagi Anda yang ingin menyewa mobil dengan
                    aman, nyaman, dan praktis langsung dari aplikasi kami. Nikmati perjalanan lebih bebas dengan harga
                    yang bersaing dan layanan terpercaya.</p>
            </div>
            <div class="row g-4 align-items-center">
                <div class="col-xl-4">
                    <div class="row gy-4 gx-0">
                        <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <span class="fa fa-trophy fa-2x"></span>
                                </div>
                                <div class="ms-4">
                                    <h5 class="mb-3">Layanan Berkualitas</h5>
                                    <p class="mb-0">Kami memberikan pelayanan terbaik dengan kendaraan yang selalu
                                        terawat dan siap pakai.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 wow fadeInUp" data-wow-delay="0.3s">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <span class="fa fa-road fa-2x"></span>
                                </div>
                                <div class="ms-4">
                                    <h5 class="mb-3">Kebebasan Berkendara</h5>
                                    <p class="mb-0">Anda bebas menentukan tujuan dan waktu perjalanan sesuai
                                        keinginan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-4 wow fadeInUp" data-wow-delay="0.2s">
                    <img src="{{ asset('images/features-img.png') }}" class="img-fluid w-100"
                        style="object-fit: cover;" alt="Img">
                </div>
                <div class="col-xl-4">
                    <div class="row gy-4 gx-0">
                        <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="feature-item justify-content-end">
                                <div class="text-end me-4">
                                    <h5 class="mb-3">Harga Terjangkau</h5>
                                    <p class="mb-0">Harga rental yang kompetitif dengan banyak promo menarik khusus
                                        pengguna aplikasi.</p>
                                </div>
                                <div class="feature-icon">
                                    <span class="fa fa-tag fa-2x"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 wow fadeInUp" data-wow-delay="0.3s">
                            <div class="feature-item justify-content-end">
                                <div class="text-end me-4">
                                    <h5 class="mb-3">Akses Mudah Via Aplikasi</h5>
                                    <p class="mb-0">Pemesanan cepat, kapan saja, di mana saja hanya dengan aplikasi
                                        Sabiki Trans.</p>
                                </div>
                                <div class="feature-icon">
                                    <span class="fa fa-mobile-alt fa-2x"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->

    <!-- About Start -->
    <div class="container-fluid overflow-hidden about py-5" id="about">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-xl-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="about-item">
                        <div class="pb-5">
                            <h1 class="display-5 text-capitalize">Tentang <span class="text-primary">Sabiki
                                    Trans</span></h1>
                            <p class="mb-0">Sabiki Trans Rental hadir memberikan solusi rental mobil yang lebih
                                mudah, aman, dan nyaman. Dengan aplikasi kami, Anda dapat melakukan pemesanan kapan
                                saja, menikmati fleksibilitas perjalanan, dan mendapatkan harga yang kompetitif.</p>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="about-item-inner border p-4">
                                    <div class="about-icon mb-4">
                                        <img src="{{ asset('images/about-icon-1.png') }}" class="img-fluid w-50 h-50"
                                            alt="Icon">
                                    </div>
                                    <h5 class="mb-3">Visi Kami</h5>
                                    <p class="mb-0">Menjadi solusi utama rental kendaraan berbasis teknologi di
                                        Indonesia.</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="about-item-inner border p-4">
                                    <div class="about-icon mb-4">
                                        <img src="{{ asset('images/about-icon-2.png') }}" class="img-fluid w-50 h-50"
                                            alt="Icon">

                                    </div>
                                    <h5 class="mb-3">Misi Kami</h5>
                                    <p class="mb-0">Memberikan layanan berkualitas, aman, nyaman, dan inovatif bagi
                                        pelanggan.</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-item my-4">Sabiki Trans berkomitmen menghadirkan pengalaman sewa kendaraan yang
                            bebas ribet dan nyaman. Kami selalu menjaga kualitas armada serta keamanan Anda selama
                            perjalanan.</p>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="text-center rounded bg-secondary p-4">
                                    <h1 class="display-6 text-white">5+</h1>
                                    <h5 class="text-light mb-0">Tahun Pengalaman</h5>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="rounded">
                                    <p class="mb-2"><i class="fa fa-check-circle text-primary me-1"></i> Pemesanan
                                        aplikasi praktis</p>
                                    <p class="mb-2"><i class="fa fa-check-circle text-primary me-1"></i> Armada aman
                                        & nyaman</p>
                                    <p class="mb-2"><i class="fa fa-check-circle text-primary me-1"></i> Kebebasan
                                        destinasi</p>
                                    <p class="mb-0"><i class="fa fa-check-circle text-primary me-1"></i> Harga
                                        kompetitif & promo</p>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/attachment-img.jpg') }}"
                                        class="img-fluid rounded-circle border border-4 border-secondary"
                                        style="width: 100px; height: 100px;" alt="Image">
                                    <div class="ms-4">
                                        <h4>Fikry</h4>
                                        <p class="mb-0">Founder Sabiki Trans</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 wow fadeInRight" data-wow-delay="0.2s">
                    <div class="about-img">
                        <div class="img-1">
                            <img src="{{ asset('images/about-img.jpg') }}" class="img-fluid rounded h-100 w-100"
                                alt="">
                        </div>
                        <div class="img-2">
                            <img src="{{ asset('images/about-img-1.jpg') }}" class="img-fluid rounded w-100"
                                alt="">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Fact Counter -->
    <div class="container-fluid counter bg-secondary py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="counter-item text-center">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-thumbs-up fa-2x"></i>
                        </div>
                        <div class="counter-counting my-3">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">1000</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                        <h4 class="text-white mb-0">Klien Puas</h4>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="counter-item text-center">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-car-alt fa-2x"></i>
                        </div>
                        <div class="counter-counting my-3">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">75</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                        <h4 class="text-white mb-0">Jumlah Armada</h4>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="counter-item text-center">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                        <div class="counter-counting my-3">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">20</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                        <h4 class="text-white mb-0">Kota Layanan</h4>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="counter-item text-center">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="counter-counting my-3">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">500.000</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                        <h4 class="text-white mb-0">Kilometer Perjalanan</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fact Counter End -->

    <!-- Services Start -->
    <div class="container-fluid service py-5" id="services">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-5 text-capitalize mb-3">Layanan <span class="text-primary">Sabiki Trans</span></h1>
                <p class="mb-0">Kami menyediakan layanan rental mobil terpercaya dengan fitur unggulan aplikasi,
                    keamanan prioritas, fleksibilitas perjalanan, dan tarif yang bersahabat.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item p-4">
                        <div class="service-icon mb-4">
                            <i class="fa fa-phone-alt fa-2x"></i>
                        </div>
                        <h5 class="mb-3">Reservasi Mudah</h5>
                        <p class="mb-0">Pesan kendaraan dengan cepat melalui aplikasi atau telepon tanpa ribet.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item p-4">
                        <div class="service-icon mb-4">
                            <i class="fa fa-money-bill-alt fa-2x"></i>
                        </div>
                        <h5 class="mb-3">Harga Bersahabat</h5>
                        <p class="mb-0">Harga kompetitif dengan berbagai pilihan paket menarik bagi pelanggan.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item p-4">
                        <div class="service-icon mb-4">
                            <i class="fa fa-road fa-2x"></i>
                        </div>
                        <h5 class="mb-3">Rental Bebas Tujuan</h5>
                        <p class="mb-0">Nikmati kebebasan berkendara ke berbagai kota sesuai kebutuhan Anda.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item p-4">
                        <div class="service-icon mb-4">
                            <i class="fa fa-umbrella fa-2x"></i>
                        </div>
                        <h5 class="mb-3">Keamanan Terjamin</h5>
                        <p class="mb-0">Kami menjamin keamanan armada dan memberikan asuransi perjalanan.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item p-4">
                        <div class="service-icon mb-4">
                            <i class="fa fa-building fa-2x"></i>
                        </div>
                        <h5 class="mb-3">Jangkauan Luas</h5>
                        <p class="mb-0">Telah melayani berbagai kota besar dengan layanan profesional.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item p-4">
                        <div class="service-icon mb-4">
                            <i class="fa fa-car-alt fa-2x"></i>
                        </div>
                        <h5 class="mb-3">Pilihan Armada Lengkap</h5>
                        <p class="mb-0">Beragam tipe kendaraan siap memenuhi kebutuhan Anda, dari city car hingga
                            SUV.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Services End -->

    <!-- Footer Start -->
    <div class="container-fluid footer bg-dark text-white py-5 wow fadeIn" data-wow-delay="0.2s">
        <div class="container">
            <div class="row justify-content-center text-center g-5">
                <!-- About -->
                <div class="col-md-4">
                    <h4 class="text-white mb-4">Tentang Kami</h4>
                    <p class="mb-0" style="text-align: justify;">
                        Sabiki Trans Rental adalah layanan penyewaan mobil berbasis aplikasi yang memprioritaskan
                        kemudahan, keamanan, dan kenyamanan Anda. Kami hadir memberikan kebebasan berkendara dengan
                        harga yang kompetitif dan layanan terpercaya.
                    </p>
                </div>

                <!-- Operational Hours -->
                <div class="col-md-3">
                    <h4 class="text-white mb-4">Jam Operasional</h4>
                    <p class="mb-1">24 Jam / 7 Hari</p>
                    <p class="mb-0">Tanpa Hari Libur</p>
                </div>

                <!-- Contact -->
                <div class="col-md-4" id="contact">
                    <h4 class="text-white mb-4">Kontak</h4>
                    <p class="mb-1"><i class="fa fa-map-marker-alt me-2"></i> Jl. Panglima Sudirman Gg. XI No. 74
                    </p>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i> <a href="mailto:sabikitrans@gmail.com"
                            class="text-white">sabikitrans@gmail.com</a></p>
                    <p class="mb-4"><i class="fas fa-phone me-2"></i> <a href="tel:+6285749264940"
                            class="text-white">+62 857 4926 4940</a></p>
                    <div class="d-flex justify-content-center pt-2">
                        <a class="btn btn-outline-light btn-sm-square rounded-circle me-2"
                            href="https://www.tiktok.com/@sabikitrans">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a class="btn btn-outline-light btn-sm-square rounded-circle me-2"
                            href="https://www.instagram.com/sabikitransindonesia/">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->



    <div class="container-fluid bg-dark copyright py-3">
        <div class="container">
            <p class="mb-0 text-white small text-start">
                &copy; Sabiki Trans Indonesia. All rights reserved.
            </p>
        </div>
    </div>


    <!-- Back to Top -->
    <a href="#" class="btn btn-secondary btn-lg-square rounded-circle back-to-top"><i
            class="fa fa-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <script>
        // Smooth scrolling with jQuery
        $(document).ready(function() {
            // Add smooth scrolling to all links
            $("a").on('click', function(event) {
                // Make sure this.hash has a value before overriding default behavior
                if (this.hash !== "" && $(this.hash).length) {
                    // Prevent default anchor click behavior
                    event.preventDefault();

                    // Store hash
                    var hash = this.hash;

                    // Using jQuery's animate() method for smooth page scroll
                    // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top - 70
                    }, 800, 'swing', function() {
                        // Add hash (#) to URL when done scrolling (default click behavior)
                        window.location.hash = hash;
                    });
                } // End if
            });

            // Update active nav link on scroll
            $(window).scroll(function() {
                var scrollDistance = $(window).scrollTop() + 100;

                // Assign active class to nav links while scrolling
                $('section').each(function(i) {
                    if ($(this).position().top <= scrollDistance) {
                        $('.navbar-nav a.active').removeClass('active');
                        $('.navbar-nav a').eq(i).addClass('active');
                    }
                });
            }).scroll();
        });
    </script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

</body>

</html>
