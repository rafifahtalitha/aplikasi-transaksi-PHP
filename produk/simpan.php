
<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tambah.php');
    exit;
}

$kode_produk = trim($_POST['kode_produk'] ?? '');
$nama_produk = trim($_POST['nama_produk'] ?? '');
$satuan = trim($_POST['satuan'] ?? '');
$harga_jual = $_POST['harga_jual'] ?? '';
$harga_beli = $_POST['harga_beli'] ?? '';
$stok = $_POST['stok'] ?? '';
$deskripsi = trim($_POST['deskripsi'] ?? '');

if (
    $kode_produk === '' ||
    $nama_produk === '' ||
    $satuan === '' ||
    !is_numeric($harga_jual) ||
    !is_numeric($harga_beli) ||
    filter_var($stok, FILTER_VALIDATE_INT) === false ||
    (int)$stok < 0 ||
    (float)$harga_jual < 0 ||
    (float)$harga_beli < 0
) {
    die("Data tidak valid. Silakan periksa kembali formulir.");
}

try {
    $sql = "INSERT INTO produk
            (kode_produk, nama_produk, satuan, harga_jual, harga_beli, stok, deskripsi)
            VALUES
            (:kode_produk, :nama_produk, :satuan, :harga_jual, :harga_beli, :stok, :deskripsi)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':kode_produk' => $kode_produk,
        ':nama_produk' => $nama_produk,
        ':satuan' => $satuan,
        ':harga_jual' => $harga_jual,
        ':harga_beli' => $harga_beli,
        ':stok' => $stok,
        ':deskripsi' => $deskripsi
    ]);

    header('Location: index.php');
    exit;

} catch (PDOException $e) {
    if ($e->getCode() === '23505') {
        die("Gagal menyimpan: kode produk sudah digunakan.");
    }

    die("Gagal menyimpan produk: " . htmlspecialchars($e->getMessage()));
}
