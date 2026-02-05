<?php
require_once '../includes/config.php';
include '../includes/header.php';
$stmt=$pdo->query("SELECT r.*, s.site_name FROM RISK r JOIN SITE s ON r.site_id=s.site_id");
?>
<h2 class="text-xl font-bold mb-4">Risks & Delays</h2>
<table class="bg-white w-full">
<tr class="bg-gray-200"><th>Site</th><th>Risk</th><th>Severity</th><th>Status</th></tr>
<?php while($r=$stmt->fetch()): ?>
<tr class="border-b"><td><?= $r['site_name'] ?></td><td><?= $r['description'] ?></td><td><?= $r['severity'] ?></td><td><?= $r['status'] ?></td></tr>
<?php endwhile; ?>
</table>
<?php include '../includes/footer.php'; ?>