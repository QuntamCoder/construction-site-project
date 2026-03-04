<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

$sites = $pdo->query("SELECT site_id, site_name FROM SITE ORDER BY site_name")->fetchAll();
$materials = $pdo->query("SELECT material_id, material_name FROM MATERIAL ORDER BY material_name")->fetchAll();

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $indentNo = "IND-" . date('Ymd-His');
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO MATERIAL_INDENT(indent_number,indent_date,need_by_date,status,priority,reason,site_id,requested_by) VALUES (?,CURDATE(),?,?,?,?,?,?)");
        $stmt->execute([
            $indentNo,
            $_POST['need_by_date'] ?: null,
            $_POST['status'],
            $_POST['priority'],
            $_POST['reason'] ?: null,
            $_POST['site_id'],
            $_SESSION['user_id'] ?? null
        ]);
        $indentId = $pdo->lastInsertId();

        $item = $pdo->prepare("INSERT INTO MATERIAL_INDENT_ITEM(indent_id,material_id,requested_qty,remarks) VALUES (?,?,?,?)");
        $item->execute([$indentId, $_POST['material_id'], $_POST['requested_qty'], $_POST['item_remarks'] ?: null]);

        $pdo->commit();
        $msg = "Indent created: $indentNo";
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "Error: " . $e->getMessage();
    }
}

$list = $pdo->query("
  SELECT mi.indent_id, mi.indent_number, mi.indent_date, mi.status, mi.priority, s.site_name, m.material_name, mii.requested_qty
  FROM MATERIAL_INDENT mi
  JOIN SITE s ON mi.site_id=s.site_id
  JOIN MATERIAL_INDENT_ITEM mii ON mi.indent_id=mii.indent_id
  JOIN MATERIAL m ON mii.material_id=m.material_id
  ORDER BY mi.indent_id DESC
")->fetchAll();
?>
<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
  <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold uppercase pl-4">Material Indent Requests</h1>
    <a href="index.php" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
  </div>

  <div class="px-4">
    <?php if($msg): ?><div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <form method="post" class="bg-white p-6 rounded shadow mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <select name="site_id" required class="border p-2 rounded"><option value="">Select Site</option><?php foreach($sites as $s): ?><option value="<?= $s['site_id'] ?>"><?= htmlspecialchars($s['site_name']) ?></option><?php endforeach; ?></select>
        <select name="material_id" required class="border p-2 rounded"><option value="">Select Material</option><?php foreach($materials as $m): ?><option value="<?= $m['material_id'] ?>"><?= htmlspecialchars($m['material_name']) ?></option><?php endforeach; ?></select>
        <input type="number" step="0.01" name="requested_qty" required placeholder="Requested Qty" class="border p-2 rounded">
        <input type="date" name="need_by_date" class="border p-2 rounded">
        <select name="priority" class="border p-2 rounded"><option>Low</option><option selected>Medium</option><option>High</option></select>
        <select name="status" class="border p-2 rounded"><option>Pending</option><option>Approved</option><option>Rejected</option><option>Fulfilled</option></select>
        <input type="text" name="reason" placeholder="Reason" class="border p-2 rounded">
        <input type="text" name="item_remarks" placeholder="Item Remarks" class="border p-2 rounded">
      </div>
      <button class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Save Indent</button>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-gray-50 border-b"><tr><th class="p-3">Indent No</th><th>Date</th><th>Site</th><th>Material</th><th>Qty</th><th>Priority</th><th>Status</th></tr></thead>
        <tbody><?php foreach($list as $r): ?><tr class="border-b"><td class="p-3"><?= htmlspecialchars($r['indent_number']) ?></td><td><?= $r['indent_date'] ?></td><td><?= htmlspecialchars($r['site_name']) ?></td><td><?= htmlspecialchars($r['material_name']) ?></td><td><?= $r['requested_qty'] ?></td><td><?= $r['priority'] ?></td><td><?= $r['status'] ?></td></tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </div>
</div>
<?php include('../includes/footer.php'); ?>
