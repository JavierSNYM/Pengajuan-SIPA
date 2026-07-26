<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPA - Dashboard Admin TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#eef2f7] min-h-screen flex flex-col overflow-x-hidden">

    <nav class="bg-[#1e4b8f] text-white px-4 sm:px-8 py-3 sm:py-4 flex justify-between items-center shadow-md gap-2">
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-400 rounded-xl flex items-center justify-center text-lg sm:text-xl shadow-inner text-[#1e4b8f] shrink-0">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h1 class="font-extrabold text-sm sm:text-lg tracking-tight leading-none uppercase">SIPA - Panel Admin</h1>
                <p class="text-[8px] sm:text-[10px] text-blue-200 font-bold uppercase tracking-widest mt-1">Fakultas Teknik & Ilmu Komputer</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 sm:gap-6">
            <div class="hidden sm:flex text-right flex-col items-end">
                <p class="text-sm font-bold tracking-wide">{{ auth()->user()->name }}</p>
                <span class="bg-yellow-400 text-blue-900 text-[9px] font-extrabold px-2 py-0.5 rounded-full mt-1 uppercase tracking-wider">Staff TU</span>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 active:scale-95 px-3 py-2 sm:px-4 sm:py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-md shadow-red-500/20 group">
                    <span class="hidden sm:inline">KELUAR</span> 
                    <i class="fa-solid fa-right-from-bracket text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
                </button>
            </form>
        </div>
    </nav>

    <main class="flex-grow p-4 sm:p-8 max-w-6xl w-full mx-auto">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-6">
            <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Masuk</p>
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-800">{{ $totalMasuk ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl"><i class="fa-solid fa-inbox"></i></div>
            </div>
            <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Perlu Validasi</p>
                    <h3 class="text-3xl sm:text-4xl font-black text-amber-500">{{ $perluValidasi ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-xl"><i class="fa-solid fa-clock-rotate-left"></i></div>
            </div>
            <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center sm:col-span-2 md:col-span-1">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Selesai Dikirim</p>
                    <h3 class="text-3xl sm:text-4xl font-black text-emerald-500">{{ $selesaiDikirim ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl"><i class="fa-solid fa-circle-check"></i></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 text-amber-500 rounded-xl flex items-center justify-center text-2xl shadow-inner shrink-0">
                        <i class="fa-solid fa-user-lock"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm sm:text-base">Pusat Bantuan: Reset Sandi Mahasiswa</h3>
                        <p class="text-[10px] sm:text-xs text-slate-500 font-medium mt-0.5">Masukkan NPM mahasiswa yang lupa sandi untuk meresetnya menjadi <b class="text-slate-700">ftik12345</b></p>
                    </div>
                </div>
                
                <form action="{{ route('admin.resetSandiNpm') }}" method="POST" class="flex flex-col sm:flex-row w-full lg:w-auto gap-2" onsubmit="return confirm('Yakin ingin mereset sandi mahasiswa ini menjadi ftik12345?');">
                    @csrf
                    <input type="text" name="npm_reset" placeholder="Ketik NPM..." class="w-full lg:w-48 bg-slate-50 border border-slate-200 focus:border-amber-400 px-4 py-2.5 rounded-xl text-xs outline-none font-bold text-slate-700 transition-all h-[38px]" required>
                    <button type="submit" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 active:scale-95 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-md shadow-amber-500/20 whitespace-nowrap h-[38px] flex items-center justify-center">
                        <i class="fa-solid fa-rotate-right mr-1"></i> RESET
                    </button>
                </form>
            </div>

            @if(session('success'))
                <p class="text-xs text-emerald-600 font-bold mt-3 bg-emerald-50 p-2 rounded-lg border border-emerald-100"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</p>
            @endif
            @if(session('error'))
                <p class="text-xs text-red-500 font-bold mt-3 bg-red-50 p-2 rounded-lg border border-red-100"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</p>
            @endif
            @if($errors->has('npm_reset'))
                <p class="text-xs text-red-500 font-bold mt-3 bg-red-50 p-2 rounded-lg border border-red-100"><i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first('npm_reset') }}</p>
            @endif
        </div>

        @php $prodiAktif = $prodiAktif ?? '66'; @endphp
        
        <div class="flex overflow-x-auto border-b border-gray-200 mb-6 bg-white p-2 rounded-2xl shadow-sm gap-2">
            <a href="?prodi=66" class="flex-1 min-w-[120px] flex flex-col items-center justify-center py-3 px-2 rounded-xl transition-all {{ $prodiAktif == '66' ? 'bg-[#1e4b8f] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="fa-solid fa-laptop-code mb-1 text-base sm:text-lg"></i>
                <span class="font-bold text-[10px] sm:text-[11px] tracking-wider uppercase mb-1.5">Informatika</span>
                <span class="{{ $prodiAktif == '66' ? 'bg-yellow-400 text-blue-900' : 'bg-red-50 text-red-600 border border-red-100' }} text-[9px] font-black px-2.5 py-0.5 rounded-full">{{ $countInfo ?? 0 }} Msk</span>
            </a>
            
            <a href="?prodi=65" class="flex-1 min-w-[120px] flex flex-col items-center justify-center py-3 px-2 rounded-xl transition-all {{ $prodiAktif == '65' ? 'bg-[#1e4b8f] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="fa-solid fa-building mb-1 text-base sm:text-lg"></i>
                <span class="font-bold text-[10px] sm:text-[11px] tracking-wider uppercase mb-1.5">Sipil</span>
                <span class="{{ $prodiAktif == '65' ? 'bg-yellow-400 text-blue-900' : 'bg-red-50 text-red-600 border border-red-100' }} text-[9px] font-black px-2.5 py-0.5 rounded-full">{{ $countSipil ?? 0 }} Msk</span>
            </a>
            
            <a href="?prodi=64" class="flex-1 min-w-[120px] flex flex-col items-center justify-center py-3 px-2 rounded-xl transition-all {{ $prodiAktif == '64' ? 'bg-[#1e4b8f] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="fa-solid fa-gear mb-1 text-base sm:text-lg"></i>
                <span class="font-bold text-[10px] sm:text-[11px] tracking-wider uppercase mb-1.5">Mesin</span>
                <span class="{{ $prodiAktif == '64' ? 'bg-yellow-400 text-blue-900' : 'bg-red-50 text-red-600 border border-red-100' }} text-[9px] font-black px-2.5 py-0.5 rounded-full">{{ $countMesin ?? 0 }} Msk</span>
            </a>
            
            <a href="?prodi=63" class="flex-1 min-w-[120px] flex flex-col items-center justify-center py-3 px-2 rounded-xl transition-all {{ $prodiAktif == '63' ? 'bg-[#1e4b8f] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="fa-solid fa-industry mb-1 text-base sm:text-lg"></i>
                <span class="font-bold text-[10px] sm:text-[11px] tracking-wider uppercase mb-1.5">Industri</span>
                <span class="{{ $prodiAktif == '63' ? 'bg-yellow-400 text-blue-900' : 'bg-red-50 text-red-600 border border-red-100' }} text-[9px] font-black px-2.5 py-0.5 rounded-full">{{ $countIndustri ?? 0 }} Msk</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-4 sm:px-8 py-4 sm:py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-list-check text-blue-600 text-lg"></i>
                    <div>
                        <h2 class="font-bold text-slate-700 text-xs sm:text-sm tracking-wide">Daftar Antrean Pengajuan Lolos Seleksi</h2>
                        <span class="text-[9px] sm:text-[10px] font-bold text-blue-600 bg-blue-100 px-3 py-0.5 rounded-full inline-block mt-1 animate-pulse">SIPA LIVE DATA</span>
                    </div>
                </div>
                
                <a href="{{ route('admin.backup', $prodiAktif) }}" 
                   target="_blank" 
                   onclick="if(confirm('Download data backup prodi ini? Setelah didownload, daftar antrean di layar saat ini akan otomatis ter-reset/diarsipkan!')) { setTimeout(() => window.location.reload(), 2500); return true; } else { return false; }" 
                   class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                   <i class="fa-solid fa-file-word text-sm"></i> <span>DOWNLOAD DATA</span>
                </a>
            </div>

            <div class="p-0 overflow-x-auto w-full">
                @if($pengajuans->isEmpty())
                    <div class="p-12 flex flex-col items-center justify-center text-center">
                        <i class="fa-regular fa-folder-open text-6xl text-slate-200 mb-4"></i>
                        <p class="text-sm font-bold text-slate-500">Belum ada dokumen antrean pengajuan saat ini.</p>
                        <p class="text-xs text-slate-400 mt-1">Semua surat yang lolos seleksi otomatis dari mahasiswa prodi ini akan masuk ke sini.</p>
                    </div>
                @else
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                <th class="p-4 pl-6 font-bold hidden md:table-cell">Waktu Masuk</th>
                                <th class="p-4 font-bold">Mahasiswa</th>
                                <th class="p-4 font-bold">Jenis Surat</th>
                                <th class="p-4 font-bold hidden sm:table-cell">Rincian Keperluan</th>
                                <th class="p-4 font-bold text-center">Status</th>
                                <th class="p-4 pr-6 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach($pengajuans as $item)
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 pl-6 text-slate-500 font-medium hidden md:table-cell">
                                    {{ $item->created_at->format('d M Y') }}<br>
                                    <span class="text-[10px] text-slate-400">{{ $item->created_at->format('H:i') }} WIB</span>
                                </td>
                                <td class="p-4">
                                    <p class="font-bold text-slate-700">{{ $item->user->name ?? 'User Terhapus' }}</p>
                                    <p class="text-[10px] text-slate-500">NPM: {{ $item->user->npm ?? '-' }}</p>
                                    <p class="text-[9px] text-blue-500 font-semibold mt-1 block md:hidden">
                                        <i class="fa-regular fa-clock"></i> {{ $item->created_at->format('d M, H:i') }}
                                    </p>
                                </td>
                                <td class="p-4 font-bold text-blue-700">
                                    {{ $item->jenis_surat }}
                                </td>
                                <td class="p-4 text-slate-600 font-medium hidden sm:table-cell">
                                    @if($item->jenis_surat == 'Aktif Kuliah')
                                        Keperluan: {{ $item->keperluan }}
                                    @elseif($item->jenis_surat == 'Cuti')
                                        Semester: {{ $item->semester_cuti }}
                                    @else
                                        Tujuan: {{ $item->perusahaan }}
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if($item->status == 'Menunggu Validasi Admin')
                                        <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-1 rounded-md whitespace-nowrap">
                                            <i class="fa-solid fa-circle-notch fa-spin mr-1"></i> Menunggu
                                        </span>
                                    @else
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-1 rounded-md whitespace-nowrap">
                                            <i class="fa-solid fa-check mr-1"></i> Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <a href="{{ route('admin.show', $item->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-[10px] font-bold px-3 py-2 rounded-lg transition-all shadow-sm inline-block whitespace-nowrap">
                                        Cek <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>

    <footer class="text-center text-[11px] text-slate-400 font-medium py-6 mt-auto">
        &copy; 2026 SIPA Fakultas Teknik - Universitas Pancasakti Tegal. All Rights Reserved.
    </footer>
</body>
</html>