<?php
$host = "103.23.103.43";
$user = "elpe";
$pass = "Bismillah99";
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

function getAllTransaksi($conn) {
    $sql = "SELECT 
                u.nama AS nama, 
                q.tipe_qurban AS tipe_qurban, 
                t.metode_pembayaran AS metode_pembayaran, 
                t.jumlah_setoran AS jumlah, 
                t.tanggal_transaksi AS waktu
            FROM users u
            JOIN kartu_qurban kq ON u.user_id = kq.user_id
            JOIN qurban q ON kq.qurban_id = q.qurban_id
            JOIN transaksi t ON kq.kartu_qurban_id = t.kartu_qurban_id";
    
    $result = $conn->query($sql);
    return $result;
}

// Fungsi mengambil data qurban
function getDetailQurban($conn) {
    $sql = "SELECT 
                q.tipe_qurban as tipe_qurban, 
                COUNT(kq.kartu_qurban_id) as jumlah
            FROM qurban q JOIN kartu_qurban kq on q.qurban_id = kq.qurban_id GROUP BY tipe_qurban";

    $result = $conn->query($sql);
    return $result;
}

// Fungsi untuk mendapatkan daftar kartu qurban
function getKartuQurban($conn) {
    $sql = "SELECT kq.*, q.tipe_qurban FROM kartu_qurban kq JOIN qurban q ON kq.qurban_id = q.qurban_id";
    $result = $conn->query($sql);
    return $result;
}
?>