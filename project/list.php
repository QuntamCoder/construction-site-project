<?php
require_once '../includes/config.php';
include '../includes/header.php';

$stmt = $pdo->query("SELECT * FROM PROJECT ORDER BY project_id DESC");
?>

<h2 class="text-xl font-bold mb-4">Projects</h2>
<a href="add.php" class="bg-blue-600 text-white px-4 py-2 rounded">Add Project</a>

<table class="mt-4 w-full bg-white shadow">
<tr class="bg-gray-200">
  <th class="p-2">Name</th>
  <th>Start</th>
  <th>End</th>
  <th>Status</th>
</tr>

<?php while($row = $stmt->fetch()): ?>
<tr class="border-b">
  <td class="p-2"><?= $row['project_name'] ?></td>
  <td><?= $row['start_date'] ?></td>
  <td><?= $row['end_date'] ?></td>
  <td><?= $row['status'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php include '../includes/footer.php'; ?>
