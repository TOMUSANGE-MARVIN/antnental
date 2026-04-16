<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MamaCare Uganda — Antenatal Care Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --blue-primary: #1d4ed8;
            --blue-mid:     #2563eb;
            --blue-light:   #3b82f6;
            --blue-pale:    #eff6ff;
        }

        html { scroll-behavior: smooth; }

        /* ── Navbar ── */
        #navbar { transition: background .3s, box-shadow .3s, padding .3s; }
        #navbar.scrolled { background: #fff !important; box-shadow: 0 4px 24px rgba(0,0,0,.08); padding-top:.75rem; padding-bottom:.75rem; }

        /* ── Reveal animations ── */
        .reveal       { opacity:0; transform:translateY(44px);  transition:opacity .7s ease, transform .7s ease; }
        .reveal-left  { opacity:0; transform:translateX(-56px); transition:opacity .7s ease, transform .7s ease; }
        .reveal-right { opacity:0; transform:translateX(56px);  transition:opacity .7s ease, transform .7s ease; }
        .reveal-scale { opacity:0; transform:scale(.88);        transition:opacity .65s ease, transform .65s ease; }
        .revealed     { opacity:1 !important; transform:none !important; }

        .d1{transition-delay:.05s} .d2{transition-delay:.15s} .d3{transition-delay:.25s}
        .d4{transition-delay:.35s} .d5{transition-delay:.45s} .d6{transition-delay:.55s}

        /* ── Hero video ── */
        #hero-video { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:0; }

        /* ── Floating doctor blob ── */
        .blob-bg {
            position: relative;
            width: 420px; height: 480px;
        }
        .blob-shape {
            position: absolute; inset: 0;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 60%, #93c5fd 100%);
            border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%;
            animation: morph 8s ease-in-out infinite;
        }
        @keyframes morph {
            0%,100% { border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%; }
            33%      { border-radius: 40% 60% 45% 55% / 60% 40% 55% 45%; }
            66%      { border-radius: 55% 45% 60% 40% / 45% 55% 45% 55%; }
        }

        /* ── Stats badge float ── */
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-8px); }
        }
        .float-card { animation: float 4s ease-in-out infinite; }

        /* ── Feature card hover ── */
        .feat-card { transition: transform .3s, box-shadow .3s; }
        .feat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(37,99,235,.12); }

        /* ── Progress bar fill ── */
        .bar-fill { width: 0; transition: width 1.4s cubic-bezier(.4,0,.2,1); }

        /* ── Bounce indicator ── */
        @keyframes bounce-s { 0%,100%{transform:translateY(0)} 50%{transform:translateY(7px)} }
        .bounce-s { animation: bounce-s 2s ease-in-out infinite; }

        /* ── Scroll nav active highlight ── */
        .nav-link { position:relative; }
        .nav-link::after { content:''; position:absolute; bottom:-3px; left:0; width:0; height:2px; background:var(--blue-mid); transition:width .3s; border-radius:2px; }
        .nav-link:hover::after { width:100%; }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-white">

<!-- ══════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════ -->
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm py-4 px-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        <!-- Logo -->
        <a href="{{ route('landing') }}" class="flex items-center gap-2">
            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-400/40">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/>
                </svg>
            </div>
            <span class="text-xl font-extrabold text-blue-700" id="logo-text">MamaCare</span>
        </a>

        <!-- Nav links (desktop) -->
        <div class="hidden md:flex items-center gap-7 text-sm font-medium" id="nav-links">
            <a href="{{ route('landing') }}" class="nav-link text-gray-600 hover:text-blue-600 transition">Home</a>
            <a href="#features"             class="nav-link text-gray-600 hover:text-blue-600 transition">Services</a>
            <a href="#about"                class="nav-link text-gray-600 hover:text-blue-600 transition">About Us</a>
            <a href="#why"                  class="nav-link text-gray-600 hover:text-blue-600 transition">Why Us</a>
            <a href="#testimonials"         class="nav-link text-gray-600 hover:text-blue-600 transition">Reviews</a>
            <a href="#contact"              class="nav-link text-gray-600 hover:text-blue-600 transition">Contact</a>
        </div>

        <!-- CTA -->
        <div class="flex items-center gap-3">
            <button id="mobile-menu-toggle" type="button" class="md:hidden inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600 hover:bg-gray-50">
                <svg id="mobile-menu-open-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="mobile-menu-close-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <a href="{{ route('login') }}" class="hidden md:inline text-gray-600 hover:text-blue-600 text-sm font-medium transition" id="nav-signin">Sign In</a>
            <a href="{{ route('register') }}" class="hidden sm:inline-flex bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold text-sm shadow-lg shadow-blue-500/30 transition">
                Book Appointment
            </a>
        </div>
    </div>
</nav>

<div id="mobile-menu" class="hidden fixed top-[72px] inset-x-0 z-40 md:hidden bg-white border-t border-gray-100 shadow-lg">
    <div class="px-4 py-4 space-y-3 text-sm font-medium">
        <a href="{{ route('landing') }}" class="block text-gray-700 hover:text-blue-600">Home</a>
        <a href="#features" class="block text-gray-700 hover:text-blue-600">Services</a>
        <a href="#about" class="block text-gray-700 hover:text-blue-600">About Us</a>
        <a href="#why" class="block text-gray-700 hover:text-blue-600">Why Us</a>
        <a href="#testimonials" class="block text-gray-700 hover:text-blue-600">Reviews</a>
        <a href="#contact" class="block text-gray-700 hover:text-blue-600">Contact</a>
        <a href="{{ route('register') }}" class="block bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2.5 rounded-lg font-semibold">
            Book Appointment
        </a>
        <div class="pt-2 border-t border-gray-100">
            <a href="{{ route('login') }}" class="block text-gray-700 hover:text-blue-600">Sign In</a>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ -->
<section class="relative min-h-screen flex items-center overflow-hidden bg-white">

    <!-- Subtle decorative circles -->
    <div class="absolute top-20 right-0 w-[480px] h-[480px] rounded-full opacity-40" style="background:radial-gradient(circle,#dbeafe,transparent 70%);"></div>
    <div class="absolute -bottom-20 -left-10 w-72 h-72 rounded-full opacity-30" style="background:radial-gradient(circle,#eff6ff,transparent 70%);"></div>
    <div class="absolute top-1/2 left-1/3 w-64 h-64 rounded-full opacity-20" style="background:radial-gradient(circle,#bfdbfe,transparent 70%);"></div>

    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <!-- LEFT COPY -->
            <div>
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-full px-4 py-1.5 text-blue-600 text-xs font-semibold uppercase tracking-wider mb-6 reveal">
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                    Uganda's #1 Antenatal Care Platform
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-5 reveal" style="transition-delay:.1s;">
                    Caring for You<br>and Your Baby<br>
                    <span class="text-blue-600">Every Step of the Way</span>
                </h1>

                <p class="text-gray-500 text-base md:text-lg leading-relaxed mb-8 max-w-lg reveal" style="transition-delay:.2s;">
                    Book antenatal appointments, track your pregnancy progress, and get SMS reminders — all managed by UMDPC-licensed OB-GYN doctors across Uganda.
                </p>

                <!-- Quick contact form -->
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 mb-8 reveal" style="transition-delay:.3s;">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                        <input type="text" placeholder="Your Name"
                               class="bg-white border border-gray-200 text-gray-700 placeholder-gray-400 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-full">
                        <input type="tel" placeholder="Phone (+256…)"
                               class="bg-white border border-gray-200 text-gray-700 placeholder-gray-400 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-full">
                        <input type="date"
                               class="bg-white border border-gray-200 text-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-full">
                    </div>
                    <a href="{{ route('register') }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition shadow-lg shadow-blue-300/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Book Now
                    </a>
                </div>

                <!-- Trust row -->
                <div class="flex flex-wrap gap-5 text-gray-500 text-sm reveal" style="transition-delay:.4s;">
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Free to Register</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>SMS Reminders</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>UMDPC Doctors</span>
                </div>
            </div>

            <!-- RIGHT — illustrated doctor area -->
            <div class="hidden lg:flex items-center justify-center relative reveal-right">
                <!-- Blob shape -->
                <div class="blob-bg">
                    <div class="blob-shape"></div>

                    <!-- Illustrated maternal figure (SVG) -->
                    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 420 480" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Body / coat -->
                        <ellipse cx="210" cy="380" rx="90" ry="60" fill="#1d4ed8" opacity=".15"/>
                        <rect x="155" y="230" width="110" height="170" rx="20" fill="#ffffff" opacity=".9"/>
                        <!-- Stethoscope -->
                        <path d="M185 270 Q175 310 185 330 Q195 350 210 340 Q225 330 220 310 Q215 290 210 280" stroke="#1d4ed8" stroke-width="4" fill="none" stroke-linecap="round"/>
                        <circle cx="210" cy="280" r="8" fill="#2563eb"/>
                        <!-- Collar / scrubs detail -->
                        <path d="M190 230 L210 255 L230 230" fill="#dbeafe"/>
                        <!-- Head -->
                        <circle cx="210" cy="185" r="52" fill="#fde8d0"/>
                        <!-- Hair -->
                        <path d="M158 185 Q158 135 210 130 Q262 130 262 185 Q260 155 210 152 Q160 155 158 185Z" fill="#92400e"/>
                        <!-- Headband -->
                        <rect x="158" y="178" width="104" height="10" rx="5" fill="#2563eb" opacity=".7"/>
                        <!-- Eyes -->
                        <ellipse cx="195" cy="190" rx="5" ry="6" fill="#1e293b"/>
                        <ellipse cx="225" cy="190" rx="5" ry="6" fill="#1e293b"/>
                        <circle cx="197" cy="188" r="2" fill="white"/>
                        <circle cx="227" cy="188" r="2" fill="white"/>
                        <!-- Smile -->
                        <path d="M198 208 Q210 218 222 208" stroke="#c2410c" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                        <!-- Arms -->
                        <rect x="110" y="240" width="48" height="22" rx="11" fill="#ffffff" opacity=".9" transform="rotate(-25 110 240)"/>
                        <rect x="262" y="240" width="48" height="22" rx="11" fill="#ffffff" opacity=".9" transform="rotate(25 310 240)"/>
                        <!-- Clipboard -->
                        <rect x="220" y="280" width="55" height="70" rx="6" fill="#eff6ff" stroke="#93c5fd" stroke-width="2"/>
                        <rect x="228" y="275" width="40" height="10" rx="4" fill="#2563eb"/>
                        <line x1="228" y1="300" x2="265" y2="300" stroke="#93c5fd" stroke-width="2"/>
                        <line x1="228" y1="310" x2="265" y2="310" stroke="#93c5fd" stroke-width="2"/>
                        <line x1="228" y1="320" x2="255" y2="320" stroke="#93c5fd" stroke-width="2"/>
                        <!-- Pregnant belly hint on clipboard -->
                        <circle cx="246" cy="340" r="12" fill="#bfdbfe" stroke="#93c5fd" stroke-width="1.5"/>
                        <path d="M240 340 Q246 333 252 340" stroke="#2563eb" stroke-width="1.5" fill="none"/>
                    </svg>
                </div>

                <!-- Floating stats card -->
                <div class="absolute -bottom-4 -left-6 bg-white rounded-2xl shadow-2xl shadow-blue-200/60 px-5 py-4 float-card z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-extrabold text-blue-700">5,000+</p>
                            <p class="text-xs text-gray-500">Mothers Supported</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 mt-2">
                        @for($i=0;$i<5;$i++)<svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                        <span class="text-xs text-gray-400 ml-1">4.9 rating</span>
                    </div>
                </div>

                <!-- Floating appointment card -->
                <div class="absolute top-6 -right-6 bg-white rounded-2xl shadow-xl shadow-blue-100/60 px-4 py-3 w-52 z-10" style="animation: float 5s ease-in-out 1.5s infinite;">
                    <p class="text-blue-400 text-[10px] font-bold uppercase tracking-wider mb-1">Next Appointment</p>
                    <p class="text-gray-800 font-semibold text-sm">Dr. Nakato Grace</p>
                    <p class="text-gray-400 text-xs">Tomorrow · 10:30 AM</p>
                    <div class="mt-2 bg-blue-50 rounded-lg px-2 py-1 flex items-center gap-1.5">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-green-600 text-xs font-medium">Confirmed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 bounce-s text-gray-400 flex flex-col items-center gap-1">
        <span class="text-[10px] tracking-widest uppercase">Scroll</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
</section>

<!-- ══════════════════════════════════════════
     QUICK FEATURES BAR
══════════════════════════════════════════ -->
<section class="py-12 bg-white border-b border-gray-100" id="features">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="feat-card flex items-start gap-4 p-5 rounded-2xl border border-blue-50 bg-blue-50/40 reveal d1">
                <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-300/40">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-base mb-1">Affordable Care</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Free registration. Pay only for your consultations — no hidden fees for SMS reminders or follow-ups.</p>
                </div>
            </div>
            <div class="feat-card flex items-start gap-4 p-5 rounded-2xl border border-blue-50 bg-blue-50/40 reveal d2">
                <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-300/40">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-base mb-1">Professional OB-GYN Doctors</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Every doctor is UMDPC-licensed and specialised in obstetrics, gynaecology, and maternal-foetal medicine.</p>
                </div>
            </div>
            <div class="feat-card flex items-start gap-4 p-5 rounded-2xl border border-blue-50 bg-blue-50/40 reveal d3">
                <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-300/40">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-base mb-1">Satisfactory Service</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">98% of our mothers rate MamaCare 5 stars. Your comfort and baby's health are our top priority.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     STATS COUNTER
══════════════════════════════════════════ -->
<section class="py-14 bg-white border-y border-gray-100" id="stats">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="reveal d1">
                <p class="text-4xl font-extrabold text-blue-600 stat-count" data-target="5000" data-suffix="+">0</p>
                <p class="text-gray-500 mt-1 text-sm">Mothers Supported</p>
            </div>
            <div class="reveal d2">
                <p class="text-4xl font-extrabold text-blue-600 stat-count" data-target="120" data-suffix="+">0</p>
                <p class="text-gray-500 mt-1 text-sm">Expert Doctors</p>
            </div>
            <div class="reveal d3">
                <p class="text-4xl font-extrabold text-blue-600" id="stat-sat">0%</p>
                <p class="text-gray-500 mt-1 text-sm">Satisfaction Rate</p>
            </div>
            <div class="reveal d4">
                <p class="text-4xl font-extrabold text-blue-600 stat-count" data-target="15" data-suffix="+">0</p>
                <p class="text-gray-500 mt-1 text-sm">Hospitals Partnered</p>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     ABOUT / TRUST SECTION
══════════════════════════════════════════ -->
<section class="py-24 bg-white" id="about">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <!-- Image side -->
            <div class="relative reveal-left">
                <!-- Main circular frame -->
                <div class="relative mx-auto w-80 h-80 lg:w-96 lg:h-96">
                    <div class="absolute inset-0 rounded-full border-8 border-blue-100"></div>
                    <div class="absolute inset-4 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center overflow-hidden">
                        <!-- Doctor illustration -->
                        <svg viewBox="0 0 200 220" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="60" y="110" width="80" height="100" rx="14" fill="#ffffff"/>
                            <rect x="60" y="110" width="80" height="30" rx="0" fill="#dbeafe"/>
                            <circle cx="100" cy="80" r="38" fill="#fde8d0"/>
                            <path d="M62 80 Q62 44 100 40 Q138 40 138 80 Q136 55 100 52 Q64 55 62 80Z" fill="#78350f"/>
                            <rect x="62" y="74" width="76" height="9" rx="4" fill="#1d4ed8" opacity=".8"/>
                            <ellipse cx="87" cy="86" rx="4" ry="5" fill="#1e293b"/>
                            <ellipse cx="113" cy="86" rx="4" ry="5" fill="#1e293b"/>
                            <circle cx="89" cy="84" r="1.5" fill="white"/>
                            <circle cx="115" cy="84" r="1.5" fill="white"/>
                            <path d="M90 100 Q100 108 110 100" stroke="#c2410c" stroke-width="2" fill="none" stroke-linecap="round"/>
                            <path d="M72 130 Q60 160 65 180 Q70 200 100 202 Q130 200 135 180 Q140 160 128 130" fill="#1d4ed8"/>
                            <circle cx="100" cy="165" r="18" fill="#bfdbfe" stroke="#93c5fd" stroke-width="2"/>
                            <path d="M93 165 Q100 157 107 165" stroke="#2563eb" stroke-width="2" fill="none"/>
                            <path d="M90 148 Q80 165 85 185" stroke="#93c5fd" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <circle cx="85" cy="148" r="6" fill="#2563eb"/>
                        </svg>
                    </div>
                    <!-- Small accent circle -->
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-blue-600 rounded-full flex flex-col items-center justify-center text-white shadow-xl shadow-blue-300/50">
                        <span class="text-xl font-extrabold leading-none">10+</span>
                        <span class="text-[10px] font-medium text-center leading-tight px-1">Years of Care</span>
                    </div>
                </div>
                <!-- Second smaller frame -->
                <div class="absolute top-4 -left-4 lg:-left-10 w-32 h-32 rounded-2xl bg-blue-50 border-2 border-blue-100 flex items-center justify-center shadow-lg">
                    <div class="text-center">
                        <svg class="w-10 h-10 text-blue-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <p class="text-blue-700 font-bold text-xs">UMDPC</p>
                        <p class="text-gray-400 text-[10px]">Certified</p>
                    </div>
                </div>
            </div>

            <!-- Text side -->
            <div class="reveal-right">
                <span class="inline-block bg-blue-50 text-blue-600 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full mb-4">More About Us</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-5 leading-tight">
                    The Best Antenatal Clinic<br>That You Can Trust
                </h2>
                <p class="text-gray-500 leading-relaxed mb-6">
                    MamaCare Uganda connects expectant mothers with experienced, UMDPC-licensed obstetricians and gynaecologists. From your first visit to delivery, we manage every aspect of your antenatal care through a simple, digital platform.
                </p>
                <ul class="space-y-3 mb-8">
                    @foreach(['Modern Digital Records — LMP, EDD, blood group stored securely','Easy Online Appointment — Book from your phone in under 2 minutes','Comfortable & Confidential — Your health data stays private','Always Monitored — Doctor tracks your pregnancy week by week'] as $item)
                    <li class="flex items-start gap-3 text-sm text-gray-600">
                        <span class="mt-0.5 flex-shrink-0 w-5 h-5 bg-blue-600 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <div class="flex flex-wrap gap-3">
                    <a href="#why" class="bg-blue-600 hover:bg-blue-700 text-white px-7 py-3 rounded-xl font-semibold text-sm transition shadow-lg shadow-blue-300/40">Learn More</a>
                    <a href="{{ route('register') }}" class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-7 py-3 rounded-xl font-semibold text-sm transition">Make an Appointment</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     WHY CHOOSE US
══════════════════════════════════════════ -->
<section class="py-24 bg-blue-50/50" id="why">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <!-- Left: Heading + progress bars -->
            <div class="reveal-left">
                <span class="inline-block bg-blue-50 text-blue-600 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full mb-4">Why Choose Us</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-3 leading-tight">
                    Helping Your<br>
                    <span class="text-blue-600">Pregnancy Problems</span>
                    <sup class="text-blue-300 text-xl">✦</sup>
                </h2>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed max-w-sm">
                    Our specialised services cover every aspect of antenatal care — from first-trimester screening to postnatal follow-up.
                </p>

                <div class="space-y-5" id="progress-bars">
                    @foreach([
                        ['Antenatal Visits & Checkups', 95],
                        ['Pregnancy Tracking & EDD', 88],
                        ['SMS Appointment Reminders', 97],
                        ['Postnatal Follow-Up Care', 80],
                    ] as [$label, $pct])
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-blue-600 font-bold">{{ $pct }}%</span>
                        </div>
                        <div class="bg-blue-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bar-fill h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full" data-width="{{ $pct }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Doctor image + consultation card -->
            <div class="relative flex justify-center reveal-right">
                <!-- Doctor circle -->
                <div class="w-72 h-72 rounded-full overflow-hidden border-8 border-white shadow-2xl shadow-blue-200/50 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-end">
                    <svg viewBox="0 0 200 240" class="w-full h-full" fill="none">
                        <circle cx="100" cy="80" r="38" fill="#fde8d0"/>
                        <path d="M62 80 Q62 44 100 40 Q138 40 138 80" fill="#78350f"/>
                        <rect x="55" y="120" width="90" height="120" rx="12" fill="#1d4ed8"/>
                        <rect x="55" y="120" width="90" height="32" rx="0" fill="#3b82f6"/>
                        <path d="M80 130 L100 148 L120 130" fill="#dbeafe"/>
                        <path d="M78 170 Q68 200 72 230" stroke="#bfdbfe" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="72" cy="170" r="7" fill="#2563eb"/>
                        <path d="M86 74 Q100 82 114 74" stroke="#c2410c" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <ellipse cx="87" cy="84" rx="4" ry="5" fill="#1e293b"/>
                        <ellipse cx="113" cy="84" rx="4" ry="5" fill="#1e293b"/>
                    </svg>
                </div>

                <!-- Consultation info card -->
                <div class="absolute -bottom-6 right-0 lg:-right-6 bg-white rounded-2xl shadow-2xl shadow-blue-200/40 p-5 w-60">
                    <p class="text-gray-800 font-bold text-sm mb-3">Don't Hesitate to Do a Consultation</p>
                    <div class="space-y-1.5 text-xs text-gray-500 mb-4">
                        <div class="flex justify-between"><span class="font-medium text-gray-700">Monday – Friday</span><span>8:00 – 5:00</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-700">Saturday</span><span>9:00 – 2:00</span></div>
                        <div class="flex justify-between"><span class="font-medium text-gray-700">Sunday</span><span class="text-red-400">Closed</span></div>
                    </div>
                    <a href="{{ route('register') }}" class="w-full block text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2.5 rounded-lg transition">
                        Book Your Visit Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     ALL SERVICES
══════════════════════════════════════════ -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="inline-block bg-blue-50 text-blue-600 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full mb-3">Our Services</span>
            <h2 class="text-4xl font-extrabold text-gray-900 mb-3">Comprehensive Antenatal Services</h2>
            <p class="text-gray-500 max-w-xl mx-auto">Everything you need for a safe and healthy pregnancy journey.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['bg-blue-50','text-blue-600','M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','Appointment Booking','Book your first antenatal visit online. Choose an OB-GYN and time slot in under 2 minutes.'],
                ['bg-pink-50','text-pink-500','M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z','Pregnancy Tracking','Real-time tracking of weeks pregnant, trimester, EDD countdown, and progress bar.'],
                ['bg-purple-50','text-purple-600','M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z','SMS Reminders','Automatic SMS sent to your phone 24 hours before every appointment — even on basic handsets.'],
                ['bg-green-50','text-green-600','M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','Digital Medical Records','Secure storage of LMP, EDD, blood group, gravida/para, and all clinical notes.'],
                ['bg-yellow-50','text-yellow-600','M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','Expert OB-GYN Team','Verified, UMDPC-licensed obstetricians from Mulago, Naguru, Nsambya and other leading hospitals.'],
                ['bg-red-50','text-red-500','M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z','Emergency Contact','Store emergency contacts so your care team can act immediately when it matters most.'],
            ] as [$bg,$color,$icon,$title,$desc])
            <div class="feat-card bg-white rounded-2xl p-7 border border-gray-100 shadow-sm reveal">
                <div class="w-14 h-14 {{ $bg }} rounded-2xl flex items-center justify-center mb-5">
                    <svg class="w-7 h-7 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $title }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     TESTIMONIALS
══════════════════════════════════════════ -->
<section class="py-24 bg-blue-50/60" id="testimonials">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="inline-block bg-white text-blue-600 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full mb-3 shadow-sm">Reviews</span>
            <h2 class="text-4xl font-extrabold text-gray-900 mb-3">What Mothers Say</h2>
            <p class="text-gray-500">Real stories from mothers across Uganda</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
            @foreach([
                ['N','Nakamya Harriet','Mummy wa baana babiri · Kampala','MamaCare yatuwa obulamu. Emindwa mu foni yange ennaku gyonna yambulirizanga obutabeera. Dr. Nakato ye omuganga omulungi nnyo — akola bulungi nnyo mu kufaayo abasajja ababba.','blue'],
                ['A','Adong Patience','First-time mother · Gulu','I tracked my EDD every single day and always knew which trimester I was in. The SMS reminder saved me from missing my 32-week check-up. I strongly recommend MamaCare to every pregnant woman in Uganda.','pink'],
                ['N','Nakirya Josephine','Mother of twins · Entebbe','Booking took less than 2 minutes on my phone. Dr. Kiggundu scheduled all my follow-ups automatically after each visit. This system is truly a blessing for mothers in Uganda.','purple'],
            ] as [$initial,$name,$meta,$quote,$color])
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 reveal feat-card">
                <div class="flex text-yellow-400 text-xl mb-4">★★★★★</div>
                <p class="text-gray-600 leading-relaxed mb-6 text-sm">"{{ $quote }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-{{ $color }}-100 flex items-center justify-center text-{{ $color }}-700 font-bold text-lg">{{ $initial }}</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $name }}</p>
                        <p class="text-gray-400 text-xs">{{ $meta }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     CTA
══════════════════════════════════════════ -->
<section class="relative py-28 overflow-hidden bg-white border-t border-gray-100" id="contact">
    <!-- Subtle blue decorative blobs -->
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full opacity-30" style="background:radial-gradient(circle,#dbeafe,transparent 70%);"></div>
    <div class="absolute -bottom-16 -left-16 w-64 h-64 rounded-full opacity-20" style="background:radial-gradient(circle,#eff6ff,transparent 70%);"></div>
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center reveal">
        <span class="inline-block bg-blue-50 text-blue-600 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full mb-5 border border-blue-100">Ready to Begin?</span>
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-5 leading-tight">Start Your MamaCare<br>Journey Today</h2>
        <p class="text-gray-500 text-lg mb-10 max-w-xl mx-auto">Join thousands of mothers across Uganda who trust MamaCare for safe, managed antenatal care.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-xl font-bold text-base shadow-lg shadow-blue-200 transition-all transform hover:scale-105">
                Register Now — It's Free
            </a>
            <a href="{{ route('login') }}" class="border-2 border-blue-200 hover:border-blue-400 text-blue-600 hover:bg-blue-50 px-10 py-4 rounded-xl font-bold text-base transition-all">
                Sign In to Your Account
            </a>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     FOOTER
══════════════════════════════════════════ -->
<footer class="bg-gray-900 text-gray-400 py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/></svg>
                    </div>
                    <span class="text-xl font-bold text-white">MamaCare Uganda</span>
                </div>
                <p class="text-sm leading-relaxed">Comprehensive antenatal care management platform dedicated to supporting mothers through every step of pregnancy across Uganda.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('landing') }}" class="hover:text-blue-400 transition">Home</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-blue-400 transition">Register as Patient</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition">Sign In</a></li>
                    <li><a href="#features" class="hover:text-blue-400 transition">Services</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">For Healthcare Providers</h4>
                <p class="text-sm leading-relaxed mb-3">Doctors are added by hospital administrators. Contact your facility admin for account creation.</p>
                <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium transition">Doctor Login →</a>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} MamaCare Uganda. All rights reserved. Built with care for mothers everywhere.</p>
        </div>
    </div>
</footer>

<!-- ══════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════ -->
<script>
// ── Navbar scroll shadow ──
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('shadow-md', window.scrollY > 40);
}, { passive: true });

// ── Mobile menu toggle ──
const menuToggle = document.getElementById('mobile-menu-toggle');
const mobileMenu = document.getElementById('mobile-menu');
const openIcon = document.getElementById('mobile-menu-open-icon');
const closeIcon = document.getElementById('mobile-menu-close-icon');

if (menuToggle && mobileMenu) {
    const toggleMenu = () => {
        const isHidden = mobileMenu.classList.contains('hidden');
        mobileMenu.classList.toggle('hidden');
        if (openIcon && closeIcon) {
            openIcon.classList.toggle('hidden', !isHidden);
            closeIcon.classList.toggle('hidden', isHidden);
        }
    };

    menuToggle.addEventListener('click', toggleMenu);

    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            openIcon?.classList.remove('hidden');
            closeIcon?.classList.add('hidden');
        });
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            mobileMenu.classList.add('hidden');
            openIcon?.classList.remove('hidden');
            closeIcon?.classList.add('hidden');
        }
    });
}

// ── Intersection observer for scroll reveals ──
const revealAll = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
const observer  = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('revealed'); observer.unobserve(e.target); }
    });
}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
revealAll.forEach(el => observer.observe(el));

// Trigger hero elements immediately
document.querySelectorAll('.reveal, .reveal-right, .reveal-left').forEach((el, i) => {
    if (el.closest('#hero, section:first-of-type') || el.closest('section')?.querySelector('#hero-video')) {
        setTimeout(() => el.classList.add('revealed'), 200 + i * 130);
    }
});

// ── Counter animation ──
function animateCount(el, target, suffix = '') {
    const dur = 1800, start = performance.now();
    (function tick(now) {
        const p = Math.min((now - start) / dur, 1);
        const v = Math.floor((1 - Math.pow(1 - p, 3)) * target);
        el.textContent = v.toLocaleString() + suffix;
        if (p < 1) requestAnimationFrame(tick);
    })(start);
}

const statsObs = new IntersectionObserver(entries => {
    if (!entries[0].isIntersecting) return;
    document.querySelectorAll('.stat-count').forEach(el => {
        animateCount(el, +el.dataset.target, el.dataset.suffix || '');
    });
    const sat = document.getElementById('stat-sat');
    if (sat) animateCount(sat, 98, '%');
    statsObs.disconnect();
}, { threshold: 0.4 });
const statsEl = document.getElementById('stats');
if (statsEl) statsObs.observe(statsEl);

// ── Progress bar animation ──
const barObs = new IntersectionObserver(entries => {
    if (!entries[0].isIntersecting) return;
    document.querySelectorAll('.bar-fill').forEach(el => {
        el.style.width = el.dataset.width + '%';
    });
    barObs.disconnect();
}, { threshold: 0.3 });
const barsEl = document.getElementById('progress-bars');
if (barsEl) barObs.observe(barsEl);

// ── Video fallback ──
const vid = document.getElementById('hero-video');
if (vid) vid.addEventListener('error', () => vid.style.display = 'none');
</script>

</body>
</html>
