<?php
require_once 'includes/config.php';
include 'includes/header.php';

$projects = $pdo->query("SELECT COUNT(*) FROM PROJECT")->fetchColumn();
$sites = $pdo->query("SELECT COUNT(*) FROM SITE")->fetchColumn();
$milestones = $pdo->query("SELECT COUNT(*) FROM MILESTONE")->fetchColumn();
?>

<h1 class="text-2xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-3 gap-6">
  <div class="bg-white p-4 rounded shadow">Projects: <b><?= $projects ?></b></div>
  <div class="bg-white p-4 rounded shadow">Sites: <b><?= $sites ?></b></div>
  <div class="bg-white p-4 rounded shadow">Milestones: <b><?= $milestones ?></b></div>
</div>

<?php include 'includes/footer.php'; ?>
