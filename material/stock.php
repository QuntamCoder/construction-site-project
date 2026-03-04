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
    $qty = (float)$_POST['quantity'];
    $signQty = ($_POST['txn_type'] === 'OUT') ? -$qty : $qty;

    $pdo->beginTransaction();
    try {
        $txn = $pdo->prepare("INSERT INTO STOCK_TRANSACTION(txn_date,txn_type,reference_type,site_id,material_id,quantity,unit_price,remarks,created_by)
                              VALUES (CURDATE(),?,?,?,?,?,?,?,?)");
        $txn->execute([
            $_POST['txn_type'], $_POST['reference_type'], $_POST['site_id'], $_POST['material_id'],
            $qty, $_POST['unit_price'] ?: 0, $_POST['remarks'] ?: null, $_SESSION['user_id'] ?? null
        ]);

        $inv = $pdo->prepare("INSERT INTO INVENTORY(site_id,material_id,quantity_in_stock,reorder_level) VALUES (?,?,?,0)
                              ON DUPLICATE KEY UPDATE quantity_in_stock = quantity_in_stock + ?");
        $inv->execute([$_POST['site_id'], $_POST['material_id'], max($signQty,0), $signQty]);

        $pdo->commit();
        $msg = "Stock transaction posted.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "Error: " . $e->getMessage();
    }
}

$stock = $pdo->query("
  SELECT i.site_id, i.material_id, s.site_name, m.material_name, i.quantity_in_stock, i.reorder_level
  FROM INVENTORY i
  JOIN SITE s ON i.site_id=s.site_id
  JOIN MATERIAL m ON i.material_id=m.material_id
  ORDER BY s.site_name, m.material_name
")->fetchAll();

$txns = $pdo->query("
  SELECT st.txn_date, st.txn_type, st.reference_type, s.site_name, m.material_name, st.quantity
  FROM STOCK_TRANSACTION st
  JOIN SITE s ON st.site_id=s.site_id
  JOIN MATERIAL m ON st.material_id=m.material_id
  ORDER BY st.stock_txn_id DESC LIMIT 25
")->fetchAll();
?>
<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
  <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold uppercase pl-4">Stock In / Stock Out</h1>
    <a href="index.php" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
  </div>

  <div class="px-4">
    <?php if($msg): ?><div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <form method="post" class="bg-white p-6 rounded shadow mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <select name="site_id" required class="border p-2 rounded"><option value="">Site</option><?php foreach($sites as $s): ?><option value="<?= $s['site_id'] ?>"><?= htmlspecialchars($s['site_name']) ?></option><?php endforeach; ?></select>
        <select name="material_id" required class="border p-2 rounded"><option value="">Material</option><?php foreach($materials as $m): ?><option value="<?= $m['material_id'] ?>"><?= htmlspecialchars($m['material_name']) ?></option><?php endforeach; ?></select>
        <select name="txn_type" class="border p-2 rounded"><option>IN</option><option>OUT</option><option>ADJUSTMENT</option></select>
        <select name="reference_type" class="border p-2 rounded"><option>MANUAL</option><option>GRN</option><option>USAGE</option><option>RETURN</option><option>TRANSFER</option></select>
        <input type="number" step="0.01" name="quantity" required placeholder="Quantity" class="border p-2 rounded">
        <input type="number" step="0.01" name="unit_price" placeholder="Unit Price" class="border p-2 rounded">
        <input type="text" name="remarks" placeholder="Remarks" class="border p-2 rounded">
      </div>
      <button class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded">Post Transaction</button>
    </form>

    <div class="bg-white rounded shadow overflow-hidden mb-6">
      <h3 class="p-4 font-bold border-b">Current Stock</h3>
      <table class="w-full text-left">
        <thead class="bg-gray-50 border-b"><tr><th class="p-3">Site</th><th>Material</th><th>Qty In Stock</th><th>Reorder Level</th></tr></thead>
        <tbody><?php foreach($stock as $r): ?><tr class="border-b"><td class="p-3"><?= htmlspecialchars($r['site_name']) ?></td><td><?= htmlspecialchars($r['material_name']) ?></td><td><?= $r['quantity_in_stock'] ?></td><td><?= $r['reorder_level'] ?></td></tr><?php endforeach; ?></tbody>
      </table>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
      <h3 class="p-4 font-bold border-b">Recent Transactions</h3>
      <table class="w-full text-left">
        <thead class="bg-gray-50 border-b"><tr><th class="p-3">Date</th><th>Type</th><th>Ref</th><th>Site</th><th>Material</th><th>Qty</th></tr></thead>
        <tbody><?php foreach($txns as $r): ?><tr class="border-b"><td class="p-3"><?= $r['txn_date'] ?></td><td><?= $r['txn_type'] ?></td><td><?= $r['reference_type'] ?></td><td><?= htmlspecialchars($r['site_name']) ?></td><td><?= htmlspecialchars($r['material_name']) ?></td><td><?= $r['quantity'] ?></td></tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </div>
</div>
<?php include('../includes/footer.php'); ?>
