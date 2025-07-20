{{-- resources/views/components/layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Online</title>
    {{-- Tailwind CSS & Alpine.js via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Memasukkan konfigurasi custom ke Tailwind CDN
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ujian-blue': '#2563EB',
                        'ujian-green-bg': '#2C7A7B',
                        'ujian-green-border': '#2C7A7B',
                        'ujian-gray': {
                            100: '#F3F4F6',
                            200: '#E5E7EB',
                            500: '#6B7280',
                            700: '#374151'
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Ganti dengan font yang sesuai */
    </style>
</head>

<body class="bg-ujian-gray-100">

    {{-- Wrapper utama untuk layout vertikal --}}
    <div class="flex min-h-screen flex-col">
        <header>
            @include('partials.header')
        </header>

        {{-- Main content yang mengisi ruang tersisa & memusatkan isinya --}}
        <main class="flex flex-grow items-center justify-center">
            <div class="w-full max-w-6xl p-4 md:p-8">
                @yield('content')
            </div>
        </main>

        <footer>
            @include('partials.footer')
        </footer>
    </div>

</body>

</html>
