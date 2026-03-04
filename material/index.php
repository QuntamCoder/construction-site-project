<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { header("Location: ../auth/login.php"); exit; }

include('../includes/header.php');
include('../includes/sidebar.php');

$materials = $pdo->query("SELECT COUNT(*) FROM MATERIAL")->fetchColumn();
$pendingIndents = $pdo->query("SELECT COUNT(*) FROM MATERIAL_INDENT WHERE status='Pending'")->fetchColumn();
$pendingApprovals = $pdo->query("SELECT COUNT(*) FROM PURCHASE_APPROVAL WHERE approval_status='Pending'")->fetchColumn();
$todayGrn = $pdo->query("SELECT COUNT(*) FROM GRN WHERE grn_date=CURDATE()")->fetchColumn();
?>
<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
  <div class="bg-white p-4 shadow-sm mb-6">
    <h1 class="text-2xl font-bold text-gray-800 uppercase pl-4">Material & Inventory Management</h1>
  </div>

  <div class="px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow">Materials<br><b class="text-xl"><?= $materials ?></b></div>
    <div class="bg-white p-4 rounded shadow">Pending Indents<br><b class="text-xl"><?= $pendingIndents ?></b></div>
    <div class="bg-white p-4 rounded shadow">Pending Approvals<br><b class="text-xl"><?= $pendingApprovals ?></b></div>
    <div class="bg-white p-4 rounded shadow">Today GRN<br><b class="text-xl"><?= $todayGrn ?></b></div>
  </div>

  <div class="px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <a href="planning.php" class="bg-blue-600 text-white p-4 rounded shadow">1. Material Requirement Planning</a>
    <a href="indent.php" class="bg-green-600 text-white p-4 rounded shadow">2. Material Indent Requests</a>
    <a href="approval.php" class="bg-yellow-600 text-white p-4 rounded shadow">3. Purchase Approval Workflow</a>
    <a href="grn.php" class="bg-purple-600 text-white p-4 rounded shadow">4. GRN (Goods Received Note)</a>
    <a href="stock.php" class="bg-indigo-600 text-white p-4 rounded shadow">5. Stock In / Stock Out</a>
    <a href="consumption.php" class="bg-red-600 text-white p-4 rounded shadow">6. Consumption vs Planned</a>
  </div>
</div>
<?php include('../includes/footer.php'); ?>
