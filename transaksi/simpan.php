
<?php
require_once __DIR__ . '/../config/database.php';

function tampilkanHasil($judul, $pesan, $berhasil = false)
{
    $warna = $berhasil ? '#15803d' : '#b91c1c';
    $ikon = $berhasil ? '✓' : '✕';

    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . htmlspecialchars($judul) . '</title>
        <link rel="stylesheet" href="../assets/style.css">
        <style>
            .result-container {
                max-width: 650px;
                margin: 70px auto;
                padding: 30px;
                background: white;
                border-radius: 12px;
                text-align: center;
            }
            .result-icon {
                font-size: 45px;
                color: ' . $warna . ';
            }
            .result-container h1 {
                color: ' . $warna . ';
            }
            .result-container p {
                line-height: 1.7;
            }
            .result-container a {
                display: inline-block;
                margin: 10px;
                padding: 11px 18px;
                background: #2563eb;
                color: white;
                text-decoration: none;
                border-radius: 7px;
            }
        </style>
    </head>
    <body>
        <div class="result-container">
            <div class="result-icon">' . $ikon . '</div>
            <h1>' . htmlspecialchars($judul) . '</h1>
            <p>' . htmlspecialchars($pesan) . '</p>
            <a href="index.php">Kembali ke Form Transaksi</a>
            <a href="../index.php">Beranda</a>
        </div>
    </body>
    </html>';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$noPenjualan = trim($_POST['no_penjualan'] ?? '');
$tanggal = $_POST['tgl_penjualan'] ?? '';
$kodePelanggan = trim($_POST['kode_pelanggan'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');

$kodeProduk = $_POST['kode_produk'] ?? [];
$jumlahProduk = $_POST['jumlah'] ?? [];

try {
    // Validasi data header
    if ($noPenjualan === '' || $tanggal === '' || $kodePelanggan === '') {
        throw new Exception('Nomor penjualan, tanggal, dan pelanggan wajib diisi.');
    }

    // Validasi format tanggal
    $tanggalObj = DateTime::createFromFormat('Y-m-d', $tanggal);

    if (!$tanggalObj || $tanggalObj->format('Y-m-d') !== $tanggal) {
        throw new Exception('Format tanggal penjualan tidak valid.');
    }

    // Validasi minimal dua detail
    if (
        !is_array($kodeProduk) ||
        !is_array($jumlahProduk) ||
        count($kodeProduk) < 2 ||
        count($kodeProduk) !== count($jumlahProduk)
    ) {
        throw new Exception('Transaksi harus memiliki minimal dua detail produk.');
    }

    // Mulai transaksi database
    $pdo->beginTransaction();

    $detailTransaksi = [];
    $totalTransaksi = 0;
    $totalPerProduk = [];

    // Validasi setiap detail dan ambil harga dari database
    foreach ($kodeProduk as $i => $kode) {
        $kode = trim($kode);
        $jumlah = filter_var(
            $jumlahProduk[$i] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($kode === '' || $jumlah === false || $jumlah < 1) {
            throw new Exception('Produk dan jumlah harus diisi dengan benar.');
        }

        // Mengunci baris produk selama transaksi berjalan
        $stmtProduk = $pdo->prepare(
            "SELECT kode_produk, nama_produk, harga_jual, stok
             FROM produk
             WHERE kode_produk = :kode
             FOR UPDATE"
        );

        $stmtProduk->execute(['kode' => $kode]);
        $produk = $stmtProduk->fetch(PDO::FETCH_ASSOC);

        if (!$produk) {
            throw new Exception('Produk dengan kode ' . $kode . ' tidak ditemukan.');
        }

        $harga = (float) $produk['harga_jual'];
        $stok = (int) $produk['stok'];

        if ($jumlah > $stok) {
            throw new Exception(
                'Stok produk ' . $produk['nama_produk'] .
                ' tidak mencukupi. Stok tersedia: ' . $stok
            );
        }

        $subtotal = $jumlah * $harga;

        $detailTransaksi[] = [
            'kode_produk' => $kode,
            'jumlah' => $jumlah,
            'harga' => $harga,
            'subtotal' => $subtotal
        ];

        // Menghitung total jumlah jika produk dipilih lebih dari sekali
        if (!isset($totalPerProduk[$kode])) {
            $totalPerProduk[$kode] = 0;
        }

        $totalPerProduk[$kode] += $jumlah;

        $totalTransaksi += $subtotal;
    }

    // Validasi total jumlah produk jika ada kode produk yang sama
    foreach ($totalPerProduk as $kode => $totalJumlah) {
        $stmtStok = $pdo->prepare(
            "SELECT stok FROM produk WHERE kode_produk = :kode"
        );

        $stmtStok->execute(['kode' => $kode]);
        $stokTersedia = (int) $stmtStok->fetchColumn();

        if ($totalJumlah > $stokTersedia) {
            throw new Exception(
                'Total jumlah produk ' . $kode .
                ' melebihi stok yang tersedia.'
            );
        }
    }

    // Simpan header penjualan
    $sqlHeader = "INSERT INTO penjualan
                    (no_penjualan, tgl_penjualan, kode_pelanggan, total, keterangan)
                  VALUES
                    (:no_penjualan, :tgl_penjualan, :kode_pelanggan, :total, :keterangan)";

    $stmtHeader = $pdo->prepare($sqlHeader);

    $stmtHeader->execute([
        'no_penjualan' => $noPenjualan,
        'tgl_penjualan' => $tanggal,
        'kode_pelanggan' => $kodePelanggan,
        'total' => $totalTransaksi,
        'keterangan' => $keterangan
    ]);

    // Simpan detail penjualan
    $sqlDetail = "INSERT INTO detil_penjualan
                    (no_penjualan, no_urut, kode_produk, jumlah, harga, subtotal)
                  VALUES
                    (:no_penjualan, :no_urut, :kode_produk, :jumlah, :harga, :subtotal)";

    $stmtDetail = $pdo->prepare($sqlDetail);

    foreach ($detailTransaksi as $index => $detail) {
        $stmtDetail->execute([
            'no_penjualan' => $noPenjualan,
            'no_urut' => $index + 1,
            'kode_produk' => $detail['kode_produk'],
            'jumlah' => $detail['jumlah'],
            'harga' => $detail['harga'],
            'subtotal' => $detail['subtotal']
        ]);
    }

    // Semua proses berhasil, simpan perubahan
    $pdo->commit();

    tampilkanHasil(
        'Transaksi Berhasil!',
        'Data header dan ' . count($detailTransaksi) .
        ' detail penjualan berhasil disimpan menggunakan COMMIT. ' .
        'Total transaksi: Rp ' .
        number_format($totalTransaksi, 0, ',', '.') . '.',
        true
    );

} catch (Throwable $e) {

    // Jika terjadi kesalahan, batalkan seluruh perubahan
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    tampilkanHasil(
        'Transaksi Gagal!',
        $e->getMessage() .
        ' Seluruh perubahan dibatalkan menggunakan ROLLBACK.',
        false
    );
}
