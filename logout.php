<?php
    session_start();
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
    if(isset($_COOKIE['foodshare_user_remember_username'])){
        setcookie('foodshare_user_remember_username', "", time()-1, '/', $domain, false);
    }
    if(isset($_COOKIE['foodshare_admin_remember_username'])){
        setcookie('foodshare_admin_remember_username', "", time()-1, '/', $domain, false);
    }
    session_destroy();
    header('location: login.php');
?>