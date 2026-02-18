<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM EMPLOYEE WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['employee_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['first_name'];

        if ($user['role'] == 'admin') {
            header("Location: ../dashboard/admin.php");
        } else {
            header("Location: ../dashboard/project_manager.php");
        }
        exit;

    } else {
        echo "<script>alert('Invalid Credentials');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-lg shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required class="w-full mb-3 p-2 border rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full mb-3 p-2 border rounded">

        <button type="submit" class="w-full bg-green-600 text-white p-2 rounded hover:bg-green-700">
            Login
        </button>
    </form>

    <p class="mt-4 text-center">
        Don't have account? 
        <a href="register.php" class="text-blue-600">Register</a>
    </p>
</div>

</body>
</html>
