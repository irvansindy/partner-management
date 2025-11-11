<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Management - PT Pralon</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-[Poppins] text-gray-800">

    {{-- Section Utama --}}
    <section class="min-h-screen flex flex-col justify-center items-center text-center px-6">
        <div class="max-w-3xl bg-white shadow-xl rounded-2xl p-10">
            <img src="{{ asset('uploads/logo/logo.png') }}" alt="PT Pralon" class="mx-auto mb-2 w-48">

            <h1 class="text-3xl font-bold text-blue-600 mb-2">
                PT Pralon Partner Management
            </h1>
            <p class="text-gray-600 mb-6">
                Sistem terintegrasi untuk mengelola mitra bisnis PT Pralon secara efektif, efisien, dan transparan.
            </p>

            <div class="border-t border-gray-200 my-6"></div>

            <div class="text-left space-y-4">
                <h2 class="text-xl font-semibold text-gray-800">Tentang Sistem</h2>
                <p class="text-gray-600 leading-relaxed">
                    Partner Management System dirancang untuk membantu tim PT Pralon dalam mengelola data mitra — mulai
                    dari
                    registrasi, verifikasi, hingga komunikasi dan kolaborasi dalam satu platform yang mudah digunakan.
                </p>

                <h2 class="text-xl font-semibold text-gray-800 pt-4">Tujuan & Manfaat</h2>
                <ul class="list-disc list-inside text-gray-600 leading-relaxed">
                    <li>Meningkatkan efisiensi pengelolaan data mitra.</li>
                    <li>Mempercepat proses administrasi dan komunikasi.</li>
                    <li>Menjamin transparansi antar tim dan partner.</li>
                    <li>Menyediakan laporan dan analitik secara real-time.</li>
                </ul>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('login') }}"
                    class="px-8 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                    Masuk ke Dashboard
                </a>
                <a href="https://www.pralon.co.id" target="_blank"
                    class="px-8 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition">
                    Kunjungi Website Pralon
                </a>
            </div>
        </div>

        <footer class="mt-10 mb-10 text-gray-500 text-sm">
            © {{ date('Y') }} PT Pralon. All rights reserved.
        </footer>
    </section>
</body>

</html>
