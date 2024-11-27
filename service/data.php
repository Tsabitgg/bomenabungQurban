<?php
$host = "localhost";
$user = "root";
$pass = "Smartpay1ct";
$database = "menabung_qurban";

$conn = new mysqli($host, $user, $pass, $database);

if ($conn-> connect_errno) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function getSUMTransaksi($conn) {
    $sql = "SELECT SUM(jumlah_setoran) FROM transaksi";
    $result = $conn->query($sql);

    return $result;
}

function getCountKartuQurban($conn) {
    $sql = "SELECT COUNT(kartu_qurban_id) FROM kartu_qurban";
    $result = $conn->query($sql);

    return $result;
}

function getCountPengqurban($conn) {
    $sql = "SELECT COUNT(DISTINCT nama_pengqurban) FROM kartu_qurban";
    $result = $conn->query($sql);

    return $result;
}

function getCountTransaksi($conn) {
    $sql = "SELECT COUNT(transaksi_id) FROM transaksi";
    $result = $conn->query($sql);

    return $result;
}

function getTransaksiForDashboard($conn) {
    $sql = "SELECT 
                u.nama AS nama, 
                q.tipe_qurban AS tipe_qurban, 
                t.metode_pembayaran AS metode_pembayaran, 
                t.jumlah_setoran AS jumlah, 
                t.tanggal_transaksi AS waktu
            FROM users u
            JOIN kartu_qurban kq ON u.user_id = kq.user_id
            JOIN qurban q ON kq.qurban_id = q.qurban_id
            JOIN transaksi t ON kq.kartu_qurban_id = t.kartu_qurban_id limit 6";
    
    $result = $conn->query($sql);
    return $result;
}

function getPaginatedTransaksi($conn, $offset, $limit) {
    $sql = "SELECT 
                u.nama AS nama, 
                q.tipe_qurban AS tipe_qurban, 
                t.metode_pembayaran AS metode_pembayaran, 
                t.jumlah_setoran AS jumlah, 
                t.tanggal_transaksi AS waktu
            FROM users u
            JOIN kartu_qurban kq ON u.user_id = kq.user_id
            JOIN qurban q ON kq.qurban_id = q.qurban_id
            JOIN transaksi t ON kq.kartu_qurban_id = t.kartu_qurban_id
            LIMIT $offset, $limit";

    return $conn->query($sql);
}

function getTotalTransaksi($conn) {
    $sql = "SELECT COUNT(*) AS total FROM transaksi";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}


// Fungsi mengambil data qurban
function getDetailQurban($conn) {
    $sql = "SELECT 
    q.tipe_qurban AS tipe_qurban, q.jenis, 
    COUNT(kq.kartu_qurban_id) AS jumlah FROM 
    qurban q LEFT JOIN kartu_qurban kq ON q.qurban_id = kq.qurban_id
    GROUP BY q.tipe_qurban, q.jenis;";

    $result = $conn->query($sql);
    return $result;
}

// Fungsi untuk mendapatkan daftar kartu qurban
function getKartuQurban($conn) {
    $sql = "SELECT kq.*, q.tipe_qurban FROM kartu_qurban kq JOIN qurban q ON kq.qurban_id = q.qurban_id Order By kq.kartu_qurban_id ASC";
    $result = $conn->query($sql);
    return $result;
}

// Mendapatkan total uang qurban (target terpenuhi)
function getUangQurban($conn) {
    $sql = "SELECT SUM(t.jumlah_setoran) AS uang_qurban 
            FROM transaksi t
            JOIN kartu_qurban kq ON t.kartu_qurban_id = kq.kartu_qurban_id
            WHERE kq.jumlah_terkumpul >= kq.biaya"; // Target terpenuhi jika jumlah_terkumpul >= biaya
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['uang_qurban'] ?? 0;
}

// Mendapatkan total uang tabungan (target belum terpenuhi)
function getUangTabungan($conn) {
    $sql = "SELECT SUM(t.jumlah_setoran) AS uang_tabungan 
            FROM transaksi t
            JOIN kartu_qurban kq ON t.kartu_qurban_id = kq.kartu_qurban_id
            WHERE kq.jumlah_terkumpul < kq.biaya"; // Target belum terpenuhi jika jumlah_terkumpul < biaya
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['uang_tabungan'] ?? 0;
}


// Mendapatkan total saldo dari tabel users
function getSaldo($conn) {
    $sql = "SELECT SUM(saldo) AS total_saldo FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total_saldo'] ?? 0;
}

function getSummaryData($conn) {
    $sql = "SELECT SUM(biaya) AS total_biaya, SUM(jumlah_terkumpul) AS total_terkumpul FROM kartu_qurban";
    return $conn->query($sql)->fetch_assoc();
}

?>
