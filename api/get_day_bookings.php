<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSPEED - Sim Racing Centar</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter - Preload critical fonts for better performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"></noscript>
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Flatpickr CSS for Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* Prilagođeni stilovi */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #111827; /* Tailwind gray-900 */
            color: #F3F4F6; /* Tailwind gray-100 */
        }
        
        /* Stil za gradijent tekst */
        .gradient-text {
            background: linear-gradient(to right, #F97316, #EF4444);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        /* Stil za aktivni link u navigaciji */
        .nav-link.active {
            color: #F97316; /* Tailwind orange-500 */
            border-bottom: 2px solid #F97316;
        }
        
        /* Stil za header pri skrolovanju */
        .header-scrolled {
            background-color: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(10px);
        }

        /* Sakrivanje sekcija */
        .page-section {
            display: none;
        }
        .page-section.active {
            display: block;
        }

        /* Stilovi za filtere */
        .filter-btn {
            display: block;
            width: 100%;
            text-align: left;
            padding: 8px 12px;
            border-radius: 6px;
            color: #D1D5DB; /* gray-300 */
            transition: background-color 0.2s, color 0.2s;
        }
        .filter-btn:hover {
            background-color: #374151; /* gray-700 */
            color: #FFF;
        }
        .filter-btn.active {
            background-color: #F97316; /* orange-500 */
            color: #FFF;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(249, 115, 22, 0.4); /* Dodatna senka za aktivni filter */
        }

        /* Novi stilovi za dugmad za datum i vreme */
        .selection-btn {
            background-color: #1F2937; /* gray-800 */
            border: 1px solid #374151; /* gray-700 */
            color: #F3F4F6; /* gray-100 */
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(0);
        }
        .selection-btn:hover:not(:disabled) {
            background-color: #2D3748; /* gray-700 hover */
            border-color: #F97316; /* orange-500 */
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }
        .selection-btn.active {
            background-color: #F97316; /* orange-500 */
            border-color: #F97316;
            color: #FFF;
            font-weight: 700;
            transform: translateY(-1px) scale(1.01);
            box-shadow: 0 8px 12px rgba(249, 115, 22, 0.3);
        }
        .selection-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            background-color: #1F2937;
            border-color: #374151;
            box-shadow: none;
            transform: none;
        }

        /* Animacije za pojavljivanje sekcija */
        .fade-in-section {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .fade-in-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Flatpickr Custom Styling */
        .flatpickr-calendar {
            background: #1F2937; /* gray-800 */
            border: 1px solid #374151; /* gray-700 */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            color: #F3F4F6; /* gray-100 */
        }
        .flatpickr-months .flatpickr-month {
            background-color: #1F2937; /* gray-800 */
            color: #F3F4F6; /* gray-100 */
        }
        .flatpickr-current-month .flatpickr-numInputWrapper span.arrowUp:after,
        .flatpickr-current-month .flatpickr-numInputWrapper span.arrowDown:after {
            color: #F3F4F6; /* gray-100 */
        }
        .flatpickr-weekdays {
            background-color: #1F2937; /* gray-800 */
        }
        .flatpickr-weekday {
            color: #D1D5DB; /* gray-300 */
        }
        .flatpickr-day {
            color: #F3F4F6; /* gray-100 */
            transition: background-color 0.2s, color 0.2s;
        }
        .flatpickr-day.today {
            border-color: #F97316; /* orange-500 */
        }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover,
        .flatpickr-day.selected.prevMonth,
        .flatpickr-day.selected.nextMonth,
        .flatpickr-day.startRange.prevMonth,
        .flatpickr-day.startRange.nextMonth,
        .flatpickr-day.endRange.prevMonth,
        .flatpickr-day.endRange.nextMonth {
            background: #F97316; /* orange-500 */
            border-color: #F97316;
            color: #FFF;
        }
        .flatpickr-day.inRange {
            background-color: rgba(249, 115, 22, 0.2); /* orange-500 transparent */
            border-color: rgba(249, 115, 22, 0.2);
            box-shadow: none;
        }
        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            color: #6B7280; /* gray-500 */
            cursor: not-allowed;
        }
        .flatpickr-day.prevMonth, .flatpickr-day.nextMonth {
            opacity: 0.6;
        }
        .flatpickr-day:hover {
            background-color: #374151; /* gray-700 */
        }
        .flatpickr-current-month .flatpickr-numInputWrapper:hover .flatpickr-numInput,
        .flatpickr-current-month .flatpickr-numInputWrapper span.arrowUp:hover,
        .flatpickr-current-month .flatpickr-numInputWrapper span.arrowDown:hover {
            color: #F97316; /* orange-500 */
        }
        .numInputWrapper span.arrowUp:after {
            border-bottom-color: #F3F4F6;
        }
        .numInputWrapper span.arrowDown:after {
            border-top-color: #F3F4F6;
        }

        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }
        .modal-overlay.visible {
            opacity: 1;
            visibility: visible;
        }

        /* Modal Content */
        .modal-content {
            background-color: #1F2937; /* gray-800 */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            width: 90%;
            text-align: center;
            transform: translateY(-20px);
            opacity: 0;
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        .modal-overlay.visible .modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        /* Loading Spinner */
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #F97316; /* orange-500 */
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Mobile Menu - Main Navigation */
        #mobile-menu {
            position: fixed;
            top: 0;
            right: 0; /* Starts off-screen to the right */
            width: 250px; /* Width of the menu */
            height: 100vh; /* Use vh for full viewport height */
            background-color: #1F2937; /* gray-800 */
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.5);
            transform: translateX(100%); /* Initially off-screen */
            transition: transform 0.3s ease-in-out;
            z-index: 900; /* Below modal overlay, above main content */
            padding-top: 80px; /* Space for fixed header */
            overflow-y: auto; /* Enable scrolling for long menus */
        }
        #mobile-menu.open {
            transform: translateX(0); /* Slides into view */
        }

        /* Overlay for mobile menu and filter sidebar */
        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 800; /* Below menu, above content */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }
        .menu-overlay.visible {
            opacity: 1;
            visibility: visible;
        }

        /* Body scroll lock */
        body.no-scroll {
            overflow: hidden;
        }

        /* Product Filters Side Menu */
        #filter-sidebar {
            position: fixed;
            top: 0;
            left: 0; /* Starts off-screen to the left */
            width: 250px; /* Width of the filter menu */
            height: 100vh; /* Use vh for full viewport height */
            background-color: #1F2937; /* gray-800 */
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.5);
            transform: translateX(-100%); /* Initially off-screen */
            transition: transform 0.3s ease-in-out;
            z-index: 900; /* Below modal overlay, above main content */
            padding-top: 80px; /* Space for fixed header */
            overflow-y: auto; /* Enable scrolling for long menus */
        }
        #filter-sidebar.open {
            transform: translateX(0); /* Slides into view */
        }
        /* Hide filters by default on small screens, show as side menu */
        @media (max-width: 767px) { /* md breakpoint */
            aside.w-full.md:w-1/4.lg:w-1\/5 { /* Target the original aside */
                display: none; /* Hide original aside */
            }
            #filter-sidebar {
                display: block; /* Show side menu on small screens */
            }
        }
        @media (min-width: 768px) { /* md breakpoint */
            #filter-sidebar {
                display: none; /* Hide side menu on larger screens */
            }
            aside.w-full.md:w-1/4.lg:w-1\/5 {
                display: block; /* Show original aside on larger screens */
            }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Zaglavlje (Header) -->
    <header id="main-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="#" class="nav-link" data-page="pocetna">
                <img src="nspeed-logo-b-97x77.png" alt="NSPEED Logo" class="h-12 w-auto">
            </a>
            
            <!-- Navigacija za desktop -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#" class="nav-link text-gray-300 hover:text-orange-500 transition duration-300 pb-1" data-page="pocetna">Početna</a>
                <a href="#" class="nav-link text-gray-300 hover:text-orange-500 transition duration-300 pb-1" data-page="novosti">Novosti</a>
                <a href="#" class="nav-link text-gray-300 hover:text-orange-500 transition duration-300 pb-1" data-page="prodaja">Prodaja Opreme</a>
                <a href="#" class="nav-link text-gray-300 hover:text-orange-500 transition duration-300 pb-1" data-page="zakazivanje" data-target-tab="mobilni">Iznajmljivanje</a>
                <a href="#" class="nav-link bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-full transition duration-300 shadow-lg" data-page="zakazivanje" data-target-tab="fiksni">Zakaži Termin</a>
            </nav>

            <!-- Mobilni meni (Hamburger) -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-white focus:outline-none" aria-label="Otvori meni">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
            </div>
        </div>
        <!-- Mobilna navigacija - Side Menu -->
        <div id="mobile-menu" class="bg-gray-800">
            <div class="p-4 text-right">
                <button id="close-mobile-menu-button" class="text-white focus:outline-none" aria-label="Zatvori meni">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>
            <a href="#" class="nav-link block text-center py-3 px-4 text-sm text-gray-200 hover:bg-gray-700" data-page="pocetna">Početna</a>
            <a href="#" class="nav-link block text-center py-3 px-4 text-sm text-gray-200 hover:bg-gray-700" data-page="novosti">Novosti</a>
            <a href="#" class="nav-link block text-center py-3 px-4 text-sm text-gray-200 hover:bg-gray-700" data-page="prodaja">Prodaja Opreme</a>
            <a href="#" class="nav-link block text-center py-3 px-4 text-sm text-gray-200 hover:bg-gray-700" data-page="zakazivanje" data-target-tab="mobilni">Iznajmljivanje</a>
            <a href="#" class="nav-link block text-center py-4 px-4 text-sm bg-orange-500 text-white font-bold" data-page="zakazivanje" data-target-tab="fiksni">Zakaži Termin</a>
        </div>
    </header>

    <!-- Overlay for side menus -->
    <div id="menu-overlay" class="menu-overlay"></div>

    <main>
        <!-- Početna Stranica -->
        <section id="pocetna" class="page-section active">
            <!-- Hero Sekcija -->
            <div class="relative h-screen flex items-center justify-center text-center bg-cover bg-center" style="background-image: url('imgi_67_NSpeed4.png');">
                <div class="absolute inset-0 bg-black opacity-40"></div> <!-- Subtler overlay -->
                <div class="relative z-10 px-4">
                    <h1 class="text-5xl md:text-7xl font-black uppercase text-white tracking-wider">
                        Doživi Adrenalin <span class="gradient-text">Prave Trke</span>
                    </h1>
                    <p class="mt-4 text-lg md:text-xl text-gray-300 max-w-3xl mx-auto">
                        Profesionalni simulatori u srcu Novog Sada. Da li si spreman za najbrži krug?
                    </p>
                    <button class="nav-link mt-8 bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-10 rounded-full transition duration-300 transform hover:scale-105 shadow-xl text-lg" data-page="zakazivanje" data-target-tab="fiksni">
                        ZAKAŽI VOŽNJU ODMAH
                    </button>
                </div>
            </div>

            <!-- O Nama Sekcija -->
            <div class="py-20 bg-gray-900">
                <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <img src="DSC-5122-1-scaled.jpg" alt="Enterijer NSPEED centra" class="rounded-lg shadow-2xl" loading="lazy">
                    </div>
                    <div>
                        <h2 class="text-4xl font-bold mb-4">Dobrodošli u <span class="gradient-text">NSPEED</span></h2>
                        <p class="text-gray-400 mb-4">
                            NSPEED Sim Racing nije samo igranje igrica - to je mesto gde strast prema trkama postaje stvarnost. Naša misija je da pružimo najrealističnije trkačko iskustvo svakome, od potpunih početnika do iskusnih vozača.
                        </p>
                        <p class="text-gray-400">
                            Sa najsavremenijom opremom, autentičnom atmosferom i zajednicom koja deli istu strast, garantujemo nezaboravne trenutke na stazi.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Novosti Stranica -->
        <section id="novosti" class="page-section py-24 bg-gray-900">
            <div class="container mx-auto px-6">
                <h2 class="text-4xl font-bold text-center mb-12">Naše <span class="gradient-text">Novosti</span></h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Kartica za vest -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                        <img src="https://placehold.co/600x400/06B6D4/FFF?text=Novi+F1+Simulator" class="w-full h-48 object-cover" alt="Slika vesti" loading="lazy">
                        <div class="p-6">
                            <p class="text-sm text-gray-400 mb-2">11. Avgust, 2025.</p>
                            <h3 class="text-xl font-bold mb-3 text-white">Novi F1 Simulatori su stigli!</h3>
                            <p class="text-gray-400 mb-4">Naša flota je jača za tri nova simulatora sa F1 volanima. Dođite i isprobajte osećaj Formule 1!</p>
                            <a href="#" class="text-cyan-400 hover:text-cyan-300 font-semibold">Pročitaj više <i class="fas fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                    <!-- Kartica za vest -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                        <img src="https://placehold.co/600x400/F97316/FFF?text=GT3+Turnir" class="w-full h-48 object-cover" alt="Slika vesti" loading="lazy">
                        <div class="p-6">
                            <p class="text-sm text-gray-400 mb-2">1. Avgust, 2025.</p>
                            <h3 class="text-xl font-bold mb-3 text-white">Najava Mesečnog Turnira u GT3 Klasi</h3>
                            <p class="text-gray-400 mb-4">Prijavite se za naš redovni mesečni turnir i osvojite vredne nagrade. Staza ovog meseca: Spa-Francorchamps.</p>
                            <a href="#" class="text-cyan-400 hover:text-cyan-300 font-semibold">Pročitaj više <i class="fas fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                    <!-- Kartica za vest -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform hover:-translate-y-2 transition-transform duration-300">
                        <img src="https://placehold.co/600x400/8B5CF6/FFF?text=Team+Building" class="w-full h-48 object-cover" alt="Slika vesti" loading="lazy">
                        <div class="p-6">
                            <p class="text-sm text-gray-400 mb-2">25. Jul, 2025.</p>
                            <h3 class="text-xl font-bold mb-3 text-white">Organizujte nezaboravan Team Building</h3>
                            <p class="text-gray-400 mb-4">Tražite jedinstvenu ideju za druženje sa kolegama? Naši paketi za firme su pravo rešenje za vas.</p>
                            <a href="#" class="text-cyan-400 hover:text-cyan-300 font-semibold">Pročitaj više <i class="fas fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Prodaja Opreme Stranica -->
        <section id="prodaja" class="page-section py-24 bg-gray-900">
            <div class="container mx-auto px-6">
                <h2 class="text-4xl font-bold text-center mb-12">Prodaja <span class="gradient-text">Opreme</span></h2>
                
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Filteri (Sidebar) - Preneseno iz Shop - NSpeed Sim Racing.html -->
                    <!-- Filter button for small screens -->
                    <div class="md:hidden w-full mb-6">
                        <button id="filter-button" class="w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-md transition duration-300" aria-label="Prikaži filtere">
                            <i class="fas fa-filter mr-2"></i> Filteri
                        </button>
                    </div>

                    <!-- Filter Sidebar (for small screens) -->
                    <div id="filter-sidebar" class="bg-gray-800 p-6 rounded-lg shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-white">Filteri</h3>
                            <button id="close-filter-button" class="text-white focus:outline-none" aria-label="Zatvori filtere">
                                <i class="fas fa-times fa-lg"></i>
                            </button>
                        </div>
                        <ul class="space-y-2">
                            <li><button class="filter-btn active" data-filter="sve">Svi artikli</button></li>
                            <li><button class="filter-btn" data-filter="setovi">Setovi</button></li>
                            <li><button class="filter-btn" data-filter="volani">Volani</button></li>
                            <li><button class="filter-btn" data-filter="pedale">Pedale</button></li>
                            <li><button class="filter-btn" data-filter="menjaci">Menjači i Ručne</button></li>
                            <li><button class="filter-btn" data-filter="sedista">Sedišta i Cockpiti</button></li>
                            <li><button class="filter-btn" data-filter="ostalo">Ostalo</button></li>
                            <li><button class="filter-btn" data-filter="baze">Baze</button></li>
                            <li><button class="filter-btn" data-filter="rukavice">Rukavice</button></li>
                        </ul>
                        <h3 class="text-xl font-bold text-white mb-4 mt-8">Brendovi</h3>
                        <ul class="space-y-2">
                            <li><button class="filter-btn" data-filter="moza">MOZA</button></li>
                            <li><button class="filter-btn" data-filter="fanatec">Fanatec</button></li>
                            <li><button class="filter-btn" data-filter="heusinkveld">Heusinkveld</button></li>
                            <li><button class="filter-btn" data-filter="simlab">Sim-Lab</button></li>
                            <li><button class="filter-btn" data-filter="playseat">Playseat</button></li>
                        </ul>
                        <h3 class="text-xl font-bold text-white mb-4 mt-8">Kompatibilnost</h3>
                        <ul class="space-y-2">
                            <li><button class="filter-btn" data-filter="pc">PC</button></li>
                        </ul>
                    </div>

                    <!-- Original filters (for larger screens) -->
                    <aside class="hidden md:block w-full md:w-1/4 lg:w-1/5">
                        <div class="bg-gray-800 p-6 rounded-lg shadow-lg sticky top-28">
                            <h3 class="text-xl font-bold text-white mb-4">Kategorije</h3>
                            <ul class="space-y-2">
                                <li><button class="filter-btn active" data-filter="sve">Svi artikli</button></li>
                                <li><button class="filter-btn" data-filter="setovi">Setovi</button></li>
                                <li><button class="filter-btn" data-filter="volani">Volani</button></li>
                                <li><button class="filter-btn" data-filter="pedale">Pedale</button></li>
                                <li><button class="filter-btn" data-filter="menjaci">Menjači i Ručne</button></li>
                                <li><button class="filter-btn" data-filter="sedista">Sedišta i Cockpiti</button></li>
                                <li><button class="filter-btn" data-filter="ostalo">Ostalo</button></li>
                                <li><button class="filter-btn" data-filter="baze">Baze</button></li>
                                <li><button class="filter-btn" data-filter="rukavice">Rukavice</button></li>
                            </ul>
                            <h3 class="text-xl font-bold text-white mb-4 mt-8">Brendovi</h3>
                            <ul class="space-y-2">
                                <li><button class="filter-btn" data-filter="moza">MOZA</button></li>
                                <li><button class="filter-btn" data-filter="fanatec">Fanatec</button></li>
                                <li><button class="filter-btn" data-filter="heusinkveld">Heusinkveld</button></li>
                                <li><button class="filter-btn" data-filter="simlab">Sim-Lab</button></li>
                                <li><button class="filter-btn" data-filter="playseat">Playseat</button></li>
                            </ul>
                            <h3 class="text-xl font-bold text-white mb-4 mt-8">Kompatibilnost</h3>
                            <ul class="space-y-2">
                                <li><button class="filter-btn" data-filter="pc">PC</button></li>
                            </ul>
                        </div>
                    </aside>


                    <!-- Lista Proizvoda - Preneseno i prilagođeno iz Shop - NSpeed Sim Racing.html -->
                    <div class="w-full md:w-3/4 lg:w-4/5">
                        <div id="product-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                            
                            <!-- Artikal 1: Aluminijumski 3090 sim kokpit -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="sedista" data-brand="none" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2024/08/IMG-4895-300x300.jpg" class="mx-auto h-40 w-auto mb-4" alt="Aluminijumski 3090 sim kokpit" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">Aluminijumski 3090 sim kokpit</h3>
                                <p class="text-gray-400">Rigovi i sedišta</p>
                                <p class="text-2xl font-bold gradient-text my-3">39.990&nbsp;rsd</p>
                                <p class="text-red-500 font-semibold">Nema na stanju</p>
                                <button class="w-full mt-auto bg-gray-600 text-white font-bold py-2 px-4 rounded-full opacity-50 cursor-not-allowed" disabled>Nema na stanju</button>
                            </div>

                            <!-- Artikal 2: Aluminijumski 40160 Prime kokpit -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="sedista" data-brand="none" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2025/04/20210422144200-300x300.jpg" class="mx-auto h-40 w-auto mb-4" alt="Aluminijumski 40160 Prime kokpit" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">Aluminijumski 40160 Prime kokpit</h3>
                                <p class="text-gray-400">Rigovi i sedišta</p>
                                <p class="text-2xl font-bold gradient-text my-3">64.990&nbsp;rsd</p>
                                <p class="text-yellow-500 font-semibold">Dostupno za avansnu kupovinu</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>

                            <!-- Artikal 3: Aluminijumski 4080 crni sim kokpit -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="sedista" data-brand="none" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2024/08/Untitled-300x300.png" class="mx-auto h-40 w-auto mb-4" alt="Aluminijumski 4080 crni sim kokpit" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">Aluminijumski 4080 crni sim kokpit</h3>
                                <p class="text-gray-400">Rigovi i sedišta</p>
                                <p class="text-2xl font-bold gradient-text my-3">48.990&nbsp;rsd</p>
                                <p class="text-green-500 font-semibold">Na zalihama</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>

                            <!-- Artikal 4: Apex postolje za volan -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="sedista" data-brand="none" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2025/05/X1-3-300x300.jpg" class="mx-auto h-40 w-auto mb-4" alt="Apex postolje za volan" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">Apex postolje za volan</h3>
                                <p class="text-gray-400">Rigovi i sedišta</p>
                                <p class="text-2xl font-bold gradient-text my-3">18.490&nbsp;rsd</p>
                                <p class="text-yellow-500 font-semibold">Dostupno za avansnu kupovinu</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>

                            <!-- Artikal 5: Držač za ručnu kočnicu i menjač za sto -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="ostalo" data-brand="none" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2024/08/36-300x300.jpg" class="mx-auto h-40 w-auto mb-4" alt="Držač za ručnu kočnicu i menjač za sto" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">Držač za ručnu kočnicu i menjač za sto</h3>
                                <p class="text-gray-400">Ostalo</p>
                                <p class="text-2xl font-bold gradient-text my-3">5.490&nbsp;rsd</p>
                                <p class="text-green-500 font-semibold">Na zalihama</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>

                            <!-- Artikal 6: ES volan formula mod -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="volani" data-brand="none" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2024/08/ES-Mod3.png-300x300.jpg" class="mx-auto h-40 w-auto mb-4" alt="ES volan formula mod" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">ES volan formula mod</h3>
                                <p class="text-gray-400">Volani</p>
                                <p class="text-2xl font-bold gradient-text my-3">7.490&nbsp;rsd</p>
                                <p class="text-yellow-500 font-semibold">Dostupno za avansnu kupovinu</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>

                            <!-- Artikal 7: ES volan mod 12ich -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="volani" data-brand="none" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2024/08/12-inch-2-300x300.png" class="mx-auto h-40 w-auto mb-4" alt="ES volan mod 12ich" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">ES volan mod 12ich</h3>
                                <p class="text-gray-400">Volani</p>
                                <p class="text-2xl font-bold gradient-text my-3">10.490&nbsp;rsd</p>
                                <p class="text-green-500 font-semibold">Na zalihama</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>

                            <!-- Artikal 8: GTZ Kokpit sa sedištem i držačem monitora -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="sedista" data-brand="none" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2025/04/1691891521-300x300.jpg" class="mx-auto h-40 w-auto mb-4" alt="GTZ Kokpit sa sedištem i držačem monitora" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">GTZ Kokpit sa sedištem i držačem monitora</h3>
                                <p class="text-gray-400">Rigovi i sedišta</p>
                                <p class="text-2xl font-bold gradient-text my-3">76.490&nbsp;rsd</p>
                                <p class="text-green-500 font-semibold">Na zalihama</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>

                            <!-- Artikal 9: Integrisani držač monitora - Single -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="sedista" data-brand="none" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2025/04/unnamed-file-300x300.png" class="mx-auto h-40 w-auto mb-4" alt="Integrisani držač monitora - Single" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">Integrisani držač monitora – Single</h3>
                                <p class="text-gray-400">Rigovi i sedišta</p>
                                <p class="text-2xl font-bold gradient-text my-3">19.990&nbsp;rsd</p>
                                <p class="text-yellow-500 font-semibold">Dostupno za avansnu kupovinu</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>

                            <!-- Artikal 10: MOZA Adapter za bazu R21/R16/R9/R9 -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="ostalo" data-brand="moza" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2024/08/slika-uskoro-300x300.png" class="mx-auto h-40 w-auto mb-4" alt="MOZA Adapter za bazu R21/R16/R9/R9" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">MOZA Adapter za bazu R21/R16/R9/R9</h3>
                                <p class="text-gray-400">Ostalo</p>
                                <p class="text-2xl font-bold gradient-text my-3">2.490&nbsp;rsd</p>
                                <p class="text-green-500 font-semibold">Na zalihama</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>

                            <!-- Artikal 11: MOZA CM2 Dashboard -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="ostalo" data-brand="moza" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2025/04/CM2-1-300x300.webp" class="mx-auto h-40 w-auto mb-4" alt="MOZA CM2 Dashboard" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">MOZA CM2 Dashboard</h3>
                                <p class="text-gray-400">Ostalo</p>
                                <p class="text-2xl font-bold gradient-text my-3">28.490&nbsp;rsd</p>
                                <p class="text-green-500 font-semibold">Na zalihama</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>

                            <!-- Artikal 12: MOZA CRP2 Pedale -->
                            <div class="product-item bg-gray-800 rounded-lg overflow-hidden shadow-lg text-center p-6 flex flex-col" data-category="pedale" data-brand="moza" data-kompatibilnost="pc">
                                <img src="https://nspeed.rs/wp-content/uploads/2025/01/2025-01-20-22-28-58-MOZA-CRP2-Load-Cell-Pedals-long-300x300.png" class="mx-auto h-40 w-auto mb-4" alt="MOZA CRP2 Pedale" loading="lazy">
                                <h3 class="text-lg font-bold text-white flex-grow">MOZA CRP2 Pedale</h3>
                                <p class="text-gray-400">Pedale</p>
                                <p class="text-2xl font-bold gradient-text my-3">59.990&nbsp;rsd</p>
                                <p class="text-green-500 font-semibold">Na zalihama</p>
                                <button class="w-full mt-auto bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Dodaj u korpu</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Iznajmljivanje Stranica -->
        <section id="iznajmljivanje" class="page-section py-24 bg-gray-900">
            <div class="container mx-auto px-6">
                <h2 class="text-4xl font-bold text-center mb-12">Iznajmljivanje <span class="gradient-text">Simulatora</span></h2>
                
                <!-- Simulator 1: GT -->
                <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden mb-12 grid md:grid-cols-2 items-center">
                    <img src="https://placehold.co/800x600/1F2937/FFF?text=GT+Simulator" class="w-full h-full object-cover" loading="lazy">
                    <div class="p-8 md:p-12">
                        <h3 class="text-3xl font-bold text-white mb-4">GT Simulator</h3>
                        <p class="text-gray-400 mb-6">Savršen za ljubitelje GT trka. Osetite snagu moćnih automobila na najpoznatijim svetskim stazama.</p>
                        <ul class="text-gray-300 space-y-2 mb-6">
                            <li><i class="fas fa-check-circle text-cyan-400 mr-2"></i><strong>Volan:</strong> Simucube 2 Pro</li>
                            <li><i class="fas fa-check-circle text-cyan-400 mr-2"></i><strong>Pedale:</strong> Heusinkveld Ultimate+</li>
                            <li><i class="fas fa-check-circle text-cyan-400 mr-2"></i><strong>Monitor:</strong> Triple 32" 144Hz</li>
                        </ul>
                        <div class="text-lg font-semibold text-white mb-4">Cenovnik:</div>
                        <div class="flex space-x-4 mb-6 text-center">
                            <div class="flex-1 bg-gray-700 p-3 rounded-lg"><span class="block font-bold text-xl">30min</span> <span class="gradient-text font-bold">800 RSD</span></div>
                            <div class="flex-1 bg-gray-700 p-3 rounded-lg"><span class="block font-bold text-xl">60min</span> <span class="gradient-text font-bold">1500 RSD</span></div>
                        </div>
                        <button class="nav-link w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-full transition duration-300" data-page="zakazivanje" data-target-tab="fiksni">Zakaži GT Vožnju</button>
                    </div>
                    <img src="https://placehold.co/800x600/1F2937/FFF?text=Formula+Simulator" class="w-full h-full object-cover" loading="lazy">
                </div>

            </div>
        </section>

        <!-- Zakazivanje Stranica -->
        
<section id="zakazivanje" class="page-section py-24 bg-gray-900">
    <div class="container mx-auto px-6 max-w-3xl">
        <h2 class="text-4xl font-bold text-center mb-2">Zakaži Svoj <span class="gradient-text">Termin</span></h2>
        <p class="text-center text-gray-400 mb-12">Izaberite datum, vreme i tip simulatora.</p>

        <div class="bg-gray-800 p-8 rounded-lg shadow-2xl">
            <!-- Tab navigacija za tipove rezervacija -->
            <div class="flex justify-center mb-6">
                <button class="tab-button bg-orange-500 text-white font-bold py-2 px-6 rounded-full mr-2" data-tab="fiksni">Fiksni simulatori</button>
                <button class="tab-button bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-full" data-tab="mobilni">Mobilni simulatori</button>
            </div>

            <!-- Forma za fiksne simulatore -->
            <form id="zakazivanjeFormFiksni" class="tab-content active" data-tab-content="fiksni" onsubmit="return submitFixedReservation(event)">

                <!-- Korak 1: Izbor datuma (SADA SA KALENDAROM) -->
                <div class="mb-6">
                    <label class="block text-lg font-semibold mb-4 text-white">1. Izaberite datum</label>
                    <input type="text" id="fixedDate" placeholder="Izaberite datum" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3 mb-4" required>
                </div>

                <!-- Korak 2: Izbor vremena -->
                <div class="mb-6 hidden fade-in-section" id="vremeSection">
                    <label class="block text-lg font-semibold mb-4 text-white">2. Izaberite vreme</label>
                    <div id="vremeContainer" class="grid grid-cols-3 sm:grid-cols-4 gap-2"></div>
                </div>

                <!-- Korak 3: Izbor trajanja rezervacije -->
                <div class="mb-6 hidden fade-in-section" id="durationSection">
                    <label class="block text-lg font-semibold mb-4 text-white">3. Izaberite trajanje</label>
                    <select id="durationSelect" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3" onchange="updateQuantityInputs()">
                        <option value="">-- Odaberite trajanje --</option>
                        <option value="30">30 minuta</option>
                        <option value="60">60 minuta</option>
                    </select>
                </div>

                <!-- Korak 4: Izbor količine simulatora (BEZ PADJUĆEG MENIJA ZA TIP) -->
                <div class="mb-6 hidden fade-in-section" id="simulatorSection">
                    <label class="block text-lg font-semibold mb-4 text-white">4. Izaberite količinu simulatora</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="standardQuantity" class="block text-gray-300 text-sm font-bold mb-2">Standardni:</label>
                            <input type="number" id="standardQuantity" value="0" min="0" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3" onchange="updateQuantityInputs()">
                            <p id="standardAvailableText" class="text-gray-400 text-xs mt-1"></p>
                        </div>
                        <div>
                            <label for="proQuantity" class="block text-gray-300 text-sm font-bold mb-2">Pro:</label>
                            <input type="number" id="proQuantity" value="0" min="0" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3" onchange="updateQuantityInputs()">
                            <p id="proAvailableText" class="text-gray-400 text-xs mt-1"></p>
                        </div>
                    </div>
                    <p id="simulatorAvailability" class="text-gray-400 text-sm mt-2"></p>
                </div>

                <!-- Korak 5: Podaci korisnika -->
                <div class="mb-6 hidden fade-in-section" id="podaciSection">
                    <label class="block text-lg font-semibold mb-4 text-white">5. Vaši podaci</label>
                    <div class="grid md:grid-cols-2 gap-4">
                        <input id="inputName" type="text" placeholder="Ime i Prezime" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3" required>
                        <input id="inputEmail" type="email" placeholder="Email Adresa" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3" required>
                        <input id="inputPhone" type="tel" placeholder="Broj telefona (opciono)" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3">
                    </div>
                </div>

                <!-- Dugme za pregled rezervacije -->
                <div class="hidden fade-in-section" id="pregledSection">
                    <button type="button" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-full transition duration-300 text-lg" onclick="showReservationOverview()">
                        Pregledaj Rezervaciju
                    </button>
                </div>

                <div id="bookingMessageFiksni" class="mt-4 text-center text-sm"></div>
            </form>

            <!-- Forma za mobilne simulatore -->
            <form id="zakazivanjeFormMobilni" class="tab-content hidden" data-tab-content="mobilni" onsubmit="return submitMobileReservation(event)">
                <div class="mb-6">
                    <label class="block text-lg font-semibold mb-4 text-white">1. Izaberite datume iznajmljivanja</label>
                    <!-- Izmenjeno: Jedno polje za Flatpickr kalendar -->
                    <input type="text" id="mobileDateRange" placeholder="Izaberite datume (od - do)" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3 mb-4" required>
                </div>

                <div class="mb-6">
                    <label class="block text-lg font-semibold mb-4 text-white">2. Lokacija i napomene</label>
                    <input type="text" id="mobileLocation" placeholder="Adresa za dostavu/preuzimanje" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3 mb-4" required>
                    <textarea id="mobileNotes" placeholder="Dodatne napomene (npr. broj mobilnih simulatora, posebni zahtevi)" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3 h-24"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-lg font-semibold mb-4 text-white">3. Vaši podaci</label>
                    <div class="grid md:grid-cols-2 gap-4">
                        <input id="mobileInputName" type="text" placeholder="Ime i Prezime" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3" required>
                        <input id="mobileInputEmail" type="email" placeholder="Email Adresa" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3" required>
                        <input id="mobileInputPhone" type="tel" placeholder="Broj telefona (opciono)" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-3">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-6 rounded-full transition duration-300 text-lg">
                        Pošalji Zahtev za Mobilne
                    </button>
                </div>
                <div id="bookingMessageMobilni" class="mt-4 text-center text-sm"></div>
            </form>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-800 text-gray-300 py-12">
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Kolona 1: Informacije o firmi -->
        <div>
            <h3 class="text-xl font-bold text-white mb-4">NSpeed Sim Racing</h3>
            <p class="text-gray-400 mb-2">PIB: 114532575</p>
            <p class="text-gray-400">Sve za sim trkanje na jednom mestu.</p>
        </div>

        <!-- Kolona 2: Navigacija -->
        <div>
            <h3 class="text-xl font-bold text-white mb-4">Brzi linkovi</h3>
            <ul class="space-y-2">
                <li><a href="#" class="nav-link hover:text-orange-500 transition duration-300" data-page="pocetna">Početna</a></li>
                <li><a href="#" class="nav-link hover:text-orange-500 transition duration-300" data-page="novosti">Novosti</a></li>
                <li><a href="#" class="nav-link hover:text-orange-500 transition duration-300" data-page="prodaja">Prodaja Opreme</a></li>
                <li><a href="#" class="nav-link hover:text-orange-500 transition duration-300" data-page="zakazivanje" data-target-tab="mobilni">Iznajmljivanje</a></li>
                <li><a href="#" class="nav-link hover:text-orange-500 transition duration-300" data-page="zakazivanje" data-target-tab="fiksni">Zakaži Termin</a></li>
            </ul>
        </div>

        <!-- Kolona 3: Društvene mreže i kontakt -->
        <div>
            <h3 class="text-xl font-bold text-white mb-4">Pratite nas</h3>
            <ul class="flex space-x-4 mb-4">
                <li>
                    <a href="https://www.instagram.com/nspeed.rs/" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-orange-500 transition duration-300">
                        <i class="fab fa-instagram fa-2x"></i>
                    </a>
                </li>
                <!-- Dodajte ostale društvene mreže po potrebi -->
            </ul>
            <p class="text-gray-400">Kontaktirajte nas za sva pitanja.</p>
        </div>
    </div>
    <div class="text-center text-gray-500 text-sm mt-8">
        &copy; 2025 NSpeed Sim Racing. Sva prava zadržana.
    </div>
</footer>

<!-- Flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
// Frontend booking integration with API endpoints
document.addEventListener('DOMContentLoaded', function () {
    // --- Postojeće varijale za fiksne simulatore ---
    const fixedDateInput = document.getElementById('fixedDate'); 
    const vremeContainer = document.getElementById('vremeContainer');
    const vremeSection = document.getElementById('vremeSection');
    const simulatorSection = document.getElementById('simulatorSection');
    const durationSection = document.getElementById('durationSection'); 
    const durationSelect = document.getElementById('durationSelect'); 
    const standardQuantityInput = document.getElementById('standardQuantity');
    const proQuantityInput = document.getElementById('proQuantity');
    const standardAvailableText = document.getElementById('standardAvailableText');
    const proAvailableText = document.getElementById('proAvailableText');
    const simulatorAvailability = document.getElementById('simulatorAvailability'); 
    const podaciSection = document.getElementById('podaciSection');
    const pregledSection = document.getElementById('pregledSection'); 
    const bookingMessageFiksni = document.getElementById('bookingMessageFiksni');
    const inputName = document.getElementById('inputName');
    const inputEmail = document.getElementById('inputEmail');
    const inputPhone = document.getElementById('inputPhone'); 

    let izabraniDatum = null;
    let izabranoVreme = null;
    let izabranoTrajanje = null; 
    let currentAvailabilityData = {}; 

    // --- Nove varijable za mobilne simulatore ---
    const zakazivanjeFormMobilni = document.getElementById('zakazivanjeFormMobilni');
    const mobileDateRange = document.getElementById('mobileDateRange'); 
    const mobileLocation = document.getElementById('mobileLocation');
    const mobileNotes = document.getElementById('mobileNotes');
    const mobileInputName = document.getElementById('mobileInputName');
    const mobileInputEmail = document.getElementById('mobileInputEmail');
    const mobileInputPhone = document.getElementById('mobileInputPhone');
    const bookingMessageMobilni = document.getElementById('bookingMessageMobilni');

    // --- Tab navigacija ---
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;

            tabButtons.forEach(btn => {
                btn.classList.remove('bg-orange-500', 'hover:bg-orange-600');
                btn.classList.add('bg-gray-700', 'hover:bg-gray-600');
            });
            this.classList.remove('bg-gray-700', 'hover:bg-gray-600');
            this.classList.add('bg-orange-500', 'hover:bg-orange-600');

            tabContents.forEach(content => {
                if (content.dataset.tabContent === targetTab) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
            // Reset messages when switching tabs
            bookingMessageFiksni.textContent = '';
            bookingMessageMobilni.textContent = '';
            // Reset quantities and selections when switching tabs
            izabraniDatum = null;
            izabranoVreme = null;
            izabranoTrajanje = null; 
            fixedFlatpickr.clear();
            standardQuantityInput.value = 0;
            proQuantityInput.value = 0;
            durationSelect.value = ""; 
            
            vremeSection.classList.add('hidden', 'fade-in-section');
            vremeSection.classList.remove('visible');
            simulatorSection.classList.add('hidden', 'fade-in-section');
            simulatorSection.classList.remove('visible');
            durationSection.classList.add('hidden', 'fade-in-section'); 
            durationSection.classList.remove('visible');
            podaciSection.classList.add('hidden', 'fade-in-section');
            podaciSection.classList.remove('visible');
            pregledSection.classList.add('hidden', 'fade-in-section');
            pregledSection.classList.remove('visible');
            updateQuantityInputs(); // Ažuriraj prikaz dostupnosti
        });
    });


    // Funkcija za navigaciju i aktiviranje sekcija
    function activateSection(sectionId, targetTab = 'fiksni') { 
        document.querySelectorAll('.page-section').forEach(section => {
            section.classList.remove('active');
        });
        document.getElementById(sectionId).classList.add('active');

        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        const currentNavLink = document.querySelector(`.nav-link[data-page="${sectionId}"]`);
        if (currentNavLink) {
            currentNavLink.classList.add('active');
        }

        // Nova logika za aktiviranje taba unutar sekcije "zakazivanje"
        if (sectionId === 'zakazivanje') {
            const tabButtonToActivate = document.querySelector(`.tab-button[data-tab="${targetTab}"]`);
            if (tabButtonToActivate) {
                tabButtonToActivate.click(); 
            }
        }
    }

    // Event listeneri za navigaciju
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.dataset.page;
            const targetTab = this.dataset.targetTab || 'fiksni'; 
            activateSection(page, targetTab); 
            // Sakrij mobilni meni ako je otvoren
            const mobileMenu = document.getElementById('mobile-menu');
            // Ukloni 'open' klasu kada se prebaci na drugu stranicu
            mobileMenu.classList.remove('open'); 
            // Takođe zatvori filter sidebar ako je otvoren
            const filterSidebar = document.getElementById('filter-sidebar');
            if (filterSidebar && filterSidebar.classList.contains('open')) { 
                filterSidebar.classList.remove('open');
                document.body.classList.remove('no-scroll');
                // Nema potrebe za menu-overlay.classList.remove('visible') ovde, jer se to radi u closeSideMenu()
            }
        });
    });

    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuOverlay = document.getElementById('menu-overlay');
    const closeMobileMenuButton = document.getElementById('close-mobile-menu-button');

    // Helper function to open a side menu
    function openSideMenu(menuElement) {
        menuElement.classList.add('open');
        menuOverlay.classList.add('visible');
        document.body.classList.add('no-scroll');
    }

    // Helper function to close a side menu
    function closeSideMenu(menuElement) {
        menuElement.classList.remove('open');
        menuOverlay.classList.remove('visible');
        document.body.classList.remove('no-scroll');
    }

    // Ensure elements exist before adding listeners
    if (mobileMenuButton && mobileMenu && menuOverlay && closeMobileMenuButton) {
        mobileMenuButton.addEventListener('click', function() {
            openSideMenu(mobileMenu);
        });

        closeMobileMenuButton.addEventListener('click', function() {
            closeSideMenu(mobileMenu);
        });
    }

    // Filter menu toggle
    const filterButton = document.getElementById('filter-button');
    const filterSidebar = document.getElementById('filter-sidebar');
    const closeFilterButton = document.getElementById('close-filter-button');

    if (filterButton && filterSidebar && menuOverlay && closeFilterButton) {
        filterButton.addEventListener('click', function() {
            openSideMenu(filterSidebar);
        });

        closeFilterButton.addEventListener('click', function() {
            closeSideMenu(filterSidebar);
        });
    }

    // Close menus if overlay is clicked
    if (menuOverlay) {
        menuOverlay.addEventListener('click', function() {
            if (mobileMenu && mobileMenu.classList.contains('open')) {
                closeSideMenu(mobileMenu);
            }
            if (filterSidebar && filterSidebar.classList.contains('open')) {
                closeSideMenu(filterSidebar);
            }
        });
    }


    // Header scroll effect (ostaje isti)
    window.addEventListener('scroll', function() {
        const header = document.getElementById('main-header');
        if (window.scrollY > 50) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
    });

    // Podesavanje aktivne klase za pocetnu stranicu pri ucitavanju (ostaje isti)
    activateSection('pocetna');


    // Inicijalizacija Flatpickr kalendara za fiksne simulatore
    const fixedFlatpickr = flatpickr("#fixedDate", {
        mode: "single", 
        minDate: "today", 
        dateFormat: "Y-m-d", 
        onClose: function(selectedDates, dateStr, instance) {
            izabraniDatum = null; 
            izabranoVreme = null; 
            izabranoTrajanje = null; 
            standardQuantityInput.value = 0; 
            proQuantityInput.value = 0;
            durationSelect.value = ""; 
            
            updateQuantityInputs(); 

            vremeSection.classList.add('hidden', 'fade-in-section');
            vremeSection.classList.remove('visible');
            simulatorSection.classList.add('hidden', 'fade-in-section');
            simulatorSection.classList.remove('visible');
            durationSection.classList.add('hidden', 'fade-in-section'); 
            durationSection.classList.remove('visible');
            podaciSection.classList.add('hidden', 'fade-in-section');
            podaciSection.classList.remove('visible');
            pregledSection.classList.add('hidden', 'fade-in-section');
            pregledSection.classList.remove('visible');
            bookingMessageFiksni.textContent = '';


            if (selectedDates.length > 0) {
                izabraniDatum = dateStr;
                vremeSection.classList.remove('hidden');
                vremeSection.classList.add('visible');
                fetchBookedSlots(dateStr); 
            } else {
                // Ako datum nije izabran ili je ponisten, sve se resetuje
            }
        }
    });


    // Funkcija za generisanje vremenskih slotova (za FIKSNE simulatore)
    function generisiVremena(availabilityData) {
        vremeContainer.innerHTML = '';
        currentAvailabilityData = {}; 

        availabilityData.forEach(item => {
            currentAvailabilityData[item.time] = item;
        });

        const startHour = 10;
        const endHour = 22; 
        const now = new Date();
        const todayIso = now.toISOString().slice(0, 10);

        for (let h = startHour; h <= endHour; h++) {
            for (let m of [0, 30]) {
                if (h === endHour && m === 30) continue; 

                const timeStr = `${String(h).padStart(2,'0')}:${m === 0 ? '00' : '30'}`;
                const currentSlotDateTime = new Date(`${izabraniDatum}T${timeStr}:00`);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.dataset.time = timeStr;
                btn.className = 'selection-btn flex flex-col items-center justify-center'; 

                let isDisabled = false;
                let standardAvailable = 0;
                let proAvailable = 0;

                if (currentAvailabilityData[timeStr]) {
                    standardAvailable = currentAvailabilityData[timeStr].standard_total - currentAvailabilityData[timeStr].standard_booked;
                    proAvailable = currentAvailabilityData[timeStr].pro_total - currentAvailabilityData[timeStr].pro_booked;
                } else {
                    standardAvailable = 4; 
                    proAvailable = 2; 
                }

                if (izabraniDatum === todayIso && currentSlotDateTime <= now) {
                    isDisabled = true;
                }

                // Proveravamo dostupnost za 30 i 60 minuta unapred
                // Ako je slot 22:00, ne moze se rezervisati 60min
                const nextSlotTime = getNextTimeSlot(timeStr);
                let canBook60Min = true;
                if (timeStr === '22:00' || !nextSlotTime || (currentAvailabilityData[nextSlotTime] && (currentAvailabilityData[nextSlotTime].standard_total - currentAvailabilityData[nextSlotTime].standard_booked <= 0 || currentAvailabilityData[nextSlotTime].pro_total - currentAvailabilityData[nextSlotTime].pro_booked <= 0))) {
                    canBook60Min = false;
                }


                if (standardAvailable <= 0 && proAvailable <= 0) {
                    isDisabled = true;
                }

                btn.innerHTML = `
                    <span class="font-bold text-lg">${timeStr}</span>
                    <span class="text-xs mt-1">Std: ${standardAvailable}/${currentAvailabilityData[timeStr]?.standard_total || 4}</span>
                    <span class="text-xs">Pro: ${proAvailable}/${currentAvailabilityData[timeStr]?.pro_total || 2}</span>
                `;
                btn.dataset.canBook60Min = canBook60Min; // Cuvanje informacije za dugme

                if (isDisabled) {
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    btn.disabled = true;
                } else {
                    btn.addEventListener('click', function () {
                        izabranoVreme = timeStr;
                        vremeContainer.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        
                        // Resetuj kolicine i trajanje
                        standardQuantityInput.value = 0;
                        proQuantityInput.value = 0;
                        durationSelect.value = "";
                        
                        // Prikaži sekciju za trajanje i ažuriraj njene opcije
                        durationSection.classList.remove('hidden'); 
                        durationSection.classList.add('visible');
                        updateDurationOptions(); 

                        // Sakrij sekciju za simulatore, podatke i dugme za pregled dok se ne izabere trajanje
                        simulatorSection.classList.add('hidden', 'fade-in-section');
                        simulatorSection.classList.remove('visible');
                        podaciSection.classList.add('hidden', 'fade-in-section'); 
                        podaciSection.classList.remove('visible');
                        pregledSection.classList.add('hidden', 'fade-in-section');
                        pregledSection.classList.remove('visible');
                        bookingMessageFiksni.textContent = '';
                    });
                }
                vremeContainer.appendChild(btn);
            }
        }
    }

    // Pomocna funkcija za dobijanje sledeceg vremenskog slota
    function getNextTimeSlot(currentTime) {
        const [hours, minutes] = currentTime.split(':').map(Number);
        let nextMinutes = minutes + 30;
        let nextHours = hours;

        if (nextMinutes >= 60) {
            nextMinutes -= 60;
            nextHours += 1;
        }
        if (nextHours > 22 || (nextHours === 22 && nextMinutes > 0)) { // Poslednji slot je 22:00
            return null;
        }
        return `${String(nextHours).padStart(2, '0')}:${String(nextMinutes).padStart(2, '0')}`;
    }

    // Ažuriranje opcija za trajanje na osnovu izabranog vremena
    function updateDurationOptions() {
        if (!izabranoVreme) {
            durationSelect.innerHTML = '<option value="">-- Odaberite trajanje --</option>';
            durationSelect.disabled = true;
            return;
        }

        const canBook60Min = vremeContainer.querySelector(`button[data-time="${izabranoVreme}"]`).dataset.canBook60Min === 'true';
        
        durationSelect.innerHTML = '<option value="">-- Odaberite trajanje --</option>';
        durationSelect.disabled = false;

        const option30 = document.createElement('option');
        option30.value = '30';
        option30.textContent = '30 minuta';
        durationSelect.appendChild(option30);

        if (canBook60Min) {
            const option60 = document.createElement('option');
            option60.value = '60';
            option60.textContent = '60 minuta';
            durationSelect.appendChild(option60);
        }
        // Pozovi updateQuantityInputs samo ako je izabrano trajanje
        if (durationSelect.value) {
            updateQuantityInputs();
        } else {
            // Ako trajanje nije izabrano, sakrij sekcije za kolicinu, podatke i pregled
            simulatorSection.classList.add('hidden', 'fade-in-section');
            simulatorSection.classList.remove('visible');
            podaciSection.classList.add('hidden', 'fade-in-section'); 
            podaciSection.classList.remove('visible');
            pregledSection.classList.add('hidden', 'fade-in-section');
            pregledSection.classList.remove('visible');
        }
    }

    // Ažuriranje input polja za količinu simulatora i prikaz dostupnosti
    window.updateQuantityInputs = function() {
        const selectedSlotData = currentAvailabilityData[izabranoVreme];
        const selectedDuration = parseInt(durationSelect.value); // Uzmi izabrano trajanje
        
        // Resetuj inpute i njihove max atribute na početku
        standardQuantityInput.setAttribute('max', 0);
        proQuantityInput.setAttribute('max', 0);
        standardQuantityInput.disabled = true;
        proQuantityInput.disabled = true;
        
        standardAvailableText.textContent = 'N/A';
        proAvailableText.textContent = 'N/A';
        simulatorAvailability.textContent = 'Molimo izaberite trajanje.'; 

        // Sakrij sekcije za podatke i pregled dok se ne unese validna količina
        podaciSection.classList.add('hidden', 'fade-in-section');
        podaciSection.classList.remove('visible');
        pregledSection.classList.add('hidden', 'fade-in-section');
        pregledSection.classList.remove('visible');


        if (!selectedSlotData || !izabranoVreme || !selectedDuration) {
            return; 
        }

        let standardAvailable = selectedSlotData.standard_total - selectedSlotData.standard_booked;
        let proAvailable = selectedSlotData.pro_total - selectedSlotData.pro_booked;

        // Ako je trajanje 60 minuta, moramo proveriti i sledeći slot
        if (selectedDuration === 60) {
            const nextSlotTime = getNextTimeSlot(izabranoVreme);
            if (nextSlotTime && currentAvailabilityData[nextSlotTime]) {
                const nextSlotData = currentAvailabilityData[nextSlotTime];
                standardAvailable = Math.min(standardAvailable, nextSlotData.standard_total - nextSlotData.standard_booked);
                proAvailable = Math.min(proAvailable, nextSlotData.pro_total - nextSlotData.pro_booked);
            } else {
                // Ako nema sledećeg slota ili je nedostupan, 60min nije moguće
                standardAvailable = 0;
                proAvailable = 0;
            }
        }


        standardAvailableText.textContent = `(${standardAvailable} slobodno)`;
        proAvailableText.textContent = `(${proAvailable} slobodno)`;

        // Omogući inpute samo ako ima dostupnih simulatora
        if (standardAvailable > 0) {
            standardQuantityInput.setAttribute('max', standardAvailable);
            standardQuantityInput.disabled = false;
        }
        if (proAvailable > 0) {
            proQuantityInput.setAttribute('max', proAvailable);
            proQuantityInput.disabled = false;
        }

        // Ako su obe količine 0, onemogući unos i dugme za potvrdu
        if (standardAvailable <= 0 && proAvailable <= 0) {
            standardQuantityInput.disabled = true;
            proQuantityInput.disabled = true;
            simulatorAvailability.textContent = 'Svi simulatori su zauzeti za ovaj termin i trajanje.';
            podaciSection.classList.add('hidden', 'fade-in-section');
            pregledSection.classList.add('hidden', 'fade-in-section');
        } else {
            simulatorAvailability.textContent = '';
            // Prikaži simulatorSection kada se izabere trajanje i ima dostupnosti
            simulatorSection.classList.remove('hidden');
            simulatorSection.classList.add('visible');

            // Proveri da li je bar jedna količina veća od 0 da bi se prikazali podaci korisnika
            const currentStandardQty = parseInt(standardQuantityInput.value);
            const currentProQty = parseInt(proQuantityInput.value);

            if (currentStandardQty > 0 || currentProQty > 0) {
                podaciSection.classList.remove('hidden');
                podaciSection.classList.add('visible');
                pregledSection.classList.remove('hidden');
                pregledSection.classList.add('visible');
            } else {
                // NE SKRIVAJ ODMAH, dozvoli korisniku da unese količinu
                // Sekcije će biti skrivene samo ako su svi simulatori zauzeti (gornji if)
                // ili ako korisnik unese 0 i 0, a pre toga su bile vidljive (validacija u showReservationOverview)
            }
        }
        bookingMessageFiksni.textContent = ''; // Resetuj poruku o gresci
    }

    // Event listener za promenu trajanja
    durationSelect.addEventListener('change', function() {
        izabranoTrajanje = parseInt(this.value);
        standardQuantityInput.value = 0; // Resetuj kolicine
        proQuantityInput.value = 0;
        updateQuantityInputs(); // Ažuriraj dostupnost na osnovu novog trajanja
    });


    // Preuzimanje zauzetih slotova za odabrani datum sa backend API-ja (za FIKSNE simulatore)
    function fetchBookedSlots(date) {
        fetch('/nspeed/api/get_bookings.php?date=' + encodeURIComponent(date)) 
        .then(r => {
            if (!r.ok) {
                throw new Error('Network response was not ok ' + r.statusText);
            }
            return r.json();
        })
        .then(j => {
            if (j.success) {
                generisiVremena(j.data); 
            } else {
                console.error('API greška:', j.message);
                bookingMessageFiksni.textContent = j.message || 'Došlo je do greške pri dohvatanju dostupnih termina.';
                generisiVremena([]); 
            }
        }).catch(err => {
            console.error('Greška pri dohvatanju zauzetih termina (fiksni):', err);
            bookingMessageFiksni.textContent = 'Došlo je do mrežne greške ili greške u komunikaciji sa serverom.';
            generisiVremena([]); 
        });
    }

    // Validacija količina pre slanja forme
    function validateFixedSimulatorQuantities() {
        const standardQty = parseInt(standardQuantityInput.value);
        const proQty = parseInt(proQuantityInput.value);
        const selectedSlotData = currentAvailabilityData[izabranoVreme];
        const selectedDuration = parseInt(durationSelect.value);

        if (!selectedSlotData) {
            showModal('error', 'Greška: Nema podataka o dostupnosti za izabrani termin. Molimo izaberite ponovo.');
            return false;
        }
        if (!selectedDuration) {
            showModal('error', 'Molimo izaberite trajanje rezervacije.');
            return false;
        }

        let standardAvailable = selectedSlotData.standard_total - selectedSlotData.standard_booked;
        let proAvailable = selectedSlotData.pro_total - selectedSlotData.pro_booked;

        // Ako je trajanje 60 minuta, moramo proveriti i sledeći slot
        if (selectedDuration === 60) {
            const nextSlotTime = getNextTimeSlot(izabranoVreme);
            if (nextSlotTime && currentAvailabilityData[nextSlotTime]) {
                const nextSlotData = currentAvailabilityData[nextSlotTime];
                standardAvailable = Math.min(standardAvailable, nextSlotData.standard_total - nextSlotData.standard_booked);
                proAvailable = Math.min(proAvailable, nextSlotData.pro_total - nextSlotData.pro_booked);
            } else {
                // Ako nema sledećeg slota ili je nedostupan, 60min nije moguće
                showModal('error', '60-minutni termin nije dostupan za izabrano vreme.');
                return false;
            }
        }


        if (standardQty < 0 || proQty < 0) {
            showModal('error', 'Količina simulatora ne može biti negativna.');
            return false;
        }

        if (standardQty === 0 && proQty === 0) {
            showModal('error', 'Morate izabrati bar jedan simulator.');
            return false;
        }

        if (standardQty > standardAvailable) { 
            showModal('error', `Nema dovoljno standardnih simulatora. Dostupno: ${standardAvailable}.`);
            return false;
        }
        if (proQty > proAvailable) { 
            showModal('error', `Nema dovoljno Pro simulatora. Dostupno: ${proAvailable}.`);
            return false;
        }
        
        return true;
    }

    // Prikaz pregleda rezervacije u modalnom prozoru
    window.showReservationOverview = function() {
        if (!validateFixedSimulatorQuantities()) {
            return;
        }

        const name = inputName.value;
        const email = inputEmail.value;
        const phone = inputPhone.value;
        const date = izabraniDatum;
        const time = izabranoVreme;
        const standardQuantity = parseInt(standardQuantityInput.value);
        const proQuantity = parseInt(proQuantityInput.value);
        const duration = parseInt(durationSelect.value); // Uzmi trajanje

        let simulatorSummary = [];
        if (standardQuantity > 0) {
            simulatorSummary.push(`Standardni: ${standardQuantity} komada`);
        }
        if (proQuantity > 0) {
            simulatorSummary.push(`Pro: ${proQuantity} komada`);
        }
        const simulatorText = simulatorSummary.join(', ');

        let overviewMessage = `
            <p class="text-lg font-semibold text-white mb-4">Molimo proverite detalje vaše rezervacije:</p>
            <p><strong>Ime i Prezime:</strong> ${name}</p>
            <p><strong>Email:</strong> ${email}</p>
            <p><strong>Telefon:</strong> ${phone || 'Nije unet'}</p>
            <p><strong>Datum:</strong> ${date}</p>
            <p><strong>Vreme:</strong> ${time}</p>
            <p><strong>Trajanje:</strong> ${duration} minuta</p>
            <p><strong>Rezervisani simulatori:</strong> ${simulatorText}</p>
        `;

        showModal('overview', overviewMessage, true); 
    };

    // Slanje rezervacije na backend (za FIKSNE simulatore)
    window.submitFixedReservation = async function(e) {
        e.preventDefault(); 
        console.log("submitFixedReservation pozvan."); 

        showLoading('fixed'); 

        const name = inputName.value;
        const email = inputEmail.value;
        const phone = inputPhone.value;
        const date = izabraniDatum;
        const time = izabranoVreme;
        const standardQuantity = parseInt(standardQuantityInput.value);
        const proQuantity = parseInt(proQuantityInput.value);
        const duration = parseInt(durationSelect.value); // Uzmi trajanje

        try {
            const response = await fetch('/nspeed/api/make_booking.php', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    phone: phone,
                    date: date,
                    time: time,
                    standardQuantity: standardQuantity, 
                    proQuantity: proQuantity,
                    duration: duration // Posalji trajanje
                })
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server error (fiksni):', response.status, errorText);
                showModal('error', `Greška servera (${response.status}): ${errorText.substring(0, 100)}...`);
                hideLoading('fixed');
                return;
            }

            const result = await response.json(); 

            if (result.success) {
                showModal('success', result.message);
                document.getElementById('zakazivanjeFormFiksni').reset(); 
                izabraniDatum = null;
                izabranoVreme = null;
                izabranoTrajanje = null; // Resetuj trajanje
                fixedFlatpickr.clear(); 
                standardQuantityInput.value = 0; 
                proQuantityInput.value = 0;
                durationSelect.value = ""; // Resetuj trajanje selekt
                updateQuantityInputs(); 
                vremeSection.classList.add('hidden', 'fade-in-section');
                vremeSection.classList.remove('visible');
                simulatorSection.classList.add('hidden', 'fade-in-section');
                simulatorSection.classList.remove('visible');
                durationSection.classList.add('hidden', 'fade-in-section'); 
                durationSection.classList.remove('visible');
                podaciSection.classList.add('hidden', 'fade-in-section');
                podaciSection.classList.remove('visible');
                pregledSection.classList.add('hidden', 'fade-in-section'); 
                pregledSection.classList.remove('visible');
            } else {
                showModal('error', result.message || 'Greška prilikom slanja rezervacije (fiksni).');
            }
        } catch (error) {
            console.error('Greška pri slanju rezervacije (fiksni):', error);
            showModal('error', 'Došlo je do mrežne greške ili greške u komunikaciji sa serverom.');
        } finally {
            hideLoading('fixed'); 
        }
    };


    // Slanje rezervacije mobilnog simulatora na backend
    window.submitMobileReservation = async function(e) {
        e.preventDefault();
        console.log("submitMobileReservation pozvan."); 

        showLoading('mobile'); 

        const selectedDates = mobileFlatpickr.selectedDates;
        let startDate = null;
        let endDate = null;

        if (selectedDates.length === 2) {
            startDate = flatpickr.formatDate(selectedDates[0], "Y-m-d");
            endDate = flatpickr.formatDate(selectedDates[1], "Y-m-d");
        } else {
            showModal('error', 'Molimo izaberite opseg datuma (početni i krajnji datum).');
            hideLoading('mobile');
            return;
        }

        const name = mobileInputName.value;
        const email = mobileInputEmail.value;
        const phone = mobileInputPhone.value;
        const location = mobileLocation.value;
        const notes = mobileNotes.value;

        if (!name || !email || !startDate || !endDate || !location) {
            showModal('error', 'Molimo popunite sva obavezna polja za mobilni simulator.');
            hideLoading('mobile');
            return;
        }

        try {
            const response = await fetch('/nspeed/api/make_mobile_booking.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    phone: phone,
                    startDate: startDate,
                    endDate: endDate,
                    location: location,
                    notes: notes
                })
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server error (mobilni):', response.status, errorText);
                showModal('error', `Greška servera (${response.status}): ${errorText.substring(0, 100)}...`);
                hideLoading('mobile');
                return;
            }

            const result = await response.json();

            if (result.success) {
                showModal('success', result.message);
                document.getElementById('zakazivanjeFormMobilni').reset();
                mobileFlatpickr.clear(); 
            } else {
                showModal('error', result.message || 'Greška prilikom slanja rezervacije (mobilni).');
            }
        } catch (error) {
            console.error('Greška pri slanju rezervacije (mobilni):', error);
            showModal('error', 'Došlo je do mrežne greške ili greške u komunikaciji sa serverom.');
        } finally {
            hideLoading('mobile'); 
        }
    };


    // Filter functionality for products page (ostaje ista)
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            const productItems = document.querySelectorAll('.product-item');

            productItems.forEach(item => {
                if (filter === 'sve' || item.dataset.category === filter || item.dataset.brand === filter || item.dataset.kompatibilnost === filter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Inicijalizacija Flatpickr kalendara za mobilne simulatore
    const mobileFlatpickr = flatpickr("#mobileDateRange", {
        mode: "range", 
        minDate: "today", 
        dateFormat: "Y-m-d", 
        inline: false, 
    });

    // Provera da li je Flatpickr instanca kreirana
    if (typeof flatpickr === 'undefined') {
        console.error("Flatpickr biblioteka nije učitana.");
    } else if (!mobileFlatpickr) {
        console.error("Flatpickr instanca za #mobileDateRange nije uspešno kreirana.");
    }

    // --- Modal i Loading funkcionalnosti ---
    const modalOverlay = document.getElementById('modalOverlay');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalCloseButton = document.getElementById('modalCloseButton');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const loadingMessage = document.getElementById('loadingMessage');

    // Novi elementi za pregled rezervacije
    const overviewModalOverlay = document.getElementById('overviewModalOverlay');
    const overviewModalContent = document.getElementById('overviewModalContent');
    const overviewDetails = document.getElementById('overviewDetails');
    const confirmReservationButton = document.getElementById('confirmReservationButton');
    const cancelOverviewButton = document.getElementById('cancelOverviewButton');

    function showModal(type, message, isOverview = false) {
        if (isOverview) {
            overviewDetails.innerHTML = message;
            overviewModalOverlay.classList.add('visible');
        } else {
            modalTitle.textContent = type === 'success' ? 'Uspešno!' : 'Greška!';
            modalTitle.className = type === 'success' ? 'text-green-400 text-2xl font-bold mb-4' : 'text-red-400 text-2xl font-bold mb-4';
            modalMessage.textContent = message;
            modalOverlay.classList.add('visible');
        }
    }

    function hideModal(isOverview = false) {
        if (isOverview) {
            overviewModalOverlay.classList.remove('visible');
        } else {
            modalOverlay.classList.remove('visible');
        }
    }

    modalCloseButton.addEventListener('click', function() { hideModal(false); });
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            hideModal(false);
        }
    });

    // Event listeneri za pregled rezervacije
    confirmReservationButton.addEventListener('click', function() {
        hideModal(true); // Sakrij pregled
        submitFixedReservation(new Event('submit')); // Pošalji rezervaciju
    });
    cancelOverviewButton.addEventListener('click', function() { hideModal(true); });
    overviewModalOverlay.addEventListener('click', function(e) {
        if (e.target === overviewModalOverlay) {
            hideModal(true);
        }
    });


    function showLoading(type) {
        loadingMessage.textContent = type === 'fixed' ? 'Slanje rezervacije...' : 'Slanje zahteva za mobilni simulator...';
        loadingOverlay.classList.add('visible');
    }

    function hideLoading() {
        loadingOverlay.classList.remove('visible');
    }
});
</script>

<!-- Modal HTML (za opšte poruke) -->
<div id="modalOverlay" class="modal-overlay">
    <div class="modal-content">
        <h3 id="modalTitle" class="text-2xl font-bold mb-4"></h3>
        <p id="modalMessage" class="text-gray-300 mb-6"></p>
        <button id="modalCloseButton" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-full transition duration-300">Zatvori</button>
    </div>
</div>

<!-- Loading Overlay HTML -->
<div id="loadingOverlay" class="modal-overlay">
    <div class="modal-content">
        <div class="spinner"></div>
        <p id="loadingMessage" class="text-gray-300 text-lg font-semibold">Slanje rezervacije...</p>
    </div>
</div>

<!-- Pregled Rezervacije Modal HTML -->
<div id="overviewModalOverlay" class="modal-overlay">
    <div class="modal-content">
        <h3 class="text-2xl font-bold text-white mb-4">Pregled Rezervacije</h3>
        <div id="overviewDetails" class="text-gray-300 text-left mb-6 space-y-2">
            <!-- Detalji rezervacije ce biti popunjeni JS-om -->
        </div>
        <div class="flex justify-center space-x-4">
            <button id="confirmReservationButton" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-full transition duration-300">Potvrdi i Pošalji</button>
            <button id="cancelOverviewButton" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-full transition duration-300">Izmeni</button>
        </div>
    </div>
</div>
