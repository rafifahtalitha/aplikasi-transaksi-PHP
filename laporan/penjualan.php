
<?php
require_once __DIR__ . '/../config/database.php';

try {
    $sql = "SELECT
                p.no_penjualan,
                p.tgl_penjualan,
                p.kode_pelanggan,
                pl.nama_pelanggan,
                p.total,
                p.keterangan
            FROM penjualan p
            LEFT JOIN pelanggan pl
                ON p.kode_pelanggan = pl.kode_pelanggan
            ORDER BY p.tgl_penjualan DESC, p.no_penjualan DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $dataPenjualan = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Gagal mengambil data penjualan: " . htmlspecialchars($e->getMessage()));
}

function rupiah($angka)
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function tanggalIndonesia($tanggal)
{
    return date('d-m-Y', strtotime($tanggal));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .report-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }

        .report-header {
            margin-bottom: 25px;
        }

        .report-header h1 {
            margin-bottom: 8px;
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
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .report-table th {
            background: #f3f4f6;
        }

        .report-table td.total {
            text-align: right;
            white-space: nowrap;
        }

        .total-row {
            font-weight: bold;
            background: #f9fafb;
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
        <h1>Laporan Penjualan</h1>
        <p>Daftar transaksi penjualan beserta nama pelanggan.</p>
    </div>

    <div class="report-table-wrapper">
        <table class="report-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Penjualan</th>
                    <th>Tanggal</th>
                    <th>Kode Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>Keterangan</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($dataPenjualan) > 0): ?>

                    <?php
                    $nomor = 1;
                    $totalKeseluruhan = 0;
                    ?>

                    <?php foreach ($dataPenjualan as $penjualan): ?>
                        <?php
                        $totalKeseluruhan += (float) $penjualan['total'];
                        ?>

                        <tr>
                            <td><?= $nomor++; ?></td>

                            <td>
                                <?= htmlspecialchars($penjualan['no_penjualan']); ?>
                            </td>

                            <td>
                                <?= tanggalIndonesia($penjualan['tgl_penjualan']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($penjualan['kode_pelanggan']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($penjualan['nama_pelanggan'] ?? 'Tidak ditemukan'); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($penjualan['keterangan'] ?? '-'); ?>
                            </td>

                            <td class="total">
                                <?= rupiah($penjualan['total']); ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    <tr class="total-row">
                        <td colspan="6">Total Seluruh Penjualan</td>
                        <td class="total">
                            <?= rupiah($totalKeseluruhan); ?>
                        </td>
                    </tr>

                <?php else: ?>

                    <tr>
                        <td colspan="7" class="empty-data">
                            Belum ada data penjualan.
                        </td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p>
        Jumlah transaksi:
        <strong><?= count($dataPenjualan); ?> transaksi</strong>
    </p>

</div>

</body>
</html>
