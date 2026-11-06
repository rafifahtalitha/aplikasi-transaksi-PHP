
<?php
require_once '../config/database.php';

$kode_produk = $_GET['kode_produk'] ?? '';

if ($kode_produk === '') {
    die("Kode produk tidak ditemukan.");
}

$stmt = $pdo->prepare(
    "SELECT * FROM produk WHERE kode_produk = :kode_produk"
);
$stmt->execute([':kode_produk' => $kode_produk]);

$produk = $stmt->fetch();

if (!$produk) {
    die("Produk tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - PHP Native</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Produk</h1>
        <p class="subtitle">Perbarui informasi produk yang dipilih.</p>

        <div class="table-wrapper" style="padding: 25px;">
            <form action="update.php" method="POST">

                <p>Kode Produk</p>
                <input
                    type="text"
                    name="kode_produk"
                    value="<?= htmlspecialchars($produk['kode_produk']) ?>"
                    readonly
                >

                <p>Nama Produk</p>
                <input
                    type="text"
                    name="nama_produk"
                    maxlength="100"
                    value="<?= htmlspecialchars($produk['nama_produk']) ?>"
                    required
                >

                <p>Satuan</p>
                <input
                    type="text"
                    name="satuan"
                    maxlength="20"
                    value="<?= htmlspecialchars($produk['satuan']) ?>"
                    required
                >

                <p>Harga Jual</p>
                <input
                    type="number"
                    name="harga_jual"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars((string)$produk['harga_jual']) ?>"
                    required
                >

                <p>Harga Beli</p>
                <input
                    type="number"
                    name="harga_beli"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars((string)$produk['harga_beli']) ?>"
                    required
                >

                <p>Stok</p>
                <input
                    type="number"
                    name="stok"
                    min="0"
                    value="<?= htmlspecialchars((string)$produk['stok']) ?>"
                    required
                >

                <p>Deskripsi</p>
                <textarea name="deskripsi" rows="4"><?= htmlspecialchars($produk['deskripsi'] ?? '') ?></textarea>

                <br><br>
                <button type="submit">Simpan Perubahan</button>
                <a href="index.php">Kembali</a>

            </form>
        </div>
    </div>
</body>
</html>
