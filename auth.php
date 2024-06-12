<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    // Her oturum başlangıcında oturum kimliğini yenile 
    // Session fixation zafiyetini giderir.
    session_regenerate_id(true);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit();
}

if (!function_exists('checkRole')) {
    function checkRole($roles) {
        if (!in_array($_SESSION['role'], $roles)) {
            echo "Bu sayfaya erişim izni yok.";
            exit();
        }
    }
}
?>