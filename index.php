<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Construction Site Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-gray-900 text-white p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">🏗 BuildTrack</h1>
        <div>
            <a href="auth/login.php" class="bg-yellow-500 px-4 py-2 rounded-lg hover:bg-yellow-600 mr-3">
                Login
            </a>
            <a href="auth/register.php" class="bg-blue-600 px-4 py-2 rounded-lg hover:bg-blue-700">
                Register
            </a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="h-screen flex items-center justify-center bg-cover bg-center"
    style="background-image: url('https://images.unsplash.com/photo-1503387762-592deb58ef4e');">

    <div class="bg-black bg-opacity-60 p-10 rounded-xl text-center text-white max-w-2xl">
        <h2 class="text-4xl font-bold mb-6">
            Construction Site Management System
        </h2>

        <p class="text-lg mb-8">
            Manage Projects, Track Workers, Monitor Expenses and
            Control Construction Operations Efficiently.
        </p>

        <a href="auth/login.php"
           class="bg-yellow-500 text-black px-6 py-3 rounded-lg text-lg font-semibold hover:bg-yellow-400">
            Get Started
        </a>
    </div>

</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto text-center">
        <h3 class="text-3xl font-bold mb-12">System Features</h3>

        <div class="grid md:grid-cols-3 gap-8 px-6">

            <div class="p-6 bg-gray-100 rounded-xl shadow-md">
                <h4 class="text-xl font-semibold mb-4">🏗 Project Tracking</h4>
                <p>Monitor progress of all construction projects in real-time.</p>
            </div>

            <div class="p-6 bg-gray-100 rounded-xl shadow-md">
                <h4 class="text-xl font-semibold mb-4">👷 Workforce Management</h4>
                <p>Assign tasks and manage workers efficiently.</p>
            </div>

            <div class="p-6 bg-gray-100 rounded-xl shadow-md">
                <h4 class="text-xl font-semibold mb-4">🧾 Expense Monitoring</h4>
                <p>Track expenses, salaries, and project budgets easily.</p>
            </div>

        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white text-center p-4">
    © <?php echo date("Y"); ?> Construction Site Management System | Developed by Your Name
</footer>

</body>
</html>
