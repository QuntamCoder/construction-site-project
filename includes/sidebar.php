<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="bg-slate-800 shadow-xl h-16 fixed bottom-0 md:relative md:h-screen z-10 w-full md:w-64">
    <div class="md:mt-12 md:w-64 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
        
        <!-- Logo -->
        <div class="p-6 text-white font-bold text-xl border-b border-slate-700">
            <i class="fas fa-hard-hat mr-2 text-yellow-500"></i> BuildControl
        </div>

        <ul class="list-reset flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-2 text-center md:text-left">

            <!-- Dashboard -->
            <li class="mr-3 flex-1">
                <a href="../dashboard/admin.php"
                   class="block py-2 md:py-3 pl-1 align-middle no-underline border-b-2
                   <?= ($currentPage == 'admin.php') ? 'text-white border-blue-600' : 'text-gray-300 hover:text-white border-slate-800 hover:border-blue-600' ?>">
                    <i class="fas fa-chart-line md:pr-3 text-blue-400"></i>
                    <span class="text-xs md:text-base block md:inline-block">Dashboard</span>
                </a>
            </li>

            <!-- Projects -->
            <li class="mr-3 flex-1">
                <a href="../project/view.php"
                   class="block py-2 md:py-3 pl-1 align-middle no-underline border-b-2
                   <?= (in_array($currentPage, ['view.php','add.php','edit.php']) && strpos($_SERVER['REQUEST_URI'],'project') !== false)
                   ? 'text-white border-pink-500'
                   : 'text-gray-300 hover:text-white border-slate-800 hover:border-pink-500' ?>">
                    <i class="fas fa-folder-open md:pr-3 text-pink-400"></i>
                    <span class="text-xs md:text-base block md:inline-block">Projects</span>
                </a>
            </li>

            <!-- Sites -->
            <li class="mr-3 flex-1">
                <a href="../site/view.php"
                   class="block py-2 md:py-3 pl-1 align-middle no-underline border-b-2
                   <?= (strpos($_SERVER['REQUEST_URI'],'site') !== false)
                   ? 'text-white border-green-500'
                   : 'text-gray-300 hover:text-white border-slate-800 hover:border-green-500' ?>">
                    <i class="fas fa-map-marked-alt md:pr-3 text-green-400"></i>
                    <span class="text-xs md:text-base block md:inline-block">Sites</span>
                </a>
            </li>

            <!-- Phases -->
            <li class="mr-3 flex-1">
                <a href="../phases/list.php"
                   class="block py-2 md:py-3 pl-1 align-middle no-underline border-b-2
                   <?= (strpos($_SERVER['REQUEST_URI'],'phases') !== false)
                   ? 'text-white border-yellow-500'
                   : 'text-gray-300 hover:text-white border-slate-800 hover:border-yellow-500' ?>">
                    <i class="fas fa-layer-group md:pr-3 text-yellow-400"></i>
                    <span class="text-xs md:text-base block md:inline-block">Phases</span>
                </a>
            </li>

            <!-- Milestones -->
            <li class="mr-3 flex-1">
                <a href="../milestone/index.php"
                   class="block py-2 md:py-3 pl-1 align-middle no-underline border-b-2
                   <?= (strpos($_SERVER['REQUEST_URI'],'milestone') !== false)
                   ? 'text-white border-purple-500'
                   : 'text-gray-300 hover:text-white border-slate-800 hover:border-purple-500' ?>">
                    <i class="fas fa-flag-checkered md:pr-3 text-purple-400"></i>
                    <span class="text-xs md:text-base block md:inline-block">Milestones</span>
                </a>
            </li>

            <!-- Reports -->
            <li class="mr-3 flex-1">
                <a href="../report/index.php"
                   class="block py-2 md:py-3 pl-1 align-middle no-underline border-b-2
                   <?= (strpos($_SERVER['REQUEST_URI'],'report') !== false)
                   ? 'text-white border-blue-500'
                   : 'text-gray-300 hover:text-white border-slate-800 hover:border-blue-500' ?>">
                    <i class="fas fa-file-alt md:pr-3 text-blue-400"></i>
                    <span class="text-xs md:text-base block md:inline-block">Reports</span>
                </a>
            </li>

            <!-- Risks -->
            <li class="mr-3 flex-1">
                <a href="../risk/index.php"
                   class="block py-2 md:py-3 pl-1 align-middle no-underline border-b-2
                   <?= (strpos($_SERVER['REQUEST_URI'],'risk') !== false)
                   ? 'text-white border-red-500'
                   : 'text-gray-300 hover:text-white border-slate-800 hover:border-red-500' ?>">
                    <i class="fas fa-exclamation-triangle md:pr-3 text-red-400"></i>
                    <span class="text-xs md:text-base block md:inline-block">Risks</span>
                </a>
            </li>

            <!-- Logout -->
            <li class="mr-3 flex-1">
                <a href="../auth/logout.php"
                   class="block py-2 md:py-3 pl-1 align-middle text-gray-300 hover:text-white no-underline border-b-2 border-slate-800 hover:border-red-600">
                    <i class="fas fa-sign-out-alt md:pr-3 text-red-500"></i>
                    <span class="text-xs md:text-base block md:inline-block">Logout</span>
                </a>
            </li>

        </ul>
    </div>
</div>