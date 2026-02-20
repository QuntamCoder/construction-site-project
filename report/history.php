<?php
session_start();
require_once('../includes/config.php');
include('../includes/header.php');
include('../includes/sidebar.php');

$query = "SELECT dr.*, s.site_name 
          FROM DAILY_REPORT dr 
          JOIN SITE s ON dr.site_id = s.site_id 
          ORDER BY dr.report_date DESC";
$reports = $pdo->query($query)->fetchAll();
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6 px-8">
        <h1 class="text-2xl font-bold text-gray-800 uppercase">Progress History</h1>
        <a href="add.php" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">
            + New Entry
        </a>
    </div>

    <div class="px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="p-4">Date</th>
                        <th class="p-4">Site Name</th>
                        <th class="p-4">Progress</th>
                        <th class="p-4">Trend</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach($reports as $r): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4"><?= date('M d, Y', strtotime($r['report_date'])) ?></td>
                        <td class="p-4 font-bold"><?= htmlspecialchars($r['site_name']) ?></td>
                        <td class="p-4 text-blue-600 font-bold"><?= $r['progress_percentage'] ?>%</td>
                        <td class="p-4">
                             <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: <?= $r['progress_percentage'] ?>%"></div>
                             </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>