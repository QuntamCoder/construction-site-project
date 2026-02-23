<?php
require_once 'includes/config.php';
include 'includes/header.php';

// Main Counts
$projects = $pdo->query("SELECT COUNT(*) FROM PROJECT")->fetchColumn();
$sites = $pdo->query("SELECT COUNT(*) FROM SITE")->fetchColumn();
$milestones = $pdo->query("SELECT COUNT(*) FROM MILESTONE")->fetchColumn();
$phases = $pdo->query("SELECT COUNT(*) FROM PHASE")->fetchColumn();
$reports = $pdo->query("SELECT COUNT(*) FROM DAILY_REPORT")->fetchColumn();

// Status Based Counts
$active_projects = $pdo->query("SELECT COUNT(*) FROM PROJECT WHERE status = 'Active'")->fetchColumn();
$inprogress_phases = $pdo->query("SELECT COUNT(*) FROM PHASE WHERE status = 'In Progress'")->fetchColumn();
?>

<h1 class="text-2xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-3 gap-6 mb-6">
  <div class="bg-white p-4 rounded shadow">
      Projects <br><b class="text-xl"><?= $projects ?></b>
  </div>
  <div class="bg-white p-4 rounded shadow">
      Sites <br><b class="text-xl"><?= $sites ?></b>
  </div>
  <div class="bg-white p-4 rounded shadow">
      Milestones <br><b class="text-xl"><?= $milestones ?></b>
  </div>
</div>

<div class="grid grid-cols-3 gap-6 mb-6">
  <div class="bg-white p-4 rounded shadow">
      Phases <br><b class="text-xl"><?= $phases ?></b>
  </div>
  <div class="bg-white p-4 rounded shadow">
      Daily Reports <br><b class="text-xl"><?= $reports ?></b>
  </div>
  <div class="bg-white p-4 rounded shadow">
      Active Projects <br><b class="text-xl"><?= $active_projects ?></b>
  </div>
</div>

<div class="grid grid-cols-3 gap-6">
  <div class="bg-white p-4 rounded shadow">
      Phases In Progress <br><b class="text-xl text-blue-600"><?= $inprogress_phases ?></b>
  </div>
</div>

<?php include 'includes/footer.php'; ?>