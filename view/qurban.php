<?php
include '../service/data.php';

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Initialize search variable
$search = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    header("Location: ?search=" . urlencode($search));
    exit();
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Default pagination values
$perPageOptions = [25, 50, 100, 500, 1000];
$perPage = isset($_GET['per_page']) && in_array($_GET['per_page'], $perPageOptions) ? (int)$_GET['per_page'] : 25;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// Query for fetching data
if ($search != '') {
    $query = "SELECT kq.*, q.tipe_qurban, u.nama AS nama_user, u.nama_orang_tua AS nama_orang_tua, u.nomor_hp AS nomor_hp
              FROM kartu_qurban kq
              JOIN qurban q ON kq.qurban_id = q.qurban_id
              JOIN users u ON u.user_id = kq.user_id
              WHERE kq.nama_pengqurban LIKE '%$search%' 
                 OR kq.va_number LIKE '%$search%'
                 OR u.nama LIKE '%$search%' 
                 OR u.nomor_hp LIKE '%$search%'
              LIMIT $perPage OFFSET $offset";
              
    $kartuQurbanResult = $conn->query($query);

    $countQuery = "SELECT COUNT(*) as total 
                   FROM kartu_qurban kq
                   JOIN qurban q ON kq.qurban_id = q.qurban_id
                   JOIN users u ON u.user_id = kq.user_id
                   WHERE kq.nama_pengqurban LIKE '%$search%' 
                      OR kq.va_number LIKE '%$search%' 
                      OR u.nama LIKE '%$search%' 
                      OR u.nomor_hp LIKE '%$search%'";
} else {
    $query = "SELECT kq.*, q.tipe_qurban, u.nama AS nama_user, u.nama_orang_tua AS nama_orang_tua, u.nomor_hp AS nomor_hp
              FROM kartu_qurban kq
              JOIN qurban q ON kq.qurban_id = q.qurban_id
              JOIN users u ON u.user_id = kq.user_id
              LIMIT $perPage OFFSET $offset";
              
    $kartuQurbanResult = $conn->query($query);

    $countQuery = "SELECT COUNT(*) as total 
                   FROM kartu_qurban kq
                   JOIN qurban q ON kq.qurban_id = q.qurban_id
                   JOIN users u ON u.user_id = kq.user_id";
}

$totalRowsResult = $conn->query($countQuery);
$totalRows = $totalRowsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $perPage);

// Get qurban data for displaying total hewan
$qurbanResult = getDetailQurban($conn);
$rincianHewan = [];
$totalHewan = 0;

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
        <?php include 'sidebar.php'; ?>

        <div class="w-3/4 p-6">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold">Qurban</h1>
            </header>
            
            <div class="bg-[#1845A2] p-6 rounded-lg text-white">
                <h2 class="text-3xl font-bold">Total Hewan Qurban</h2>
                <p class="text-4xl font-bold"><?php echo number_format($totalHewan); ?> Hewan</p>
            </div>

            <div class="mt-6 bg-[#F9F9F9] text-black p-4 rounded-lg">
                <h3 class="text-xl font-bold mb-4">Rincian Hewan Qurban</h3>
                <table class="w-full border-collapse border">
                    <thead class="bg-[#E0E0E0]">
                        <tr>
                            <th class="border p-2">Tipe Hewan</th>
                            <th class="border p-2">Jenis Hewan</th>
                            <th class="border p-2">Jumlah Hewan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rincianHewan as $hewan) : ?>
                            <tr>
                                <td class="border p-2"><?php echo $hewan['tipe_qurban']; ?></td>
                                <td class="border p-2"><?php echo $hewan['jenis']; ?></td>
                                <td class="border p-2"><?php echo number_format($hewan['jumlah']); ?> Hewan</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Button untuk Tambah Qurban -->
            <div class="mt-8">
                <button id="addQurbanBtn" class="bg-blue-500 text-white p-2 rounded">Tambah Qurban</button>
                <button id="editQurbanBtn" class="bg-green-500 text-white p-2 rounded ml-4">Edit Qurban</button>
            </div>

            <!-- Modal Tambah Qurban -->
            <div id="addQurbanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded-lg w-1/3">
                    <h2 class="text-xl font-bold">Tambah Tipe Qurban</h2>
                    <form method="POST" action="../service/prosesQurban.php">
                        <div class="mb-4">
                            <label for="tipe_qurban" class="block">Tipe Qurban: (contoh: Sapi, Kambing dll.)</label>
                            <input type="text" id="tipe_qurban" name="tipe_qurban" class="border p-2 w-full" required>
                        </div>
                        <div class="mb-4">
    <label for="biaya" class="block">Biaya:</label>
    <input type="text" id="biaya" name="biaya" class="border p-2 w-full" required oninput="formatRupiah(this)">
</div>
                        <div class="mb-4">
                            <label for="jenis" class="block">Jenis (contoh: Berjamaah, 1 Ekor):</label>
                            <input type="text" id="jenis" name="jenis" class="border p-2 w-full" required>
                        </div>
                        <button type="submit" name="add_qurban" class="bg-blue-500 text-white p-2 rounded">Tambah</button>
                        <button type="button" id="closeAddQurbanModal" class="bg-red-500 text-white p-2 rounded mt-2">Tutup</button>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Qurban -->
            <div id="editQurbanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded-lg w-1/3">
                    <h2 class="text-xl font-bold">Edit Tipe Qurban</h2>
                    <form method="POST" action="../service/prosesQurban.php">
                        <div class="mb-4">
                            <label for="qurban_id" class="block">Pilih Tipe Qurban:</label>
                            <select id="qurban_id" name="qurban_id" class="border p-2 w-full" required>
                                <?php
                                $qurbanData = $conn->query("SELECT qurban_id, tipe_qurban, jenis FROM qurban WHERE status = 'Aktif'");
                                while ($row = $qurbanData->fetch_assoc()) {
                                    echo "<option value='" . $row['qurban_id'] . "'>" . $row['tipe_qurban'] . (!empty($row['jenis']) ? " (" . $row['jenis'] . ")" : "") . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="tipe_qurban" class="block">Nama Tipe Qurban Baru:</label>
                            <input type="text" id="tipe_qurban" name="tipe_qurban" class="border p-2 w-full" required>
                        </div>
                        <button type="submit" name="edit_qurban" class="bg-green-500 text-white p-2 rounded">Edit</button>
                        <button type="button" id="closeEditQurbanModal" class="bg-red-500 text-white p-2 rounded mt-2">Tutup</button>
                    </form>
                </div>
            </div>


            <div class="mt-6 mb-4">
                <form method="POST" class="flex items-center">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="border p-2 w-1/2 mr-2" placeholder="Cari berdasarkan Nama atau VA Number">
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded">Cari</button>
                </form>
            </div>

            <form method="GET" class="mb-4">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <label for="per_page">Tampilkan:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()" class="border p-2">
                    <?php foreach ($perPageOptions as $option) : ?>
                        <option value="<?php echo $option; ?>" <?php if ($option == $perPage) echo 'selected'; ?>>
                            <?php echo $option; ?>
                        </option>
                    <?php endforeach; ?>
                </select> data
            </form>

            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-300">
                        <th class="border p-2">No</th>
                        <th class="border p-2">Akun</th>
                        <th class="border p-2">Nama Pengqurban</th>
                        <th class="border p-2">Tipe Qurban</th>
                        <th class="border p-2">Biaya</th>
                        <th class="border p-2">Jumlah Terkumpul</th>
                        <th class="border p-2">Nomor Handphone</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">VA Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($kartuQurbanResult->num_rows > 0) : ?>
                        <?php $no = $offset + 1; ?>
                        <?php while ($row = $kartuQurbanResult->fetch_assoc()) : ?>
                            <tr>
                                <td class="border p-2"><?php echo $no++; ?></td>
                                <td class="border p-2">
                                    <?php 
                                    echo isset($row['nama_user']) ? $row['nama_user'] : ''; 
                                    echo ' '; 
                                    echo isset($row['nama_orang_tua']) ? $row['nama_orang_tua'] : ''; 
                                    ?>
                                </td>
                                <td class="border p-2"><?php echo $row['nama_pengqurban']; ?></td>
                                <td class="border p-2"><?php echo $row['tipe_qurban']; ?></td>
                                <td class="border p-2"><?php echo 'Rp ' . number_format($row['biaya'], 0, ',', '.'); ?></td>
                                <td class="border p-2"><?php echo 'Rp ' . number_format($row['jumlah_terkumpul'], 0, ',', '.'); ?></td>
                                <td class="border p-2"><?php echo $row['nomor_hp']; ?></td>
                                <td class="border p-2"><?php echo $row['status']; ?></td>
                                <td class="border p-2"><?php echo $row['va_number']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="border p-2 text-center">Belum ada data kartu qurban.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="mt-4 flex justify-center">
                <?php if ($page > 1) : ?>
                    <a href="?search=<?php echo urlencode($search); ?>&per_page=<?php echo $perPage; ?>&page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-gray-300 rounded mr-2">Sebelumnya</a>
                <?php endif; ?>
                <span class="px-4 py-2">Halaman <?php echo $page; ?> dari <?php echo $totalPages; ?></span>
                <?php if ($page < $totalPages) : ?>
                    <a href="?search=<?php echo urlencode($search); ?>&per_page=<?php echo $perPage; ?>&page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-gray-300 rounded ml-2">Berikutnya</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Open Modal Add Qurban
        document.getElementById('addQurbanBtn').addEventListener('click', function() {
            document.getElementById('addQurbanModal').classList.remove('hidden');
        });

        // Open Modal Edit Qurban
        document.getElementById('editQurbanBtn').addEventListener('click', function() {
            document.getElementById('editQurbanModal').classList.remove('hidden');
        });

        // Close Modal Add Qurban
        document.getElementById('closeAddQurbanModal').addEventListener('click', function() {
            document.getElementById('addQurbanModal').classList.add('hidden');
        });

        // Close Modal Edit Qurban
        document.getElementById('closeEditQurbanModal').addEventListener('click', function() {
            document.getElementById('editQurbanModal').classList.add('hidden');
        });

        function formatRupiah(input) {
    let value = input.value.replace(/[^,\d]/g, '').toString();
    let split = value.split(',');
    let main = split[0];
    let suffix = split.length > 1 ? ',' + split[1] : '';
    
    // Add commas as thousand separator
    main = main.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    
    input.value = 'Rp ' + main + suffix;
}
    </script>
</body>
</html>
