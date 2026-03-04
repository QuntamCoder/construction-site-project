<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

$pos = $pdo->query("SELECT po_id, po_number, supplier_id FROM PURCHASE_ORDER ORDER BY po_id DESC")->fetchAll();
$sites = $pdo->query("SELECT site_id, site_name FROM SITE ORDER BY site_name")->fetchAll();
$suppliers = $pdo->query("SELECT supplier_id, supplier_name FROM SUPPLIER ORDER BY supplier_name")->fetchAll();
$materials = $pdo->query("SELECT material_id, material_name FROM MATERIAL ORDER BY material_name")->fetchAll();

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grnNo = "GRN-" . date('Ymd-His');
    $pdo->beginTransaction();
    try {
        $hdr = $pdo->prepare("INSERT INTO GRN(grn_number,grn_date,po_id,supplier_id,site_id,received_by,status,remarks) VALUES (?,CURDATE(),?,?,?,?,?,?)");
        $hdr->execute([
            $grnNo, $_POST['po_id'], $_POST['supplier_id'], $_POST['site_id'],
            $_SESSION['user_id'] ?? null, $_POST['status'], $_POST['remarks'] ?: null
        ]);
        $grnId = $pdo->lastInsertId();

        $accepted = (float)$_POST['accepted_qty'];
        $unitPrice = (float)$_POST['unit_price'];

        $line = $pdo->prepare("INSERT INTO GRN_ITEM(grn_id,material_id,received_qty,accepted_qty,rejected_qty,unit_price) VALUES (?,?,?,?,?,?)");
        $line->execute([
            $grnId, $_POST['material_id'], $_POST['received_qty'], $_POST['accepted_qty'], $_POST['rejected_qty'], $unitPrice
        ]);

        $inv = $pdo->prepare("INSERT INTO INVENTORY(site_id,material_id,quantity_in_stock,reorder_level) VALUES (?,?,?,0)
                              ON DUPLICATE KEY UPDATE quantity_in_stock = quantity_in_stock + VALUES(quantity_in_stock)");
        $inv->execute([$_POST['site_id'], $_POST['material_id'], $accepted]);

        $txn = $pdo->prepare("INSERT INTO STOCK_TRANSACTION(txn_date,txn_type,reference_type,reference_id,site_id,material_id,quantity,unit_price,remarks,created_by)
                              VALUES (CURDATE(),'IN','GRN',?,?,?,?,?,?,?)");
        $txn->execute([$grnId, $_POST['site_id'], $_POST['material_id'], $accepted, $unitPrice, $_POST['remarks'] ?: null, $_SESSION['user_id'] ?? null]);

        $pdo->commit();
        $msg = "GRN posted: $grnNo";
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "Error: " . $e->getMessage();
    }
}

$list = $pdo->query("
  SELECT g.grn_number, g.grn_date, s.site_name, sp.supplier_name, m.material_name, gi.accepted_qty, gi.unit_price
  FROM GRN g
  JOIN SITE s ON g.site_id=s.site_id
  JOIN SUPPLIER sp ON g.supplier_id=sp.supplier_id
  JOIN GRN_ITEM gi ON g.grn_id=gi.grn_id
  JOIN MATERIAL m ON gi.material_id=m.material_id
  ORDER BY g.grn_id DESC
")->fetchAll();
?>
<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
  <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold uppercase pl-4">GRN (Goods Received Note)</h1>
    <a href="index.php" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
  </div>

  <div class="px-4">
    <?php if($msg): ?><div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <form method="post" class="bg-white p-6 rounded shadow mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <select name="po_id" required class="border p-2 rounded"><option value="">PO</option><?php foreach($pos as $po): ?><option value="<?= $po['po_id'] ?>"><?= htmlspecialchars($po['po_number']) ?></option><?php endforeach; ?></select>
        <select name="supplier_id" required class="border p-2 rounded"><option value="">Supplier</option><?php foreach($suppliers as $s): ?><option value="<?= $s['supplier_id'] ?>"><?= htmlspecialchars($s['supplier_name']) ?></option><?php endforeach; ?></select>
        <select name="site_id" required class="border p-2 rounded"><option value="">Site</option><?php foreach($sites as $s): ?><option value="<?= $s['site_id'] ?>"><?= htmlspecialchars($s['site_name']) ?></option><?php endforeach; ?></select>
        <select name="material_id" required class="border p-2 rounded"><option value="">Material</option><?php foreach($materials as $m): ?><option value="<?= $m['material_id'] ?>"><?= htmlspecialchars($m['material_name']) ?></option><?php endforeach; ?></select>
        <input type="number" step="0.01" name="received_qty" required placeholder="Received Qty" class="border p-2 rounded">
        <input type="number" step="0.01" name="accepted_qty" required placeholder="Accepted Qty" class="border p-2 rounded">
        <input type="number" step="0.01" name="rejected_qty" value="0" required placeholder="Rejected Qty" class="border p-2 rounded">
        <input type="number" step="0.01" name="unit_price" required placeholder="Unit Price" class="border p-2 rounded">
        <select name="status" class="border p-2 rounded"><option>Draft</option><option selected>Posted</option><option>Cancelled</option></select>
        <input type="text" name="remarks" placeholder="Remarks" class="border p-2 rounded">
      </div>
      <button class="mt-4 bg-purple-600 text-white px-4 py-2 rounded">Save GRN</button>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-gray-50 border-b"><tr><th class="p-3">GRN</th><th>Date</th><th>Site</th><th>Supplier</th><th>Material</th><th>Accepted Qty</th><th>Rate</th></tr></thead>
        <tbody><?php foreach($list as $r): ?><tr class="border-b"><td class="p-3"><?= htmlspecialchars($r['grn_number']) ?></td><td><?= $r['grn_date'] ?></td><td><?= htmlspecialchars($r['site_name']) ?></td><td><?= htmlspecialchars($r['supplier_name']) ?></td><td><?= htmlspecialchars($r['material_name']) ?></td><td><?= $r['accepted_qty'] ?></td><td><?= $r['unit_price'] ?></td></tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </div>
</div>
<?php include('../includes/footer.php'); ?>
