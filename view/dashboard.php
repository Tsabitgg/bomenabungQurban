<?php

include '../service/data.php';

// Fetch necessary data
$total = getSUMTransaksi($conn)->fetch_row()[0]; 
$count_qurban = getCountKartuQurban($conn)->fetch_row()[0];
$transaksi = getAllTransaksi($conn);
$count_pengqurban = getCountPengqurban($conn)->fetch_row()[0];
$count_transaksi = getCountTransaksi($conn)->fetch_row()[0];
$detail_qurban = getDetailQurban($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
  <style>
   body {
     font-family: 'Roboto', sans-serif;
   }
  </style>
</head>
<body class="bg-white text-black">
  <div class="flex">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <!-- Main Content -->
   <div class="w-3/4 p-6">
    <header class="flex justify-between items-center mb-8">
     <h1 class="text-2xl font-bold">Dashboard</h1>
     <div class="flex items-center space-x-4">
      <i class="fas fa-bell text-gray-600"></i>
      <i class="fas fa-envelope text-gray-600"></i>
      <input class="bg-gray-300 text-black p-2 rounded" placeholder="Search Here" type="text"/>
      <img alt="User profile picture" class="rounded-full" height="40" src="https://storage.googleapis.com/a1aa/image/fXmQRhwDrN1fkEEWMWTGml4kjjbCLk0wRrcrlVnYWcfnpONnA.jpg" width="40"/>
     </div>
    </header>
    <div class="grid grid-cols-2 gap-6">

     <!-- Tabungan Total -->
     <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-8 rounded-lg text-white space-y-4 shadow-lg">
      <div class="text-center space-y-1">
        <p class="text-lg font-bold text-center">DT PEDULI</p>
        <p class="text-2xl text-center">Tabungan Qurban</p>
      </div>
      <h2 class="text-3xl font-bold text-center">Tabungan Total</h2>
      <p class="text-5xl font-extrabold text-center">
        Rp<?= number_format($total, 0, ',', '.'); ?>
      </p>
      <p class="text-2xl text-center">
        <?= $count_qurban . " Qurban Card"; ?>
      </p>
     </div>

     <!-- Transaksi -->
     <div class="bg-gray-200 p-6 rounded-lg">
      <h2 class="text-xl font-bold mb-4">Transaksi</h2>
      <table class="w-full text-left">
        <thead>
          <tr class="bg-gray-300">
            <th class="p-2">Nama</th>
            <th class="p-2">Tipe Qurban</th>
            <th class="p-2">Metode Pembayaran</th>
            <th class="p-2">Jumlah</th>
            <th class="p-2">Waktu</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            if ($transaksi->num_rows > 0) {
              while ($row = $transaksi->fetch_assoc()) {
                $jenisTransaksiClass = $row['metode_pembayaran'] == 'qris' ? 'text-green-500' : 'text-yellow-500';
                echo "<tr class='border-t'>
                        <td class='p-2'>{$row['nama']}</td>
                        <td class='p-2'>{$row['tipe_qurban']}</td>
                        <td class='p-2 {$jenisTransaksiClass}'>{$row['metode_pembayaran']}</td>
                        <td class='p-2 {$jenisTransaksiClass}'>" . number_format($row['jumlah'], 0, ',', '.') . "</td>
                        <td class='p-2'>{$row['waktu']}</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='5' class='text-center'>Tidak ada transaksi.</td></tr>";
            }
          ?>
        </tbody>
      </table>
     </div>

     <!-- Pengqurban & Transaksi -->
     <div class="flex space-x-4">
      <!-- Box 1: Pengqurban -->
      <div class="bg-gray-200 p-6 rounded-lg flex justify-between items-center w-1/2 shadow-lg">
        <div class="flex items-center space-x-3">
          <i class="fas fa-users text-green-500 text-4xl"></i>
          <div>
            <h2 class="text-xl font-bold">Pengqurban</h2>
            <p class="text-3xl font-bold text-green-500">
              <?= $count_pengqurban; ?>
            </p>
          </div>
        </div>
      </div>

      <!-- Box 2: Transaksi -->
      <div class="bg-gray-200 p-6 rounded-lg flex justify-between items-center w-1/2 shadow-lg">
        <div class="flex items-center space-x-3">
          <i class="fas fa-hand-holding-usd text-red-500 text-4xl"></i>
          <div>
            <h2 class="text-xl font-bold">Transaksi</h2>
            <p class="text-3xl font-bold text-red-500">
              <?= $count_transaksi; ?>
            </p>
          </div>
        </div>
      </div>
     </div>

     <!-- Tipe Qurban -->
     <div class="bg-gray-200 p-6 rounded-lg">
      <h2 class="text-xl font-bold mb-4">Tipe Qurban</h2>
      <div class="space-y-2">
        <?php 
          while ($row = $detail_qurban->fetch_assoc()) {
            echo "<div class='flex justify-between'>
                    <span>{$row['tipe_qurban']}</span>
                    <span>{$row['jumlah']} Hewan</span>
                  </div>";
          }
        ?>
      </div>
     </div>

    </div>
   </div>
  </div>
</body>
</html>
