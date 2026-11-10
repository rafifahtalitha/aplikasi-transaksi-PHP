
<?php
$host = 'localhost';
$port = '5432';
$dbname = 'db_praktikum_transaksi';
$username = 'postgres';
$password = 'akucantik28'; // Isi password PostgreSQL lokal, jangan unggah ke GitHub

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
