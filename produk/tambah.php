
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - PHP Native</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Tambah Produk</h1>
        <p class="subtitle">Masukkan informasi produk baru.</p>

        <div class="table-wrapper" style="padding: 25px;">
            <form action="simpan.php" method="POST">

                <p>Kode Produk</p>
                <input type="text" name="kode_produk" maxlength="10" required>

                <p>Nama Produk</p>
                <input type="text" name="nama_produk" maxlength="100" required>

                <p>Satuan</p>
                <input type="text" name="satuan" maxlength="20" required>

                <p>Harga Jual</p>
                <input type="number" name="harga_jual" min="0" step="0.01" required>

                <p>Harga Beli</p>
                <input type="number" name="harga_beli" min="0" step="0.01" required>

                <p>Stok</p>
                <input type="number" name="stok" min="0" value="0" required>

                <p>Deskripsi</p>
                <textarea name="deskripsi" rows="4"></textarea>

                <br><br>
                <button type="submit">Simpan Produk</button>
                <a href="index.php">Kembali</a>

            </form>
        </div>
    </div>
</body>
</html>
