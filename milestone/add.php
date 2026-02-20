<?php
session_start();
require_once('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO MILESTONE (milestone_name, target_date, status, project_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['milestone_name'],
        $_POST['target_date'],
        $_POST['status'],
        $_POST['project_id']
    ]);
    header("Location: index.php");
    exit;
}

$projects = $pdo->query("SELECT project_id, project_name FROM PROJECT")->fetchAll();
include('../includes/header.php');
include('../includes/sidebar.php');
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5 px-6">
    <div class="bg-white p-4 shadow-sm mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase">Create New Milestone</h1>
    </div>

    <form method="POST" class="bg-white p-8 shadow rounded-lg max-w-xl mx-auto">
        <div class="mb-4">
            <label class="block font-bold mb-2">Select Project</label>
            <select name="project_id" required class="w-full border p-3 rounded focus:ring-2 focus:ring-purple-500 outline-none">
                <?php foreach($projects as $p): ?>
                    <option value="<?= $p['project_id'] ?>"><?= htmlspecialchars($p['project_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-2">Milestone Name</label>
            <input type="text" name="milestone_name" required placeholder="e.g., Phase 1 Completion" class="w-full border p-3 rounded">
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold mb-2">Target Date</label>
                <input type="date" name="target_date" required class="w-full border p-3 rounded">
            </div>
            <div>
                <label class="block font-bold mb-2">Initial Status</label>
                <select name="status" class="w-full border p-3 rounded">
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                </select>
            </div>
        </div>

        <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded font-bold w-full hover:bg-purple-700 shadow-lg">Save Milestone</button>
    </form>
</div>
<?php include('../includes/footer.php'); ?>