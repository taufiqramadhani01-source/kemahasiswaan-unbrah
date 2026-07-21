<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Backoffice - Kemahasiswaan Unbrah</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-unbrah.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-[#fcfbf9] text-[#1a2421] min-h-screen flex items-center justify-center p-6 antialiased selection:bg-[#c8b282]/20">

    <!-- Outer Container -->
    <div class="w-full max-w-md">
        
        <!-- Logo & Title -->
        <div class="mb-10 text-center">
            <img src="{{ asset('images/logo-unbrah.png') }}" alt="Logo" class="mx-auto h-16 w-auto mb-4 object-contain">
            <h2 class="text-xl font-bold tracking-tight text-[#052c22] uppercase">Kemahasiswaan</h2>
            <p class="text-[10px] text-[#8c887d] uppercase tracking-widest font-bold mt-1">Situs Portal Administrasi</p>
        </div>

        <!-- Login Card (Minimal White with Gold/Sand border) -->
        <div class="bg-white border border-[#e5e2d9] p-8 rounded-2xl shadow-sm">
            @if($errors->any())
                <div class="p-4 mb-6 rounded-xl bg-red-50 border border-red-100 text-xs text-red-700 font-semibold">
                    Kredensial yang dimasukkan salah. Silakan coba lagi.
                </div>
            @endif

            <form method="POST" action="/admin/login" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d] mb-1.5">Email Akun</label>
                    <input type="email" name="email" id="email" required placeholder="admin.mahasiswa@unbrah.ac.id" 
                        class="w-full px-4 py-2.5 rounded-xl border border-[#dcd9d0] bg-[#faf9f6] text-[#1a2421] placeholder-slate-400 focus:outline-none focus:border-[#c8b282] focus:bg-white transition text-sm">
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-[10px] font-bold uppercase tracking-wider text-[#8c887d]">Password</label>
                    </div>
                    <input type="password" name="password" id="password" required placeholder="••••••••" 
                        class="w-full px-4 py-2.5 rounded-xl border border-[#dcd9d0] bg-[#faf9f6] text-[#1a2421] placeholder-slate-400 focus:outline-none focus:border-[#c8b282] focus:bg-white transition text-sm">
                </div>

                <!-- Submit Button (Muted Gold/Camel accent color matching reference) -->
                <button type="submit" 
                    class="w-full py-3 px-4 rounded-xl bg-[#052c22] hover:bg-[#073d30] text-[#c8b282] hover:text-[#e4d4b3] font-bold text-xs uppercase tracking-wider transition duration-150">
                    Masuk Ke Dasbor
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 text-[10px] text-[#a5a195] uppercase tracking-wider font-bold">
            &copy; 2026 Universitas Baiturrahmah
        </div>
    </div>

</body>
</html>
