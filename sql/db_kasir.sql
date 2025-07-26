CREATE TABLE barang (
    id_barang INT(11) AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    nama_barang VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    kategori VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    satuan VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    stok INT(11) DEFAULT 0,
    harga DECIMAL(15,2) NOT NULL,
    harga_beli DECIMAL(10,2) DEFAULT 0.00,
    harga_jual DECIMAL(10,2) DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT(11) DEFAULT NULL
);
CREATE TABLE detail_penjualan (
    id_detail INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_penjualan INT(11) NOT NULL,
    id_barang INT(11) NOT NULL,
    jumlah INT(11) NOT NULL,
    harga DECIMAL(15,2) NOT NULL,
    user_id INT(11) DEFAULT NULL,
    metode_pembayaran ENUM('qris', 'cash', 'debit') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'cash'
);
CREATE TABLE lokasi (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_lokasi VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
);
CREATE TABLE pembelian (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    nama_supplier VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    nama_barang VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    qty INT(11) NOT NULL,
    harga INT(11) NOT NULL,
    user_id INT(11) DEFAULT NULL
);
CREATE TABLE penjualan (
    id_penjualan INT(11) AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    user_id INT(11) DEFAULT NULL,
    nama_produk VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    qty INT(11) DEFAULT NULL,
    harga DOUBLE DEFAULT NULL,
    metode_bayar VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
);
CREATE TABLE servis (
    id_servis INT(11) AUTO_INCREMENT PRIMARY KEY,
    kode_servis VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    nama_pelanggan VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    no_hp VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    jenis_barang VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    kerusakan TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    status ENUM('Masuk', 'Proses', 'Selesai', 'Diambil') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Masuk',
    tgl_masuk DATETIME DEFAULT CURRENT_TIMESTAMP,
    tgl_selesai DATETIME DEFAULT NULL,
    user_id INT(11) DEFAULT NULL
);
CREATE TABLE stok_lokasi (
    id INT(11) NOT NULL AUTO_INCREMENT,
    id_barang INT(11) DEFAULT NULL,
    id_lokasi INT(11) DEFAULT NULL,
    jumlah INT(11) DEFAULT 0,
    user_id INT(11) NOT NULL,
    PRIMARY KEY (id),
    KEY (id_barang),
    KEY (id_lokasi)
);
CREATE TABLE transaksi (
    id_transaksi INT(11) NOT NULL AUTO_INCREMENT,
    tanggal DATETIME DEFAULT NULL,
    total DECIMAL(12,2) DEFAULT NULL,
    metode_bayar VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    user_id INT(11) NOT NULL,
    PRIMARY KEY (id_transaksi),
    KEY (user_id)
);
CREATE TABLE transfer_stok (
    id INT(11) NOT NULL AUTO_INCREMENT,
    id_barang INT(11) DEFAULT NULL,
    lokasi_asal INT(11) DEFAULT NULL,
    lokasi_tujuan INT(11) DEFAULT NULL,
    jumlah INT(11) DEFAULT NULL,
    tanggal DATETIME DEFAULT NULL,
    user_id INT(11) DEFAULT NULL,
    PRIMARY KEY (id),
    KEY (id_barang),
    KEY (lokasi_asal),
    KEY (lokasi_tujuan),
    KEY (user_id)
);
CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    nama_lengkap VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (username)
);
