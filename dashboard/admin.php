<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Include the modular parts
include('../includes/header.php');
include('../includes/sidebar.php');
?>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    <div class="bg-white p-4 shadow-sm flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase pl-4">Admin Overview</h1>
        <div class="pr-4 flex items-center">
            <span class="text-gray-600 mr-2">Hello, <strong><?php echo $_SESSION['name']; ?></strong></span>
            <img class="w-10 h-10 rounded-full border-2 border-blue-500" src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['name']; ?>" alt="Admin Profile">
        </div>
    </div>

    <div class="flex flex-wrap px-4">
        <div class="w-full md:w-1/2 xl:w-1/3 p-3">
            <div class="bg-white border rounded shadow p-5 border-l-4 border-blue-500 hover:shadow-lg transition">
                <div class="flex flex-row items-center">
                    <div class="flex-shrink pr-4"><div class="rounded p-3 bg-blue-600"><i class="fa fa-wallet fa-2x fa-fw fa-inverse"></i></div></div>
                    <div class="flex-1 text-right md:text-center">
                        <h5 class="font-bold uppercase text-gray-500">Active Sites</h5>
                        <h3 class="font-bold text-3xl">14</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 xl:w-1/3 p-3">
            <div class="bg-white border rounded shadow p-5 border-l-4 border-red-500">
                <div class="flex flex-row items-center">
                    <div class="flex-shrink pr-4"><div class="rounded p-3 bg-red-600"><i class="fas fa-exclamation-triangle fa-2x fa-fw fa-inverse"></i></div></div>
                    <div class="flex-1 text-right md:text-center">
                        <h5 class="font-bold uppercase text-gray-500">High Risks</h5>
                        <h3 class="font-bold text-3xl">02</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 xl:w-1/3 p-3">
            <div class="bg-white border rounded shadow p-5 border-l-4 border-yellow-500">
                <div class="flex flex-row items-center">
                    <div class="flex-shrink pr-4"><div class="rounded p-3 bg-yellow-600"><i class="fas fa-clipboard-list fa-2x fa-fw fa-inverse"></i></div></div>
                    <div class="flex-1 text-right md:text-center">
                        <h5 class="font-bold uppercase text-gray-500">Pending Reviews</h5>
                        <h3 class="font-bold text-3xl">07</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Recent Project Logs</h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-600 border-b border-gray-200">
                        <th class="pb-3 px-2">Project Name</th>
                        <th class="pb-3 px-2">Status</th>
                        <th class="pb-3 px-2">Completion</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-2">City Center Plaza</td>
                        <td class="py-3 px-2"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">On Schedule</span></td>
                        <td class="py-3 px-2">65%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>