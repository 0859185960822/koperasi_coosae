<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Leaflet & Chart.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-muted/40">
    <div class="flex min-h-screen w-full flex-col md:flex-row">
        
        {{-- Sidebar --}}
        <aside class="hidden w-[240px] flex-col border-r bg-background md:flex">
            <div class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6">
                <a href="/" class="flex items-center gap-2 font-semibold text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
                    <span>Sales Monitor</span>
                </a>
            </div>
            <div class="flex-1 overflow-auto py-4">
                <nav class="grid items-start px-2 text-sm font-medium lg:px-4 gap-1">
                    @if(auth()->user()->role === 'marketing')
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-foreground hover:bg-muted {{ request()->routeIs('dashboard') ? 'bg-muted text-foreground' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('prospek.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-foreground hover:bg-muted {{ request()->routeIs('prospek.index') ? 'bg-muted text-foreground' : '' }}">
                            Prospek Customer
                        </a>
                        <a href="{{ route('status.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-foreground hover:bg-muted {{ request()->routeIs('status.index') ? 'bg-muted text-foreground' : '' }}">
                            Status Perjalanan
                        </a>
                        <a href="{{ route('aktif.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-foreground hover:bg-muted {{ request()->routeIs('aktif.index') ? 'bg-muted text-foreground' : '' }}">
                            Customer Aktif
                        </a>
                    @elseif(auth()->user()->role === 'manager')
                        <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-foreground hover:bg-muted {{ request()->routeIs('manager.dashboard') ? 'bg-muted text-foreground' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('manager.laporan') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-foreground hover:bg-muted {{ request()->routeIs('manager.laporan') ? 'bg-muted text-foreground' : '' }}">
                            Laporan Marketing
                        </a>
                        <a href="{{ route('manager.users.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-foreground hover:bg-muted {{ request()->routeIs('manager.users.*') ? 'bg-muted text-foreground' : '' }}">
                            Manajemen User
                        </a>
                    @endif
                </nav>
            </div>
        </aside>

        {{-- Main Content Wrapper --}}
        <div class="flex flex-col flex-1 w-full min-w-0">
            
            {{-- Top Navbar (Desktop & Mobile) --}}
            <header class="sticky top-0 z-30 flex h-14 lg:h-[60px] items-center justify-between border-b bg-background/95 backdrop-blur px-4 sm:px-6">
                <!-- Mobile Logo -->
                <div class="font-semibold text-primary md:hidden flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
                    <span>Sales Monitor</span>
                </div>
                
                <!-- Spacer -->
                <div class="flex-1 md:hidden"></div>
                <div class="flex-1 hidden md:flex items-center">
                    @if (isset($header))
                        <h1 class="text-lg font-semibold">{{ $header }}</h1>
                    @endif
                </div>

                <!-- User Profile & Logout -->
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-bold text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:flex flex-col items-start leading-none">
                            <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-muted-foreground uppercase tracking-wide">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                    
                    <div class="w-px h-4 bg-border hidden sm:block"></div>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="text-sm text-muted-foreground hover:text-destructive flex items-center gap-1 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" x2="9" y1="12" y2="12"></line></svg>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 p-4 sm:px-6 sm:py-6 overflow-x-hidden">
                {{-- Mobile Title --}}
                @if (isset($header))
                    <div class="md:hidden mb-4">
                        <h1 class="text-xl font-semibold">{{ $header }}</h1>
                    </div>
                @endif
                
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="rounded-md border border-green-200 bg-green-50 p-4 text-green-800 text-sm mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="rounded-md border border-red-200 bg-red-50 p-4 text-red-800 text-sm mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>

        </div>
    </div>
</body>
</html>
