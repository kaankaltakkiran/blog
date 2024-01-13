<?php
@session_start();
$activePage = "blogAdd";
?>
<?php
if (isset($_POST['submit']) && isset($_FILES['form_image'])) {
    require_once 'db.php';
    //!Form elemanları
    $writerId = $_SESSION['id'];
    $title = $_POST['form_title'];
    $categoryId = $_POST['form_category'];
    $summary = $_POST['form_summary'];
    $blogDate = $_POST['form_date'];
//!Checkbox değeri kontrolü
    //?checkbox işaretli ise 1 değilse 0
    $isPublish = isset($_POST['form_ispublish']) ? 1 : 0;
    $content = $_POST['form_content'];
    //!Resim yükleme
    $img_name = $_FILES['form_image']['name'];
    $img_size = $_FILES['form_image']['size'];
    $tmp_name = $_FILES['form_image']['tmp_name'];
    $error = $_FILES['form_image']['error'];
    // Hata kontrolü
    $errors = array();

    if ($error === 0) {
        //!Resim boyutu kontrolü gözden geçmeli
        if ($img_size < 0) {
            $errors[] = "Sorry, your file is too large.";
        } else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            //! Resim türü kontrolü
            $allowed_exs = array("jpg", "jpeg", "png");

            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                $img_upload_path = 'images/' . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);

                // Insert into Database
                $sql = "INSERT INTO blogs (writerid, title,categoryid,summary,blogdate,ispublish,content,blogimage) VALUES (:id, :form_title,:form_category,:form_summary,:form_date,:form_ispublish,:form_content,'$new_img_name')";
                $SORGU = $DB->prepare($sql);

                $SORGU->bindParam(':id', $writerId);
                $SORGU->bindParam(':form_title', $title);
                $SORGU->bindParam(':form_category', $categoryId);
                $SORGU->bindParam(':form_summary', $summary);
                $SORGU->bindParam(':form_date', $blogDate);
                $SORGU->bindParam(':form_ispublish', $isPublish);
                $SORGU->bindParam(':form_content', $content);

                $SORGU->execute();
                $approves[] = "Blog Added...";
            } else {
                $errors[] = "You can't upload files of this type";
            }
        }
    } else {
        /*     $errors[] = "unknown error occurred!"; */
        $errors[] = "Image Not Selected";
    }

}
require_once 'db.php';
$id = $_GET["idUser"];
$SORGU = $DB->prepare("SELECT * FROM users WHERE userid=:idUser");
$SORGU->bindParam(':idUser', $id);
$SORGU->execute();
$user = $SORGU->fetchAll(PDO::FETCH_ASSOC);
/* echo '<pre>';
print_r($user);
die(); */
if ($_SESSION['id'] !== $user[0]['userid']) {
    //!Yetkilendirme hatası durumunda bir hata sayfasına yönlendir
    header("Location: authorizationControl.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Add Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
    <?php include 'navbar.php';?>
  <div class="container">
  <div class="row justify-content-center mt-3">
  <div class="col-6">

<form method="POST"enctype="multipart/form-data">
<h1 class="alert alert-info text-center">Blog Add Form</h1>
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
<?php
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
  <label>Writer</label>
</div>
<div class="form-floating mb-3">
  <input type="text" name="form_title"  class="form-control"required>
  <label>Title</label>
</div>
<?php
require_once 'db.php';
$sql = "SELECT * FROM categories";
$SORGU = $DB->prepare($sql);
$SORGU->execute();
$categories = $SORGU->fetchAll(PDO::FETCH_ASSOC);

/* var_dump($categories);
die(); */

$optionCategories = "";
foreach ($categories as $category) {
    $optionCategories = $optionCategories . "<option value='{$category['categoryid']}'>{$category['categoryname']}</option>";
}

?>
<div class="form-floating mb-3">
<select class="form-select" name="form_category"required>
<option disabled selected value="">Select Category</option>
      <?php echo $optionCategories; ?>
    </select>
</div>
<div class="form-floating mb-3">
  <input type="text" name="form_summary"  class="form-control"required>
  <label>Short Summary</label>
</div>
<!-- <div class="form-floating mb-3">
  <input type="text" name="form_date"  class="form-control"required>
  <label>Yayın Tarihi</label>
</div> -->
<div class="form-floating mb-3">
<div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Publish Date</label>
  <input type="date" name="form_date" class="form-control" id="exampleFormControlInput1"  min="<?php echo date('Y-m-d'); ?>" />
</div>
</div>
<div class="mb-3">
<div class="form-check form-switch">
  <input class="form-check-input" value="0" name='form_ispublish' type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
  <label class="form-check-label" for="flexSwitchCheckChecked">Publish My Blog</label>
</div>
</div>
<div class="mb-3">
<label for="exampleFormControlInput2" class="form-label">Content</label>
  <textarea id="exampleFormControlInput2"  rows="15" cols="85" name="form_content" id="floatingTextarea"required>
  </textarea>
</div>
<div class="input-group mb-3">
  <input type="file"  name='form_image' class="form-control" id="inputGroupFile02"required>
  <label class="input-group-text" for="inputGroupFile02">Upload Blog Image</label>
</div>
                  <button type="submit" name="submit" class="btn btn-primary mb-3">Add Blog</button>
     </form>
     </div>
</div>

</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="./public/js/autoCloseAlert.js"></script>
  </body>
</html>