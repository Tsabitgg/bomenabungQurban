<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <script src="https://cdn.tailwindcss.com">
  </script>
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
                <h1 class="text-2xl font-bold">Transaksi</h1>
            </header>
            <div class="grid grid-cols-2 gap-6">
                <!-- Card Total Transaksi -->
                <div class="bg-green-500 p-6 rounded-lg text-white">
                    <h2 class="text-3xl font-bold">Total Transaksi</h2>
                    <p class="text-4xl font-bold">15.750.920.000</p>
                </div>
                <!-- Card Qurban Card -->
                <div class="bg-gray-200 p-6 rounded-lg">
                    <h2 class="text-xl font-bold">Qurban Card</h2>
                    <p class="text-2xl font-bold">7.520 Kartu</p>
                </div>
                <!-- Tabel History Transaksi -->
                <div class="bg-gray-200 p-6 rounded-lg col-span-2">
                    <h2 class="text-xl font-bold mb-4">History Transaksi</h2>
                    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-300">
                <th class="p-2">Nama</th>
                <th class="p-2">Jenis Qurban</th>
                <th class="p-2">Jenis Transaksi</th>
                <th class="p-2">Jumlah</th>
                <th class="p-2">Waktu</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-t">
                <td class="p-2">Ahmad</td>
                <td class="p-2">Kambing</td>
                <td class="p-2 text-red-500">Pengeluaran</td>
                <td class="p-2 text-red-500">70.000</td>
                <td class="p-2">12/10/2024</td>
            </tr>
            <tr class="border-t">
                <td class="p-2">Hambali</td>
                <td class="p-2">Sapi</td>
                <td class="p-2 text-green-500">Pemasukan</td>
                <td class="p-2 text-green-500">1.800.000</td>
                <td class="p-2">12/10/2024</td>
            </tr>
            <tr class="border-t">
                <td class="p-2">Syafii</td>
                <td class="p-2">Domba</td>
                <td class="p-2 text-green-500">Pemasukan</td>
                <td class="p-2 text-green-500">1.950.000</td>
                <td class="p-2">12/10/2024</td>
            </tr>
            <tr class="border-t">
                <td class="p-2">Lukman</td>
                <td class="p-2">Kambing</td>
                <td class="p-2 text-red-500">Pengeluaran</td>
                <td class="p-2 text-red-500">20.000</td>
                <td class="p-2">12/10/2024</td>
            </tr>
            <tr class="border-t">
                <td class="p-2">Umar</td>
                <td class="p-2">Sapi</td>
                <td class="p-2 text-red-500">Pengeluaran</td>
                <td class="p-2 text-red-500">70.000</td>
                <td class="p-2">12/10/2024</td>
            </tr>
        </tbody>
    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
