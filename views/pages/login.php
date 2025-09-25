<?php
$pageTitle = 'Login';
$cssFile = 'login.css';
$jsFile = 'login.js';
//Panggil header (ini uat buka tag <html>, <head>, <body> sama aja kek buka html biasa)
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="main_container">
    <form class="login_form" method="POST" action="">
        <h1>Log In</h1>
        <input type="text" name="email_input" placeholder="Email" required>
        <input type="password" name="password_input" placeholder="Password" required>
        
        <div class="remember">
            <input type="checkbox" id="remember">
            <label for="remember">Remember me</label>
        </div>

        <input type="submit" name="login_button" value="Log in" class="login_button">

        <p class="terms">
            By continuing, you agree to the <a href="/terms">Terms of Use</a> and <a href="/terms">Privacy Policy</a>.
        </p>

        <p class="extra_links"><a href="#">Forgot Password</a></p>
        <p class="extra_links">Haven't registered yet? <a href="/signup">Sign up</a></p>
    </form>
</div>

<?php
//Panggil lagi aja footernya (ini buat tutup tag </body> dan </html> sama aja)
require_once __DIR__ . '/../layouts/footer.php';
?>