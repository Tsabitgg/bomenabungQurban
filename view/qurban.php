<?php
include '../service/data.php';

// Mengambil data qurban dan kartu qurban
$qurbanResult = getDetailQurban($conn);
$kartuQurbanResult = getKartuQurban($conn);

// Variabel total hewan
$totalHewan = 0;
$rincianHewan = [];

if ($qurbanResult->num_rows > 0) {
    while ($row = $qurbanResult->fetch_assoc()) {
        $rincianHewan[] = $row;
        $totalHewan += $row['jumlah'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qurban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-white text-black">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold">Qurban</h1>
            </header>
            
            <div class="grid grid-cols-2 gap-6">
                <!-- Card Hewan Qurban -->
                <div class="bg-yellow-500 p-6 rounded-lg text-white">
                    <h2 class="text-3xl font-bold">Total Hewan Qurban</h2>
                    <p class="text-4xl font-bold"><?php echo number_format($totalHewan); ?> Hewan</p>
                </div>
                <!-- Card Per Hewan -->
                <div class="bg-gray-200 p-6 rounded-lg">
                    <h2 class="text-xl font-bold">Rincian Hewan Qurban</h2>
                    <div class="space-y-2">
                        <?php foreach ($rincianHewan as $hewan) : ?>
                            <div class="flex justify-between">
                                <span><?php echo $hewan['tipe_qurban']; ?></span>
                                <span><?php echo number_format($hewan['jumlah']); ?> Hewan</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Form untuk menambah tipe qurban -->
            <div class="mt-8">
                <h2 class="text-xl font-bold">Tambah Tipe Qurban</h2>
                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="tipe_qurban" class="block">Tipe Qurban:</label>
                        <input type="text" id="tipe_qurban" name="tipe_qurban" class="border p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="biaya" class="block">Biaya:</label>
                        <input type="number" id="biaya" name="biaya" class="border p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="jenis" class="block">Jenis:</label>
                        <input type="text" id="jenis" name="jenis" class="border p-2 w-full" required>
                    </div>
                    <button type="submit" name="add_qurban" class="bg-blue-500 text-white p-2 rounded">Tambah</button>
                </form>
            </div>

            <!-- Form untuk mengedit tipe qurban -->
            <div class="mt-8">
                <h2 class="text-xl font-bold">Edit Tipe Qurban</h2>
                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="qurban_id" class="block">Pilih Tipe Qurban:</label>
                        <select id="qurban_id" name="qurban_id" class="border p-2 w-full" required>
                            <?php
                            $qurbanData = $conn->query("SELECT qurban_id, tipe_qurban FROM qurban WHERE status = 'Aktif'");
                            while ($row = $qurbanData->fetch_assoc()) {
                                echo "<option value='" . $row['qurban_id'] . "'>" . $row['tipe_qurban'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="tipe_qurban" class="block">Nama Tipe Qurban Baru:</label>
                        <input type="text" id="tipe_qurban" name="tipe_qurban" class="border p-2 w-full" required>
                    </div>
                    <button type="submit" name="edit_qurban" class="bg-green-500 text-white p-2 rounded">Edit</button>
                </form>
            </div>

            <!-- Tabel data kartu qurban -->
            <div class="mt-8">
                <h2 class="text-xl font-bold">Daftar Kartu Qurban</h2>
                <table class="w-full border-collapse border">
                    <thead>
                        <tr class="bg-gray-300">
                            <th class="border p-2">Nama Pengqurban</th>
                            <th class="border p-2">Tipe Qurban</th>
                            <th class="border p-2">Jumlah Terkumpul</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2">VA Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($kartuQurbanResult->num_rows > 0) : ?>
                            <?php while ($row = $kartuQurbanResult->fetch_assoc()) : ?>
                                <tr>
                                    <td class="border p-2"><?php echo $row['nama_pengqurban']; ?></td>
                                    <td class="border p-2"><?php echo $row['tipe_qurban']; ?></td>
                                    <td class="border p-2"><?php echo number_format($row['jumlah_terkumpul']); ?></td>
                                    <td class="border p-2"><?php echo $row['status']; ?></td>
                                    <td class="border p-2"><?php echo $row['va_number']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="border p-2 text-center">Belum ada data kartu qurban.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>