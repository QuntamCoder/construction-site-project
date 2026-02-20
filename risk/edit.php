<?php
session_start();
require_once('../includes/config.php');

// 1. HANDLE POST DATA FIRST (Before any HTML output)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $risk_id = $_POST['risk_id']; // Get from hidden input
    $stmt = $pdo->prepare("UPDATE RISK SET risk_type = ?, severity = ? WHERE risk_id = ?");
    $stmt->execute([$_POST['risk_type'], $_POST['severity'], $risk_id]);
    
    // This will now work because no HTML has been sent yet!
    header("Location: index.php"); 
    exit;
}

// 2. FETCH DATA FOR THE FORM
$risk_id = $_GET['id'];
$risk = $pdo->prepare("SELECT * FROM RISK WHERE risk_id = ?");
$risk->execute([$risk_id]);
$data = $risk->fetch();

// 3. NOW INCLUDE UI FILES
include('../includes/header.php');
include('../includes/sidebar.php');
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5 px-6">
    <div class="bg-white p-4 shadow-sm mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase pl-4">Update Risk Status</h1>
    </div>

    <form method="POST" class="bg-white p-8 shadow rounded-lg max-w-lg mx-auto">
        <input type="hidden" name="risk_id" value="<?= $data['risk_id'] ?>">
        
        <div class="mb-4">
            <label class="block font-bold mb-2">Risk Type</label>
            <input type="text" name="risk_type" value="<?= htmlspecialchars($data['risk_type']) ?>" class="w-full border p-3 rounded">
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-2">Current Severity</label>
            <select name="severity" class="w-full border p-3 rounded">
                <option value="Low" <?= $data['severity'] == 'Low' ? 'selected' : '' ?>>Low</option>
                <option value="Medium" <?= $data['severity'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                <option value="High" <?= $data['severity'] == 'High' ? 'selected' : '' ?>>High</option>
                <option value="Critical" <?= $data['severity'] == 'Critical' ? 'selected' : '' ?>>Critical</option>
            </select>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">Update Risk</button>
            <a href="index.php" class="bg-gray-500 text-white px-6 py-2 rounded font-bold">Cancel</a>
        </div>
    </form>
</div>
<?php include('../includes/footer.php'); ?>