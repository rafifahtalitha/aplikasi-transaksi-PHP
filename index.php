
<?php
require_once '../config/database.php';

try {
    $query = $pdo->query(
        "SELECT * FROM produk ORDER BY kode_produk ASC"
    );

    $produk = $query->fetchAll();

} catch (PDOException $e) {
    die("Gagal mengambil data produk: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - PHP Native</title>

    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

    <div class="container">

        <h1>Data Produk</h1>

        <p class="subtitle">
            Daftar seluruh produk yang tersimpan dalam database.
        </p>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Satuan</th>
                        <th>Harga Jual</th>
                        <th>Harga Beli</th>
                        <th>Stok</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($produk) > 0): ?>

                        <?php foreach ($produk as $item): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($item['kode_produk']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['nama_produk']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['satuan']) ?>
                                </td>

                                <td>
                                    Rp <?= number_format((float)$item['harga_jual'], 0, ',', '.') ?>
                                </td>

                                <td>
                                    Rp <?= number_format((float)$item['harga_beli'], 0, ',', '.') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string)$item['stok']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['deskripsi'] ?? '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="empty-data">
                                Belum ada data produk.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
