<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Account') - MamaCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased overflow-x-hidden">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full bg-gradient-to-b from-teal-700 to-teal-900 text-white flex flex-col shadow-xl transition-transform duration-300 lg:static lg:inset-auto lg:translate-x-0">
        <div class="p-6 border-b border-teal-600">
            <div class="flex items-start justify-between gap-3">
                <a href="{{ route('patient.dashboard') }}" class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-pink-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/>
                    </svg>
                    <span class="text-xl font-bold text-white">MamaCare</span>
                </a>
                <button id="sidebar-close" type="button" class="lg:hidden text-teal-200 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-teal-200 text-xs mt-1">Patient Portal</p>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('patient.dashboard') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('patient.dashboard') ? 'bg-teal-600 text-white' : 'text-teal-100 hover:bg-teal-600 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>My Dashboard</span>
            </a>
            <a href="{{ route('patient.appointments.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('patient.appointments*') ? 'bg-teal-600 text-white' : 'text-teal-100 hover:bg-teal-600 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Appointments</span>
            </a>
            <a href="{{ route('patient.profile') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('patient.profile*') ? 'bg-teal-600 text-white' : 'text-teal-100 hover:bg-teal-600 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>My Profile</span>
            </a>
        </nav>
        <div class="p-4 border-t border-teal-600">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-pink-500 flex items-center justify-center text-white font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-teal-200 text-xs">Patient</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left flex items-center space-x-2 px-3 py-2 rounded-lg text-teal-100 hover:bg-teal-600 hover:text-white transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"></div>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden min-w-0">
        <header class="bg-white shadow-sm px-4 sm:px-6 py-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <button id="sidebar-open" type="button" class="lg:hidden inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600 hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg sm:text-xl font-semibold text-gray-800 truncate">@yield('header', 'Dashboard')</h1>
            </div>
            <span class="hidden sm:inline text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
        </header>
        <div class="flex-1 overflow-auto p-4 sm:p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg px-4 py-3">
                    {{ session('info') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </main>
</div>
<script>
    (() => {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('sidebar-open');
        const closeBtn = document.getElementById('sidebar-close');

        if (!sidebar || !overlay || !openBtn || !closeBtn) return;

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        };

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        };

        openBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) closeSidebar();
        });
    })();
</script>
</body>
</html>
