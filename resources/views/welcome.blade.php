<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>CBS Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('/image.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Prevent horizontal scroll caused by transforms/images */
        html, body {
            overflow-x: hidden;
        }

        /* --- Animations --- */
        @keyframes zoom {
            0% { transform: scale(1); }
            50% { transform: scale(1.06); }
            100% { transform: scale(1); }
        }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(18px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* --- Hero specifics --- */
        .hero-img {
            will-change: transform;
            transform-origin: center;
            animation: zoom 20s ease-in-out infinite;
            display: block;
            max-width: none;    /* prevent image from shrinking/resizing leading to gaps */
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            -webkit-transform-style: preserve-3d;
            /* keep element contained (overflow-x hidden on body handles extra edges) */
        }

        .hero-content {
            opacity: 0;
            transform: translateY(18px);
            animation: fadeInUp 900ms ease-out 300ms forwards;
        }

        .hero-overlay-gradient {
            background: linear-gradient(120deg, rgba(99,102,241,0.12), rgba(59,130,246,0.08) 40%, rgba(139,92,246,0.06));
            background-size: 200% 200%;
            animation: gradientMove 12s ease infinite;
            mix-blend-mode: overlay;
            pointer-events: none;
        }

        /* Button micro-interactions */
        .btn-cta {
            transition: transform 220ms cubic-bezier(.2,.9,.2,1), box-shadow 220ms;
        }
        .btn-cta:focus {
            outline: none;
            box-shadow: 0 6px 30px rgba(99,102,241,0.18);
        }

        /* small utility for better text shadows on big headings */
        .text-glow {
            text-shadow: 0 6px 20px rgba(0,0,0,0.45);
        }

        /* ensure hero content stays readable on very small screens */
        @media (max-width: 420px) {
            .hero-title { font-size: 1.6rem; }
            .hero-sub { font-size: 0.95rem; }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">


    <!-- Hero Section -->
    <section id="hero" class="relative w-full h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image (fills screen) -->
        <img
            id="hero-img"
            src="{{ asset('hostimage (1).webp') }}"
            alt="Real estate background"
            class="absolute inset-0 w-full h-full object-cover z-0 hero-img"
            data-parallax
            />

        <!-- Animated subtle gradient overlay to spice it up -->
        <div class="absolute inset-0 hero-overlay-gradient z-5"></div>

        <!-- Dark overlay for contrast -->
        <div class="absolute inset-0 bg-black bg-opacity-35 z-10"></div>

        <!-- Content -->
        <div class="relative z-20 text-center px-6 max-w-4xl hero-content">
            <h1 class="hero-title text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-4 text-glow">
                Manage Your Cars Easily
            </h1>
            <p class="hero-sub text-lg sm:text-xl text-white mb-8 drop-shadow-md">
                Track Cars, bookings, and clients all in one place.
            </p>

            <!-- Buttons: stacked on small screens, inline on sm+ -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <!-- Primary: Login -->
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center bg-black border border-stone-700 text-white font-bold px-8 py-4 rounded-full text-lg shadow-xl btn-cta focus:outline-none">
                    Login Now
                </a>
            </div>

        </div>


        <!-- Decorative floating cards (subtle, accessible: hidden for very small screens) -->
        <div aria-hidden="true" class="hidden lg:block absolute -left-20 -bottom-10 transform rotate-6 opacity-60 z-0">
            <div class="w-56 h-36 rounded-2xl bg-white bg-opacity-6 backdrop-blur p-4 shadow-2xl"></div>
        </div>
    </section>



</body>
</html>
