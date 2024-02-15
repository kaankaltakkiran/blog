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
    //!htmlspecialchars()kullanıcıdan alınan veriyi güvenli hale getirir
    //! eğer kullanıcı zararlı bir kod gönderirse bunu html etiketlerine dönüştürür
    //?Form elemanları
    $categoryName = htmlspecialchars($_POST['form_categoryname']);
    //?Category var mı yok mu kontrol etme
    $sql = "SELECT * FROM categories WHERE categoryname = :form_categoryname";
    $SORGU = $DB->prepare($sql);
    $SORGU->bindParam(':form_categoryname', $categoryName);
    $SORGU->execute();
    $isCategory = $SORGU->fetch(PDO::FETCH_ASSOC);
    /*  echo '<pre>';
    print_r($isCategory);
    die(); */
    //!Eğer Category varsa hata mesajı göster
    if ($isCategory) {
        $errors[] = "Previously Added Category With This Name";

        //!Eğer Category yoksa kaydet
    } else {
        $sql = "INSERT INTO categories (categoryname) VALUES (:form_categoryname)";
        $SORGU = $DB->prepare($sql);
        $SORGU->bindParam(':form_categoryname', $categoryName);

        $SORGU->execute();
        $approves[] = "Category Added...";
    }
}
?>
    <div class="container">
  <div class="row justify-content-center mt-3">
  <div class="col-6">

<form method="POST">
<h1 class="alert alert-info text-center">Add Blog Category</h1>
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
//! Başarılı mesajlarını göster
if (!empty($approves)) {
    foreach ($approves as $approve) {
        echo '
      <div class="container">
  <div class="auto-close alert mt-3 text-center alert-success" role="alert">
  ' . $approve . '
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
  <input type="text"  class="form-control" name="form_categoryname" required>
  <label>Category Name</label>
</div>

                  <button type="submit" name="submit" class="btn btn-primary mt-3 ">Add Category</button>
     </form>
     </div>
</div>
</div>
<?php require 'modal.php';?>
  <?php require 'down.html.php';?>
