<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPA - Portal Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        /* Custom scrollbar untuk kotak preview */
        .preview-scroll::-webkit-scrollbar { width: 5px; }
        .preview-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .preview-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
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

    <main class="flex-grow p-8 max-w-2xl w-full mx-auto flex flex-col justify-center">
        
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl mb-6 flex items-start gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-xl text-emerald-500 mt-0.5"></i>
            <div class="text-xs">
                <p class="font-bold">Sistem Validasi Lolos:</p>
                <p class="mt-1 text-emerald-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl mb-6 flex items-start gap-3 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation text-xl text-rose-500 mt-0.5"></i>
            <div class="text-xs">
                <p class="font-bold text-rose-900">Aturan Validasi Sistem Menolak Berkas:</p>
                <ul class="list-disc pl-5 space-y-1 mt-1 font-medium">
             @foreach(array_unique($errors->all()) as $error)
                 <li>{{ $error }}</li>
                 @endforeach
                    </ul>
            </div>
        </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-blue-700 bg-white border border-slate-200 hover:border-blue-300 px-4 py-2 rounded-xl transition-all shadow-sm active:scale-95">
                <i class="fa-solid fa-arrow-left"></i> KEMBALI KE DASHBOARD
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-md border border-slate-200/60 overflow-hidden">
            
            <div class="relative w-full bg-[#1e4b8f] flex flex-col justify-center overflow-hidden">
                <img src="/asset/img/falkutas_ftik.png" alt="Banner FTIK" class="w-full h-auto object-contain z-0">
            </div>

            <div class="px-8 pt-6 pb-2 border-b border-slate-100 bg-slate-50/50">
                <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-[#1e4b8f]"></i> Formulir Permohonan Dokumen Administrasi
                </h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-1">SIPA Fakultas Teknik & Ilmu Komputer - Universitas Pancasakti Tegal</p>
            </div>

            <form id="formPengajuan" action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-8 space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email Aktif Mahasiswa</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"><i class="fa-solid fa-envelope text-xs"></i></span>
                            <input type="email" name="email_aktif" value="{{ old('email_aktif') }}" placeholder="Contoh: nama@gmail.com" class="w-full bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white pl-11 pr-4 py-3 rounded-xl text-xs transition-all outline-none font-medium" >
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1.5">*Surat yang selesai divalidasi Admin TU akan otomatis dikirimkan ke email ini.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Jenis Layanan Surat</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"><i class="fa-solid fa-folder-tree text-xs"></i></span>
                            <select name="jenis_surat" id="jenis_surat" onchange="kendaliFormBunglon()" class="w-full bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white pl-11 pr-4 py-3 rounded-xl text-xs transition-all outline-none font-semibold text-slate-700 appearance-none cursor-pointer" >
                                <option value="">-- Silakan Pilih Surat --</option>
                                <option value="Aktif Kuliah">Surat Pernyataan Aktif Kuliah</option>
                                <option value="Cuti">Surat Permohonan Cuti Kuliah</option>
                                <option value="PKL">Surat Pengantar KKL / PKL</option>
                                <option value="Peminjaman Ruangan">Surat Peminjaman Ruangan</option>
                            </select>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 pointer-events-none"><i class="fa-solid fa-chevron-down text-[10px]"></i></span>
                        </div>
                    </div>

                    <div id="blok_aktif_kuliah" class="hidden bg-blue-50/40 border border-blue-100 rounded-2xl p-6 space-y-4">
                        <div>
                            <label class="block text-[11px] font-bold text-blue-800 mb-1.5">Keperluan Pengambilan Dokumen:</label>
                            <select name="keperluan" id="keperluan" onchange="kendaliFormBunglon()" class="w-full bg-white border border-blue-200 focus:border-blue-500 px-3 py-2.5 rounded-xl text-xs font-medium text-slate-700 outline-none">
                                <option value="">-- Pilih Keperluan Dokumen --</option>
                                <option value="BPJS">Keperluan Pengaktifan Kembali BPJS Kesehatan</option>
                                <option value="Beasiswa">Persyaratan Pendaftaran Beasiswa / Instansi</option>
                                <option value="Tunjangan">Tunjangan Gaji Orang Tua (PNS / TNI / POLRI / Karyawan)</option>
                            </select>
                        </div>

                        <div id="sub_data_ortu" class="hidden bg-white border border-blue-200 rounded-xl p-4 space-y-3 shadow-sm">
                            <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-id-card-clip text-xs"></i> Data Kepegawaian Orang Tua (Wajib):
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                                <input type="text" name="nama_ortu" placeholder="Nama Lengkap Ayah / Ibu" class="border border-slate-200 p-2.5 rounded-lg text-[11px] outline-none focus:border-blue-500 font-medium bg-slate-50/40">
                                <input type="text" name="nip_ortu" placeholder="NIP / NRP Orang Tua" class="border border-slate-200 p-2.5 rounded-lg text-[11px] outline-none focus:border-blue-500 font-medium bg-slate-50/40">
                                <input type="text" name="pangkat_ortu" placeholder="Pangkat / Golongan (ex: Pembina / IVa)" class="border border-slate-200 p-2.5 rounded-lg text-[11px] outline-none focus:border-blue-500 font-medium bg-slate-50/40">
                                <input type="text" name="pekerjaan_ortu" placeholder="Pekerjaan / Instansi Tempat Kerja" class="border border-slate-200 p-2.5 rounded-lg text-[11px] outline-none focus:border-blue-500 font-medium bg-slate-50/40">
                            </div>
                        </div>

                        <div class="space-y-3 pt-2 border-t border-blue-100/60">
                            <p class="text-[10px] font-bold text-blue-800 uppercase tracking-wider">Lengkapi Persyaratan Dokumen</p>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-bold mb-1">1. SCAN KTM (WAJIB PDF, MAKSIMAL 2MB) <span class="text-red-500 text-sm">*</span></label>
                                <input type="file" name="file_ktm" class="w-full bg-white border border-slate-200 p-2 rounded-xl text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-bold mb-1">2. SCAN SLIP PEMBAYARAN UKT TERAKHIR (WAJIB PDF, MAKSIMAL 2MB) <span class="text-red-500 text-sm">*</span></label>
                                <input type="file" name="file_ukt" class="w-full bg-white border border-slate-200 p-2 rounded-xl text-xs font-medium" >
                            </div>
                        </div>
                    </div>

                    <div id="blok_cuti" class="hidden bg-amber-50/40 border border-amber-100 rounded-2xl p-5 space-y-4">
                        <p class="text-xs font-bold text-amber-800"><i class="fa-solid fa-calendar-day mr-1"></i> Rincian Target Periode Cuti Kuliah:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] text-slate-600 font-bold mb-1">Cuti Pada Semester (Tulisan Huruf):</label>
                                <input type="text" name="semester_cuti" placeholder="Contoh: Empat - Genap" class="w-full bg-white border border-amber-200 focus:border-amber-500 px-3 py-2.5 rounded-xl text-xs outline-none font-medium">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-600 font-bold mb-1">Tahun Akademik Target Cuti:</label>
                                <input type="text" name="tahun_akademik" placeholder="Contoh: 2025/2026" class="w-full bg-white border border-amber-200 focus:border-amber-500 px-3 py-2.5 rounded-xl text-xs outline-none font-medium">
                            </div>
                        </div>
                        
                        <div class="pt-3 border-t border-amber-100">
                            <label class="block text-[10px] text-slate-700 font-bold mb-1.5">
                                UPLOAD SURAT KETERANGAN ALASAN CUTI DARI ORANG TUA / PERUSAHAAN (WAJIB PDF, MAX 2MB) <span class="text-red-500 text-sm">*</span>
                            </label>
                            
                            <input type="file" name="surat_keterangan_cuti" class="w-full bg-white border border-slate-200 p-2 rounded-xl text-xs font-medium focus:border-amber-500" >
                            
                            <div class="mt-3 p-4 bg-white border border-amber-200 rounded-xl shadow-sm relative overflow-hidden">
                                <div class="absolute left-0 top-0 w-1 h-full bg-amber-400"></div>
                                <p class="text-[10px] font-bold text-amber-600 mb-2 flex items-center gap-1.5 ml-1">
                                    <i class="fa-solid fa-file-lines"></i> CONTOH FORMAT SURAT KETERANGAN:
                                </p>
                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 text-[10px] text-slate-600 font-mono leading-relaxed">
                                    <strong>Hal:</strong> Surat Keterangan Persetujuan Cuti Kuliah<br><br>
                                    Saya yang bertanda tangan di bawah ini, selaku Orang Tua / Pimpinan Perusahaan dari mahasiswa:<br>
                                    Nama : (Nama Mahasiswa)<br>
                                    NPM  : (NPM)<br><br>
                                    Menyatakan mengetahui dan <b>menyetujui</b> pengajuan Cuti Akademik mahasiswa tersebut dengan alasan: (Sebutkan alasan, misal: Sakit / Bekerja / Masalah Keluarga).<br><br>
                                    Tegal, (Tanggal)<br>
                                    Hormat Saya,<br><br><br>
                                    (Tanda Tangan & Nama Terang)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="blok_pkl" class="hidden bg-teal-50/40 border border-teal-100 rounded-2xl p-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-teal-800 mb-1">Nama Perusahaan / Tempat Magang KKL/PKL:</label>
                            <input type="text" name="perusahaan" placeholder="Contoh: PT. PLN (Persero) Area Tegal" class="w-full bg-white border border-teal-200 focus:border-teal-500 px-3 py-2.5 rounded-xl text-xs outline-none font-medium">
                        </div>
                        
                        <div class="space-y-3 pt-3 border-t border-teal-100">
                            <p class="text-[10px] font-bold text-teal-800 uppercase tracking-wider mb-2 flex items-center gap-1">
                                <i class="fa-solid fa-list-check"></i> Lengkapi Persyaratan Dokumen
                            </p>
                            
                            <div>
                                <label class="block text-[10px] text-slate-500 font-bold mb-1">1. SCAN KRS TERKINI (WAJIB PDF, MAX 2MB) <span class="text-red-500 text-sm">*</span></label>
                                <input type="file" name="file_krs" class="w-full bg-white border border-slate-200 p-2 rounded-xl text-xs font-medium focus:border-teal-500" >
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-bold mb-1">2. SCAN TRANSKRIP NILAI (WAJIB PDF, MAX 2MB) <span class="text-red-500 text-sm">*</span></label>
                                <input type="file" name="file_transkrip" class="w-full bg-white border border-slate-200 p-2 rounded-xl text-xs font-medium focus:border-teal-500" >
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-bold mb-1">3. SCAN BUKTI PEMBAYARAN PKL/KKL (WAJIB PDF, MAX 2MB) <span class="text-red-500 text-sm">*</span></label>
                                <input type="file" name="file_pembayaran" class="w-full bg-white border border-slate-200 p-2 rounded-xl text-xs font-medium focus:border-teal-500" >
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-bold mb-1">4. SCAN SURAT PUAS KKL (WAJIB PDF, MAX 2MB) <span class="text-red-500 text-sm">*</span></label>
                                <input type="file" name="file_puas_kkl" class="w-full bg-white border border-slate-200 p-2 rounded-xl text-xs font-medium focus:border-teal-500" >
                            </div>
                        </div>
                    </div>

                    <div id="blok_ruangan" class="hidden bg-purple-50/40 border border-purple-100 rounded-2xl p-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-purple-800 mb-1">Nama Ruangan / Gedung yang Ingin Dipinjam:</label>
                            <input type="text" name="nama_ruangan" placeholder="Contoh: Aula Gedung Lantai 3 FTIK" class="w-full bg-white border border-purple-200 focus:border-purple-500 px-3 py-2.5 rounded-xl text-xs outline-none font-medium">
                        </div>
                        
                        <div class="pt-3 border-t border-purple-100">
                            <label class="block text-[10px] text-slate-700 font-bold mb-1.5">
                                UPLOAD FILE PROPOSAL PEMINJAMAN (WAJIB PDF, MAKSIMAL 2MB) <span class="text-red-500 text-sm">*</span>
                            </label>
                            <input type="file" name="file_proposal" class="w-full bg-white border border-slate-200 p-2 rounded-xl text-xs font-medium focus:border-purple-500" >
                            
                            <div class="mt-3 p-4 bg-white border border-purple-200 rounded-xl shadow-sm relative overflow-hidden">
                                <div class="absolute left-0 top-0 w-1 h-full bg-purple-400"></div>
                                <p class="text-[10px] font-bold text-purple-600 mb-2 flex items-center gap-1.5 ml-1">
                                    <i class="fa-solid fa-file-pdf"></i> PATOKAN SUSUNAN PROPOSAL:
                                </p>
                                
                                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 text-[10px] text-slate-700 font-mono leading-relaxed h-56 overflow-y-auto preview-scroll shadow-inner">
                                    <div class="text-center font-bold mb-3 underline text-[11px]">PROPOSAL PEMINJAMAN RUANGAN</div>
                                    
                                    <strong class="text-purple-800">A. LATAR BELAKANG</strong><br>
                                    Dalam rangka meningkatkan soft skill... kami dari [Nama Organisasi] bermaksud mengadakan kegiatan [Nama Kegiatan]. Untuk menunjang kelancaran kegiatan tersebut, kami membutuhkan fasilitas ruangan kampus.<br><br>
                                    
                                    <strong class="text-purple-800">B. DETAIL KEGIATAN & PEMINJAMAN</strong><br>
                                    <table class="w-full mt-1 mb-2">
                                        <tr><td width="35%">Nama Kegiatan</td><td width="5%">:</td><td>[Nama Kegiatan]</td></tr>
                                        <tr><td>Hari / Tanggal</td><td>:</td><td>[Hari, Tanggal Bulan]</td></tr>
                                        <tr><td>Waktu</td><td>:</td><td>[08.00 WIB] s/d Selesai</td></tr>
                                        <tr><td>Ruangan</td><td>:</td><td>[Nama Ruangan]</td></tr>
                                    </table>
                                    
                                    <strong class="text-purple-800">C. PENUTUP</strong><br>
                                    Demikian proposal permohonan peminjaman ruangan ini kami sampaikan. Kami berharap Bapak/Ibu pimpinan dapat memberikan izin...<br><br>
                                    
                                    <div class="flex justify-between text-center mt-5 mb-4 border-t border-slate-200 pt-4">
                                        <div class="w-1/2">
                                            Hormat Kami,<br>Pemohon / Peminjam<br><br><br><br>
                                            <b>( [Nama Peminjam] )</b><br>NPM. [NPM]
                                        </div>
                                        <div class="w-1/2">
                                            Mengetahui,<br>Ketua Program Studi<br><br><br><br>
                                            <b>( [Nama Kaprodi] )</b><br>NIDN. [NIDN]
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        Mengesahkan,<br>Dekan FTIK<br><br><br><br>
                                        <b>( Rusnoto, ST., M.Eng )</b><br>NIPY. 14054121974
                                    </div>
                                </div>
                                <p class="text-[9px] text-purple-500 mt-2 font-medium bg-purple-50 p-2 rounded-lg">*Gunakan format dan susunan tanda tangan di atas sebagai patokan pembuatan proposal kegiatan Anda.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" id="btnSubmitForm" class="w-full bg-[#1e4b8f] hover:bg-blue-800 active:scale-[0.99] text-white font-bold py-3.5 px-6 rounded-xl text-xs tracking-wider uppercase transition-all shadow-lg shadow-blue-800/10 flex items-center justify-center gap-2">
                            KIRIM PENGAJUAN <i class="fa-solid fa-paper-plane text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <footer class="text-center text-[11px] text-slate-400 font-medium py-6">
        &copy; 2026 SIPA Fakultas Teknik - Universitas Pancasakti Tegal.
    </footer>

    <script>
        function kendaliFormBunglon() {
            let jenis = document.getElementById('jenis_surat').value;
            let keperluan = document.getElementById('keperluan').value;

            let blokAktif = document.getElementById('blok_aktif_kuliah');
            let blokCuti = document.getElementById('blok_cuti');
            let blokPkl = document.getElementById('blok_pkl');
            let blokRuangan = document.getElementById('blok_ruangan');
            let subOrtu = document.getElementById('sub_data_ortu');

            // Menyembunyikan seluruh blok form di awal
            blokAktif.classList.add('hidden');
            blokCuti.classList.add('hidden');
            blokPkl.classList.add('hidden');
            blokRuangan.classList.add('hidden');
            subOrtu.classList.add('hidden');

            // Menampilkan blok form secara dinamis berdasarkan input user
            if (jenis === "Aktif Kuliah") {
                blokAktif.classList.remove('hidden');
                if (keperluan === "Tunjangan") {
                    subOrtu.classList.remove('hidden');
                }
            } else if (jenis === "Cuti") {
                blokCuti.classList.remove('hidden');
            } else if (jenis === "PKL") {
                blokPkl.classList.remove('hidden');
            } else if (jenis === "Peminjaman Ruangan") {
                blokRuangan.classList.remove('hidden');
            }
        }

        // 🌟 SCRIPT ANTI SPAM KLIK DITAMBAHKAN DI SINI 🌟
        document.getElementById('formPengajuan').addEventListener('submit', function(e) {
            let btn = document.getElementById('btnSubmitForm');
            // Matikan tombol seketika setelah klik pertama
            btn.disabled = true;
            // Ubah tampilan tombol jadi agak redup
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            // Ganti teks tombolnya
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-[10px]"></i> SEDANG MEMPROSES...';
        });
    </script>
</body>
</html>