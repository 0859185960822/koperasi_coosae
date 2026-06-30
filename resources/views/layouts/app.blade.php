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
    <div x-data="{ sidebarOpen: window.innerWidth >= 768 }" class="flex min-h-screen w-full flex-col md:flex-row relative">
        
        {{-- Sidebar Overlay (Mobile only) --}}
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-background/80 backdrop-blur-sm md:hidden" @click="sidebarOpen = false" x-transition.opacity style="display: none;"></div>
        
        {{-- Sidebar --}}
        <aside 
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-[240px] flex-col border-r bg-background md:static md:flex shrink-0"
            style="display: none;"
        >
            <div class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6">
                <a href="/" class="flex items-center gap-2 font-semibold text-primary">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg> -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark-icon lucide-landmark"><path d="M10 18v-7"/><path d="M11.119 2.205a2 2 0 0 1 1.762 0l7.84 3.846A.5.5 0 0 1 20.5 7h-17a.5.5 0 0 1-.22-.949z"/><path d="M14 18v-7"/><path d="M18 18v-7"/><path d="M3 22h18"/><path d="M6 18v-7"/></svg>
                    <span>Customer Journey Coosae</span>
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
                <!-- Hamburger Menu -->
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-1 -ml-1 text-muted-foreground hover:bg-muted hover:text-foreground rounded-md focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                    </button>
                    
                    <div class="font-semibold text-primary md:hidden flex items-center gap-2">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg> -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark-icon lucide-landmark"><path d="M10 18v-7"/><path d="M11.119 2.205a2 2 0 0 1 1.762 0l7.84 3.846A.5.5 0 0 1 20.5 7h-17a.5.5 0 0 1-.22-.949z"/><path d="M14 18v-7"/><path d="M18 18v-7"/><path d="M3 22h18"/><path d="M6 18v-7"/></svg>
                        <span>Customer Journey Coosae</span>
                    </div>
                </div>
                
                <!-- Spacer -->
                <div class="flex-1 md:hidden"></div>
                <div class="flex-1 hidden md:flex items-center ml-2">
                    @if (isset($header))
                        <h1 class="text-lg font-semibold">{{ $header }}</h1>
                    @endif
                </div>

                <!-- User Profile & Logout -->
                <div class="flex items-center gap-4">
                    @php
                        $uncontactedCustomers = collect();
                        if (auth()->check() && auth()->user()->role === 'marketing') {
                            $thirtyDaysAgo = \Carbon\Carbon::now()->subDays(30);
                            $uncontactedCustomers = auth()->user()->customers()
                                ->where('status', '!=', 'Customer Aktif')
                                ->where('updated_at', '<', $thirtyDaysAgo)
                                ->get();
                        }
                    @endphp

                    @if(auth()->user()->role === 'marketing')
                    <div class="relative flex items-center">
                        <button class="text-muted-foreground hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        </button>
                        @if($uncontactedCustomers->count() > 0)
                            <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                {{ $uncontactedCustomers->count() }}
                            </span>
                        @endif
                    </div>
                    @endif

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
                @if($errors->any())
                    <div class="rounded-md border border-red-200 bg-red-50 p-4 text-red-800 text-sm mb-4">
                        <div class="flex items-center gap-2 font-semibold mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            Terdapat kesalahan:
                        </div>
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>

        </div>
    </div>

    {{-- Toast Notification --}}
    @if(auth()->check() && auth()->user()->role === 'marketing' && $uncontactedCustomers->count() > 0)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-full"
         class="fixed top-20 right-4 z-50 rounded-md border border-yellow-200 bg-yellow-50 p-4 text-yellow-800 text-sm shadow-lg flex items-start gap-3 w-80 max-h-screen overflow-y-auto">
        <div class="mt-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="font-medium text-yellow-900">Perhatian!</h3>
            <div class="mt-1 flex flex-col gap-1">
                @foreach($uncontactedCustomers->take(3) as $uncontacted)
                    <p>Customer <strong>{{ $uncontacted->nama }}</strong> sudah 30 hari tidak ada kabar.</p>
                @endforeach
                @if($uncontactedCustomers->count() > 3)
                    <p class="font-semibold text-xs">+ {{ $uncontactedCustomers->count() - 3 }} lainnya</p>
                @endif
            </div>
        </div>
        <button @click="show = false" class="text-yellow-600 hover:text-yellow-800 focus:outline-none">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif
</body>
</html>
