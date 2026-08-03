<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\NpmWhitelist;
use App\Imports\MahasiswaImport;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon; 

class AdminController extends Controller
{
    // 1. MENAMPILKAN DASHBOARD UTAMA ADMIN PER PRODI
    public function index(Request $request)
    {
        // Default menampilkan prodi Informatika (66)
        $prodiAktif = $request->get('prodi', '66');

        // Mengambil data yang BUKAN 'Diarsipkan' dan NPM berawalan sesuai prodi
        $pengajuans = Pengajuan::where('status', '!=', 'Diarsipkan')
            ->whereHas('user', function($query) use ($prodiAktif) {
                $query->where('npm', 'LIKE', $prodiAktif . '%');
            })
            ->latest()
            ->get();

        // IMPLEMENTASI OPSI A: Menghitung statistik total masuk KHUSUS bulan dan tahun berjalan saat ini
        $totalMasuk = Pengajuan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
            
        $perluValidasi = Pengajuan::where('status', 'Menunggu Validasi Admin')->count();
        $selesaiDikirim = Pengajuan::whereIn('status', ['Selesai', 'Disetujui'])->count();

        // AUTOMATISASI COUNTER PRODI: Menghitung surat masuk aktif (belum diarsip) per prodi berdasarkan NIM/NPM
        $countInfo = Pengajuan::where('status', '!=', 'Diarsipkan')
            ->whereHas('user', function($query) {
                $query->where('npm', 'LIKE', '66%');
            })->count();

        $countSipil = Pengajuan::where('status', '!=', 'Diarsipkan')
            ->whereHas('user', function($query) {
                $query->where('npm', 'LIKE', '65%');
            })->count();

        $countMesin = Pengajuan::where('status', '!=', 'Diarsipkan')
            ->whereHas('user', function($query) {
                $query->where('npm', 'LIKE', '64%');
            })->count();

        $countIndustri = Pengajuan::where('status', '!=', 'Diarsipkan')
            ->whereHas('user', function($query) {
                $query->where('npm', 'LIKE', '63%');
            })->count();

        // Mengirimkan data counter ke file blade dashboard
        return view('admin.dashboard', compact(
            'pengajuans', 
            'prodiAktif', 
            'totalMasuk', 
            'perluValidasi', 
            'selesaiDikirim',
            'countInfo',
            'countSipil',
            'countMesin',
            'countIndustri'
        ));
    }

    // 2. FITUR DOWNLOAD WORD & ARSIPKAN
    public function downloadDanArsipkan($prodi)
    {
        // Mapping nama prodi
        $namaProdi = match($prodi) {
            '66' => 'Teknik Informatika',
            '65' => 'Teknik Sipil',
            '64' => 'Teknik Mesin',
            '63' => 'Teknik Industri',
            default => 'Program Studi Lain'
        };

        // Ambil data aktif
        $data = Pengajuan::where('status', '!=', 'Diarsipkan')
            ->whereHas('user', function($query) use ($prodi) {
                $query->where('npm', 'LIKE', $prodi . '%');
            })
            ->latest()
            ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data pengajuan aktif untuk dibackup!');
        }

        // PROSES ARSIP: Ubah status massal
        Pengajuan::where('status', '!=', 'Diarsipkan')
            ->whereHas('user', function($query) use ($prodi) {
                $query->where('npm', 'LIKE', $prodi . '%');
            })
            ->update(['status' => 'Diarsipkan']);

        // PROSES DOWNLOAD WORD
        $filename = "Data_Pengajuan_" . str_replace(' ', '_', $namaProdi) . "_" . date('d-m-Y') . ".doc";

        $htmlContent = "
        <html>
        <head>
            <meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">
            <style>
                body { font-family: 'Arial', sans-serif; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 11pt; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .title { text-align: center; font-size: 14pt; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='title'>DAFTAR MAHASISWA YANG MENGAJUKAN SURAT ADMINISTRASI</div>
            <div class='title'>FAKULTAS TEKNIK DAN ILMU KOMPUTER</div>
            <br>
            <p><b>Program Studi:</b> $namaProdi</p>
            <p><b>Tanggal Backup:</b> " . date('d-m-Y H:i') . " WIB</p>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NPM</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Surat</th>
                        <th>Alamat Email</th>
                    </tr>
                </thead>
                <tbody>";
                
                foreach ($data as $index => $row) {
                    $htmlContent .= "
                    <tr>
                        <td>" . ($index + 1) . "</td>
                        <td>'" . $row->user->npm . "</td>
                        <td>" . $row->user->name . "</td>
                        <td>" . $row->jenis_surat . "</td>
                        <td>" . $row->email_aktif . "</td>
                    </tr>";
                }

        $htmlContent .= "
                </tbody>
            </table>
        </body>
        </html>";

        return response($htmlContent)
            ->header('Content-Type', 'application/vnd.ms-word')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // 3. FITUR CEK DETAIL BERKAS (DAN PENYESUAIAN BIODATA)
    public function show($id)
    {
        // Ambil data pengajuan beserta data relasi mahasiswa (user)-nya
        $pengajuan = Pengajuan::with('user')->findOrFail($id);
        
        // 🌟 BAGIAN SULAP BIODATA DRAF SURAT 🌟
        $tempatLahir = $pengajuan->user->tempat_lahir ?? '..........';
        $tanggalLahir = $pengajuan->user->tanggal_lahir 
               ? \Carbon\Carbon::parse($pengajuan->user->tanggal_lahir)->locale('id')->translatedFormat('d F Y') 
               : '..........';
               
        $biodataLengkap = $tempatLahir . ', ' . $tanggalLahir;

        // Mengarahkan ke file detail.blade.php di folder admin, lemparkan juga variabel $biodataLengkap
        return view('admin.detail', compact('pengajuan', 'biodataLengkap'));
    }

    // 4. FITUR UPDATE STATUS BERKAS & KIRIM EMAIL (ACC/TOLAK)
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = $request->status;
        
        // Tangkap ketikan Admin dari CKEditor/Summernote
        $isiSuratText = $request->input('isi_surat');

        // ==========================================
        // JIKA ADMIN MENEKAN TOMBOL "SELESAI" (ACC)
        // ==========================================
        if ($request->status === 'Selesai') {
            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.surat-pdf', [
                    'isi_surat' => $isiSuratText, 
                    'pengajuan' => $pengajuan
                ]);
                $pdf->setPaper('A4', 'portrait');

                // Desain Email ACC
                $htmlBody = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
                        <div style='text-align: center; padding: 20px;'>
                            <h2 style='color: #1e4b8f; margin: 0;'>Pemberitahuan SIPA FTIK</h2>
                        </div>
                        <div style='padding: 20px; color: #333;'>
                            <p>Halo <b>{$pengajuan->user->name}</b>,</p>
                            <p>Pengajuan <b>{$pengajuan->jenis_surat}</b> Anda telah <b>DISETUJUI</b> oleh Admin Tata Usaha FTIK Universitas Pancasakti Tegal.</p>
                            <p>Silakan unduh dokumen surat resmi Anda pada lampiran (Attachment) file PDF yang ada di email ini.</p>
                            <hr style='border: none; border-top: 1px dashed #cbd5e1; margin: 20px 0;'>
                            <p style='font-size: 10px; color: #64748b; text-align: center;'>Ini adalah email otomatis dari sistem SIPA. Dokumen yang terlampir sah dan dapat dipertanggungjawabkan.</p>
                        </div>
                    </div>
                ";

                // Kirim Email beserta Lampiran PDF
                \Illuminate\Support\Facades\Mail::html($htmlBody, function ($message) use ($pengajuan, $pdf) {
                    $message->to($pengajuan->email_aktif)
                            ->subject('Dokumen SIPA FTIK Selesai - ' . $pengajuan->jenis_surat)
                            ->attachData($pdf->output(), "Surat_Resmi_{$pengajuan->jenis_surat}.pdf", [
                                'mime' => 'application/pdf',
                            ]);
                });

            } catch (\Exception $e) {
            // 🌟 MENAMPILKAN ERROR ASLI DARI SISTEM LARAVEL
            return back()->with('error', 'SYSTEM ERROR: ' . $e->getMessage());
        }
        } 
        // ==========================================
        // JIKA ADMIN MENEKAN TOMBOL "DITOLAK"
        // ==========================================
        elseif ($request->status === 'Ditolak') {
            // 🌟 PERBAIKAN: Tangkap alasan tolak dari pop-up javascript tadi
            $alasanTolak = $request->input('alasan_tolak', 'Persyaratan tidak lengkap/tidak sesuai.');

            try {
                // Desain Email PENOLAKAN
                $htmlBodyTolak = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #fecaca; border-radius: 8px; overflow: hidden;'>
                        <div style='background-color: #ef4444; text-align: center; padding: 20px; color: white;'>
                            <h2 style='margin: 0;'>Pemberitahuan Penolakan Berkas</h2>
                        </div>
                        <div style='padding: 20px; color: #333;'>
                            <p>Halo <b>{$pengajuan->user->name}</b>,</p>
                            <p>Mohon maaf, permohonan <b>{$pengajuan->jenis_surat}</b> Anda terpaksa <b>DIKEMBALIKAN / DITOLAK</b> oleh Admin Tata Usaha karena ada persyaratan yang belum sesuai.</p>
                            
                            <p style='margin-top: 20px; font-weight: bold;'>Catatan / Alasan Penolakan dari Admin:</p>
                            <div style='padding: 15px; border-left: 4px solid #ef4444; background-color: #fef2f2; border-radius: 4px;'>
                                <p style='margin: 0; font-weight: bold; color: #991b1b;'>{$alasanTolak}</p>
                            </div>
                            
                            <p style='margin-top: 20px;'>Silakan perbaiki dokumen Anda dan ajukan kembali melalui portal SIPA.</p>
                            <hr style='border: none; border-top: 1px dashed #cbd5e1; margin: 20px 0;'>
                            <p style='font-size: 10px; color: #64748b; text-align: center;'>Ini adalah email otomatis dari sistem SIPA FTIK UPS Tegal.</p>
                        </div>
                    </div>
                ";

                // Kirim Email Penolakan (Tanpa PDF)
                \Illuminate\Support\Facades\Mail::html($htmlBodyTolak, function ($message) use ($pengajuan) {
                    $message->to($pengajuan->email_aktif)
                            ->subject('PENTING: Perbaikan Berkas SIPA - ' . $pengajuan->jenis_surat);
                });

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengirim email penolakan: ' . $e->getMessage());
            }
        }

        // Hanya menyimpan perubahan status saja ke database
        $pengajuan->save();
        
        $pesanSukses = $request->status === 'Selesai' 
            ? 'Status berhasil diperbarui dan File PDF Surat Asli telah sukses dikirim ke Email Mahasiswa!' 
            : 'Berkas berhasil DITOLAK dan email pemberitahuan/revisi telah dikirim ke Mahasiswa!';

        return redirect()->back()->with('success', $pesanSukses);
    }

    // 5. FUNGSI RESET SANDI VIA DASBOR ADMIN (BERDASARKAN NPM)
    public function resetPasswordByNpm(Request $request)
    {
        $request->validate([
            'npm_reset' => 'required|exists:users,npm'
        ], [
            'npm_reset.required' => 'Kolom NPM tidak boleh kosong!',
            'npm_reset.exists'   => 'Gagal! NPM tersebut belum terdaftar di aplikasi SIPA.'
        ]);

        $mahasiswa = \App\Models\User::where('npm', $request->npm_reset)->first();

        // Keamanan tambahan: Cegah admin mereset akun admin lainnya
        if($mahasiswa->role === 'admin') {
            return redirect()->back()->with('error', 'Akses Ditolak! Anda tidak bisa mereset kata sandi sesama Admin.');
        }
        
        // Ubah sandi menjadi default
        $mahasiswa->password = \Illuminate\Support\Facades\Hash::make('ftik12345');
        $mahasiswa->save();

        return redirect()->back()->with('success', 'BERHASIL! Kata sandi akun atas nama ' . $mahasiswa->name . ' (' . $mahasiswa->npm . ') telah direset menjadi: ftik12345');
    }

    // ==========================================
    // REVISI DOSEN: PENGELOLAAN DATA MAHASISWA
    // ==========================================

   public function kelolaMahasiswa(Request $request)
    {
        // 1. Ambil prodi yang sedang aktif dipilih (Default: Informatika - 66)
        $prodiAktif = $request->get('prodi', '66');

        // 2. Filter data menggunakan CAST agar tipe data integer di MySQL aman dibaca string LIKE
        $mahasiswas = NpmWhitelist::whereRaw("CAST(npm AS CHAR) LIKE ?", [$prodiAktif . '%'])
                        ->orderBy('id', 'desc')
                        ->get();

        // 3. Hitung jumlah mahasiswa aktif per prodi dengan proteksi CAST yang sama
        $countInfo = NpmWhitelist::whereRaw("CAST(npm AS CHAR) LIKE '66%'")->count();
        $countSipil = NpmWhitelist::whereRaw("CAST(npm AS CHAR) LIKE '65%'")->count();
        $countMesin = NpmWhitelist::whereRaw("CAST(npm AS CHAR) LIKE '64%'")->count();
        $countIndustri = NpmWhitelist::whereRaw("CAST(npm AS CHAR) LIKE '63%'")->count();

        return view('admin.mahasiswa', compact(
            'mahasiswas', 
            'prodiAktif', 
            'countInfo', 
            'countSipil', 
            'countMesin', 
            'countIndustri'
        ));
    }

    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'npm' => 'required|unique:npm_whitelists,npm',
            'nama_mahasiswa' => 'required|string|max:255',
        ], [
            'npm.unique' => 'NPM ini sudah ada di dalam sistem!',
        ]);

        NpmWhitelist::create([
            'npm' => $request->npm,
            'nama_mahasiswa' => $request->nama_mahasiswa,
        ]);

        return back()->with('success', 'Data Mahasiswa berhasil ditambahkan secara manual.');
    }

    public function destroyMahasiswa($id)
    {
        $mahasiswa = NpmWhitelist::findOrFail($id);
        $mahasiswa->delete();

        return back()->with('success', 'Data Mahasiswa berhasil dihapus dari sistem.');
    }

    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ], [
            'file_excel.mimes' => 'Format file wajib berakhiran .xlsx atau .csv'
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file_excel'));
            return back()->with('success', 'Data Mahasiswa dari Excel berhasil di-import massal!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Import: Pastikan format excel memiliki kolom [npm] dan [nama_mahasiswa].');
        }
    }

    // 🌟 FUNGSI BARU: HAPUS MASSAL MAHASISWA 🌟
    public function hapusMassalMahasiswa(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return back()->with('error', 'Gagal! Anda belum mencentang satu pun data mahasiswa yang akan dihapus.');
        }

        // Hapus semua data yang ID-nya ada di dalam array $ids
        NpmWhitelist::whereIn('id', $ids)->delete();

        return back()->with('success', count($ids) . ' data mahasiswa berhasil dihapus secara massal dari sistem.');
    }
}