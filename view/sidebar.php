<div class="w-1/4 bg-gray-200 min-h-screen p-6">
    <div class="mb-8">
        <img src="../assets/img/favicon/dtpeduli.png" alt="DT Peduli Logo" style="height: 40px; vertical-align: middle;">
        <h1 class="text-2xl font-bold">Admin </h1>
    </div>
    <nav class="space-y-4">
        <a id="dashboard-link" class="flex items-center text-gray-600" href="dashboard.php">
            <i class="fas fa-tachometer-alt mr-2"></i>
            Dashboard
        </a>
        <a id="qurban-link" class="flex items-center text-gray-600" href="qurban.php">
            <i class="fas fa-credit-card mr-2"></i>
            Qurban
        </a>
        <a id="transaksi-link" class="flex items-center text-gray-600" href="transaksi.php">
            <i class="fas fa-exchange-alt mr-2"></i>
            Transaction
        </a>
        <a id="logout-link" class="flex items-center text-gray-600" href="login.php">
            <i class="fas fa-sign-out-alt mr-2"></i>
            Log Out
        </a>
    </nav>
</div>

<script>
    // Get the current page URL
    const currentPage = window.location.pathname;

    // Define the links
    const links = document.querySelectorAll('nav a');

    // Loop through the links and add active class to the current page
    links.forEach(link => {
        if (link.href.includes(currentPage)) {
            link.classList.add('text-yellow-500');  // Add yellow color
        }
    });
</script>
