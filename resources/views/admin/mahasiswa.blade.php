<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPA - Kelola Data Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#eef2f7] min-h-screen flex flex-col overflow-x-hidden">

    <nav class="bg-[#1e4b8f] text-white px-4 sm:px-8 py-3 sm:py-4 flex justify-between items-center shadow-md gap-2">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.index') }}" class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-lg transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="font-extrabold text-sm sm:text-lg tracking-tight leading-none uppercase">Kelola Mahasiswa</h1>
                <p class="text-[8px] sm:text-[10px] text-blue-200 font-bold uppercase tracking-widest mt-1">SIPA FTIK UPS TEGAL</p>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-4 sm:p-8 max-w-6xl w-full mx-auto">
        
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-200 font-bold text-sm shadow-sm flex items-center gap-3 mb-6">
                <i class="fa-solid fa-check-circle text-xl"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-200 font-bold text-sm shadow-sm flex items-center gap-3 mb-6">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                <div>
                    {{ session('error') }}
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
                <h3 class="font-bold text-slate-800 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-user-plus text-blue-600"></i> Tambah Manual (Satuan)
                </h3>
                <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">NPM Mahasiswa</label>
                        <input type="text" name="npm" placeholder="Contoh: 6622600001" class="w-full bg-slate-50 border border-slate-200 focus:border-blue-500 px-4 py-2.5 rounded-xl text-sm outline-none font-semibold text-slate-700 transition-all" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Nama Lengkap</label>
                        <input type="text" name="nama_mahasiswa" placeholder="Ketik nama lengkap..." class="w-full bg-slate-50 border border-slate-200 focus:border-blue-500 px-4 py-2.5 rounded-xl text-sm outline-none font-semibold text-slate-700 transition-all" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-95 text-white px-5 py-3 rounded-xl text-xs font-bold transition-all shadow-md shadow-blue-600/20">
                        SIMPAN DATA MAHASISWA
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
                <h3 class="font-bold text-slate-800 flex items-center gap-2 mb-2">
                    <i class="fa-solid fa-file-excel text-emerald-600"></i> Import Data Excel (Massal)
                </h3>
                <p class="text-[10px] text-slate-500 mb-4 font-medium leading-relaxed">Format File Excel wajib memiliki header di baris pertama: <b>npm</b> dan <b>nama_mahasiswa</b>. (Hanya menerima file .xlsx atau .csv).</p>
                
                <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:bg-slate-50 transition-colors relative cursor-pointer group">
                        <input type="file" id="upload_excel" name="file_excel" accept=".xlsx, .xls, .csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required 
                        onchange="document.getElementById('teks_nama_file').innerText = this.files[0].name; document.getElementById('ikon_upload').className = 'fa-solid fa-file-circle-check text-3xl text-emerald-500 mb-2';">
                        
                        <i id="ikon_upload" class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 group-hover:text-emerald-500 transition-colors mb-2"></i>
                        <p id="teks_nama_file" class="text-xs font-bold text-slate-600">Klik atau Drag & Drop File Excel di sini</p>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white px-5 py-3 rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-600/20">
                        IMPORT FILE SEKARANG
                    </button>
                </form>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('admin.mahasiswa', ['prodi' => '66']) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm {{ $prodiAktif === '66' ? 'bg-[#1e4b8f] text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <i class="fa-solid fa-laptop-code"></i> Informatika
                <span class="px-2 py-0.5 rounded-md text-[10px] {{ $prodiAktif === '66' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $countInfo }}</span>
            </a>
            <a href="{{ route('admin.mahasiswa', ['prodi' => '65']) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm {{ $prodiAktif === '65' ? 'bg-[#1e4b8f] text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <i class="fa-solid fa-building"></i> Sipil
                <span class="px-2 py-0.5 rounded-md text-[10px] {{ $prodiAktif === '65' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $countSipil }}</span>
            </a>
            <a href="{{ route('admin.mahasiswa', ['prodi' => '64']) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm {{ $prodiAktif === '64' ? 'bg-[#1e4b8f] text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <i class="fa-solid fa-gear"></i> Mesin
                <span class="px-2 py-0.5 rounded-md text-[10px] {{ $prodiAktif === '64' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $countMesin }}</span>
            </a>
            <a href="{{ route('admin.mahasiswa', ['prodi' => '63']) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm {{ $prodiAktif === '63' ? 'bg-[#1e4b8f] text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <i class="fa-solid fa-industry"></i> Industri
                <span class="px-2 py-0.5 rounded-md text-[10px] {{ $prodiAktif === '63' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $countIndustri }}</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center flex-wrap gap-4">
                <h2 class="font-bold text-slate-700 text-sm tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-users text-blue-600"></i> Data Whitelist - Prodi 
                    <span class="text-blue-700">
                        {{ match($prodiAktif) { '66' => 'Teknik Informatika', '65' => 'Teknik Sipil', '64' => 'Teknik Mesin', '63' => 'Teknik Industri', default => '' } }}
                    </span>
                </h2>
                
                <button type="submit" form="form-hapus-massal" onclick="return confirm('PERINGATAN: Yakin ingin menghapus semua data mahasiswa yang dicentang?')" class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-xl text-[11px] font-bold transition-all flex items-center gap-2 shadow-sm active:scale-95">
                    <i class="fa-solid fa-trash-can-arrow-up"></i> HAPUS TERPILIH
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <form id="form-hapus-massal" action="{{ route('admin.mahasiswa.hapusMassal') }}" method="POST">
                    @csrf
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                <th class="p-4 pl-6 font-bold w-12 text-center">
                                    <input type="checkbox" id="check-all" onclick="toggleCheckboxes(this)" class="w-4 h-4 cursor-pointer accent-blue-600 rounded">
                                </th>
                                <th class="p-4 font-bold w-16">No</th>
                                <th class="p-4 font-bold">NPM</th>
                                <th class="p-4 font-bold">Nama Mahasiswa</th>
                                <th class="p-4 pr-6 font-bold text-right w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs text-slate-700">
                            @forelse($mahasiswas as $index => $mhs)
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 pl-6 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $mhs->id }}" class="check-item w-4 h-4 cursor-pointer accent-blue-600 rounded">
                                </td>
                                <td class="p-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                                <td class="p-4 font-bold text-blue-600">{{ $mhs->npm }}</td>
                                <td class="p-4 font-semibold">{{ $mhs->nama_mahasiswa }}</td>
                                <td class="p-4 pr-6 text-right">
                                    <button type="button" onclick="if(confirm('Hapus mahasiswa ini?')) { document.getElementById('delete-form-{{ $mhs->id }}').submit(); }" class="w-8 h-8 bg-rose-50 hover:bg-rose-500 text-rose-500 hover:text-white rounded-lg inline-flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 font-medium bg-slate-50/20">
                                    <i class="fa-solid fa-folder-open text-2xl block mb-2 text-slate-300"></i>
                                    Tidak ada data mahasiswa untuk Program Studi ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>

                @foreach($mahasiswas as $mhs)
                <form id="delete-form-{{ $mhs->id }}" action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                @endforeach

            </div>
        </div>
    </main>

    <script>
        function toggleCheckboxes(source) {
            let checkboxes = document.querySelectorAll('.check-item');
            for(let i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</body>
</html>