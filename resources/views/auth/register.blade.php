<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SIPA Fakultas Teknik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen relative flex items-center justify-center p-4 overflow-hidden bg-slate-900">

    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-[#1e4b8f] via-slate-900 to-blue-900 opacity-90"></div>
    </div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.5)] p-8 border border-white/20">
        
        <div class="text-center mb-6 flex flex-col items-center">
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight mb-1">Buat Akun SIPA</h1>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1 px-2">Fakultas Teknik & Ilmu Komputer</p>
            <div class="w-12 h-1 bg-blue-600 rounded-full mt-4 mb-2 opacity-20"></div>
        </div>

        @if($errors->any())
        <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold shadow-sm">
            <div class="flex items-center gap-2 mb-1 text-red-800">
                <i class="fa-solid fa-triangle-exclamation"></i> Pendaftaran Gagal:
            </div>
            <ul class="list-disc pl-5 space-y-1 mt-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ url('/register') }}" method="POST">
            @csrf
            
            <div class="mb-4 relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <input type="text" name="name" value="{{ old('name') }}" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 block w-full pl-11 p-3 outline-none font-medium transition-all" placeholder="Nama Lengkap" required autocomplete="off">
            </div>

            <div class="mb-4 relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fa-solid fa-city"></i>
                </div>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 block w-full pl-11 p-3 outline-none font-medium transition-all" placeholder="Tempat Lahir (Contoh: Tegal)" required autocomplete="off">
            </div>

            <div class="mb-4 relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 block w-full pl-11 p-3 outline-none font-medium transition-all" required>
            </div>

            <div class="mb-4 relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fa-solid fa-address-card"></i>
                </div>
                <input type="text" name="npm" value="{{ old('npm') }}" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 block w-full pl-11 p-3 outline-none font-medium transition-all" placeholder="Masukkan NPM Valid" required autocomplete="off">
            </div>

            <div class="mb-4 relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="password" id="password" name="password" minlength="8" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 block w-full pl-11 pr-12 p-3 outline-none font-medium transition-all" placeholder="Buat Kata Sandi (Min. 8 Karakter)" required>
                
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 cursor-pointer text-gray-400 hover:text-blue-600 transition-colors" onclick="togglePassword('password', 'eye_pass')">
                    <i class="fa-solid fa-eye" id="eye_pass"></i>
                </div>
            </div>

            <div class="mb-6 relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <input type="password" id="password_confirmation" name="password_confirmation" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 block w-full pl-11 pr-12 p-3 outline-none font-medium transition-all" placeholder="Ulangi Kata Sandi" required>
                
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 cursor-pointer text-gray-400 hover:text-blue-600 transition-colors" onclick="togglePassword('password_confirmation', 'eye_conf')">
                    <i class="fa-solid fa-eye" id="eye_conf"></i>
                </div>
            </div>

            <button type="submit" class="w-full text-white bg-gradient-to-r from-[#1e4b8f] to-blue-600 hover:scale-[1.02] active:scale-[0.98] font-bold rounded-xl text-sm px-5 py-3.5 text-center flex justify-center items-center gap-2 shadow-lg shadow-blue-600/30 transition-all duration-300">
                DAFTAR SEKARANG <i class="fa-solid fa-user-plus"></i>
            </button>

            <div class="mt-5 text-center">
                <p class="text-[11px] text-gray-500 font-medium">Sudah punya akun? 
                    <a href="{{ url('/login') }}" class="font-bold text-blue-600 hover:text-blue-800 transition-colors">Masuk di sini</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>