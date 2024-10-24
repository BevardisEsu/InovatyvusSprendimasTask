<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truck Management - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            light: '#E3F2FD', // Light blue background
                            DEFAULT: '#2196F3', // Main blue
                            dark: '#1E88E5', // Darker blue for hover
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
<nav class="bg-primary-DEFAULT shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <span class="text-gray-300 text-xl font-bold">Truck Management</span>
                </div>
                <div class="hidden md:ml-6 md:flex md:items-center">
                    <a href="{{ route('trucks.index') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded-md mr-2">
                        Trucks
                    </a>
                    <a href="{{ route('activity-logs.show') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded-md">
                        Activity Logs
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
