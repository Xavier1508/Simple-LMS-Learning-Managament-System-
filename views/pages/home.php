<?php

//INI DASHBOARD YG LAMA TANPA TAILWIND
$pageTitle = 'Dashboard';
$cssFile = 'home.css';
$jsFile = 'home.js';
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = "Demo User";
}

$upcomingClass = [
    "id" => 4,
    "title" => "Network Penetration Testing",
    "time" => "Tuesday, 9.20",
    "lecturer" => "Christopher Limawan"
];
?>

<div class="layout">
    <header class="topbar">
        <div class="logo">LMS</div>
    </header>

    <!-- navbar samping -->
    <nav class="sidebar">
        <ul>
            <li><a href="/home">Dashboard</a></li>
            <li><a href="/courses">Courses</a></li>
            <li><a href="/calendar">Calendar</a></li>
            <li><a href="/forum">Forum</a></li>
        </ul>
    </nav>

    <main class="dashboard">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <h2>Upcoming Class</h2>

        <div class="class-card">
            <h3><?php echo htmlspecialchars($upcomingClass['title']); ?></h3>
            <p><strong>Time:</strong> <?php echo htmlspecialchars($upcomingClass['time']); ?></p>
            <p><strong>Lecturer:</strong> <?php echo htmlspecialchars($upcomingClass['lecturer']); ?></p>
            <a href="class.php?id=<?php echo $upcomingClass['id']; ?>" class="btn">View Details</a>
        </div>
    </main>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
