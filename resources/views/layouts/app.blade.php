<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem UAS') — Pemrograman Web</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
        .section-teori  { background:#dbeafe; color:#1d4ed8; }
        .section-logika { background:#fef3c7; color:#92400e; }
        .section-coding { background:#d1fae5; color:#065f46; }
        .num-teori  { background:#2563eb; }
        .num-logika { background:#d97706; }
        .num-coding { background:#059669; }
        @keyframes pulse-timer { 0%,100%{opacity:1} 50%{opacity:.5} }
        .timer-warning { animation: pulse-timer 1s ease-in-out infinite; }
        .answer-saved { border-color: #10b981 !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    {{-- Navbar --}}
    <nav class="bg-gradient-to-r from-blue-900 to-blue-700 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="font-bold text-sm sm:text-base">UAS — Pemrograman Web</span>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->isMahasiswa())
                            <div id="timer-display" class="font-mono text-lg font-bold bg-blue-800 px-4 py-1 rounded-full text-white"></div>
                        @endif
                        <div class="flex items-center gap-2 text-sm">
                            <span class="hidden sm:block opacity-80">{{ auth()->user()->name }}</span>
                            <span class="text-xs bg-blue-600 px-2 py-0.5 rounded-full capitalize">{{ auth()->user()->role }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-xs bg-red-600 hover:bg-red-500 px-3 py-1.5 rounded-lg transition">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto px-4 mt-4">
        @foreach(['success'=>'green','error'=>'red','warning'=>'yellow','info'=>'blue'] as $type=>$color)
            @if(session($type))
                <div class="mb-3 px-4 py-3 bg-{{ $color }}-50 border border-{{ $color }}-200 text-{{ $color }}-800 rounded-lg text-sm flex items-start gap-2">
                    <span>{{ session($type) }}</span>
                </div>
            @endif
        @endforeach
    </div>

    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    <footer class="mt-12 border-t border-gray-200 py-6 text-center text-xs text-gray-400">
        Sistem UAS Pemrograman Web — React &amp; Laravel REST API &nbsp;|&nbsp; Kejujuran adalah nilai terbaik 🎯
    </footer>

    @stack('scripts')
</body>
</html>
