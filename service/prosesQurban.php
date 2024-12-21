<?php
include 'data.php';

// Fungsi untuk menambahkan tipe qurban
if (isset($_POST['add_qurban'])) {
    $tipe_qurban = $_POST['tipe_qurban'];
    $biaya = $_POST['biaya'];
    $biaya = str_replace(['Rp', '.', ','], '', $biaya); // Remove 'Rp', dots, and commas
    $biaya = (float)$biaya; // Convert to a floating-point number
    $jenis = $_POST['jenis'];
    
    $sql = "INSERT INTO qurban (tipe_qurban, biaya, status, jenis) VALUES ('$tipe_qurban', $biaya, 'Aktif', '$jenis')";
    if ($conn->query($sql) === TRUE) {
        echo "Tipe qurban berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fungsi untuk mengedit tipe qurban
if (isset($_POST['edit_qurban'])) {
    $qurban_id = $_POST['qurban_id'];
    $tipe_qurban = $_POST['tipe_qurban'];
    $biaya = $_POST['biaya'];
    
    // Update tipe_qurban dan biaya
    $sql = "UPDATE qurban SET tipe_qurban='$tipe_qurban', biaya='$biaya' WHERE qurban_id=$qurban_id";
    if ($conn->query($sql) === TRUE) {
        echo "Tipe qurban dan biaya berhasil diupdate!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}



// Fungsi untuk menonaktifkan tipe qurban
if (isset($_GET['disable_qurban'])) {
    $qurban_id = $_GET['qurban_id'];
    
    $sql = "UPDATE qurban SET status='Nonaktif' WHERE qurban_id=$qurban_id";
    if ($conn->query($sql) === TRUE) {
        echo "Tipe qurban berhasil dinonaktifkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>