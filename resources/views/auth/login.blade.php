<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPA Fakultas Teknik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Menghilangkan warna background Autofill bawaan browser */
        input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #ffffff inset !important;
            -webkit-text-fill-color: #111827 !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="min-h-screen relative overflow-y-auto">

    <div class="fixed inset-0 z-0">
        <img src="{{ asset('asset/img/bg-fakultas.jpg') }}" class="w-full h-full object-cover" alt="Background Fakultas">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    </div>

    <div class="relative z-10 min-h-screen flex items-center justify-center p-4 py-12">
        
        <div class="w-full max-w-sm bg-white rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.5)] p-8 border border-white/20">
            
            <div class="text-center mb-6 flex flex-col items-center">
            <div class="mb-4">
                <img src="{{ asset('asset/img/logo.png') }}" alt="Logo SIPA" class="w-24 h-24 object-contain drop-shadow-md">
            </div>

            <h1 class="text-xl font-extrabold text-gray-800 tracking-tight mb-1">SIPA Fakultas Teknik</h1>
            <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-1 px-2">Universitas Pancasakti Tegal</p>
            
            <div class="w-10 h-1 bg-blue-600 rounded-full mt-4 mb-4 opacity-20"></div>
        </div>

        @if(session('success'))
        <div class="mb-4 p-2.5 rounded-xl bg-green-50 border border-green-200 text-green-700 text-xs text-center font-medium">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
    <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><strong>Login Gagal!</strong> NPM atau Kata Sandi yang Anda masukkan salah.</span>
    </div>
@endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            
            <div class="mb-4 relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fa-regular fa-user"></i>
                </div>
                <input type="text" name="npm" id="npm" value="{{ old('npm') }}" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent block w-full pl-11 p-3 transition duration-200 outline-none" placeholder="Masukkan NPM" required>
            </div>

            <div class="mb-4 relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent block w-full pl-11 pr-11 p-3 transition duration-200 outline-none" placeholder="Kata Sandi" required>
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i class="fa-regular fa-eye-slash" id="eye-icon"></i>
                </button>
            </div>

            <div class="flex justify-between items-center mb-6 px-1">
                <label for="remember" class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-[11px] text-gray-500 font-medium">Ingat saya</span>
                </label>
                <a href="#" onclick="alert('Silakan hubungi Staf Admin Tata Usaha (TU) Fakultas Teknik untuk melakukan reset kata sandi Anda.'); return false;" class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 transition-colors">Lupa Sandi?</a>
            </div>

            <button type="submit" class="w-full text-white bg-gradient-to-r from-[#1e4b8f] to-blue-600 hover:from-blue-900 hover:to-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-xl text-sm px-5 py-3 text-center flex justify-center items-center gap-2 shadow-lg shadow-blue-600/30 transition-all duration-300 transform hover:-translate-y-0.5">
                Masuk ke SIPA <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>

            <div class="mt-5 text-center">
                <p class="text-[11px] text-gray-500">Belum punya akun? 
                    <a href="{{ url('/register') }}" class="font-bold text-blue-600 hover:text-blue-800 transition-colors">Buat Akun Baru</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>