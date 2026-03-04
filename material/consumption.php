<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

$rows = $pdo->query("
  SELECT
    s.site_name,
    m.material_name,
    COALESCE(planned.total_planned,0) AS planned_qty,
    COALESCE(used.total_used,0) AS used_qty,
    (COALESCE(planned.total_planned,0) - COALESCE(used.total_used,0)) AS variance_qty
  FROM MATERIAL m
  CROSS JOIN SITE s
  LEFT JOIN (
    SELECT mp.site_id, mpi.material_id, SUM(mpi.planned_qty) AS total_planned
    FROM MATERIAL_PLAN mp
    JOIN MATERIAL_PLAN_ITEM mpi ON mp.plan_id = mpi.plan_id
    GROUP BY mp.site_id, mpi.material_id
  ) planned ON planned.site_id=s.site_id AND planned.material_id=m.material_id
  LEFT JOIN (
    SELECT site_id, material_id, SUM(quantity_used) AS total_used
    FROM MATERIAL_USAGE
    GROUP BY site_id, material_id
  ) used ON used.site_id=s.site_id AND used.material_id=m.material_id
  WHERE COALESCE(planned.total_planned,0) > 0 OR COALESCE(used.total_used,0) > 0
  ORDER BY s.site_name, m.material_name
")->fetchAll();
?>
<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
  <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold uppercase pl-4">Material Consumption vs Planned</h1>
    <a href="index.php" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
  </div>

  <div class="px-4">
    <div class="bg-white rounded shadow overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
          <tr><th class="p-3">Site</th><th>Material</th><th>Planned</th><th>Used</th><th>Variance</th></tr>
        </thead>
        <tbody>
          <?php foreach($rows as $r): ?>
          <tr class="border-b">
            <td class="p-3"><?= htmlspecialchars($r['site_name']) ?></td>
            <td><?= htmlspecialchars($r['material_name']) ?></td>
            <td><?= $r['planned_qty'] ?></td>
            <td><?= $r['used_qty'] ?></td>
            <td class="<?= ($r['variance_qty'] < 0 ? 'text-red-600 font-semibold' : 'text-green-700 font-semibold') ?>">
              <?= $r['variance_qty'] ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include('../includes/footer.php'); ?>
