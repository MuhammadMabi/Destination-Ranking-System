<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destination Ranking System</title>
    <link rel="icon" href="{{ asset('favicon-32x32.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-32x32.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;500;600&family=Syne:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('landingpage/templatemo-nexaverse.css') }}">
</head>

<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loader-ring"></div>
        <div class="loading-text">Preparing Your Experience...</div>
    </div>

    <!-- Ambient Background -->
    <div class="ambient-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <!-- Grid Overlay -->
    <div class="grid-overlay"></div>

    <!-- Main Container -->
    <div class="container">
        <div class="content-section active" id="introduction">

            <!-- Hero Banner -->
            <div class="logo-container">
                <img src="{{ asset('Logo-MaybeTech.png') }}" alt="destination-ranking-logo" class="img-responsive"
                    style="height: 60px;">
            </div>
            <div class="intro-hero">
                <div class="intro-hero-content">
                    <h1 class="intro-headline">Destination<br><span>Ranking</span><br>System</h1>
                    <div class="about-content">
                        <div class="about-text">
                            <p>A Decision Support System (DSS) that ranks tourist destinations using the Simple Additive
                                Weighting (SAW) method. It evaluates criteria like cost, access, affordability, and
                                safety,
                                assigning weights to each. Higher scores indicate better destinations, helping travelers
                                make data-driven decisions.</p>
                        </div>
                        {{-- <div class="about-text animate-on-scroll">
                            <p>A Decision Support System (DSS) that ranks tourist destinations using the Simple Additive
                                Weighting (SAW) method. It evaluates criteria like cost, access, affordability, and
                                safety, assigning weights to each. Higher scores indicate better destinations, helping
                                travelers make data-driven decisions.</p>
                        </div> --}}
                    </div>
                    <div class="intro-cta-group">
                        <button class="intro-cta-primary" onclick="window.location.href='/admin'">Login</button>
                    </div>
                </div>
                <div class="intro-hero-visual">
                    <div class="intro-floating-card card-1">
                        <span class="card-icon">🌍</span>
                        <span class="card-text">Discover Top Destinations</span>
                    </div>
                    <div class="intro-floating-card card-2">
                        <span class="card-icon">📊</span>
                        <span class="card-text">Data-Driven Decisions</span>
                    </div>
                    <div class="intro-floating-card card-3">
                        <span class="card-icon">⚡</span>
                        <span class="card-text">Fast & Insightful Rankings</span>
                    </div>
                    <div class="intro-orb"></div>
                </div>
            </div>
        </div>

        <footer class="footer" id="mainFooter">
            <p>
                @php
                    $currentYear = date('Y');
                @endphp
                &copy; {{ $currentYear }} Destination Ranking System. Developed by Muhammad Mabi Palaka (NIM
                231011401691). All rights reserved.
            </p>
        </footer>
    </div>
    <script src="{{ asset('landingpage/templatemo-nexa-scripts.js') }}"></script>
</body>

</html>
