<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

$query = "SELECT s.*, p.project_name 
          FROM SITE s 
          JOIN PROJECT p ON s.project_id = p.project_id 
          ORDER BY p.project_name ASC";
$sites = $pdo->query($query)->fetchAll();
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase pl-4">Construction Sites</h1>
        <a href="add.php" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Add New Site
        </a>
    </div>

    <div class="px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($sites as $site): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-blue-500">
                <div class="p-5">
                    <div class="flex justify-between items-start">
                        <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($site['site_name']); ?></h3>
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">ID: #<?php echo $site['site_id']; ?></span>
                    </div>
                    <p class="text-sm text-blue-600 font-semibold mb-3"><i class="fas fa-project-diagram mr-1"></i> <?php echo htmlspecialchars($site['project_name']); ?></p>
                    <p class="text-gray-600 text-sm mb-4"><i class="fas fa-map-marker-alt mr-1"></i> <?php echo htmlspecialchars($site['location']); ?></p>
                    
                    <div class="border-t pt-4 flex justify-between">
                        <a href="details.php?id=<?php echo $site['site_id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium text-sm">View Details →</a>
                        <div class="flex space-x-3">
                            <a href="edit.php?id=<?php echo $site['site_id']; ?>" class="text-gray-400 hover:text-yellow-600"><i class="fas fa-edit"></i></a>
                            <a href="delete.php?id=<?php echo $site['site_id']; ?>" class="text-gray-400 hover:text-red-600" onclick="return confirm('Delete this site?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>