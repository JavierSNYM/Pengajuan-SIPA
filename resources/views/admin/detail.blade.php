<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPA - Detail Berkas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        .ck-editor__editable_inline { min-height: 280px; font-size: 14px; }
        @media (min-width: 1024px) {
            .ck-editor__editable_inline { min-height: 400px; }
        }
        
        .preview-container::-webkit-scrollbar { width: 6px; }
        .preview-container::-webkit-scrollbar-track { background: #f1f5f9; }
        .preview-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="bg-[#eef2f7] min-h-screen">

    <nav class="bg-[#1e4b8f] text-white px-4 sm:px-8 py-4 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.index') }}" class="w-9 h-9 sm:w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-lg transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="font-extrabold text-sm sm:text-lg tracking-tight leading-none uppercase">Verifikasi Dokumen</h1>
                <p class="text-[9px] sm:text-[10px] text-blue-200 font-bold uppercase tracking-widest mt-1">SIPA FTIK UPS TEGAL</p>
            </div>
        </div>
    </nav>

    <main class="p-4 sm:p-8 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="space-y-6 order-1">
            
            <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 flex flex-col sm:flex-row gap-4">
                <div class="bg-blue-50 p-3 sm:p-4 rounded-xl sm:rounded-2xl flex flex-row sm:flex-col items-center justify-center border border-blue-100 gap-2 sm:gap-0 min-w-full sm:min-w-[120px]">
                    <i class="fa-solid fa-user-graduate text-2xl sm:text-3xl text-blue-600 sm:mb-2"></i>
                    <p class="text-[10px] font-bold text-blue-800 uppercase tracking-wider">{{ $pengajuan->jenis_surat }}</p>
                </div>
                <div class="space-y-2 text-sm w-full">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Mahasiswa</p>
                        <p class="font-bold text-slate-700 text-base sm:text-lg flex flex-wrap items-center gap-2">
                            {{ $pengajuan->user->name ?? 'Data Terhapus' }} 
                            <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">{{ $pengajuan->user->npm ?? '-' }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Email Pengiriman</p>
                        <p class="font-semibold text-blue-600 break-all text-xs sm:text-sm">{{ $pengajuan->email_aktif }}</p>
                    </div>
                </div>
            </div>

            @if($pengajuan->status == 'Menunggu Validasi Admin')
            
            @if(session('error'))
                <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-200 font-bold text-sm shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                    <div>
                        <p class="uppercase text-[10px] font-black opacity-70 tracking-wider">Gagal Memproses</p>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-200 font-bold text-sm shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-check-circle text-2xl"></i>
                    <div>
                        <p class="uppercase text-[10px] font-black opacity-70 tracking-wider">Berhasil</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @php
                $npm = $pengajuan->user->npm ?? '';
                $kodeProdi = substr($npm, 0, 2);

                if ($kodeProdi == '65') {
                    $namaProdi = 'Teknik Sipil';
                    $namaKaprodi = 'Dr. Ir. H. M. Yusuf, MT';
                    $nipyKaprodi = '24762061967';
                } elseif ($kodeProdi == '64') {
                    $namaProdi = 'Teknik Mesin';
                    $namaKaprodi = 'Hadi Wibowo, ST., MT';
                    $nipyKaprodi = '20651641971';
                } elseif ($kodeProdi == '63') {
                    $namaProdi = 'Teknik Industri';
                    $namaKaprodi = 'Dr. M. Fajar Nurwildani, MT';
                    $nipyKaprodi = '19856101978';
                } else {
                    $namaProdi = 'Informatika';
                    $namaKaprodi = 'Ali Sofyan, S.T., M.Kom';
                    $nipyKaprodi = '3126511985';
                }
            @endphp

            <form action="{{ route('admin.updateStatus', $pengajuan->id) }}" method="POST" id="formValidasi" class="space-y-6">
                @csrf
                
                <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-lg border-2 border-blue-100">
                    <h3 class="font-bold text-blue-800 text-sm mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> Draf Surat Balasan (Bisa Diedit)
                    </h3>
                    <p class="text-[10px] text-slate-500 mb-4">*Silakan lengkapi draf. Biarkan kode <b>[TTD]</b> agar tercetak sebagai QR Code.</p>
                    
                    <button type="button" id="btnEditSurat" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-bold mb-4 transition-all flex items-center gap-2 w-max">
                        <i class="fa-solid fa-pen-to-square"></i> AKTIFKAN EDIT SURAT
                    </button>

                  <div class="overflow-x-auto">
                        <textarea name="isi_surat" id="editor">
                            @if(!empty($pengajuan->isi_surat))
                                {!! $pengajuan->isi_surat !!}
                            @else
                                @if($pengajuan->jenis_surat == 'Aktif Kuliah')
                                    <p style="text-align: center;"><strong><u>SURAT KETERANGAN</u></strong></p>
                                    <p style="text-align: center;">Nomor : .../K/I/FTIK/UPS/{{ date('m/Y') }}</p>
                                    
                                    <p>Dekan Fakultas Teknik dan Ilmu Komputer Universitas Pancasakti Tegal menerangkan dengan sebenarnya bahwa :</p>
                                    <figure class="table">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr><td style="width: 25%;">Nama</td><td style="width: 2%;">:</td><td><strong>{{ $pengajuan->user->name }}</strong></td></tr>
                                                <tr><td>NPM</td><td>:</td><td>{{ $pengajuan->user->npm }}</td></tr>
                                                <tr><td>Tempat, Tgl lahir</td><td>:</td><td>{{ $biodataLengkap ?? '[Isi Tempat, Tgl Lahir]' }}</td></tr>
                                                <tr><td>Progdi / Jenjang</td><td>:</td><td>{{ $namaProdi }} / S1</td></tr>
                                            </tbody>
                                        </table>
                                    </figure>
                                    
                                    <p>Adalah benar-benar Mahasiswa Fakultas Teknik dan Ilmu Komputer Universitas Pancasakti Tegal, sampai sekarang masih aktif kuliah dan terdaftar pada Semester [Gasal/Genap] Tahun Akademik [Tahun].</p>
                                    <p>Surat Keterangan ini digunakan untuk <strong>{{ $pengajuan->keperluan }}</strong>.</p>
                                    <p>Demikian Surat keterangan ini dibuat untuk digunakan sebagaimana mestinya.</p>
                                    
                                @elseif($pengajuan->jenis_surat == 'Cuti')
                                    <figure class="table">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr><td style="width: 15%;">Nomor</td><td style="width: 2%;">:</td><td style="width: 45%;">.../K/I/FTIK/UPS/{{ date('m/Y') }}</td><td style="width: 38%; text-align: right;">Tegal, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td></tr>
                                                <tr><td>Lampiran</td><td>:</td><td colspan="2">-</td></tr>
                                                <tr><td>Perihal</td><td>:</td><td colspan="2"><strong>Permohonan Cuti Kuliah</strong></td></tr>
                                            </tbody>
                                        </table>
                                    </figure>

                                    <p>Kepada Yth : Ka. Biro Akademik dan Kemahasiswaan<br>Jl. Halmahera Km. 1, Mintaragen, Kec. Tegal Timur<br>Di Kota Tegal</p>
                                    <p>Disampaikan dengan hormat, Saya yang bertanda tangan dibawah ini :</p>
                                    <figure class="table">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr><td style="width: 30%;">Nama</td><td style="width: 2%;">:</td><td><strong>{{ $pengajuan->user->name }}</strong></td></tr>
                                                <tr><td>NPM</td><td>:</td><td>{{ $pengajuan->user->npm }}</td></tr>
                                                <tr><td>Progdi / Jenjang</td><td>:</td><td>{{ $namaProdi }} / S1</td></tr>
                                            </tbody>
                                        </table>
                                    </figure>
                                    <p>Dengan ini mengajukan permohonan cuti kuliah pada semester :</p>
                                    <figure class="table">
                                        <table border="1" style="width: 80%; border-collapse: collapse; text-align: center;" cellpadding="5">
                                            <tbody>
                                                <tr><th style="width: 10%;">No.</th><th>Semester</th><th>Tahun Akademik</th></tr>
                                                <tr><td>1.</td><td>{{ $pengajuan->semester_cuti }}</td><td>{{ $pengajuan->tahun_akademik }}</td></tr>
                                            </tbody>
                                        </table>
                                    </figure>
                                    <p>Demikian Surat keterangan ini dibuat untuk digunakan sebagaimana mestinya.</p>

                                @elseif($pengajuan->jenis_surat == 'PKL' or $pengajuan->jenis_surat == 'KKL')
                                    <p><strong>FORM-1<br>LEMBAR REKOMENDASI</strong></p>
                                    
                                    <p>Yang bertanda tangan di bawah ini Ketua Program Studi {{ $namaProdi }} :</p>
                                    <figure class="table">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr><td style="width: 30%;">Nama</td><td style="width: 2%;">:</td><td><strong>{{ $namaKaprodi }}</strong></td></tr>
                                                <tr><td>NIPY</td><td>:</td><td>{{ $nipyKaprodi }}</td></tr>
                                                <tr><td>Jabatan</td><td>:</td><td>Ka. Prodi {{ $namaProdi }}</td></tr>
                                            </tbody>
                                        </table>
                                    </figure>
                                    
                                    <p>Memberikan rekomendasi untuk dapat melaksanakan PKL / KKL kepada mahasiswa:</p>
                                    <figure class="table">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr><td style="width: 30%;">Nama</td><td style="width: 2%;">:</td><td><strong>{{ $pengajuan->user->name }}</strong></td></tr>
                                                <tr><td>NPM</td><td>:</td><td>{{ $pengajuan->user->npm }}</td></tr>
                                                <tr><td>Semester</td><td>:</td><td>[Isi Semester Berjalan]</td></tr>
                                            </tbody>
                                        </table>
                                    </figure>
                                    
                                    <p>Sesuai dengan persyaratan administrasi yang telah dilengkapinya yaitu:</p>
                                    <figure class="table">
                                        <table border="1" style="width: 100%; border-collapse: collapse;" cellpadding="5">
                                            <tbody>
                                                <tr><th style="width: 10%;">No</th><th>Persyaratan Administrasi</th><th style="width: 20%;">Keterangan (&radic;)</th></tr>
                                                <tr><td style="text-align: center;">1</td><td>Telah menempuh semester IV</td><td style="text-align: center;">&radic;</td></tr>
                                                <tr><td style="text-align: center;">2</td><td>Telah memempuh minimal 79 sks</td><td style="text-align: center;">&radic;</td></tr>
                                                <tr><td style="text-align: center;">3</td><td>Telah mencantumkan mata kuliah PKL pada KRS pada semester berjalan</td><td style="text-align: center;">&radic;</td></tr>
                                                <tr><td style="text-align: center;">4</td><td>Telah mengikuti KKL (ditandai kepemilikan sertifikat KKL)</td><td style="text-align: center;">&radic;</td></tr>
                                                <tr><td style="text-align: center;">5</td><td>Telah heregristasi pada semester berlangsung</td><td style="text-align: center;">&radic;</td></tr>
                                            </tbody>
                                        </table>
                                    </figure>
                                    <p>Demikian rekomendasi ini untuk dapat dipergunakan sebagaimana mestinya.</p>

                                @elseif($pengajuan->jenis_surat == 'Peminjaman Ruangan')
                                    <p style="text-align: center;"><strong><u>SURAT PERSETUJUAN PEMINJAMAN RUANGAN</u></strong></p>
                                    <p style="text-align: center;">Nomor : .../K/I/FTIK/UPS/{{ date('m/Y') }}</p>
                                    
                                    <p>Menindaklanjuti permohonan peminjaman ruangan/fasilitas, Ketua Program Studi {{ $namaProdi }} Fakultas Teknik dan Ilmu Komputer Universitas Pancasakti Tegal dengan ini memberikan persetujuan kepada:</p>
                                    <figure class="table">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr><td style="width: 30%;">Nama Penanggung Jawab</td><td style="width: 2%;">:</td><td><strong>{{ $pengajuan->user->name }}</strong></td></tr>
                                                <tr><td>NPM</td><td>:</td><td>{{ $pengajuan->user->npm }}</td></tr>
                                                <tr><td>Ruangan / Fasilitas</td><td>:</td><td><strong>{{ $pengajuan->perusahaan ?? '[Nama Ruangan]' }}</strong></td></tr>
                                                <tr><td>Hari, Tanggal Kegiatan</td><td>:</td><td>[Isi Hari dan Tanggal]</td></tr>
                                                <tr><td>Waktu</td><td>:</td><td>[Isi Jam Pelaksanaan]</td></tr>
                                                <tr><td>Keperluan / Acara</td><td>:</td><td>[Isi Nama Acara/Kegiatan]</td></tr>
                                            </tbody>
                                        </table>
                                    </figure>
                                    <p>Untuk menggunakan fasilitas / ruangan tersebut sesuai dengan proposal permohonan yang diajukan. Diharapkan untuk menjaga kebersihan dan ketertiban fasilitas kampus selama kegiatan berlangsung.</p>
                                    <p>Demikian surat persetujuan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>

                                @else
                                    <p>Teks balasan default untuk jenis surat ini belum diatur. Silakan ketik secara manual.</p>
                                @endif
                            @endif
                        </textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="hidden" name="alasan_tolak" id="alasan_tolak" value="">
                    
                    <button type="button" id="btnTolak" onclick="prosesTolak()" class="bg-rose-50 hover:bg-rose-500 text-rose-600 hover:text-white border border-rose-200 font-bold py-3.5 rounded-2xl text-xs transition-all flex items-center justify-center gap-2 order-2 sm:order-1">
                        <i class="fa-solid fa-xmark"></i> TOLAK BERKAS
                    </button>
                    
                    <button type="submit" name="status" value="Selesai" id="btnSelesai" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 rounded-2xl text-xs transition-all shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2 order-1 sm:order-2">
                        <i class="fa-solid fa-check-double"></i> SIMPAN & KIRIM SURAT
                    </button>
                </div>

                <script>
                    function prosesTolak() {
                        Swal.fire({
                            title: 'Tolak Berkas?',
                            text: "Ketik alasan penolakan (akan dikirim ke email mahasiswa):",
                            input: 'textarea',
                            inputPlaceholder: 'Contoh: KTP buram, tidak ada tanda tangan...',
                            inputAttributes: {
                                'aria-label': 'Ketik alasan penolakan'
                            },
                            showCancelButton: true,
                            confirmButtonColor: '#e11d48',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: '<i class="fa-solid fa-paper-plane"></i> Tolak & Kirim Email',
                            cancelButtonText: 'Batal',
                            customClass: {
                                confirmButton: 'font-bold rounded-xl shadow-md',
                                cancelButton: 'font-bold rounded-xl'
                            },
                            inputValidator: (value) => {
                                if (!value || value.trim() === '') {
                                    return 'Alasan penolakan WAJIB diisi!'
                                }
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Memproses...',
                                    html: 'Sedang mengirim email ke mahasiswa.',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                });

                                document.getElementById('alasan_tolak').value = result.value;
                                
                                let inputStatus = document.createElement("input");
                                inputStatus.setAttribute("type", "hidden");
                                inputStatus.setAttribute("name", "status");
                                inputStatus.setAttribute("value", "Ditolak");
                                document.getElementById('formValidasi').appendChild(inputStatus);
                                
                                document.getElementById('formValidasi').submit();
                            }
                        });
                    }
                </script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </form>
            @else
            <div class="bg-slate-200 text-slate-500 text-center py-4 rounded-2xl text-xs font-bold uppercase tracking-widest border border-slate-300">
                BERKAS SUDAH DIPROSES ({{ $pengajuan->status }})
            </div>
            @endif
        </div>

        <div class="h-full order-2">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden h-[500px] sm:h-[800px] flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center z-10 shadow-sm">
                    <h3 class="font-bold text-slate-700 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-blue-600"></i> Pratinjau Proposal / Berkas
                    </h3>
                </div>
                <div class="flex-grow bg-slate-200 p-3 sm:p-4 overflow-y-auto preview-container space-y-4">
                    @php
                        $berkas_array = json_decode($pengajuan->file_path, true);
                        if (!is_array($berkas_array)) { $berkas_array = [$pengajuan->file_path]; }
                    @endphp
                    
                    @foreach($berkas_array as $index => $berkas)
                        @php                        
                            $localPdfUrl = '/storage/' . $berkas;
                        @endphp
                        
                        <div class="bg-white border border-slate-300 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all">
                            
                            <button type="button" onclick="togglePdf('pdf-{{ $index }}', '{{ $localPdfUrl }}', {{ $index }})" class="w-full bg-slate-50 hover:bg-slate-100 px-4 py-4 flex justify-between items-center text-slate-700 font-bold text-sm transition-all border-b border-slate-200 cursor-pointer">
                                <span class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-red-100 text-red-500 rounded-md flex items-center justify-center text-lg">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </div>
                                    Dokumen Persyaratan #{{ $index + 1 }}
                                </span>
                                <span class="flex items-center gap-2 text-blue-600 text-xs uppercase tracking-wider bg-blue-50 px-3 py-1.5 rounded-lg">
                                    <i class="fa-solid fa-chevron-down transition-transform duration-300" id="icon-{{ $index }}"></i> <span id="text-{{ $index }}">Lihat</span>
                                </span>
                            </button>
                            
                            <div id="pdf-{{ $index }}" class="hidden bg-slate-300 p-2 sm:p-4 flex justify-center overflow-x-auto">
                                <div class="relative w-full flex flex-col items-center">
                                    <canvas id="the-canvas-{{ $index }}" class="bg-white border border-slate-400 shadow-lg max-w-full rounded-md"></canvas>
                                    <div class="mt-3 text-center">
                                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-emerald-200 shadow-sm">
                                            <i class="fa-solid fa-shield-halved"></i> Mode Aman: Tidak Mengunduh File
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let myEditor;

        if(document.querySelector('#editor')) {
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'insertTable', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
                })
                .then(editor => {
                    myEditor = editor;
                    editor.enableReadOnlyMode('lock-id');
                })
                .catch(error => { console.error(error); });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const btnEdit = document.getElementById('btnEditSurat');
            
            if(btnEdit) {
                btnEdit.addEventListener('click', function() {
                    if(myEditor) {
                        myEditor.disableReadOnlyMode('lock-id');
                        
                        this.classList.remove('bg-amber-500', 'hover:bg-amber-600', 'text-white');
                        this.classList.add('bg-slate-200', 'text-slate-500', 'cursor-not-allowed');
                        this.innerHTML = '<i class="fa-solid fa-lock-open"></i> MODE EDIT AKTIF';
                        this.disabled = true;
                    }
                });
            }

            const formValidasi = document.getElementById('formValidasi');
            if(formValidasi) {
                formValidasi.addEventListener('submit', function(e) {
                    const btnSelesai = document.getElementById('btnSelesai');
                    const btnTolak = document.getElementById('btnTolak');
                    
                    setTimeout(function() {
                        if(btnSelesai) {
                            btnSelesai.disabled = true;
                            btnSelesai.classList.add('opacity-70', 'cursor-not-allowed');
                            btnSelesai.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> MENGIRIM...';
                        }
                        if(btnTolak) {
                            btnTolak.disabled = true;
                            btnTolak.classList.add('opacity-70', 'cursor-not-allowed');
                        }
                    }, 50);
                });
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        let renderedPdfs = {};

        function togglePdf(id, url, index) {
            let element = document.getElementById(id);
            let icon = document.getElementById(id.replace('pdf-', 'icon-'));
            let text = document.getElementById(id.replace('pdf-', 'text-'));
            
            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                text.innerText = "Tutup";

                if(!renderedPdfs[index]) {
                    renderPDF(url, 'the-canvas-' + index);
                    renderedPdfs[index] = true;
                }
            } 
            else {
                element.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                text.innerText = "Lihat";
            }
        }

        function renderPDF(url, canvasId) {
            let loadingTask = pdfjsLib.getDocument({
                url: url,
                httpHeaders: {
                    'ngrok-skip-browser-warning': '69420'
                }
            });
            
            loadingTask.promise.then(function(pdf) {
                pdf.getPage(1).then(function(page) {
                    let scale = 1.5; 
                    let viewport = page.getViewport({scale: scale});
                    
                    let canvas = document.getElementById(canvasId);
                    let context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    
                    let renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(renderContext);
                });
            }).catch(function(error) {
                console.error("Error merender PDF: ", error);
                alert("Gagal memuat PDF. Pastikan file ada dan koneksi stabil.");
            });
        }
    </script>
</body>
</html>