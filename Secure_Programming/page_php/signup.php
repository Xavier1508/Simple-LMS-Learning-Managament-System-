

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../page_styles/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="main_container">
        <form action = "signup.php" method = "post" enctype = "multipart/form-data">
            <div class="container_A">
                <div class="container_C">
                    <h1>Sign Up</h1>

                    <input 
                        type = "text" 
                        name = "nama_pertama_input" 
                        maxlength = "100"
                        value = "<?php $signup_controller->nama_pertama_temp(); ?>"
                        placeholder = "<?php echo $signup_controller->nama_pertama_placeholder; ?>"
                    >
                    <input 
                        type = "text" 
                        name = "nama_akhir_input" 
                        maxlength = "100"
                        value = "<?php $signup_controller->nama_akhir_temp(); ?>"
                        placeholder = "<?php echo $signup_controller->nama_akhir_placeholder; ?>"
                    >
                    <input 
                        type = "text" 
                        name = "tanggal_lahir_input" 
                        maxlength = "100"
                        value = "<?php $signup_controller->tanggal_lahir_temp(); ?>"
                        placeholder = "<?php echo $signup_controller->tanggal_lahir_placeholder; ?>"
                    >
                    <input 
                        type = "text" 
                        name = "handphone_input" 
                        maxlength = "13"
                        value = "<?php $signup_controller->handphone_temp(); ?>"
                        placeholder = "<?php echo $signup_controller->handphone_placeholder; ?>"
                    >
                    <input 
                        type = "text" 
                        name = "email_input" 
                        maxlength = "100"
                        value = "<?php $signup_controller->email_temp(); ?>"
                        placeholder = "<?php echo $signup_controller->email_placeholder; ?>"
                    >
                    <input 
                        type = "password" 
                        name = "pass_input" 
                        maxlength = "255"
                        value = "<?php $signup_controller->pass_temp(); ?>"
                        placeholder = "<?php echo $signup_controller->pass_placeholder; ?>"
                    >
                    <input 
                        type = "password" 
                        name = "konfirmasi_pass_input" 
                        maxlength = "255"
                        value = "<?php $signup_controller->konfirmasi_pass_temp(); ?>"
                        placeholder = "<?php echo $signup_controller->konfirmasi_pass_placeholder; ?>"
                    >
<!-- 
                    <input type="text" placeholder="Nama Pertama" required>
                    <input type="text" placeholder="Nama Akhir" required>
                    <input type="text" placeholder="Tanggal Lahir" required>
                    <input type="text" placeholder="No. Handphone" required>
                    <input type="text" placeholder="Email" required>
                    <input type="password" placeholder="Password" required>
                    <input type="password" placeholder="Konfirmasi Password" required> -->

                    <input name = "signup_button" type = "submit" value = "Sign Up" class = "signup_button">

                    <p class="terms">
                        By continuing, you agree to the 
                        <a href="#">Terms of Use</a> and 
                        <a href="#">Privacy Policy</a>.
                    </p>
                    <p class="extra_links">Sudah memiliki akun? <a href="login.php">Login</a></p>
                </div>
            </div>
            <div class="container_B">
                <img src = "<?=$signup_controller->display_photo; ?>" id = "profile_pic">
                <label for = "photo_input">Upload Image</label>
                <input 
                    type = "file"
                    name = "photo_input"
                    accept = "image/jpeg, image/png, image/jpg"
                    id = "photo_input"
                >
            </div>
        </form>
    </div>
    <script src = "../page_scripts/signup.js"></script>
</body>
</html>