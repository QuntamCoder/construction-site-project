<?php
require_once '../includes/config.php';
include '../includes/header.php';
$stmt=$pdo->query("SELECT d.*, s.site_name FROM DAILY_REPORT d JOIN SITE s ON d.site_id=s.site_id ORDER BY report_date DESC");
?>
<h2 class="text-xl font-bold mb-4">Daily Progress</h2>
<table class="bg-white w-full">
<tr class="bg-gray-200"><th>Date</th><th>Site</th><th>Progress %</th><th>Remarks</th></tr>
<?php while($d=$stmt->fetch()): ?>
<tr class="border-b"><td><?= $d['report_date'] ?></td><td><?= $d['site_name'] ?></td><td><?= $d['progress'] ?>%</td><td><?= $d['remarks'] ?></td></tr>
<?php endwhile; ?>
</table>
<?php include '../includes/footer.php'; ?>