<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased font-sans bg-slate-100 flex flex-col items-center justify-center min-h-screen">
        <div class="max-w-xl text-center p-8 bg-white rounded-3xl shadow-sm border border-slate-200">
            <div class="flex justify-center mb-6 text-[#FF2D20]">
                <svg class="h-16 w-auto" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.7 15.1V23.7C16.7 24.3 17.2 24.8 17.8 24.8H26.4C27 24.8 27.5 24.3 27.5 23.7V15.1C27.5 14.5 27 14 26.4 14H17.8C17.2 14 16.7 14.5 16.7 15.1Z" fill="currentColor"/>
                    <path d="M4.3 29.8V51.4C4.3 52 4.8 52.5 5.4 52.5H27C27.6 52.5 28.1 52 28.1 51.4V29.8C28.1 29.2 27.6 28.7 27H5.4C4.8 28.7 4.3 29.2 4.3 29.8Z" fill="currentColor"/>
                    <path d="M34.5 4V24C34.5 24.6 35 25.1 35.6 25.1H55.6C56.2 25.1 56.7 24.6 56.7 24V4C56.7 3.4 56.2 2.9 55.6 2.9H35.6C35 2.9 34.5 3.4 34.5 4Z" fill="currentColor"/>
                    <path d="M34.5 35.6V55.6C34.5 56.2 35 56.7 35.6 56.7H55.6C56.2 56.7 56.7 56.2 56.7 55.6V35.6C56.7 35 56.2 34.5 55.6 34.5H35.6C35 34.5 34.5 35 34.5 35.6Z" fill="currentColor"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-slate-800">Laravel Installation Success</h1>
            <p class="text-sm text-slate-500 mt-2">Halaman default ini berhasil dipulihkan. Selamat mengembangkan aplikasi SIPA, Mas Yami!</p>
            <div class="mt-6 flex justify-center gap-4 text-xs font-bold">
                <a href="/login" class="text-blue-600 hover:underline">Menuju Halaman Login &rarr;</a>
            </div>
        </div>
    </body>
</html>