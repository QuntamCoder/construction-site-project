<?php
session_start();
require_once('../includes/config.php');

if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

// Fetch sites for the dropdown
$sites = $pdo->query("SELECT site_id, site_name FROM SITE")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $sql = "INSERT INTO DAILY_REPORT (report_date, progress_percentage, site_id) 
                VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['report_date'],
            $_POST['progress'],
            $_POST['site_id']
        ]);
        $success = "Report submitted successfully!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    <div class="bg-white p-4 shadow-sm mb-6">
        <h1 class="text-2xl font-bold text-gray-800 pl-4 uppercase">Submit Daily Progress</h1>
    </div>

    <div class="px-4">
        <form method="POST" class="bg-white p-6 shadow rounded-lg max-w-lg">
            <?php if(isset($success)) echo "<p class='text-green-600 mb-4'>$success</p>"; ?>
            <?php if(isset($error)) echo "<p class='text-red-600 mb-4'>$error</p>"; ?>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Select Site</label>
                <select name="site_id" required class="w-full border p-2 rounded">
                    <?php foreach($sites as $site): ?>
                        <option value="<?= $site['site_id'] ?>"><?= htmlspecialchars($site['site_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Date</label>
                <input type="date" name="report_date" value="<?= date('Y-m-d') ?>" required class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Progress Percentage (%)</label>
                <input type="number" name="progress" min="0" max="100" step="0.01" required class="w-full border p-2 rounded" placeholder="e.g. 15.50">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">
                Submit Report
            </button>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>