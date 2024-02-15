<?php
@session_start();
$activePage = "index";
require 'up.html.php';
?>
  <?php include 'navbar.php';?>
<?php
//!Eğer admin kullnıcı dışında biri girmeye çalışırsa hata verdir
if (empty($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("location: authorizationcontrol.php");
    die();
}
//!form submit edilmişse
if (isset($_POST['submit'])) {
    //!Hata mesajlarını göstermek için boş bir dizi
    $errors = array();
    require_once 'db.php';
    //!htmlspecialchars() kullanıcıdan alınan veriyi güvenli hale getirir
    //! eğer kullanıcı zararlı bir kod gönderirse bunu html etiketlerine dönüştürür
    //?Form elemanları
    $name = htmlspecialchars($_POST['form_username']);
    $email = htmlspecialchars($_POST['form_email']);
    $gender = $_POST['form_gender'];
    $rePassword = $_POST['form_repassword'];
    $password = $_POST['form_password'];
/*  Şifrele hashleme */
    $password = password_hash($password, PASSWORD_DEFAULT);

    //?Kullanıcı var mı yok mu kontrol etme
    $sql = "SELECT * FROM users WHERE useremail = :form_email";
    $SORGU = $DB->prepare($sql);
    $SORGU->bindParam(':form_email', $email);
    $SORGU->execute();
    $isUser = $SORGU->fetch(PDO::FETCH_ASSOC);
    /*  echo '<pre>';
    print_r($isUser);
    die(); */
    //!Eğer kullanıcı üye olmuşsa  hata ver
    if ($isUser) {
        $errors[] = "This email is already registered";

        //!Eğer kullanıcı yoksa kaydet
    } else if ($rePassword != $password) {
        $errors[] = "Passwords do not match";
    } else {
        $sql = "INSERT INTO users (username,useremail,usergender,userpassword) VALUES (:form_username,:form_email,:form_gender,'$password')";
        $SORGU = $DB->prepare($sql);
        $SORGU->bindParam(':form_username', $name);
        $SORGU->bindParam(':form_email', $email);
        $SORGU->bindParam(':form_gender', $gender);

        $SORGU->execute();
        //!Kayıt başarılıysa login sayfasına yönlendir
        /* header("location: login.php"); */
        echo '
        <div class="container">
    <div class="auto-close alert mt-3 text-center alert-success" role="alert">
    User Added..
    </div>
    </div>
    ';
    }
}
?>
    <div class="container">
  <div class="row justify-content-center mt-3">
  <div class="col-6">

<form method="POST">
<h1 class="alert alert-info text-center">Add Writer User</h1>
<?php
//! Hata mesajlarını göster
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo '
        <div class="container">
    <div class="auto-close alert mt-3 text-center alert-danger" role="alert">
    ' . $error . '
    </div>
    </div>
    ';
    }
}
?>
  <div class="form-floating mb-3">
  <input type="text"  class="form-control" value="<?php echo $_SESSION['userName'] ?>"disabled readonly>
  <label>Added By Admin Name</label>
</div>
<div class="form-floating mb-3">
  <input type="text"  class="form-control" name="form_username" required>
  <label>UserName</label>
</div>
  <div class="form-floating mb-3">
  <input type="email" name="form_email"class="form-control"required>
  <label>Email</label>
</div>
<div class="input-group mb-3  input-group-lg">
  <input type="password"  name="form_password" class="form-control" id="password" placeholder="Password"required>
  <span class="input-group-text bg-transparent"><i id="togglePassword" class="bi bi-eye-slash"></i></span>
</div>
<div class="input-group mb-3  input-group-lg">
  <input type="password"  name="form_repassword" class="form-control" id="password2" placeholder="Please Enter Your Password Again"required>
  <span class="input-group-text bg-transparent"><i id="togglePassword2" class="bi bi-eye-slash"></i></span>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="form_gender" value="M" required >
  <label class="form-check-label" >
  Male
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="form_gender" value="F" required>
  <label class="form-check-label" >
  Female
  </label>
</div>

                  <button type="submit" name="submit" class="btn btn-primary mt-3 ">Add Writer</button>
     </form>
     </div>
</div>
</div>
<?php require 'modal.php';?>
<?php include 'footer.php';?>
  <?php require 'down.html.php';?>
