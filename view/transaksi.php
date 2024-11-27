<?php
include '../service/data.php';

session_start();

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit();
}

// Pagination setup
$total_data = getTotalTransaksi($conn);
$per_page = 50;
$total_pages = ceil($total_data / $per_page);
$current_page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($current_page - 1) * $per_page;

// Fetch data for current page
$transactions = getPaginatedTransaksi($conn, $offset, $per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Header -->
            <header class="mb-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold">Transaksi</h1>
            </header>

            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Total Transactions Card -->
                <div class="bg-[#1845A2] text-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold">Jumlah Nominal Transaksi</h2>
                    <p class="text-4xl font-bold mt-2">
                        Rp<?= number_format(getSUMTransaksi($conn)->fetch_array()[0], 0, ',', '.'); ?>
                    </p>
                </div>

                <!-- Total Qurban Cards -->
                <div class="bg-[#1845A2] text-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold">Jumlah Transaksi</h2>
                    <p class="text-3xl font-bold mt-2">
                        <?= number_format(getCountTransaksi($conn)->fetch_array()[0], 0, ',', '.'); ?>
                    </p>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">History Transaksi</h2>
                <div class="overflow-auto">
                    <table class="w-full border-collapse border border-[#1845A2]">
                        <thead>
                            <tr class="bg-gray-300 text-left">
                                <th class="p-2 border">Nama</th>
                                <th class="p-2 border">Jenis Qurban</th>
                                <th class="p-2 border">Jenis Transaksi</th>
                                <th class="p-2 border">Jumlah</th>
                                <th class="p-2 border">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($transactions->num_rows > 0): ?>
                                <?php while ($row = $transactions->fetch_assoc()): ?>
                                    <tr class="hover:bg-gray-100">
                                        <td class="p-2 border"><?= htmlspecialchars($row['nama']) ?></td>
                                        <td class="p-2 border"><?= htmlspecialchars($row['tipe_qurban']) ?></td>
                                        <td class="p-2 border <?= $row['metode_pembayaran'] === 'va' ? 'text-orange-500' : 'text-green-500' ?>">
                                            <?= htmlspecialchars($row['metode_pembayaran']) ?>
                                        </td>
                                        <td class="p-2 border">Rp<?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                                        <td class="p-2 border"><?= htmlspecialchars($row['waktu']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada transaksi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    <nav class="flex justify-center">
                        <ul class="flex space-x-2">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li>
                                    <a href="?page=<?= $i ?>" class="px-4 py-2 rounded <?= $i == $current_page ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800 hover:bg-blue-100' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
