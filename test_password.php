<?php
$input_password = 'password_admin'; // Password yang Anda masukkan di login
$stored_hash = '$2y$10$B.cWFrDRNsX02.46WgXfB.7s0iaAfVY1FSzFmnQhk7AmV2fi3Y8x6'; // Hash dari database

if (password_verify($input_password, $stored_hash)) {
    echo "Password cocok!";
} else {
    echo "Password tidak cocok!";
}
?>