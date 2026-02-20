<?php
session_start();
require_once('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE MILESTONE SET milestone_name=?, target_date=?, status=? WHERE milestone_id=?");
    $stmt->execute([$_POST['milestone_name'], $_POST['target_date'], $_POST['status'], $_POST['milestone_id']]);
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$m = $pdo->prepare("SELECT * FROM MILESTONE WHERE milestone_id = ?");
$m->execute([$id]);
$data = $m->fetch();

include('../includes/header.php');
include('../includes/sidebar.php');
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5 px-6">
    <div class="bg-white p-4 shadow-sm mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase">Update Milestone</h1>
    </div>

    <form method="POST" class="bg-white p-8 shadow rounded-lg max-w-xl mx-auto">
        <input type="hidden" name="milestone_id" value="<?= $data['milestone_id'] ?>">
        
        <div class="mb-4">
            <label class="block font-bold mb-2">Milestone Name</label>
            <input type="text" name="milestone_name" value="<?= htmlspecialchars($data['milestone_name']) ?>" required class="w-full border p-3 rounded">
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-2">Status</label>
            <select name="status" class="w-full border p-3 rounded font-bold">
                <option value="Pending" <?= $data['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="In Progress" <?= $data['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="Completed" <?= $data['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                <option value="Delayed" <?= $data['status'] == 'Delayed' ? 'selected' : '' ?>>Delayed</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-2">Target Date</label>
            <input type="date" name="target_date" value="<?= $data['target_date'] ?>" class="w-full border p-3 rounded">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded font-bold w-full hover:bg-blue-700">Update Status</button>
    </form>
</div>
<?php include('../includes/footer.php'); ?>