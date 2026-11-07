
<?php
$pageTitle = "Dashboard PHP Native";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .dashboard {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .dashboard-header {
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            margin-bottom: 8px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
        }

        .menu-card {
            display: block;
            padding: 24px;
            background: white;
            border-radius: 12px;
            text-decoration: none;
            color: #222;
            border: 1px solid #e5e5e5;
            transition: 0.2s;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
        }

        .menu-card h3 {
            margin-top: 0;
            color: #2457a6;
        }

        .menu-card p {
            color: #666;
            line-height: 1.5;
        }

        .dashboard-footer {
            margin-top: 35px;
            color: #777;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <div class="dashboard-header">
        <h1>Aplikasi Transaksi PHP Native</h1>
        <p>
            Praktikum Basis Data Lanjut — Pengelolaan Produk,
            Laporan Penjualan, dan Transaksi Database.
        </p>
    </div>

    <div class="menu-grid">

        <a href="produk/index.php" class="menu-card">
            <h3>Data Produk</h3>
            <p>Melihat, menambahkan, mengubah, menghapus, dan mencari data produk.</p>
        </a>

        <a href="laporan/penjualan.php" class="menu-card">
            <h3>Data Penjualan</h3>
            <p>Melihat data penjualan beserta nama pelanggan menggunakan JOIN.</p>
        </a>

        <a href="laporan/agregasi_pelanggan.php" class="menu-card">
            <h3>Agregasi Pelanggan</h3>
            <p>Melihat jumlah transaksi dan total pembelian setiap pelanggan.</p>
        </a>

        <a href="laporan/penjualan_produk.php" class="menu-card">
            <h3>Laporan Penjualan Produk</h3>
            <p>Melihat jumlah produk terjual dan nilai penjualannya.</p>
        </a>

        <a href="transaksi/index.php" class="menu-card">
            <h3>Transaksi Baru</h3>
            <p>Membuat transaksi dengan pengujian COMMIT dan ROLLBACK.</p>
        </a>

    </div>

    <div class="dashboard-footer">
        <p>Praktikum Basis Data Lanjut | PHP Native dengan PDO dan PostgreSQL</p>
    </div>
</div>

</body>
</html>
