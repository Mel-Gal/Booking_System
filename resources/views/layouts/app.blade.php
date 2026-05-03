<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Appointment Management System</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden font-sans">

    <aside class="w-64 bg-white shadow-md flex-shrink-0 flex flex-col">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-xl font-extrabold text-gray-800">Booking System</h1>
        </div>
        
        <nav class="p-4 space-y-2 flex-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition-colors">
                Dashboard
            </a>
            <a href="{{ route('clients.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition-colors">
                Clients
            </a>
            <a href="{{ route('appointments.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition-colors">
                Appointments
            </a>

            @if(auth()->check() && auth()->user()->hasRole('Admin', 'Staff'))
                <a href="{{ route('service-records.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition-colors">
                    Service Records
                </a>
            @endif

            @if(auth()->check() && auth()->user()->hasRole('Admin'))
                <a href="{{ route('reports.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition-colors">
                    Reports
                </a>
                
                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition-colors">
                    User Management
                </a>
            @endif
        </nav>
        
        </aside>

    <div class="flex-1 flex flex-col overflow-y-auto">
        
        <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-700">
                Welcome back!
            </h2>

            @auth
            <div class="relative" x-data="{ open: false }">
                
                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-gray-700 hover:text-blue-600 focus:outline-none transition-colors">
                    <div class="text-right">
                        <p class="text-sm font-semibold leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 leading-tight">{{ auth()->user()->role }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" 
                     x-transition.opacity.duration.200ms
                     style="display: none;"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-200 z-50">
                    
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        My Profile
                    </a>
                    
                    <hr class="my-1 border-gray-200">

                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold transition-colors">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </header>

        <main class="p-6">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // This transforms any <select> tag with the class "searchable-select"
            document.querySelectorAll('.searchable-select').forEach((el) => {
                new TomSelect(el, {
                    create: false, 
                    sortField: {
                        field: "text",
                        direction: "asc" 
                    }
                });
            });
        });
    </script>
</body>
</html>