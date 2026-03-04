<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

$pos = $pdo->query("SELECT po_id, po_number FROM PURCHASE_ORDER ORDER BY po_id DESC")->fetchAll();
$indents = $pdo->query("SELECT indent_id, indent_number FROM MATERIAL_INDENT ORDER BY indent_id DESC")->fetchAll();
$employees = $pdo->query("SELECT employee_id, first_name, last_name FROM EMPLOYEE ORDER BY first_name")->fetchAll();

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO PURCHASE_APPROVAL(po_id, indent_id, requested_by, approver_id, approval_status, approval_date, remarks) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([
            $_POST['po_id'],
            $_POST['indent_id'] ?: null,
            $_SESSION['user_id'] ?? null,
            $_POST['approver_id'] ?: null,
            $_POST['approval_status'],
            $_POST['approval_date'] ?: null,
            $_POST['remarks'] ?: null
        ]);
        $msg = "Approval record saved.";
    } catch (Exception $e) {
        $msg = "Error: " . $e->getMessage();
    }
}

$list = $pdo->query("
  SELECT pa.approval_id, po.po_number, mi.indent_number, pa.approval_status, pa.approval_date,
         CONCAT(e.first_name,' ',e.last_name) AS approver
  FROM PURCHASE_APPROVAL pa
  JOIN PURCHASE_ORDER po ON pa.po_id=po.po_id
  LEFT JOIN MATERIAL_INDENT mi ON pa.indent_id=mi.indent_id
  LEFT JOIN EMPLOYEE e ON pa.approver_id=e.employee_id
  ORDER BY pa.approval_id DESC
")->fetchAll();
?>
<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
  <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold uppercase pl-4">Purchase Approval Workflow</h1>
    <a href="index.php" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
  </div>

  <div class="px-4">
    <?php if($msg): ?><div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <form method="post" class="bg-white p-6 rounded shadow mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <select name="po_id" required class="border p-2 rounded"><option value="">Select PO</option><?php foreach($pos as $po): ?><option value="<?= $po['po_id'] ?>"><?= htmlspecialchars($po['po_number']) ?></option><?php endforeach; ?></select>
        <select name="indent_id" class="border p-2 rounded"><option value="">Indent (Optional)</option><?php foreach($indents as $i): ?><option value="<?= $i['indent_id'] ?>"><?= htmlspecialchars($i['indent_number']) ?></option><?php endforeach; ?></select>
        <select name="approver_id" class="border p-2 rounded"><option value="">Approver</option><?php foreach($employees as $e): ?><option value="<?= $e['employee_id'] ?>"><?= htmlspecialchars($e['first_name'].' '.$e['last_name']) ?></option><?php endforeach; ?></select>
        <select name="approval_status" class="border p-2 rounded"><option>Pending</option><option>Approved</option><option>Rejected</option></select>
        <input type="date" name="approval_date" class="border p-2 rounded">
        <input type="text" name="remarks" placeholder="Remarks" class="border p-2 rounded">
      </div>
      <button class="mt-4 bg-yellow-600 text-white px-4 py-2 rounded">Save Approval</button>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-gray-50 border-b"><tr><th class="p-3">PO</th><th>Indent</th><th>Status</th><th>Date</th><th>Approver</th></tr></thead>
        <tbody><?php foreach($list as $r): ?><tr class="border-b"><td class="p-3"><?= htmlspecialchars($r['po_number']) ?></td><td><?= htmlspecialchars($r['indent_number'] ?? '-') ?></td><td><?= $r['approval_status'] ?></td><td><?= $r['approval_date'] ?: '-' ?></td><td><?= htmlspecialchars($r['approver'] ?? '-') ?></td></tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </div>
</div>
<?php include('../includes/footer.php'); ?>
