<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 text-center">
        <div class="w-20 h-20 bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-8v6m0-6V7m0 8h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">403</h1>
        <h2 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4">Akses Ditolak / Forbidden</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
            Maaf, Anda tidak memiliki izin atau peran (*role*) yang cukup untuk mengakses halaman rahasia ini. Silakan hubungi Administrator jika Anda merasa ini adalah kesalahan.
        </p>
        <a href="{{ route('dashboard') }}" 
           class="inline-flex w-full items-center justify-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition">
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
