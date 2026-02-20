<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

// Fetch projects for the dropdown
$projects = $pdo->query("SELECT project_id, project_name FROM PROJECT WHERE status != 'Completed'")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO SITE (site_name, location, project_id) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['site_name'], $_POST['location'], $_POST['project_id']]);
    header("Location: view.php");
    exit;
}
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    <div class="bg-white p-4 shadow-sm mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase pl-4">Register New Site</h1>
    </div>

    <div class="px-4 flex justify-center">
        <form method="POST" class="bg-white p-8 shadow-lg rounded-lg w-full max-w-xl">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Assign to Project</label>
                <select name="project_id" required class="w-full border rounded p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Select Project --</option>
                    <?php foreach($projects as $p): ?>
                        <option value="<?php echo $p['project_id']; ?>"><?php echo htmlspecialchars($p['project_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Site Name</label>
                <input type="text" name="site_name" required placeholder="e.g. Block A Foundation" class="w-full border rounded p-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Location/Address</label>
                <input type="text" name="location" required placeholder="Full address or GPS coordinates" class="w-full border rounded p-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded font-bold hover:bg-blue-700 transition">Save Site</button>
                <a href="view.php" class="text-gray-500 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>