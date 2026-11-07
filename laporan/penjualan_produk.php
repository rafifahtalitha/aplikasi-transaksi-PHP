
<?php
require_once __DIR__ . '/../config/database.php';

try {
    $sql = "SELECT
                pr.kode_produk,
                pr.nama_produk,
                COALESCE(SUM(dp.jumlah), 0) AS total_terjual,
                COALESCE(SUM(dp.subtotal), 0) AS total_penjualan
            FROM produk pr
            LEFT JOIN detil_penjualan dp
                ON pr.kode_produk = dp.kode_produk
            GROUP BY
                pr.kode_produk,
                pr.nama_produk
            ORDER BY total_terjual DESC, pr.nama_produk ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $dataProduk = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Gagal mengambil laporan penjualan produk: " .
        htmlspecialchars($e->getMessage()));
}

function rupiah($angka)
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Produk</title>
    <link rel="stylesheet" href="../assets/style.css">

    <style>
        .report-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }

        .report-header {
            margin-bottom: 25px;
        }

        .report-table-wrapper {
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            padding: 15px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th,
        .report-table td {
            padding: 13px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .report-table th {
            background: #f3f4f6;
        }

        .report-table td.number {
            text-align: center;
        }

        .report-table td.total {
            text-align: right;
            white-space: nowrap;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .empty-data {
            text-align: center;
            padding: 25px;
        }
    </style>
</head>

<body>

<div class="report-container">

    <a href="../index.php" class="back-link">← Kembali ke Beranda</a>

    <div class="report-header">
        <h1>Laporan Penjualan Produk</h1>
        <p>Ringkasan jumlah produk terjual dan nilai penjualannya.</p>
    </div>

    <div class="report-table-wrapper">
        <table class="report-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Total Terjual</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($dataProduk) > 0): ?>

                    <?php
                    $nomor = 1;
                    $totalSeluruhProduk = 0;
                    $totalSeluruhPenjualan = 0;
                    ?>

                    <?php foreach ($dataProduk as $produk): ?>

                        <?php
                        $totalSeluruhProduk += (int) $produk['total_terjual'];
                        $totalSeluruhPenjualan += (float) $produk['total_penjualan'];
                        ?>

                        <tr>
                            <td><?= $nomor++; ?></td>

                            <td>
                                <?= htmlspecialchars($produk['kode_produk']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($produk['nama_produk']); ?>
                            </td>

                            <td class="number">
                                <?= (int) $produk['total_terjual']; ?>
                            </td>

                            <td class="total">
                                <?= rupiah($produk['total_penjualan']); ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    <tr>
                        <th colspan="3">Total Keseluruhan</th>
                        <th class="number"><?= $totalSeluruhProduk; ?></th>
                        <th class="total"><?= rupiah($totalSeluruhPenjualan); ?></th>
                    </tr>

                <?php else: ?>

                    <tr>
                        <td colspan="5" class="empty-data">
                            Belum ada data produk.
                        </td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p>
        Jumlah jenis produk:
        <strong><?= count($dataProduk); ?> produk</strong>
    </p>

</div>

</body>
</html>
