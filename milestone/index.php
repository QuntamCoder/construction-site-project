<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

$query = "SELECT m.*, p.project_name 
          FROM MILESTONE m 
          JOIN PROJECT p ON m.project_id = p.project_id 
          ORDER BY m.target_date ASC";
$milestones = $pdo->query($query)->fetchAll();
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5 px-6">
    <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase">Project Milestones</h1>
        <a href="add.php" class="bg-purple-600 text-white px-4 py-2 rounded shadow hover:bg-purple-700 transition">
            <i class="fas fa-plus mr-2"></i>Add Milestone
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="p-4">Milestone Name</th>
                    <th class="p-4">Project</th>
                    <th class="p-4">Target Date</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php foreach($milestones as $m): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 font-bold"><?= htmlspecialchars($m['milestone_name']) ?></td>
                    <td class="p-4 text-sm"><?= htmlspecialchars($m['project_name']) ?></td>
                    <td class="p-4">
                        <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                        <?= date('M d, Y', strtotime($m['target_date'])) ?>
                    </td>
                    <td class="p-4">
                        <?php 
                            $statusColor = [
                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                'In Progress' => 'bg-blue-100 text-blue-800',
                                'Completed' => 'bg-green-100 text-green-800',
                                'Delayed' => 'bg-red-100 text-red-800'
                            ];
                            $class = $statusColor[$m['status']] ?? 'bg-gray-100';
                        ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold <?= $class ?>">
                            <?= $m['status'] ?>
                        </span>
                    </td>
                    <td class="p-4">
                        <a href="edit.php?id=<?= $m['milestone_id'] ?>" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>