
<?php
require_once '../config/database.php';

$keyword = trim($_GET['keyword'] ?? '');

if ($keyword !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM produk
         WHERE nama_produk ILIKE :keyword
         ORDER BY kode_produk ASC"
    );

    $stmt->execute([
        ':keyword' => '%' . $keyword . '%'
    ]);
} else {
    $stmt = $pdo->query(
        "SELECT * FROM produk ORDER BY kode_produk ASC"
    );
}

$produk = $stmt->fetchAll();
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
        Kelola dan cari data produk yang tersimpan dalam database.
    </p>

    <div class="toolbar">
        <form action="index.php" method="GET" class="search-form">
            <input
                type="text"
                name="keyword"
                placeholder="Cari nama produk..."
                value="<?= htmlspecialchars($keyword) ?>"
            >
            <button type="submit" class="btn btn-primary">
                Cari
            </button>

            <?php if ($keyword !== ''): ?>
                <a href="index.php" class="btn btn-secondary">
                    Reset
                </a>
            <?php endif; ?>
        </form>

        <a href="tambah.php" class="btn btn-success">
            + Tambah Produk
        </a>
    </div>

    <p class="result-info">
        <?php if ($keyword !== ''): ?>
            Hasil pencarian untuk:
            <strong><?= htmlspecialchars($keyword) ?></strong>
        <?php else: ?>
            Seluruh data produk
        <?php endif; ?>

        — <?= count($produk) ?> data ditemukan.
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
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($produk)): ?>

                    <?php foreach ($produk as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['kode_produk']) ?></td>
                        <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                        <td><?= htmlspecialchars($item['satuan']) ?></td>
                        <td>Rp <?= number_format((float)$item['harga_jual'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format((float)$item['harga_beli'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars((string)$item['stok']) ?></td>
                        <td><?= htmlspecialchars($item['deskripsi'] ?? '-') ?></td>

                        <td class="action-cell">
                            <a
                                href="edit.php?kode_produk=<?= urlencode($item['kode_produk']) ?>"
                                class="btn btn-edit"
                            >
                                Edit
                            </a>

                            <a
                                href="hapus.php?kode_produk=<?= urlencode($item['kode_produk']) ?>"
                                class="btn btn-delete"
                                onclick="return confirm('Yakin ingin menghapus produk ini?')"
                            >
                                Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty-data">
                            Tidak ada produk yang sesuai dengan pencarian.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
