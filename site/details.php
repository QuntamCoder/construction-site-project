<?php
session_start();
require_once('../includes/config.php');
include('../includes/header.php');
include('../includes/sidebar.php');

$site_id = $_GET['id'];

// 1. Get Site & Project Info
$site = $pdo->prepare("SELECT s.*, p.project_name FROM SITE s JOIN PROJECT p ON s.project_id = p.project_id WHERE s.site_id = ?");
$site->execute([$site_id]);
$siteData = $site->fetch();

// 2. Get Latest Progress
$progress = $pdo->prepare("SELECT progress_percentage FROM DAILY_REPORT WHERE site_id = ? ORDER BY report_date DESC LIMIT 1");
$progress->execute([$site_id]);
$latestProgress = $progress->fetch();

// 3. Get Site Risks
$risks = $pdo->prepare("SELECT * FROM RISK WHERE site_id = ? ORDER BY severity DESC");
$risks->execute([$site_id]);
$siteRisks = $risks->fetchAll();
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5 px-6">
    <div class="flex flex-col md:flex-row justify-between items-center py-6 border-b mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($siteData['site_name']); ?></h1>
            <p class="text-gray-500">Part of Project: <span class="font-semibold"><?php echo htmlspecialchars($siteData['project_name']); ?></span></p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-2">
            <a href="../report/add.php?site_id=<?php echo $site_id; ?>" class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-green-700">Update Progress</a>
            <a href="../risk/add.php?site_id=<?php echo $site_id; ?>" class="bg-red-600 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-red-700">Report Risk</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-gray-400 uppercase text-xs font-bold mb-4">Overall Site Progress</h3>
            <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                    <div><span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">Completion</span></div>
                    <div class="text-right"><span class="text-sm font-semibold inline-block text-blue-600"><?php echo $latestProgress['progress_percentage'] ?? 0; ?>%</span></div>
                </div>
                <div class="overflow-hidden h-4 mb-4 text-xs flex rounded bg-blue-100">
                    <div style="width:<?php echo $latestProgress['progress_percentage'] ?? 0; ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 transition-all duration-500"></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-gray-400 uppercase text-xs font-bold mb-4">Site Location</h3>
            <p class="text-gray-700"><i class="fas fa-map-pin mr-2 text-red-500"></i> <?php echo htmlspecialchars($siteData['location']); ?></p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-gray-400 uppercase text-xs font-bold mb-4">Active Risks</h3>
            <span class="text-2xl font-bold text-red-600"><?php echo count($siteRisks); ?></span> Issues logged
        </div>
    </div>
    
    <div class="mt-8 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 bg-gray-50 border-b font-bold text-gray-700">Recent Safety Risks</div>
        <table class="w-full text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Severity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($siteRisks as $r): ?>
                <tr class="border-t">
                    <td class="px-6 py-4"><?php echo htmlspecialchars($r['risk_type']); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold <?php echo ($r['severity'] == 'High') ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                            <?php echo $r['severity']; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>