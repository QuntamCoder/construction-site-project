<?php
require_once '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $hire_date = date('Y-m-d');
    $job_title = "Employee";
    $salary = 0.00;
    $status = "Active";

    $stmt = $pdo->prepare("INSERT INTO EMPLOYEE 
        (first_name, last_name, email, password, role, hire_date, job_title, salary, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt->execute([$first_name, $last_name, $email, $password, $role, $hire_date, $job_title, $salary, $status])) {
        echo "<script>alert('Registration Successful'); window.location='login.php';</script>";
    } else {
        echo "Error!";
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
    <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

    <form method="POST">
        <input type="text" name="first_name" placeholder="First Name" required class="w-full mb-3 p-2 border rounded">
        <input type="text" name="last_name" placeholder="Last Name" required class="w-full mb-3 p-2 border rounded">
        <input type="email" name="email" placeholder="Email" required class="w-full mb-3 p-2 border rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full mb-3 p-2 border rounded">

        <select name="role" required class="w-full mb-3 p-2 border rounded">
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="project_manager">Project Manager</option>
        </select>

        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
            Register
        </button>
    </form>

    <p class="mt-4 text-center">
        Already have account? 
        <a href="login.php" class="text-blue-600">Login</a>
    </p>
</div>

</body>
</html>
