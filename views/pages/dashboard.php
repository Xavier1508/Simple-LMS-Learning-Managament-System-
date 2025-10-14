<?php
//DASHBOARD BARU, PAKE TAILWIND
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../layouts/header.php';
?>

<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="flex h-screen">

        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 h-screen w-60 bg-white border-r border-gray-200 flex flex-col p-5">
            <h1 class="text-2xl font-bold mb-8">Cinau</h1>
            <nav class="flex flex-col gap-3">
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md bg-orange-500 text-white font-medium">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                <a href="assignments" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-orange-500">
                    <i class="fa-solid fa-book"></i> Assignments
                </a>
                <a href="schedule" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-orange-500">
                    <i class="fa-solid fa-calendar"></i> Schedule
                </a>
                <a href="discussions" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-orange-500">
                    <i class="fa-solid fa-comments"></i> Discussions
                </a>
                <a href="notes" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-orange-500">
                    <i class="fa-solid fa-note-sticky"></i> Notes
                </a>
                <a href="classes" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-orange-500">
                    <i class="fa-solid fa-chalkboard-user"></i> Classes
                </a>
                <a href="courses" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-orange-500">
                    <i class="fa-solid fa-book-open"></i> Courses
                </a>
                <a href="settings" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-orange-500">
                    <i class="fa-solid fa-gear"></i> Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col ml-60 h-screen overflow-y-auto">

            <!-- Topbar -->
            <div class="sticky top-0 z-10 flex items-center justify-between bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center gap-3 w-1/2">
                    <div class="relative w-full">
                        <input type="text" placeholder="Search"
                               class="w-full border border-gray-300 rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <button class="text-xl">🔔</button>
                    <div class="flex items-center gap-2 cursor-pointer">
                        <img src="https://via.placeholder.com/32" class="w-8 h-8 rounded-full" alt="Avatar">
                        <span>Christopher ▼</span>
                    </div>
                </div>
            </div>

            <!-- Dashboard Header -->
            <div class="px-8 py-6">
                <h2 class="text-2xl font-bold text-gray-800">Hello Christopher 👋</h2>
                <p class="text-gray-500">Let's learn something new today!</p>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-4 gap-6 px-8 pb-8">

                <!-- Recent Course -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <h3 class="font-semibold mb-3">Recent enrolled course</h3>
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-orange-100 rounded-lg text-orange-500">📐</div>
                        <div>
                            <p class="font-semibold text-gray-800">Product Design Course</p>
                            <div class="h-2 bg-gray-200 rounded-full mt-2 mb-1">
                                <div class="h-2 bg-orange-500 rounded-full" style="width: 40%;"></div>
                            </div>
                            <p class="text-sm text-gray-500">14/30 class</p>
                        </div>
                    </div>
                </div>

                <!-- Your Informations -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm col-span-1">
                    <h3 class="font-semibold mb-3">Your Informations</h3>
                    <div class="flex flex-col gap-3">
                        <div>
                            <p class="font-semibold text-gray-800">Breaking News Christopher Attack Kominfo</p>
                            <p class="text-sm text-gray-500">Happening this noon at lalala</p>
                            <p class="text-xs text-gray-400">11 Sep 2025</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Xavier found a vulnerability</p>
                            <p class="text-sm text-gray-500">It is said that xavier is a man that does things</p>
                            <p class="text-xs text-gray-400">25 Sep 2025</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">YEAAHHHHH</p>
                            <p class="text-sm text-gray-500">YEAHHHHHHHHHH</p>
                            <p class="text-xs text-gray-400">25 Sep 2025</p>
                        </div>
                    </div>
                </div>

                <!-- Hours Spent -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <h3 class="font-semibold mb-3">Hours Spent</h3>
                    <div class="flex items-end justify-around h-32">
                        <div class="w-6 bg-orange-400 rounded-t" style="height: 60%;"></div>
                        <div class="w-6 bg-orange-300 rounded-t" style="height: 80%;"></div>
                        <div class="w-6 bg-orange-400 rounded-t" style="height: 50%;"></div>
                        <div class="w-6 bg-orange-300 rounded-t" style="height: 70%;"></div>
                    </div>
                    <p class="text-sm text-gray-500 mt-3">Study: 40 hrs | Online Test: 35 hrs</p>
                </div>

                <!-- Performance -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <h3 class="font-semibold mb-3">Performance</h3>
                    <div class="w-24 h-24 rounded-full border-8 border-green-500 mx-auto"></div>
                    <p class="text-center mt-2 text-gray-600">Assignment Submission performance</p>
                </div>

                <!-- To-do List -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <h3 class="font-semibold mb-3">To do List</h3>
                    <ul class="space-y-2">
                        <li><input type="checkbox" class="mr-2">Secure Programming (Tuesday, 30 June 2025)</li>
                        <li><input type="checkbox" class="mr-2">Reverse Engineering (Monday, 24 June 2025)</li>
                        <li><input type="checkbox" class="mr-2">Penetration Testing (Friday, 10 June 2025)</li>
                        <li><input type="checkbox" class="mr-2">Entrepreneurship (Friday, 10 June 2025)</li>
                    </ul>
                </div>

                <!-- Recent Classes -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm col-span-2">
                    <h3 class="font-semibold mb-3">Recent enrolled classes</h3>
                    <div class="flex justify-between items-center border border-orange-400 rounded-lg p-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="text-2xl">👨‍🏫</div>
                            <div>
                                <p class="font-bold text-orange-500">Secure Programming</p>
                                <p class="text-sm text-gray-500">5:30hrs | 5 Lessons | Assignments</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center gap-3">
                            <div class="text-2xl">🧠</div>
                            <div>
                                <p class="font-bold text-gray-700">Reverse Engineering apa ini tuhanku</p>
                                <p class="text-sm text-gray-500">4:00hrs | 3 Lessons | Assignments</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Lesson -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <h3 class="font-semibold mb-3">Upcoming Lesson</h3>
                    <div class="flex justify-between items-center border border-gray-200 rounded-lg p-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="text-2xl">🎓</div>
                            <div>
                                <p class="font-bold text-gray-800">Belajar Ngaji</p>
                                <p class="text-sm text-gray-500">5:30pm</p>
                            </div>
                        </div>
                        <button class="bg-orange-500 text-white px-4 py-1 rounded-md">Join</button>
                    </div>
                    <div class="flex justify-between items-center border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center gap-3">
                            <div class="text-2xl">🎓</div>
                            <div>
                                <p class="font-bold text-gray-800">Belajar membaca Firman</p>
                                <p class="text-sm text-gray-500">9:00pm</p>
                            </div>
                        </div>
                        <button class="bg-orange-500 text-white px-4 py-1 rounded-md">Join</button>
                    </div>
                </div>

            </div>
        </main>
    </div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
