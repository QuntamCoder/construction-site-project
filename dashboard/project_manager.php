<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'project_manager') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<h1>Welcome Project Manager, <?php echo $_SESSION['name']; ?> 📁</h1>
<a href="../auth/logout.php">Logout</a>
