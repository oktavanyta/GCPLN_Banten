<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Groundcheck PLN</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <script>
        (function () {
            const theme = localStorage.getItem('theme');

            if (!theme) {
                // default dark
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

<!-- NAVBAR -->
<header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="w-full flex flex-col md:flex-row items-start md:items-center justify-between px-4 md:px-10 py-3 gap-3 md:gap-0">

        <!-- LOGO -->
        <div class="flex items-center gap-3">
            <img src="/img/bps.png" class="w-10 h-10" />
            <img src="/img/pln.jpg" class="w-8 h-8" />
            <div>
                <h1 class="text-base font-semibold text-gray-800 dark:text-gray-200">
                    Groundcheck PLN
                </h1>
                <p class="text-xs text-gray-400 dark:text-gray-400">
                    BPS Provinsi Banten
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="flex flex-col md:flex-row w-full md:w-auto items-center md:items-center gap-2 md:gap-4 mt-3 md:mt-0">

            <!-- MENU -->
            <nav class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-2 text-sm w-full md:w-auto">
                <a href="/" 
                    class="w-full md:w-auto px-3 py-1.5 rounded-md transition text-center
                    {{ request()->is('/') 
                        ? 'bg-blue-50 text-blue-600 font-semibold border border-blue-100 dark:bg-gray-600 dark:text-gray-100 dark:border-gray-600' 
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                    Dashboard
                </a>

                @auth
                <a href="/groundcheck" 
                    class="w-full md:w-auto px-3 py-1.5 rounded-md transition text-center
                    {{ request()->is('groundcheck*') 
                        ? 'bg-blue-50 text-blue-600 font-semibold border border-blue-100 dark:bg-gray-600 dark:text-gray-100 dark:border-gray-600' 
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                    Input Data
                </a>
                @endauth
            </nav>
            
            <button id="darkToggle"
                class="p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition md:w-auto">
                <!-- ICON -->
                <svg id="iconSun" xmlns="http://www.w3.org/2000/svg" 
                    class="w-5 h-5 text-yellow-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 3v1m0 16v1m8.66-10H21m-18 0h1m15.364 6.364l.707.707M4.929 4.929l.707.707m0 12.728l-.707.707m12.728-12.728l-.707.707M12 7a5 5 0 100 10 5 5 0 000-10z"/>
                </svg>

                <svg id="iconMoon" xmlns="http://www.w3.org/2000/svg" 
                    class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                </svg>
            </button>

            @auth
            <!-- SEPARATOR -->
            <div class="hidden md:block h-5 w-px bg-gray-200 dark:bg-gray-700"></div>

            <div class="flex flex-col md:flex-row w-full md:w-auto items-center gap-2 md:gap-0">

                <!-- USER -->
                <div class="relative w-full md:w-auto">
                    <button id="userMenuBtn" 
                        class="flex items-center gap-2 px-2 py-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition w-full md:w-auto justify-center">

                        <div class="w-8 h-8 bg-blue-100 text-blue-600 flex items-center justify-center rounded-full text-sm font-semibold">
                            @php
                                $name = auth()->user()->name;
                                $words = preg_split('/\s+/', trim($name));
                                $initials = '';
                                foreach ($words as $i => $word) {
                                    if ($i >= 2) break;
                                    $initials .= mb_substr($word, 0, 1);
                                }
                            @endphp
                            {{ strtoupper($initials) }}
                        </div>

                        <svg id="caretIcon" xmlns="http://www.w3.org/2000/svg" 
                            class="w-4 h-4 text-gray-400 dark:text-gray-300 transition-transform duration-200"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- DROPDOWN -->
                    <div id="userDropdown" 
                        class="opacity-0 pointer-events-none scale-95 origin-top-right absolute right-0 mt-2 w-52 
                        bg-white/95 backdrop-blur rounded-xl shadow-lg border border-gray-100 
                        text-sm z-50 transition-all duration-200 ease-out">

                        <!-- USER INFO -->
                        <div class="px-4 py-3 border-b bg-gray-50/70 rounded-t-xl">
                            <p class="font-medium text-gray-800">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <!-- ACTION -->
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button 
                                class="w-full text-left px-4 py-2 hover:bg-red-50 hover:text-red-700 text-red-600 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            @guest
            <a href="{{ route('login') }}" 
                class="px-4 py-1.5 rounded-md text-sm font-medium transition-all duration-200

                {{ request()->is('login') 
                    ? 'bg-blue-800 text-white hover:bg-blue-900 dark:bg-blue-800 dark:hover:bg-blue-900'
                    : 'bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700' }}">
                
                Admin
            </a>
            @endguest

        </div>
    </div>
</header>

<!-- CONTENT -->
<main class="p-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border">
        @yield('content')
    </div>
</main>

<!-- FOOTER -->
<footer class="text-center pb-4 text-sm text-gray-400">
    &copy; {{ date('Y') }}. BPS Provinsi Banten - Tim Metodologi, Pengolahan, dan Layanan Teknologi Informasi.
</footer>

<!-- SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('userMenuBtn');
    const dropdown = document.getElementById('userDropdown');
    const caret = document.getElementById('caretIcon');

    if (!btn || !dropdown) return;

    function openDropdown() {
        dropdown.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
        dropdown.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');

        if (caret) caret.classList.add('rotate-180');
    }

    function closeDropdown() {
        dropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        dropdown.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');

        if (caret) caret.classList.remove('rotate-180');
    }

    btn.addEventListener('click', function (e) {
        e.stopPropagation();

        if (dropdown.classList.contains('opacity-100')) {
            closeDropdown();
        } else {
            openDropdown();
        }
    });

    dropdown.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    document.addEventListener('click', closeDropdown);
});
</script>
<script>
    (function () {
        const html = document.documentElement;
        const toggle = document.getElementById('darkToggle');
        const iconSun = document.getElementById('iconSun');
        const iconMoon = document.getElementById('iconMoon');

        // sync icon sesuai theme
        function updateIcon() {
            const isDark = html.classList.contains('dark');

            iconSun.classList.toggle('hidden', !isDark);
            iconMoon.classList.toggle('hidden', isDark);
        }

        updateIcon();

        toggle.addEventListener('click', function () {
            html.classList.toggle('dark');

            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');

            updateIcon();
        });
    })();
</script>
</body>
</html>