<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

$site_id = $_GET['site_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO RISK (risk_type, severity, site_id) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['risk_type'], $_POST['severity'], $_POST['site_id']]);
    header("Location: ../site/details.php?id=" . $_POST['site_id']);
    exit;
}
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5 px-6">
    <div class="bg-white p-4 shadow-sm mb-6 border-l-4 border-red-500">
        <h1 class="text-2xl font-bold text-gray-800 uppercase">Log New Safety Risk</h1>
    </div>

    <form method="POST" class="bg-white p-8 shadow rounded-lg max-w-lg mx-auto">
        <input type="hidden" name="site_id" value="<?= $site_id ?>">
        
        <div class="mb-4">
            <label class="block font-bold mb-2">Hazard/Risk Type</label>
            <input type="text" name="risk_type" required placeholder="e.g. Unsecured Scaffolding" class="w-full border p-3 rounded">
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-2">Severity Level</label>
            <select name="severity" class="w-full border p-3 rounded text-red-600 font-bold">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
                <option value="Critical">Critical</option>
            </select>
        </div>

        <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded font-bold w-full hover:bg-red-700">Submit Risk Report</button>
    </form>
</div>
<?php include('../includes/footer.php'); ?>