<?php
@session_start(); // Oturumu aç
@session_destroy(); // Oturumu sonlandır
?>

<?php
@session_start();
if (!isset($_SESSION['isLogin'])) {
    // Oturum açmış
    header("location: index.php");
    die();
}
?>
<!-- <h1>Oturum sonlandı.</h1>

<div class='text-center'>
  <a href='login.php' class='btn btn-warning'>Yeniden Giriş Yap</a>
</div> -->

