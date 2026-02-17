<?php
require_once '../includes/config.php';
include '../includes/header.php';
$stmt=$pdo->query("SELECT m.*, p.project_name FROM MILESTONE m JOIN PROJECT p ON m.project_id=p.project_id");
?>
<h2 class="text-xl font-bold mb-4">Milestones</h2>
<table class="bg-white w-full">
<tr class="bg-gray-200"><th>Name</th><th>Project</th><th>Target</th><th>Status</th></tr>
<?php while($m=$stmt->fetch()): ?>
<tr class="border-b"><td><?= $m['milestone_name'] ?></td><td><?= $m['project_name'] ?></td><td><?= $m['target_date'] ?></td><td><?= $m['status'] ?></td></tr>
<?php endwhile; ?>
</table>
<?php include '../includes/footer.php'; ?>