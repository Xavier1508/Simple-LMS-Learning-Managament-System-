<?php
$pageTitle = 'Courses';
require_once __DIR__ . '/../layouts/header.php';
?>

<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="flex h-screen">

        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 h-screen w-60 bg-white border-r border-gray-200 flex flex-col p-5">
            <h1 class="text-2xl font-bold mb-8">Cinau</h1>
            <nav class="flex flex-col gap-3">
                <a href="dashboard" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-orange-500">
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
                <a href="courses" class="flex items-center gap-3 px-3 py-2 rounded-md bg-orange-500 text-white font-medium">
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
                        <input type="text" placeholder="Search courses..."
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

            <!-- Page Header -->
            <div class="px-8 py-6">
                <h2 class="text-2xl font-bold text-gray-800">Available Courses 📚</h2>
            </div>

            <!-- Courses Grid -->
            <div class="grid grid-cols-3 gap-6 px-8 pb-8">
                <!-- Example course card -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-1">Secure Programming</h3>
                        <p class="text-gray-500 text-sm mb-3">Programming Secure</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-medium">13 Lessons</span>
                            <button class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm">View</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-1">Reverse Engineering</h3>
                        <p class="text-gray-500 text-sm mb-3">Apa ini Tuhanku</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-medium">13 Lessons</span>
                            <button class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm">View</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-1">Network Penetration Testing</h3>
                        <p class="text-gray-500 text-sm mb-3">Spam CTF sampai mati</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-medium">13 Lessons</span>
                            <button class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm">View</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-1">Entrepreneurship</h3>
                        <p class="text-gray-500 text-sm mb-3">Aku mau duit gratis</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-medium">13 Lessons</span>
                            <button class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm">View</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-1">Compilation Techniques</h3>
                        <p class="text-gray-500 text-sm mb-3">Teknik</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-medium">26 Lessons</span>
                            <button class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm">View</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-1">Blockchain Fundamentals</h3>
                        <p class="text-gray-500 text-sm mb-3">Aku nak bitcoin gratis</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-medium">13 Lessons</span>
                            <button class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm">Enroll</button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-1">Computer Forensics</h3>
                        <p class="text-gray-500 text-sm mb-3">Clear browsing history tidak berguna</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-medium">13 Lessons</span>
                            <button class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm">View</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-1">Operating Systems</h3>
                        <p class="text-gray-500 text-sm mb-3">Windows, Linux, macOS</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-medium">13 Lessons</span>
                            <button class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm">Enroll</button>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
