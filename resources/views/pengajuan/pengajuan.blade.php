<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPA - Dashboard Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col overflow-x-hidden">

    @php
        // Logika Deteksi Prodi Otomatis dari NPM
        $npmMhs = auth()->user()->npm ?? '';
        $kodeProdi = substr($npmMhs, 0, 2);
        $namaProdi = match($kodeProdi) {
            '66' => 'Teknik Informatika',
            '65' => 'Teknik Sipil',
            '64' => 'Teknik Mesin',
            '63' => 'Teknik Industri',
            default => 'Prodi Lainnya'
        };
    @endphp

    <nav class="bg-[#1e4b8f] text-white px-4 sm:px-8 py-3 sm:py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center shadow-md gap-4">
        
        <div class="flex items-center justify-between w-full sm:w-auto">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center text-xl shadow-inner text-[#1e4b8f] shrink-0">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-sm sm:text-lg tracking-tight leading-none uppercase">Portal Mahasiswa</h1>
                    <p class="text-[8px] sm:text-[10px] text-blue-200 font-bold uppercase tracking-widest mt-1">Fakultas Teknik & Ilmu Komputer</p>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="block sm:hidden shrink-0 ml-2">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 active:scale-95 p-2.5 rounded-xl text-xs font-bold transition-all shadow-md shadow-red-500/20 group">
                    <i class="fa-solid fa-right-from-bracket text-sm group-hover:translate-x-0.5 transition-transform"></i>
                </button>
            </form>
        </div>

        <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto border-t border-blue-700/50 sm:border-none pt-3 sm:pt-0">
            
            <div class="flex flex-col items-start sm:items-end w-full sm:w-auto">
                <p class="text-xs sm:text-sm font-bold tracking-wide">{{ auth()->user()->name ?? 'Mahasiswa' }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-1">
                    <span class="text-[9px] sm:text-[10px] text-blue-200 font-medium">NPM: {{ auth()->user()->npm ?? '-' }}</span>
                    <span class="bg-yellow-400 text-blue-900 text-[8px] sm:text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider">{{ $namaProdi }}</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="hidden sm:block ml-6">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 active:scale-95 px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-md shadow-red-500/20 group">
                    <span>KELUAR</span>
                    <i class="fa-solid fa-right-from-bracket text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
                </button>
            </form>
        </div>
    </nav>
    
    <main class="flex-grow p-4 sm:p-8 max-w-4xl w-full mx-auto">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200">
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight">Riwayat Dokumen Anda</h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium">Pantau status pengesahan surat secara langsung di sini.</p>
            </div>
            <a href="{{ route('pengajuan.create') }}" class="w-full sm:w-auto justify-center bg-gradient-to-r from-[#1e4b8f] to-blue-600 hover:scale-[1.02] active:scale-95 text-white px-5 sm:px-6 py-3 rounded-xl text-xs font-bold shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> AJUKAN SURAT BARU
            </a>
        </div>

        <div class="space-y-4 sm:space-y-5">
            @forelse($pengajuans as $item)
                <div class="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-8 shadow-sm border border-slate-200 relative overflow-hidden">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-2 @if($item->status == 'Selesai' || $item->status == 'Disetujui') bg-emerald-500 @elseif($item->status == 'Menunggu Validasi Admin') bg-amber-400 @else bg-rose-500 @endif"></div>

                    <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest">JENIS LAYANAN</span>
                            <h3 class="font-black text-slate-800 text-base sm:text-lg mt-0.5">{{ $item->jenis_surat }}</h3>
                            <p class="text-[10px] sm:text-xs text-slate-500 font-medium mt-1"><i class="fa-regular fa-calendar-check mr-1 text-blue-500"></i> Diajukan: {{ $item->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="relative mt-4 sm:mt-8 px-2 sm:px-6">
                        
                        <div class="flex items-center justify-between relative z-10 w-full">
                            
                            <div class="flex flex-col items-center gap-1 sm:gap-2 w-1/3 z-10">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/30 text-base sm:text-lg border-2 sm:border-4 border-white">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </div>
                                <span class="text-[9px] sm:text-[11px] font-bold text-blue-600 text-center leading-tight">Form Terkirim</span>
                            </div>

                            <div class="absolute left-[16%] right-[50%] h-1 sm:h-1.5 rounded-full z-0 
                                @if($item->status == 'Menunggu Validasi Admin' || $item->status == 'Selesai' || $item->status == 'Disetujui') bg-blue-500 
                                @else bg-slate-100 
                                @endif" style="top: 25%;"></div>

                            <div class="flex flex-col items-center gap-1 sm:gap-2 w-1/3 z-10">
                                @if($item->status == 'Menunggu Validasi Admin')
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-amber-400 text-white flex items-center justify-center shadow-lg shadow-amber-400/40 text-base sm:text-lg border-2 sm:border-4 border-white animate-pulse">
                                        <i class="fa-solid fa-gears"></i>
                                    </div>
                                    <span class="text-[9px] sm:text-[11px] font-bold text-amber-600 text-center leading-tight">Diproses TU</span>
                                @elseif($item->status == 'Selesai' || $item->status == 'Disetujui')
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30 text-base sm:text-lg border-2 sm:border-4 border-white">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <span class="text-[9px] sm:text-[11px] font-bold text-emerald-600 text-center leading-tight">Validasi Lolos</span>
                                @else
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center border-2 sm:border-4 border-white text-base sm:text-lg">
                                        <i class="fa-solid fa-circle-notch"></i>
                                    </div>
                                    <span class="text-[9px] sm:text-[11px] font-bold text-slate-400 text-center leading-tight">Menunggu</span>
                                @endif
                            </div>

                            <div class="absolute left-[50%] right-[16%] h-1 sm:h-1.5 rounded-full z-0 
                                @if($item->status == 'Selesai' || $item->status == 'Disetujui') bg-emerald-500 
                                @else bg-slate-100 
                                @endif" style="top: 25%;"></div>

                            <div class="flex flex-col items-center gap-1 sm:gap-2 w-1/3 z-10">
                                @if($item->status == 'Selesai' || $item->status == 'Disetujui')
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/40 text-base sm:text-lg border-2 sm:border-4 border-white animate-bounce">
                                        <i class="fa-solid fa-envelope-circle-check"></i>
                                    </div>
                                    <span class="text-[9px] sm:text-[11px] font-bold text-emerald-600 text-center leading-tight">Cek Email Anda</span>
                                @else
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center border-2 sm:border-4 border-white text-base sm:text-lg">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <span class="text-[9px] sm:text-[11px] font-bold text-slate-400 text-center leading-tight">Pengiriman PDF</span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-8 sm:p-12 text-center shadow-sm border border-slate-200 flex flex-col items-center justify-center">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-blue-50 rounded-full flex items-center justify-center mb-4 text-3xl sm:text-4xl text-blue-300">
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <h3 class="text-slate-700 font-extrabold text-lg sm:text-xl tracking-tight">Belum Ada Pengajuan Surat</h3>
                    <p class="text-slate-500 text-xs sm:text-sm mt-2 font-medium max-w-sm">Anda belum pernah mengajukan dokumen. Klik tombol di atas untuk memulai permintaan surat baru.</p>
                </div>
            @endforelse
        </div>
    </main>

    <footer class="text-center text-[10px] sm:text-[11px] text-slate-400 font-medium py-6 mt-auto">
        &copy; 2026 SIPA Fakultas Teknik - Universitas Pancasakti Tegal. All Rights Reserved.
    </footer>
</body>
</html>