<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Balasan SIPA</title>
    <style>
        /* Pengaturan wajib untuk render PDF ukuran A4 */
        @page {
            size: A4;
            margin: 2cm; /* Margin standar surat resmi */
        }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            margin: 0; 
            padding: 0; 
            font-size: 12pt; 
            color: #000;
        }
        .container { 
            width: 100%;
        }
        
        /* 🌟 GAYA BARU KOP SURAT SESUAI GAMBAR 🌟 */
        .garis-emas { 
            border-top: 4px solid #a97b2d; /* Garis emas tebal */
            margin-top: 5px; 
            margin-bottom: 20px; 
        }
        .tagline-container { 
            margin-top: 5px; 
            margin-left: 0; 
        }
        .tagline { 
            background-color: #d1e56b; /* Hijau kekuningan */
            color: #fff; /* Teks putih */
            padding: 4px 18px; 
            font-style: italic; 
            font-weight: bold; 
            font-size: 10pt; 
            border-radius: 0 15px 15px 0; 
        }
        
        /* 🌟 FOOTER KONTAK DI BAWAH 🌟 */
        .footer-kontak {
            position: fixed;
            bottom: -2cm; /* Nempel ke dasar kertas */
            left: -2cm;
            right: -2cm;
            height: 1.2cm;
        }
        .footer-kontak img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .isi-surat { 
            text-align: justify; 
            line-height: 1.5; 
        }
        .isi-surat p { 
            margin-top: 6px; 
            margin-bottom: 6px; 
        }
        table { 
            page-break-inside: avoid; 
            border-collapse: collapse;
        }
        
        /* 🌟 JURUS FIX TTD KANAN MUTLAK BERSATU 🌟 */
        .ttd-wrapper {
            width: 100%;
            text-align: right; 
            margin-top: 30px;
        }
        .ttd-box {
            width: 250px; 
            display: inline-block;
            text-align: left; 
            vertical-align: top;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="15%" align="left" valign="middle">
                    <img src="{{ public_path('asset/img/logo_ups.png') }}" width="85" alt="UPS">
                </td>
                
                <td width="70%" align="center" valign="middle" style="line-height: 1.1;">
                    <div style="font-family: Arial, Helvetica, sans-serif; font-size: 11pt; font-weight: bold; color: #000; letter-spacing: 1px;">YAYASAN PENDIDIKAN PANCASAKTI</div>
                    <div style="font-family: 'Times New Roman', Times, serif; font-size: 16pt; font-weight: bold; color: #a97b2d; margin: 3px 0; letter-spacing: 1px;">UNIVERSITAS PANCASAKTI TEGAL</div>
                    <div style="font-family: 'Times New Roman', Times, serif; font-size: 12pt; font-weight: bold; color: #000; letter-spacing: 0.5px;">FAKULTAS TEKNIK DAN ILMU KOMPUTER</div>
                    <div style="font-size: 8pt; font-weight: bold; color: #000; margin-top: 5px;">Kampus 1 : Jl. Halmahera KM 01 Kota Tegal | (0283) 351082 Fax. (0283) 351267</div>
                    <div style="font-size: 8pt; font-weight: bold; color: #000;">Kampus 2 : Jl. Perintis Kemerdekaan Kota Tegal</div>
                </td>
                
                <td width="15%" align="right" valign="middle">
                    <img src="{{ public_path('asset/img/logo_unggul.png') }}" width="85" alt="Unggul">
                </td>
            </tr>
        </table>

        <div class="tagline-container">
            <span class="tagline">Inovatif | Adaptif | Global</span>
        </div>
        
        <div class="garis-emas"></div>

        @php
            $domainAktif = request()->getSchemeAndHttpHost();
            $urlValidasi = $domainAktif . "/verifikasi/dokumen/" . ($pengajuan->id ?? '0');

            
            try {
                $qrcodeData = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                ->size(85)
                                ->margin(0)
                                ->generate($urlValidasi));
                $img_qr = '<img src="data:image/svg+xml;base64,' . $qrcodeData . '" width="85" height="85" style="margin: 6px 0;"><br>';
            } catch (\Exception $e) {
                $img_qr = '<div style="width:85px; height:85px; border:1px dashed #000; font-size:8pt; text-align:center; padding-top:30px; margin: 6px 0;">[QR TTD]</div><br>';
            }

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
                $namaProdi = 'Teknik Informatika';
                $namaKaprodi = 'Ali Sofyan, S.T., M.Kom';
                $nipyKaprodi = '3126511985';
            }
        @endphp

        <div class="isi-surat">
            {!! $isi_surat !!}
        </div>

        <div class="ttd-wrapper">
            <div class="ttd-box">
                @if($pengajuan->jenis_surat == 'Peminjaman Ruangan')
                    Tegal, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
                    Dekan Fakultas Teknik dan Ilmu Komputer,<br>
                    {!! $img_qr !!}
                    <strong>Dr. Agus Wibowo, S.T., M.T.</strong><br>
                    NIPY. 126518101972
                @elseif($pengajuan->jenis_surat == 'PKL' || $pengajuan->jenis_surat == 'KKL')
                    Tegal, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y') }}<br>
                    Kaprodi {{ $namaProdi }},<br>
                    {!! $img_qr !!}
                    <strong>{{ $namaKaprodi }}</strong><br>
                    NIPY. {{ $nipyKaprodi }}
                @else
                    Tegal, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
                    An. Dekan,<br>
                    Ka. Prodi {{ $namaProdi }},<br>
                    {!! $img_qr !!}
                    <strong>{{ $namaKaprodi }}</strong><br>
                    NIPY. {{ $nipyKaprodi }}
                @endif
            </div>
        </div>

    </div>

    <div class="footer-kontak">
        <img src="{{ public_path('asset/img/kop_bawah.png') }}" alt="Footer Kontak UPS">
    </div>

</body>
</html>