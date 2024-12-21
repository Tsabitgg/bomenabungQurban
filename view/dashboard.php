<?php

include '../service/data.php';

session_start();

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit();
}

// Fetch necessary data
$total = getSUMTransaksi($conn)->fetch_row()[0]; 
$count_qurban = getCountKartuQurban($conn)->fetch_row()[0];
$transaksi = getTransaksiForDashboard($conn);
$count_pengqurban = getCountPengqurban($conn)->fetch_row()[0];
$count_transaksi = getCountTransaksi($conn)->fetch_row()[0];
$detail_qurban = getDetailQurban($conn);

$summaryData = getSummaryData($conn);
$totalBiaya = $summaryData['total_biaya'];
$totalTerkumpul = $summaryData['total_terkumpul'];


$uang_qurban = getUangQurban($conn);
$uang_tabungan = getUangTabungan($conn);
$saldo = getSaldo($conn);

// Initialize arrays for the chart labels and data
$labels = [];
$data = [];

while ($row = $detail_qurban->fetch_assoc()) {
    $labels[] = $row['tipe_qurban'];  // Animal types (e.g., Sapi, Kambing, Domba)
    $data[] = $row['jumlah'];  // Count of animals
}
?>

<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-white">
<div class="flex">
<?php include 'sidebar.php'; ?>
    <div class="bg-white p-8 rounded-lg shadow-lg w-full">
        <!-- Ringkasan Data -->
        <div class="mb-8">
            <h2 class="text-center text-2xl font-bold mb-6 text-[#1845A2]">Ringkasan Data</h2>
                    <!-- Information Icon and Tooltip -->
                <div class="mt-4 text-left">
                <span class="relative group">
                    <!-- Information icon positioned to the left -->
                    <i class="fas fa-info-circle text-xl cursor-pointer"></i>

                    <!-- Tooltip with better spacing and wrapping -->
                    <span 
                    class="absolute left-0 transform translate-x-0 bg-black text-white text-sm rounded px-3 py-2 mt-2 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-opacity duration-200 max-w-xs w-max break-words">
                    <strong>Uang Qurban</strong> : Uang yang sudah masuk dan target biaya qurban sudah terpenuhi.<br>
                    <strong>Uang Tabungan</strong> : Uang yang sudah masuk dan target biaya qurban belum terpenuhi.<br>
                    <strong>Total Saldo</strong> ; Kelebihan uang masuk dari qurban yang sudha terpenuhi
                    </span>
                </span>
                </div>
            <div class="grid grid-cols-3 gap-6">
                <div class="bg-[#1845A2] p-6 rounded-lg text-center shadow-md text-white">
                    <p class="text-lg font-semibold">Uang Qurban</p>
                    <p class="text-2xl font-bold">Rp<?= number_format($uang_qurban, 0, ',', '.'); ?></p>
                </div>
                <div class="bg-[#1845A2] p-6 rounded-lg text-center shadow-md text-white">
                    <p class="text-lg font-semibold">Uang Tabungan</p>
                    <p class="text-2xl font-bold">Rp<?= number_format($uang_tabungan, 0, ',', '.'); ?></p>
                </div>
                <div class="bg-[#1845A2] p-6 rounded-lg text-center shadow-md text-white">
                    <p class="text-lg font-semibold">Saldo</p>
                    <p class="text-2xl font-bold">Rp<?= number_format($saldo, 0, ',', '.'); ?></p>
                </div>
                <div class="bg-[#1845A2] p-6 rounded-lg text-center shadow-md text-white">
                    <p class="text-lg font-semibold">Total Hewan</p>
                    <p class="text-2xl font-bold"><?= $count_qurban . " Hewan Qurban"; ?></p>
                </div>
                <div class="bg-[#1845A2] p-6 rounded-lg text-center shadow-md text-white">
                    <p class="text-lg font-semibold">Jumlah Pengurban</p>
                    <p class="text-2xl font-bold"><?= $count_pengqurban; ?></p>
                </div>
                <div class="bg-[#1845A2] p-6 rounded-lg text-center shadow-md text-white">
                    <p class="text-lg font-semibold">Jumlah Transaksi</p>
                    <p class="text-2xl font-bold"><?= $count_transaksi; ?></p>
                </div>
            </div>
        </div>

        <!--grid untuk Detail Qurban dan Statistik -->
        <div class="grid grid-cols-2 gap-6">
            <!--statistik keuangan -->
            <div class="bg-gray-200 p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-6 text-[#1845A2]">Statistik Keuangan</h2>
                <canvas id="chartKeuangan" ></canvas>
            </div>

            <!--statistik Qurban -->
            <div class="bg-gray-200 p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-6 text-[#1845A2]">Statistik Qurban</h2>
                <canvas id="chartQurban"></canvas>
            </div>
        </div>

        <!-- Transaksi Terakhir -->
        <div class="mt-8">
            <h2 class="text-center text-2xl font-bold mb-6 text-[#1845A2]">Transaksi Terakhir</h2>
            <p class="text-sm text-gray-500 mb-4 text-center">* Hanya menampilkan 20 transaksi terakhir</p>
            <table class="w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-[#1845A2] text-white">
                        <th class="p-4 text-left">Nama</th>
                        <th class="p-4 text-left">Tipe</th>
                        <th class="p-4 text-left">Metode</th>
                        <th class="p-4 text-left">Jumlah</th>
                        <th class="p-4 text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                if ($transaksi->num_rows > 0) {
                    while ($row = $transaksi->fetch_assoc()) {
                        $jenisTransaksiClass = $row['metode_pembayaran'] == 'qris' ? 'text-green-500' : 'text-yellow-500';
                        echo "<tr class='hover:bg-gray-50'>
                                <td class='border border-gray-300 p-3'>{$row['nama']}</td>
                                <td class='border border-gray-300 p-3'>{$row['tipe_qurban']}</td>
                                <td class='border border-gray-300 p-3 {$jenisTransaksiClass}'>{$row['metode_pembayaran']}</td>
                                <td class='border border-gray-300 p-3 text-right {$jenisTransaksiClass}'>Rp" . number_format($row['jumlah'], 0, ',', '.') . "</td>
                                <td class='border border-gray-300 p-3'>{$row['waktu']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr>
                            <td colspan='5' class='border border-gray-300 p-3 text-center text-gray-500'>Tidak ada transaksi.</td>
                        </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>

// Function to generate a random color in hex format
function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

const ctx = document.getElementById('chartQurban').getContext('2d');

    // PHP data passed into JavaScript
    const labels = <?php echo json_encode($labels); ?>;
    const data = <?php echo json_encode($data); ?>;

    // Generate random colors for each dataset entry
    const backgroundColors = data.map(() => getRandomColor());

    const chartQurban = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,  // Dynamic labels from PHP
            datasets: [{
                label: 'Jumlah Hewan Qurban',
                data: data,  // Dynamic data from PHP
                backgroundColor: backgroundColors  // Random colors for each bar
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

<script>
const pieCtx = document.getElementById('chartKeuangan').getContext('2d');

// Ubah ukuran canvas sebelum membuat chart
document.getElementById('chartKeuangan').width = 50;
document.getElementById('chartKeuangan').height = 50;

// Data total_biaya dan total_terkumpul dari PHP
const pieData = {
    labels: ['Total Biaya', 'Total Terkumpul'],
    datasets: [{
        data: [<?= $totalBiaya ?>, <?= $totalTerkumpul ?>],  // Data dari PHP
        backgroundColor: ['#FF6384', '#36A2EB']  // Warna untuk setiap bagian
    }]
};

const pieChart = new Chart(pieCtx, {
    type: 'pie',
    data: pieData,
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        }
    }
});
</script>



</body>
</html>
