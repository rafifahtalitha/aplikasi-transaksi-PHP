
<?php
require_once __DIR__ . '/../config/database.php';

try {
    $sql = "SELECT
                pl.kode_pelanggan,
                pl.nama_pelanggan,
                COUNT(p.no_penjualan) AS jumlah_transaksi,
                COALESCE(SUM(p.total), 0) AS total_pembelian
            FROM pelanggan pl
            LEFT JOIN penjualan p
                ON pl.kode_pelanggan = p.kode_pelanggan
            GROUP BY
                pl.kode_pelanggan,
                pl.nama_pelanggan
            ORDER BY total_pembelian DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $dataPelanggan = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Gagal mengambil data agregasi pelanggan: " .
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
    <title>Agregasi Penjualan Pelanggan</title>
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
        <h1>Agregasi Penjualan Pelanggan</h1>
        <p>Ringkasan jumlah transaksi dan total pembelian setiap pelanggan.</p>
    </div>

    <div class="report-table-wrapper">
        <table class="report-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Pembelian</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($dataPelanggan) > 0): ?>

                    <?php $nomor = 1; ?>

                    <?php foreach ($dataPelanggan as $pelanggan): ?>
                        <tr>
                            <td><?= $nomor++; ?></td>

                            <td>
                                <?= htmlspecialchars($pelanggan['kode_pelanggan']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($pelanggan['nama_pelanggan']); ?>
                            </td>

                            <td class="number">
                                <?= (int) $pelanggan['jumlah_transaksi']; ?>
                            </td>

                            <td class="total">
                                <?= rupiah($pelanggan['total_pembelian']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5" class="empty-data">
                            Belum ada data pelanggan.
                        </td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p>
        Jumlah pelanggan:
        <strong><?= count($dataPelanggan); ?> pelanggan</strong>
    </p>

</div>

</body>
</html>
