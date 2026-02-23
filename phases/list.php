<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { 
    header("Location: ../auth/login.php"); 
    exit; 
}

include('../includes/header.php');
include('../includes/sidebar.php');

// Fetch phases with site & project
$stmt = $pdo->query("
    SELECT 
        ph.*, 
        s.site_name,
        pr.project_name
    FROM PHASE ph
    JOIN SITE s ON ph.site_id = s.site_id
    JOIN PROJECT pr ON s.project_id = pr.project_id
    ORDER BY ph.start_date DESC
");
$phases = $stmt->fetchAll();
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    
    <!-- Page Header -->
    <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase pl-4">Phase Management</h1>
        <a href="add.php" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>New Phase
        </a>
    </div>

    <div class="px-4">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4 font-semibold text-gray-700">Project</th>
                        <th class="p-4 font-semibold text-gray-700">Site</th>
                        <th class="p-4 font-semibold text-gray-700">Phase</th>
                        <th class="p-4 font-semibold text-gray-700">Timeline</th>
                        <th class="p-4 font-semibold text-gray-700">Progress</th>
                        <th class="p-4 font-semibold text-gray-700">Status</th>
                        <th class="p-4 font-semibold text-gray-700">Priority</th>
                        <th class="p-4 font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($phases as $row): ?>
                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-4 font-medium">
                            <?php echo htmlspecialchars($row['project_name']); ?>
                        </td>

                        <td class="p-4">
                            <?php echo htmlspecialchars($row['site_name']); ?>
                        </td>

                        <td class="p-4 font-semibold">
                            <?php echo htmlspecialchars($row['phase_name']); ?>
                        </td>

                        <td class="p-4 text-sm text-gray-600">
                            <?php echo $row['start_date']; ?> 
                            to 
                            <?php echo $row['end_date'] ?? 'TBD'; ?>
                        </td>

                        <!-- Progress Bar -->
                        <td class="p-4 w-40">
                            <div class="w-full bg-gray-200 rounded h-3">
                                <div class="bg-green-500 h-3 rounded"
                                     style="width: <?php echo $row['progress_percentage']; ?>%">
                                </div>
                            </div>
                            <span class="text-xs">
                                <?php echo $row['progress_percentage']; ?>%
                            </span>
                        </td>

                        <!-- Status Badge -->
                        <td class="p-4">
                            <?php
                            $statusClass = 'bg-gray-100 text-gray-800';
                            if ($row['status'] == 'Completed') {
                                $statusClass = 'bg-green-100 text-green-800';
                            } elseif ($row['status'] == 'In Progress') {
                                $statusClass = 'bg-blue-100 text-blue-800';
                            } elseif ($row['status'] == 'Delayed') {
                                $statusClass = 'bg-red-100 text-red-800';
                            } elseif ($row['status'] == 'On Hold') {
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                            }
                            ?>
                            <span class="px-2 py-1 rounded text-xs <?php echo $statusClass; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>

                        <!-- Priority -->
                        <td class="p-4">
                            <span class="text-sm font-medium 
                                <?php echo $row['priority'] == 'High' ? 'text-red-600' : 
                                           ($row['priority'] == 'Medium' ? 'text-yellow-600' : 'text-green-600'); ?>">
                                <?php echo $row['priority']; ?>
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="p-4">
                            <a href="edit.php?id=<?php echo $row['phase_id']; ?>" 
                               class="text-blue-600 hover:underline mr-3">Edit</a>

                            <a href="delete.php?id=<?php echo $row['phase_id']; ?>" 
                               class="text-red-600 hover:underline"
                               onclick="return confirm('Delete phase?')">Delete</a>
                        </td>

                    </tr>
                    <?php endforeach; ?>

                    <?php if(count($phases) == 0): ?>
                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">
                            No phases found.
                        </td>
                    </tr>
                    <?php endif; ?>

                </tbody>

            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>