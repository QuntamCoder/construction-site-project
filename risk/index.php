<?php
session_start();
require_once('../includes/config.php');
include('../includes/header.php');
include('../includes/sidebar.php');

$risks = $pdo->query("SELECT r.*, s.site_name FROM RISK r JOIN SITE s ON r.site_id = s.site_id ORDER BY r.risk_id DESC")->fetchAll();
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5 px-6">
    <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase pl-4">Safety Risk Log</h1>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-4">Site</th>
                    <th class="p-4">Risk Type</th>
                    <th class="p-4">Severity</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($risks as $r): ?>
                <tr class="border-b hover:bg-red-50">
                    <td class="p-4 font-bold"><?= htmlspecialchars($r['site_name']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($r['risk_type']) ?></td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                            <?= ($r['severity'] == 'Critical' || $r['severity'] == 'High') ? 'bg-red-200 text-red-800' : 'bg-yellow-100 text-yellow-700' ?>">
                            <?= $r['severity'] ?>
                        </span>
                    </td>
                    <td class="p-4">
                        <a href="edit.php?id=<?= $r['risk_id'] ?>" class="text-blue-600 hover:underline">Update</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('../includes/footer.php'); ?>