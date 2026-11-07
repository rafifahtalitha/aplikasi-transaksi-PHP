
<?php
require_once __DIR__ . '/../config/database.php';

try {
    $stmtPelanggan = $pdo->query(
        "SELECT kode_pelanggan, nama_pelanggan
         FROM pelanggan
         ORDER BY nama_pelanggan ASC"
    );

    $dataPelanggan = $stmtPelanggan->fetchAll(PDO::FETCH_ASSOC);

    $stmtProduk = $pdo->query(
        "SELECT kode_produk, nama_produk, harga_jual, stok
         FROM produk
         ORDER BY nama_produk ASC"
    );

    $dataProduk = $stmtProduk->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Gagal mengambil data form: " .
        htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi Penjualan</title>
    <link rel="stylesheet" href="../assets/style.css">

    <style>
        .transaction-container {
            max-width: 850px;
            margin: 30px auto;
            padding: 20px;
        }

        .transaction-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            box-sizing: border-box;
        }

        .detail-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 15px;
        }

        .detail-card h3 {
            margin-top: 0;
        }

        .btn-submit {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 15px;
            text-decoration: none;
        }

        .info {
            background: #eff6ff;
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #1e40af;
        }
    </style>
</head>

<body>

<div class="transaction-container">

    <a href="../index.php" class="back-link">
        ← Kembali ke Beranda
    </a>

    <h1>Tambah Transaksi Penjualan</h1>
    <p>Masukkan informasi transaksi beserta minimal dua detail produk.</p>

    <div class="transaction-card">

        <div class="info">
            Satu transaksi akan menyimpan satu header penjualan
            dan dua detail produk secara bersamaan.
        </div>

        <form action="simpan.php" method="POST">

            <div class="form-group">
                <label for="no_penjualan">Nomor Penjualan</label>
                <input
                    type="text"
                    id="no_penjualan"
                    name="no_penjualan"
                    placeholder="Contoh: J20261107-001"
                    required
                >
            </div>

            <div class="form-group">
                <label for="tgl_penjualan">Tanggal Penjualan</label>
                <input
                    type="date"
                    id="tgl_penjualan"
                    name="tgl_penjualan"
                    value="<?= date('Y-m-d'); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="kode_pelanggan">Pelanggan</label>
                <select
                    id="kode_pelanggan"
                    name="kode_pelanggan"
                    required
                >
                    <option value="">-- Pilih Pelanggan --</option>

                    <?php foreach ($dataPelanggan as $pelanggan): ?>
                        <option value="<?= htmlspecialchars($pelanggan['kode_pelanggan']); ?>">
                            <?= htmlspecialchars($pelanggan['kode_pelanggan']); ?>
                            - <?= htmlspecialchars($pelanggan['nama_pelanggan']); ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea
                    id="keterangan"
                    name="keterangan"
                    rows="3"
                    placeholder="Keterangan transaksi (opsional)"
                ></textarea>
            </div>

            <hr>

            <h2>Detail Produk</h2>

            <?php for ($i = 1; $i <= 2; $i++): ?>

                <div class="detail-card">

                    <h3>Produk <?= $i; ?></h3>

                    <div class="form-group">
                        <label for="produk_<?= $i; ?>">
                            Pilih Produk
                        </label>

                        <select
                            id="produk_<?= $i; ?>"
                            name="kode_produk[]"
                            required
                        >
                            <option value="">-- Pilih Produk --</option>

                            <?php foreach ($dataProduk as $produk): ?>
                                <option value="<?= htmlspecialchars($produk['kode_produk']); ?>">
                                    <?= htmlspecialchars($produk['kode_produk']); ?>
                                    - <?= htmlspecialchars($produk['nama_produk']); ?>
                                    (Stok: <?= (int) $produk['stok']; ?>)
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_<?= $i; ?>">
                            Jumlah
                        </label>

                        <input
                            type="number"
                            id="jumlah_<?= $i; ?>"
                            name="jumlah[]"
                            min="1"
                            placeholder="Masukkan jumlah"
                            required
                        >
                    </div>

                </div>

            <?php endfor; ?>

            <button type="submit" class="btn-submit">
                Simpan Transaksi
            </button>

        </form>

    </div>

</div>

</body>
</html>
