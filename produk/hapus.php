
<?php
require_once '../config/database.php';

// Pastikan permintaan menggunakan metode GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Location: index.php');
    exit;
}

$kode_produk = $_GET['kode_produk'] ?? '';

if ($kode_produk === '') {
    die("Kode produk tidak ditemukan.");
}

try {
    $sql = "DELETE FROM produk WHERE kode_produk = :kode_produk";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':kode_produk' => $kode_produk
    ]);

    if ($stmt->rowCount() > 0) {
        header('Location: index.php');
        exit;
    }

    die("Produk tidak ditemukan atau sudah dihapus.");

} catch (PDOException $e) {
    die("Gagal menghapus produk. Produk mungkin masih digunakan dalam transaksi. "
        . htmlspecialchars($e->getMessage()));
}
