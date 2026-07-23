<!DOCTYPE html>
<html>
<head>
    <title>Cetak Surat Akademik</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; padding: 20px; }
        .kop-surat { text-align: center; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h2, .kop-surat h3, .kop-surat p { margin: 2px 0; }
        .content { line-height: 1.5; text-align: justify; }
        .tabel-biodata { margin-left: 30px; margin-bottom: 20px; }
        .tabel-biodata td { padding: 3px 10px; }
        .ttd { float: right; width: 250px; text-align: left; margin-top: 50px; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h3>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h3>
        <h2>UNIVERSITAS PANCASAKTI TEGAL</h2>
        <h3>FAKULTAS TEKNIK DAN ILMU KOMPUTER</h3>
        <p>Jl. Halmahera Km. 1 Tegal, Jawa Tengah</p>
    </div>

    <div class="content">
        <h3 style="text-align: center; text-decoration: underline; margin-bottom: 20px;">SURAT KETERANGAN</h3>
        <p>Yang bertanda tangan di bawah ini menerangkan dengan sesungguhnya bahwa:</p>
        
        <table class="tabel-biodata">
            <tr><td>Nama</td><td>: <b>{{ $pengajuan->user->name }}</b></td></tr>
            <tr><td>NPM</td><td>: {{ $pengajuan->user->npm }}</td></tr>
            <tr><td>Semester</td><td>: {{ $pengajuan->user->semester }}</td></tr>
            <tr><td>Status</td><td>: {{ $pengajuan->user->status_akademik }}</td></tr>
        </table>

        <p>Adalah benar mahasiswa kami yang berstatus aktif pada Tahun Akademik ini. Surat ini diterbitkan sebagai pemenuhan syarat pengajuan <b>{{ $pengajuan->jenis_surat }}</b> yang telah divalidasi oleh sistem akademik.</p>

        <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="ttd">
        <p>Tegal, {{ date('d F Y') }}</p>
        <p>Dekan Fakultas,</p>
        <br><br><br>
        <p><b>(Nama Dekan Dummy, M.T.)</b></p>
        <p>NIP. 19800101 200501 1 001</p>
    </div>

</body>
</html>