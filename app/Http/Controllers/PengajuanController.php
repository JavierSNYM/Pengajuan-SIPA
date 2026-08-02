<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Str; // 🌟 TAMBAHAN REVISI 3: Library Pembuat Teks Acak

class PengajuanController extends Controller
{
    public function index()
    {
        // 1. Ambil data bulan dan tahun berjalan saat ini menggunakan Carbon
        $bulanSekarang = \Carbon\Carbon::now()->month;
        $tahunSekarang = \Carbon\Carbon::now()->year;

        // 2. IMPLEMENTASI REFRESH BULANAN: Hanya mengambil data khusus bulan ini saja untuk mahasiswa yang login
        $pengajuans = \App\Models\Pengajuan::where('user_id', auth()->user()->id)
                        ->whereMonth('created_at', $bulanSekarang)
                        ->whereYear('created_at', $tahunSekarang)
                        ->latest()
                        ->get();

        return view('pengajuan.pengajuan', compact('pengajuans'));
    }

    // 🌟 FUNGSI UNTUK MENAMPILKAN FORMULIR PENGAJUAN
    public function create()
    {
        return view('pengajuan.create');
    }

    // 🌟 FUNGSI UNTUK MENYIMPAN PENGAJUAN (SUDAH BERSIH DARI VALIDASI TEMPAT/TGL LAHIR)
    public function ajukanSurat(Request $request)
    {
        $jenisSurat = $request->input('jenis_surat');
        $emailAktif = $request->input('email_aktif');
        
        $aturan = [];
        $pesanError = [];

        // RULE-BASED 1: AKTIF KULIAH
        if ($jenisSurat === "Aktif Kuliah") {
            $aturan = [
                'keperluan' => 'required|string',
                // DIKUNCI: Hanya boleh PDF
                'file_ktm'  => 'required|mimes:pdf|max:2048',
                'file_ukt'  => 'required|mimes:pdf|max:2048',
            ];
            $pesanError = [
                'keperluan.required' => '[Rule-Based Failed] Keperluan Surat Aktif Kuliah wajib dipilih!',
                'file_ktm.required'  => '[Rule-Based Failed] Berkas KTM tidak boleh kosong!',
                'file_ktm.mimes'     => '[Rule-Based Failed] Berkas harus PDF!',
                'file_ktm.max'       => '[Rule-Based Failed] Ukuran berkas maksimal 2MB!',
                'file_ukt.required'  => '[Rule-Based Failed] Berkas Slip UKT tidak boleh kosong!',
                'file_ukt.mimes'     => '[Rule-Based Failed] Berkas harus PDF!',
                'file_ukt.max'       => '[Rule-Based Failed] Ukuran berkas maksimal 2MB!',
            ];

            if ($request->input('keperluan') === 'Tunjangan') {
                $aturan['nama_ortu']     = 'required|string|max:255';
                $aturan['nip_ortu']      = 'required|string|max:50';
                $aturan['pangkat_ortu']  = 'required|string|max:100';
                $aturan['pekerjaan_ortu'] = 'required|string|max:255';

                $pesanError['nama_ortu.required']     = '[Rule-Based Failed] Nama Orang Tua wajib diisi!';
                $pesanError['nip_ortu.required']      = '[Rule-Based Failed] NIP Orang Tua wajib diisi!';
                $pesanError['pangkat_ortu.required']  = '[Rule-Based Failed] Pangkat Orang Tua wajib diisi!';
                $pesanError['pekerjaan_ortu.required'] = '[Rule-Based Failed] Pekerjaan Orang Tua wajib diisi!';
            }

        // RULE-BASED 2: CUTI KULIAH
        } elseif ($jenisSurat === "Cuti") {
            $aturan = [
                'semester_cuti'         => 'required|string',
                'tahun_akademik'        => 'required|string',
                // DIKUNCI: Hanya boleh PDF
                'surat_keterangan_cuti' => 'required|mimes:pdf|max:2048',
            ];
            $pesanError = [
                'semester_cuti.required'         => '[Rule-Based Failed] Semester Cuti wajib diisi!',
                'tahun_akademik.required'        => '[Rule-Based Failed] Tahun Akademik wajib diisi!',
                'surat_keterangan_cuti.required' => '[Rule-Based Failed] Surat Keterangan Alasan Cuti Dari Orang Tua/Perusahaan wajib diunggah!',
                'surat_keterangan_cuti.mimes'    => '[Rule-Based Failed] Berkas harus PDF!',
                'surat_keterangan_cuti.max'      => '[Rule-Based Failed] Ukuran berkas maksimal 2MB!',
            ];

        // RULE-BASED 3: SURAT PKL / KKL
        } elseif ($jenisSurat === "PKL" || $jenisSurat === "KKL") {
            $aturan = [
                'perusahaan'      => 'required|string',
                // DIKUNCI: Semua berkas wajib PDF
                'file_krs'        => 'required|mimes:pdf|max:2048',
                'file_transkrip'  => 'required|mimes:pdf|max:2048',
                'file_pembayaran' => 'required|mimes:pdf|max:2048',
                'file_puas_kkl'   => 'required|mimes:pdf|max:2048',
            ];
            $pesanError = [
                'perusahaan.required'      => '[Rule-Based Failed] Tempat Magang / KKL wajib diisi!',
                'file_krs.required'        => '[Rule-Based Failed] Berkas KRS wajib diunggah!',
                'file_krs.mimes'           => '[Rule-Based Failed] Berkas harus PDF!',
                'file_krs.max'             => '[Rule-Based Failed] Ukuran berkas maksimal 2MB!',
                'file_transkrip.required'  => '[Rule-Based Failed] Berkas Transkrip Nilai wajib diunggah!',
                'file_transkrip.mimes'     => '[Rule-Based Failed] Berkas harus PDF!',
                'file_transkrip.max'       => '[Rule-Based Failed] Ukuran berkas maksimal 2MB!',
                'file_pembayaran.required' => '[Rule-Based Failed] Berkas Bukti Pembayaran wajib diunggah!',
                'file_pembayaran.mimes'    => '[Rule-Based Failed] Berkas harus PDF!',
                'file_pembayaran.max'      => '[Rule-Based Failed] Ukuran berkas maksimal 2MB!',
                'file_puas_kkl.required'   => '[Rule-Based Failed] Berkas Surat Puas KKL wajib diunggah!',
                'file_puas_kkl.mimes'      => '[Rule-Based Failed] Berkas harus PDF!',
                'file_puas_kkl.max'        => '[Rule-Based Failed] Ukuran berkas maksimal 2MB!',
            ];

        // RULE-BASED 4: PEMINJAMAN RUANGAN
        } elseif ($jenisSurat === "Peminjaman Ruangan") {
            $aturan = [
                'nama_ruangan'  => 'required|string',
                // DIKUNCI: Hanya boleh PDF
                'file_proposal' => 'required|mimes:pdf|max:2048',
            ];
            $pesanError = [
                'nama_ruangan.required'  => '[Rule-Based Failed] Nama ruangan yang dipinjam wajib diisi!',
                'file_proposal.required' => '[Rule-Based Failed] File proposal peminjaman wajib diunggah!',
                'file_proposal.mimes'    => '[Rule-Based Failed] Berkas harus PDF!',
                'file_proposal.max'      => '[Rule-Based Failed] Ukuran berkas maksimal 2MB!',
            ];
        }

        // EKSEKUSI VALIDASI RULE-BASED
        if (!empty($aturan)) {
            $request->validate($aturan, $pesanError);
        }

        // SIMPAN KE DATABASE
        $pengajuan = new Pengajuan();
        $pengajuan->user_id = Auth::user()->id;
        
        // 🌟 REVISI 3: GENERATE KODE UNIK (Contoh Hasil: SIPA-8A4F9X) 🌟
        $pengajuan->kode_verifikasi = 'SIPA-' . strtoupper(Str::random(6));
        
        $pengajuan->jenis_surat = $jenisSurat;
        $pengajuan->email_aktif = $emailAktif;
        $pengajuan->status = 'Menunggu Validasi Admin'; 

        $pengajuan->keperluan     = $request->input('keperluan');
        $pengajuan->nama_ortu     = $request->input('nama_ortu');
        $pengajuan->nip_ortu      = $request->input('nip_ortu');
        $pengajuan->pangkat_ortu  = $request->input('pangkat_ortu');
        $pengajuan->pekerjaan_ortu = $request->input('pekerjaan_ortu');
        $pengajuan->semester_cuti  = $request->input('semester_cuti');
        $pengajuan->tahun_akademik = $request->input('tahun_akademik');
        
        if ($jenisSurat === "Peminjaman Ruangan") {
            $pengajuan->perusahaan = $request->input('nama_ruangan'); 
        } else {
            $pengajuan->perusahaan = $request->input('perusahaan');
        }

        // Menyimpan banyak file sekaligus (JSON Array)
        $path_berkas = []; 

        $daftar_input_file = [
            'file_ktm', 
            'file_ukt', 
            'surat_keterangan_cuti', 
            'file_krs', 
            'file_transkrip', 
            'file_pembayaran', 
            'file_puas_kkl', 
            'file_proposal'
        ];

        foreach ($daftar_input_file as $input_name) {
            if ($request->hasFile($input_name)) {
                $path_berkas[] = $request->file($input_name)->store('persyaratan', 'public');
            }
        }

        $pengajuan->file_path = json_encode($path_berkas);
        $pengajuan->save();

        // AUTOMATED NOTIFICATION EMAIL TO TATA USAHA
        try {
            $emailAdminTU = 'sipatest049@gmail.com'; 
            
            $bodyNotifAdmin = "
                <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #cbd5e1; border-radius: 8px;'>
                    <h3 style='color: #1e4b8f;'>Notifikasi SIPA: Pengajuan Surat Baru Masuk</h3>
                    <p>Halo Admin TU FTIK,</p>
                    <p>Ada dokumen pengajuan baru yang memerlukan validasi dengan detail berikut:</p>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr><td><b>Nama Mahasiswa</b></td><td>: {$pengajuan->user->name}</td></tr>
                        <tr><td><b>NPM / NIM</b></td><td>: {$pengajuan->user->npm}</td></tr>
                        <tr><td><b>Jenis Layanan</b></td><td>: {$pengajuan->jenis_surat}</td></tr>
                        <tr><td><b>Kode Keamanan</b></td><td>: {$pengajuan->kode_verifikasi}</td></tr>
                        <tr><td><b>Waktu Masuk</b></td><td>: " . date('d-m-Y H:i') . " WIB</td></tr>
                    </table>
                    <p style='margin-top: 20px;'>Silakan login ke Dashboard Admin SIPA untuk memverifikasi berkas fisik tersebut.</p>
                </div>
            ";

            \Illuminate\Support\Facades\Mail::html($bodyNotifAdmin, function ($message) use ($emailAdminTU, $pengajuan) {
                $message->to($emailAdminTU)
                        ->subject('SIPA NOTIF: Pengajuan Baru - ' . $pengajuan->user->name);
            });
        } catch (\Exception $e) {
            // Tetap pass jika email down
        }

        return redirect()->back()->with('success', 'Persyaratan VALID (Lolos Seleksi Aturan Rule-Based). Pengajuan Anda berhasil masuk antrean Admin TU!');
    }
}