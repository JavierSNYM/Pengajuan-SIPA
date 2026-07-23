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
<body class="bg-slate-100 min-h-screen flex flex-col">

    <nav class="bg-[#1e4b8f] text-white px-8 py-4 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-graduation-cap text-yellow-400"></i>
            </div>
            <div>
                <h1 class="font-extrabold text-lg tracking-tight leading-none uppercase">SIPA - PORTAL MAHASISWA</h1>
                <p class="text-[10px] text-blue-200 font-bold uppercase tracking-widest mt-1">Fakultas Teknik & Ilmu Komputer</p>
            </div>
        </div>
        
        @php
            // Logika Deteksi Prodi Otomatis dari NPM
            $npmMhs = auth()->user()->npm ?? '';
            $kodeProdi = substr($npmMhs, 0, 2);
            $namaProdi = match($kodeProdi) {
                '66' => 'Teknik Informatika',
                '65' => 'Teknik Sipil',
                '64' => 'Teknik Mesin',
                '63' => 'Teknik Industri',
                default => 'Prodi Tidak Diketahui'
            };
        @endphp

        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-sm font-bold tracking-wide">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-yellow-300 font-bold uppercase mt-0.5">
                    NPM: {{ auth()->user()->npm }} <span class="text-blue-200 font-semibold px-1">•</span> {{ $namaProdi }}
                </p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 active:scale-95 px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-md shadow-red-500/20">
                    LOGOUT <i class="fa-solid fa-power-off text-[11px]"></i>
                </button>
            </form>
        </div>
    </nav>
    

    <main class="flex-grow p-8 max-w-4xl w-full mx-auto">
        
        <div class="flex justify-between items-center mb-6 bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Riwayat Dokumen Anda</h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Pantau status pengesahan surat secara langsung di sini.</p>
            </div>
            <a href="{{ route('pengajuan.create') }}" class="bg-gradient-to-r from-[#1e4b8f] to-blue-600 hover:scale-105 active:scale-95 text-white px-6 py-3 rounded-xl text-xs font-bold shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> AJUKAN SURAT BARU
            </a>
        </div>

        <div class="space-y-5">
            @forelse($pengajuans as $item)
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200 relative overflow-hidden">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-2 @if($item->status == 'Selesai' || $item->status == 'Disetujui') bg-emerald-500 @elseif($item->status == 'Menunggu Validasi Admin') bg-amber-400 @else bg-rose-500 @endif"></div>

                    <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">JENIS LAYANAN</span>
                            <h3 class="font-black text-slate-800 text-lg mt-0.5">{{ $item->jenis_surat }}</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1"><i class="fa-regular fa-calendar-check mr-1 text-blue-500"></i> Diajukan pada: {{ $item->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between relative mt-8 px-6">
                        
                        <div class="absolute top-1/2 left-12 right-12 h-1.5 bg-slate-100 -translate-y-1/2 z-0 rounded-full"></div>

                        @if($item->status == 'Menunggu Validasi Admin')
                            <div class="absolute top-1/2 left-12 right-1/2 h-1.5 bg-blue-500 -translate-y-1/2 z-0 rounded-full"></div>
                        @elseif($item->status == 'Selesai' || $item->status == 'Disetujui')
                            <div class="absolute top-1/2 left-12 right-12 h-1.5 bg-emerald-500 -translate-y-1/2 z-0 rounded-full"></div>
                        @endif

                        <div class="relative z-10 flex flex-col items-center gap-2">
                            <div class="w-12 h-12 rounded-full bg-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/30 text-lg border-4 border-white">
                                <i class="fa-solid fa-paper-plane"></i>
                            </div>
                            <span class="text-[11px] font-bold text-blue-600">Form Terkirim</span>
                        </div>

                        <div class="relative z-10 flex flex-col items-center gap-2">
                            @if($item->status == 'Menunggu Validasi Admin')
                                <div class="w-12 h-12 rounded-full bg-amber-400 text-white flex items-center justify-center shadow-lg shadow-amber-400/40 text-lg border-4 border-white animate-pulse">
                                    <i class="fa-solid fa-gears"></i>
                                </div>
                                <span class="text-[11px] font-bold text-amber-600">Diproses TU</span>
                            @elseif($item->status == 'Selesai' || $item->status == 'Disetujui')
                                <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30 text-lg border-4 border-white">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <span class="text-[11px] font-bold text-emerald-600">Validasi Lolos</span>
                            @else
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center border-4 border-white text-lg">
                                    <i class="fa-solid fa-circle-notch"></i>
                                </div>
                                <span class="text-[11px] font-bold text-slate-400">Menunggu</span>
                            @endif
                        </div>

                        <div class="relative z-10 flex flex-col items-center gap-2">
                            @if($item->status == 'Selesai' || $item->status == 'Disetujui')
                                <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/40 text-lg border-4 border-white animate-bounce">
                                    <i class="fa-solid fa-envelope-circle-check"></i>
                                </div>
                                <span class="text-[11px] font-bold text-emerald-600">Cek Email Anda</span>
                            @else
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center border-4 border-white text-lg">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <span class="text-[11px] font-bold text-slate-400">Pengiriman PDF</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-slate-200 flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-4 text-4xl text-blue-300">
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <h3 class="text-slate-700 font-extrabold text-xl tracking-tight">Belum Ada Pengajuan Surat</h3>
                    <p class="text-slate-500 text-sm mt-2 font-medium max-w-sm">Anda belum pernah mengajukan dokumen. Klik tombol di atas untuk memulai permintaan surat baru.</p>
                </div>
            @endforelse
        </div>
    </main>

    <footer class="text-center text-[11px] text-slate-400 font-medium py-6 mt-auto">
        &copy; 2026 SIPA Fakultas Teknik - Universitas Pancasakti Tegal. All Rights Reserved.
    </footer>
</body>
</html>