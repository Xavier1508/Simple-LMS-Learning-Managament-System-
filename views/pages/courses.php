<?php
$pageTitle = 'Courses';
$cssFile = 'courses.css';
$jsFile = 'courses.js';
require_once __DIR__ . '/../layouts/header.php';

//course dummy, kalo databasenya udah jadi diganti aja
$courses = [
    ["id" => 1, "title" => "Operating Systems", "description" => "Windows, Linux, macOS"],
    ["id" => 2, "title" => "Secure Programming", "description" => "Programming secure."],
    ["id" => 3, "title" => "Compilation Techniques", "description" => "Teknik kompilasi"],
    ["id" => 4, "title" => "Network Penetration Testing", "description" => "Pentest bersama Ko CP :)"],
    ["id" => 5, "title" => "Blockchain Technology", "description" => "Aku mau bitcoin gratis"],
    ["id" => 6, "title" => "Entrepreneurship", "description" => "Aku mau duit gratis"],
    ["id" => 7, "title" => "Computer Forensics", "description" => "Kapolres garuda dikeroyok warga"],
    ["id" => 8, "title" => "Reverse Engineering", "description" => "Assembly :("],
];
?>

<div class="layout">
    <header class="topbar">
        <div class="logo">LMS</div>
    </header>

    <!-- sidebar -->
    <nav class="sidebar">
        <ul>
            <li><a href="/home">Dashboard</a></li>
            <li><a href="/courses" class="active">Courses</a></li>
            <li><a href="/calendar">Calendar</a></li>
            <li><a href="/forum">Forum</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <h1>Available Courses</h1>
        <div class="courses-grid">
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                    <p><?php echo htmlspecialchars($course['description']); ?></p>
                    <a href="course.php?id=<?php echo $course['id']; ?>" class="btn">View Course</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
