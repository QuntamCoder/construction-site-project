<?php
require_once '../includes/config.php';
include '../includes/header.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
  $sql = "INSERT INTO PROJECT(project_name,start_date,end_date,budget,status)
          VALUES (?,?,?,?,?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $_POST['name'],
    $_POST['start'],
    $_POST['end'],
    $_POST['budget'],
    $_POST['status']
  ]);
  header("Location: list.php");
}
?>

<h2 class="text-xl font-bold mb-4">Add Project</h2>

<form method="post" class="bg-white p-4 shadow w-1/2">
  <input name="name" placeholder="Project Name" class="border p-2 w-full mb-2">
  <input type="date" name="start" class="border p-2 w-full mb-2">
  <input type="date" name="end" class="border p-2 w-full mb-2">
  <input name="budget" placeholder="Budget" class="border p-2 w-full mb-2">
  <select name="status" class="border p-2 w-full">
    <option>Planned</option>
    <option>In Progress</option>
    <option>Completed</option>
  </select>
  <button class="bg-green-600 text-white px-4 py-2 mt-3">Save</button>
</form>

<?php include '../includes/footer.php'; ?>
