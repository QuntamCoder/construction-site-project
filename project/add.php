<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// 1. ADD THIS LINE: Include your database configuration
require_once('../includes/config.php'); 

// Include the UI parts
include('../includes/header.php');
include('../includes/sidebar.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Basic validation and handling empty end dates
    $end_date = !empty($_POST['end']) ? $_POST['end'] : null;

    try {
        $sql = "INSERT INTO PROJECT (project_name, start_date, end_date, budget, status)
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['name'],
            $_POST['start'],
            $end_date,
            $_POST['budget'],
            $_POST['status']
        ]);

        // Redirect to your view page (ensure the filename matches your actual file, e.g., view.php)
        header("Location: view.php");
        exit;
        
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    <div class="bg-white p-4 shadow-sm mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase pl-4">Create New Project</h1>
    </div>

    <div class="px-4">
        <?php if(isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="bg-white p-8 shadow-md rounded-lg max-w-2xl">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Project Name</label>
                <input name="name" required placeholder="e.g., Riverside Complex" class="border rounded p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                    <input type="date" name="start" required class="border rounded p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">End Date (Optional)</label>
                    <input type="date" name="end" class="border rounded p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Budget ($)</label>
                <input type="number" step="0.01" name="budget" required placeholder="0.00" class="border rounded p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status" class="border rounded p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Planned">Planned</option>
                    <option value="In Progress">In Progress</option>
                    <option value="On Hold">On Hold</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition">
                    Save Project
                </button>
                <a href="view.php" class="text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>