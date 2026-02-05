<?php
require_once '../includes/config.php';
include '../includes/header.php';


$sql = "SELECT s.*, p.project_name FROM SITE s JOIN PROJECT p ON s.project_id=p.project_id";
$stmt = $pdo->query($sql);
?>
<h2 class="text-xl font-bold mb-4">Sites</h2>
<a href="add.php" class="bg-blue-600 text-white px-4 py-2 rounded">Add Site</a>
<table class="mt-4 w-full bg-white shadow">
<tr class="bg-gray-200"><th>Site</th><th>Project</th><th>Status</th></tr>
<?php while($r=$stmt->fetch()): ?>
<tr class="border-b"><td class="p-2"><?= $r['site_name'] ?></td><td><?= $r['project_name'] ?></td><td><?= $r['status'] ?></td></tr>
<?php endwhile; ?>
</table>
<?php include '../includes/footer.php'; ?>