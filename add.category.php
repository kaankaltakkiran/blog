<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  </head>
  <body>
  <?php include 'navbar.php';?>
<?php
@session_start();
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
    $categoryName = $_POST['form_categoryname'];

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="./public/js/hideShow.js"></script>
    <script src="./public/js/autoCloseAlert.js"></script>
  </body>
</html>
