<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-muted/40 flex items-center justify-center min-h-screen">
    
    <div class="w-full max-w-md px-4">
        <x-ui.card>
            <x-ui.card-header class="space-y-1 text-center">
                <x-ui.card-title class="text-2xl">Masuk</x-ui.card-title>
                <p class="text-sm text-muted-foreground">
                    Masukkan email dan password Anda untuk masuk ke sistem.
                </p>
            </x-ui.card-header>
            <x-ui.card-content>
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <x-ui.label for="email">Email</x-ui.label>
                        <x-ui.input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="m@example.com" />
                        @error('email')
                            <p class="text-[0.8rem] font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <x-ui.label for="password">Password</x-ui.label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-muted-foreground hover:text-primary hover:underline">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <x-ui.input id="password" type="password" name="password" required autocomplete="current-password" />
                        @error('password')
                            <p class="text-[0.8rem] font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center space-x-2">
                        <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary" name="remember">
                        <x-ui.label for="remember_me" class="font-normal">Ingat Saya</x-ui.label>
                    </div>

                    <x-ui.button variant="masuk" type="submit" class="w-full">
                        Masuk
                    </x-ui.button>
                </form>
            </x-ui.card-content>
        </x-ui.card>
    </div>

</body>
</html>
