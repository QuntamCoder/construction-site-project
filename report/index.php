<?php
session_start();
require_once('../includes/config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include('../includes/header.php');
include('../includes/sidebar.php');

// Fetch Latest Progress per Site
$progressQuery = "SELECT p.project_name, s.site_name, dr.report_date, dr.progress_percentage 
                  FROM DAILY_REPORT dr
                  JOIN SITE s ON dr.site_id = s.site_id
                  JOIN PROJECT p ON s.project_id = p.project_id
                  WHERE dr.report_date = (SELECT MAX(report_date) FROM DAILY_REPORT WHERE site_id = s.site_id)
                  ORDER BY dr.report_date DESC";
$progressData = $pdo->query($progressQuery)->fetchAll();

// Fetch Active Risks
$riskQuery = "SELECT r.*, s.site_name 
              FROM RISK r 
              JOIN SITE s ON r.site_id = s.site_id 
              WHERE r.severity IN ('High', 'Critical')
              ORDER BY r.severity DESC";
$risks = $pdo->query($riskQuery)->fetchAll();
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    <div class="bg-white p-4 shadow-sm flex flex-col md:flex-row justify-between items-center mb-6 px-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase">Operational Reports</h1>
        <div class="flex space-x-2 mt-4 md:mt-0">
            <a href="add.php" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-1"></i> Submit Daily Report
            </a>
            <a href="history.php" class="bg-purple-600 text-white px-4 py-2 rounded text-sm hover:bg-purple-700 transition">
                <i class="fas fa-history mr-1"></i> Full History
            </a>
            <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-black transition">
                <i class="fas fa-print mr-1"></i> Print
            </button>
        </div>
    </div>

    <div class="flex flex-wrap px-4">
        <div class="w-full xl:w-2/3 p-3">
            <div class="bg-white border rounded shadow p-5">
                <h2 class="font-bold uppercase text-gray-600 mb-4 text-sm border-b pb-2">Latest Site Progress</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 text-sm">
                            <th class="pb-3 px-2">Project/Site</th>
                            <th class="pb-3 px-2 text-center">Completion</th>
                            <th class="pb-3 px-2">Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($progressData)): ?>
                            <tr><td colspan="3" class="py-4 text-center text-gray-400">No reports found.</td></tr>
                        <?php else: ?>
                            <?php foreach($progressData as $report): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-4 px-2">
                                    <div class="font-bold text-gray-800"><?php echo htmlspecialchars($report['site_name']); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($report['project_name']); ?></div>
                                </td>
                                <td class="py-4 px-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo $report['progress_percentage']; ?>%"></div>
                                    </div>
                                    <div class="text-center text-xs mt-1 font-semibold text-blue-700"><?php echo $report['progress_percentage']; ?>%</div>
                                </td>
                                <td class="py-4 px-2 text-sm text-gray-600">
                                    <?php echo date('M d, Y', strtotime($report['report_date'])); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="w-full xl:w-1/3 p-3">
            <div class="bg-white border rounded shadow p-5 border-t-4 border-red-500">
                <h2 class="font-bold uppercase text-red-600 mb-4 text-sm">Critical Risk Alerts</h2>
                <?php if(empty($risks)): ?>
                    <p class="text-gray-500 text-sm italic">No high-severity risks reported.</p>
                <?php else: ?>
                    <?php foreach($risks as $risk): ?>
                    <div class="mb-4 p-3 bg-red-50 rounded border-l-4 border-red-600 shadow-sm">
                        <div class="flex justify-between">
                            <span class="font-bold text-red-800 text-xs uppercase"><?php echo $risk['severity']; ?></span>
                            <span class="text-xs text-gray-500"><?php echo htmlspecialchars($risk['site_name']); ?></span>
                        </div>
                        <p class="text-sm text-gray-700 mt-1 font-medium"><?php echo htmlspecialchars($risk['risk_type']); ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>