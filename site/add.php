<?php
require_once '../includes/config.php';
include '../includes/header.php';
$projects=$pdo->query("SELECT * FROM PROJECT")->fetchAll();
if($_POST){
$stmt=$pdo->prepare("INSERT INTO SITE(site_name,project_id,status) VALUES(?,?,?)");
$stmt->execute([$_POST['site'],$_POST['project'],$_POST['status']]);
header('Location:list.php');
}
?>
<form method="post" class="bg-white p-4 w-1/2">
<input name="site" placeholder="Site Name" class="border p-2 w-full mb-2">
<select name="project" class="border p-2 w-full mb-2">
<?php foreach($projects as $p): ?>
<option value="<?= $p['project_id'] ?>"><?= $p['project_name'] ?></option>
<?php endforeach; ?>
</select>
<select name="status" class="border p-2 w-full"><option>Active</option><option>Closed</option></select>
<button class="bg-green-600 text-white px-4 py-2 mt-2">Save</button>
</form>
<?php include '../includes/footer.php'; ?>