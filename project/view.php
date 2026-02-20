<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

// Fetch projects
$stmt = $pdo->query("SELECT * FROM PROJECT ORDER BY start_date DESC");
$projects = $stmt->fetchAll();
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase pl-4">Project Management</h1>
        <a href="add.php" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>New Project
        </a>
    </div>

    <div class="px-4">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4 font-semibold text-gray-700">Project Name</th>
                        <th class="p-4 font-semibold text-gray-700">Budget</th>
                        <th class="p-4 font-semibold text-gray-700">Status</th>
                        <th class="p-4 font-semibold text-gray-700">Timeline</th>
                        <th class="p-4 font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($projects as $row): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-medium"><?php echo htmlspecialchars($row['project_name']); ?></td>
                        <td class="p-4">$<?php echo number_format($row['budget'], 2); ?></td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-xs <?php echo $row['status'] == 'Active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td class="p-4 text-sm text-gray-600">
                            <?php echo $row['start_date']; ?> to <?php echo $row['end_date'] ?? 'TBD'; ?>
                        </td>
                        <td class="p-4">
                            <a href="edit.php?id=<?php echo $row['project_id']; ?>" class="text-blue-600 hover:underline mr-3">Edit</a>
                            <a href="delete.php?id=<?php echo $row['project_id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete project?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>