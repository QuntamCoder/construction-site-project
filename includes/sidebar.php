<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$uri = $_SERVER['REQUEST_URI'];

$isProjectModule = (
    strpos($uri, '/project/') !== false ||
    strpos($uri, '/site/') !== false ||
    strpos($uri, '/phases/') !== false ||
    strpos($uri, '/milestone/') !== false ||
    strpos($uri, '/daily_report/') !== false ||
    strpos($uri, '/risk/') !== false ||
    strpos($uri, '/equipment/') !== false
);

$isMaterialModule = (strpos($uri, '/material/') !== false);
$isHRModule = (strpos($uri, '/hr/') !== false);
?>

<div class="w-64 bg-slate-900 text-gray-200 min-h-screen shadow-lg">

    <!-- Logo -->
    <div class="p-6 text-xl font-bold border-b border-slate-700 text-center">
        <i class="fas fa-hard-hat text-yellow-400 mr-2"></i>
        BuildControl
    </div>

    <ul class="p-4 space-y-2 text-sm">

        <!-- ================= DASHBOARD ================= -->
        <li>
            <a href="../dashboard/admin.php"
               class="flex items-center px-3 py-2 rounded-lg
               <?= ($currentPage == 'admin.php') ? 'bg-blue-600 text-white' : 'hover:bg-slate-700' ?>">
                <i class="fas fa-chart-line w-5 mr-2"></i>
                Dashboard
            </a>
        </li>

        <!-- ================= PROJECT MANAGEMENT ================= -->
        <li class="pt-4">
            <button onclick="toggleMenu('projectMenu')"
                class="w-full flex justify-between items-center px-3 py-2 rounded-lg
                <?= $isProjectModule ? 'bg-slate-700' : 'hover:bg-slate-700' ?>">
                <span class="flex items-center">
                    <i class="fas fa-folder-open w-5 mr-2 text-pink-400"></i>
                    Project & Site
                </span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>

            <ul id="projectMenu"
                class="ml-6 mt-2 space-y-1 text-gray-400 <?= $isProjectModule ? '' : 'hidden' ?>">

                <li>
                    <a href="../project/view.php"
                       class="block px-3 py-1 rounded
                       <?= ($currentPage == 'view.php' && strpos($uri, '/project/') !== false) ? 'bg-pink-600 text-white' : 'hover:bg-pink-600 hover:text-white' ?>">
                        Projects
                    </a>
                </li>

                <li>
                    <a href="../site/view.php"
                       class="block px-3 py-1 rounded hover:bg-green-600 hover:text-white">
                        Sites
                    </a>
                </li>

                <li>
                    <a href="../phases/list.php"
                       class="block px-3 py-1 rounded hover:bg-yellow-600 hover:text-white">
                        Phases
                    </a>
                </li>

                <li>
                    <a href="../milestone/index.php"
                       class="block px-3 py-1 rounded hover:bg-purple-600 hover:text-white">
                        Milestones
                    </a>
                </li>

                <li>
                    <a href="../daily_report/index.php"
                       class="block px-3 py-1 rounded hover:bg-blue-600 hover:text-white">
                        Daily Reports
                    </a>
                </li>

                <li>
                    <a href="../risk/index.php"
                       class="block px-3 py-1 rounded hover:bg-red-600 hover:text-white">
                        Risk Tracking
                    </a>
                </li>

                <li>
                    <a href="../equipment/index.php"
                       class="block px-3 py-1 rounded hover:bg-indigo-600 hover:text-white">
                        Equipment
                    </a>
                </li>

            </ul>
        </li>

        <!-- ================= MATERIAL & MANAGEMENT ================= -->
        <li class="pt-4">
            <button onclick="toggleMenu('materialMenu')"
                class="w-full flex justify-between items-center px-3 py-2 rounded-lg
                <?= $isMaterialModule ? 'bg-slate-700' : 'hover:bg-slate-700' ?>">
                <span class="flex items-center">
                    <i class="fas fa-warehouse w-5 mr-2 text-orange-400"></i>
                    Material & Management
                </span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>

            <ul id="materialMenu"
                class="ml-6 mt-2 space-y-1 text-gray-400 <?= $isMaterialModule ? '' : 'hidden' ?>">

                <li>
                    <a href="../material/planning/index.php"
                       class="block px-3 py-1 rounded
                       <?= (strpos($uri, '/material/planning/') !== false) ? 'bg-orange-600 text-white' : 'hover:bg-orange-600 hover:text-white' ?>">
                        Material Planning
                    </a>
                </li>

                <li>
                    <a href="../material/indent/index.php"
                       class="block px-3 py-1 rounded
                       <?= (strpos($uri, '/material/indent/') !== false) ? 'bg-orange-600 text-white' : 'hover:bg-orange-600 hover:text-white' ?>">
                        Indent Requests
                    </a>
                </li>

                <li>
                    <a href="../material/approval.php"
                       class="block px-3 py-1 rounded
                       <?= ($currentPage == 'approval.php') ? 'bg-orange-600 text-white' : 'hover:bg-orange-600 hover:text-white' ?>">
                        Purchase Approval
                    </a>
                </li>

                <li>
                    <a href="../material/grn/index.php"
                       class="block px-3 py-1 rounded
                       <?= (strpos($uri, '/material/grn/') !== false) ? 'bg-orange-600 text-white' : 'hover:bg-orange-600 hover:text-white' ?>">
                        GRN
                    </a>
                </li>

                <li>
                    <a href="../material/stock.php"
                       class="block px-3 py-1 rounded
                       <?= ($currentPage == 'stock.php') ? 'bg-orange-600 text-white' : 'hover:bg-orange-600 hover:text-white' ?>">
                        Stock In / Out
                    </a>
                </li>

                <li>
                    <a href="../material/consumption/index.php"
                       class="block px-3 py-1 rounded
                       <?= (strpos($uri, '/material/consumption/') !== false) ? 'bg-orange-600 text-white' : 'hover:bg-orange-600 hover:text-white' ?>">
                        Material Consumption
                    </a>
                </li>

            </ul>
        </li>

        <!-- ================= HR MANAGEMENT ================= -->
        <li class="pt-4">
            <button onclick="toggleMenu('hrMenu')"
                class="w-full flex justify-between items-center px-3 py-2 rounded-lg
                <?= $isHRModule ? 'bg-slate-700' : 'hover:bg-slate-700' ?>">
                <span class="flex items-center">
                    <i class="fas fa-users w-5 mr-2 text-cyan-400"></i>
                    HR Management
                </span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>

            <ul id="hrMenu"
                class="ml-6 mt-2 space-y-1 text-gray-400 <?= $isHRModule ? '' : 'hidden' ?>">

                <li>
                    <a href="../hr/employee.php"
                       class="block px-3 py-1 rounded hover:bg-cyan-600 hover:text-white">
                        Employees
                    </a>
                </li>

                <li>
                    <a href="../hr/attendance.php"
                       class="block px-3 py-1 rounded hover:bg-cyan-600 hover:text-white">
                        Attendance
                    </a>
                </li>

                <li>
                    <a href="../hr/payroll.php"
                       class="block px-3 py-1 rounded hover:bg-cyan-600 hover:text-white">
                        Payroll
                    </a>
                </li>

            </ul>
        </li>

        <!-- ================= LOGOUT ================= -->
        <li class="pt-6">
            <a href="../auth/logout.php"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-red-600">
                <i class="fas fa-sign-out-alt w-5 mr-2"></i>
                Logout
            </a>
        </li>

    </ul>
</div>

<script>
function toggleMenu(menuId) {
    document.getElementById(menuId).classList.toggle('hidden');
}
</script>