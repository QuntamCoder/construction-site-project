<?php
session_start();
require_once('../includes/config.php');
if (!isset($_SESSION['role'])) { 
    header("Location: ../auth/login.php"); 
    exit; 
}

include('../includes/header.php');
include('../includes/sidebar.php');

// Fetch sites for dropdown
$stmt = $pdo->query("
    SELECT s.site_id, s.site_name, p.project_name 
    FROM SITE s
    JOIN PROJECT p ON s.project_id = p.project_id
    ORDER BY p.project_name, s.site_name
");
$sites = $stmt->fetchAll();

$message = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $site_id = $_POST['site_id'];
    $phase_name = $_POST['phase_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'] ?: null;
    $status = $_POST['status'];
    $priority = $_POST['priority'];

    $stmt = $pdo->prepare("
        INSERT INTO PHASE 
        (site_id, phase_name, description, start_date, end_date, status, priority) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $site_id,
        $phase_name,
        $description,
        $start_date,
        $end_date,
        $status,
        $priority
    ]);

    $message = "Phase added successfully!";
}
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

    <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase pl-4">Add New Phase</h1>
        <a href="list.php" class="bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-700 transition">
            Back
        </a>
    </div>

    <div class="px-4">

        <?php if($message): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST">

                <!-- Site Selection -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Select Site</label>
                    <select name="site_id" required class="w-full border p-2 rounded">
                        <option value="">-- Select Site --</option>
                        <?php foreach($sites as $site): ?>
                            <option value="<?php echo $site['site_id']; ?>">
                                <?php echo htmlspecialchars($site['project_name'] . " - " . $site['site_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Phase Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Phase Name</label>
                    <input type="text" name="phase_name" required 
                           class="w-full border p-2 rounded"
                           placeholder="e.g. Foundation Work">
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full border p-2 rounded"
                              placeholder="Phase details..."></textarea>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Start Date</label>
                        <input type="date" name="start_date" required 
                               class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">End Date</label>
                        <input type="date" name="end_date"
                               class="w-full border p-2 rounded">
                    </div>
                </div>

                <!-- Status & Priority -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Status</label>
                        <select name="status" required class="w-full border p-2 rounded">
                            <option value="Not Started">Not Started</option>
                            <option value="In Progress">In Progress</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Completed">Completed</option>
                            <option value="Delayed">Delayed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Priority</label>
                        <select name="priority" class="w-full border p-2 rounded">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>Save Phase
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>