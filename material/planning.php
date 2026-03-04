<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

$sites = $pdo->query("SELECT site_id, site_name FROM SITE ORDER BY site_name")->fetchAll();
$phases = $pdo->query("SELECT phase_id, phase_name FROM PHASE ORDER BY phase_name")->fetchAll();
$materials = $pdo->query("SELECT material_id, material_name, unit_price FROM MATERIAL ORDER BY material_name")->fetchAll();

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO MATERIAL_PLAN(plan_date,required_by_date,status,remarks,site_id,phase_id,created_by) VALUES (CURDATE(),?,?,?,?,?,?)");
        $stmt->execute([
            $_POST['required_by_date'] ?: null,
            $_POST['status'],
            $_POST['remarks'] ?: null,
            $_POST['site_id'],
            $_POST['phase_id'] ?: null,
            $_SESSION['user_id'] ?? null
        ]);

        $planId = $pdo->lastInsertId();
        $item = $pdo->prepare("INSERT INTO MATERIAL_PLAN_ITEM(plan_id,material_id,planned_qty,estimated_unit_price,notes) VALUES (?,?,?,?,?)");
        $item->execute([
            $planId,
            $_POST['material_id'],
            $_POST['planned_qty'],
            $_POST['estimated_unit_price'],
            $_POST['item_notes'] ?: null
        ]);

        $pdo->commit();
        $msg = "Plan created successfully.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "Error: " . $e->getMessage();
    }
}

$list = $pdo->query("
    SELECT mp.plan_id, mp.plan_date, mp.required_by_date, mp.status, s.site_name, ph.phase_name,
           m.material_name, mpi.planned_qty, mpi.estimated_unit_price
    FROM MATERIAL_PLAN mp
    JOIN SITE s ON mp.site_id = s.site_id
    LEFT JOIN PHASE ph ON mp.phase_id = ph.phase_id
    JOIN MATERIAL_PLAN_ITEM mpi ON mp.plan_id = mpi.plan_id
    JOIN MATERIAL m ON mpi.material_id = m.material_id
    ORDER BY mp.plan_id DESC
")->fetchAll();
?>
<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
  <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold uppercase pl-4">Material Requirement Planning</h1>
    <a href="index.php" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
  </div>

  <div class="px-4">
    <?php if($msg): ?><div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <form method="post" class="bg-white p-6 rounded shadow mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <select name="site_id" required class="border p-2 rounded">
          <option value="">Select Site</option>
          <?php foreach($sites as $s): ?><option value="<?= $s['site_id'] ?>"><?= htmlspecialchars($s['site_name']) ?></option><?php endforeach; ?>
        </select>
        <select name="phase_id" class="border p-2 rounded">
          <option value="">Select Phase (Optional)</option>
          <?php foreach($phases as $p): ?><option value="<?= $p['phase_id'] ?>"><?= htmlspecialchars($p['phase_name']) ?></option><?php endforeach; ?>
        </select>
        <input type="date" name="required_by_date" class="border p-2 rounded">
        <select name="material_id" required class="border p-2 rounded">
          <option value="">Select Material</option>
          <?php foreach($materials as $m): ?><option value="<?= $m['material_id'] ?>"><?= htmlspecialchars($m['material_name']) ?></option><?php endforeach; ?>
        </select>
        <input type="number" step="0.01" name="planned_qty" required placeholder="Planned Qty" class="border p-2 rounded">
        <input type="number" step="0.01" name="estimated_unit_price" required placeholder="Estimated Unit Price" class="border p-2 rounded">
        <select name="status" class="border p-2 rounded">
          <option>Draft</option><option>Submitted</option><option>Approved</option><option>Rejected</option>
        </select>
        <input type="text" name="item_notes" placeholder="Item Notes" class="border p-2 rounded">
        <input type="text" name="remarks" placeholder="Header Remarks" class="border p-2 rounded">
      </div>
      <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Save Plan</button>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-gray-50 border-b"><tr><th class="p-3">ID</th><th>Site</th><th>Phase</th><th>Material</th><th>Qty</th><th>Unit Rate</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach($list as $r): ?>
          <tr class="border-b"><td class="p-3"><?= $r['plan_id'] ?></td><td><?= htmlspecialchars($r['site_name']) ?></td><td><?= htmlspecialchars($r['phase_name'] ?? '-') ?></td><td><?= htmlspecialchars($r['material_name']) ?></td><td><?= $r['planned_qty'] ?></td><td><?= $r['estimated_unit_price'] ?></td><td><?= $r['status'] ?></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include('../includes/footer.php'); ?>
