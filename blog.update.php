<?php
@session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Update</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
    <?php include 'navbar.php';?>
  <div class="container">
  <div class="row justify-content-center mt-3">
  <div class="col-6">

<form method="POST"enctype="multipart/form-data">
<h1 class="alert alert-info text-center">Blog Update</h1>
<?php
require_once 'db.php';

$id = $_GET['blogid'];

$sql = "SELECT * FROM blogs WHERE blogid = :blogid";
$SORGU = $DB->prepare($sql);

$SORGU->bindParam(':blogid', $id);

$SORGU->execute();

$blogs = $SORGU->fetchAll(PDO::FETCH_ASSOC);
$blog = $blogs[0];
/* echo "<pre>";
print_r($blogs);
die(); */
//! Giriş yapan kullanıcı id'si ile blog yazarının id'si aynı değilse yetkilendirme hatası ver
if ($_SESSION['id'] !== $blog['writerid']) {
    //!Yetkilendirme hatası durumunda bir hata sayfasına yönlendir
    header("Location: authorizationControl.php");
    exit();
}
if (isset($_POST['form_submit'])) {

    //!Form elemanları
    $title = $_POST['form_title'];
    $categoryId = $_POST['form_category'];
    $summary = $_POST['form_summary'];
    $blogStartDate = $_POST['form_startdate'];
    $blogLastDate = $_POST['form_lastdate'];
    $content = $_POST['form_content'];

    $id = $_GET['blogid'];

    $img_name = $_FILES['form_image']['name'];
    $img_size = $_FILES['form_image']['size'];
    $tmp_name = $_FILES['form_image']['tmp_name'];
    $error = $_FILES['form_image']['error'];

    // Hata kontrolü
    $errors = array();
    //!Eski fotoğraf adını al
    $old_img_name = $blog['blogimage'];

    if ($error === 0) {
        //!Resim boyutlarını gözden geçir
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
                //? Eğer yeni fotoğraf yüklendiyse eski fotoğrafı sil
                //?unlink dosya silmek için kullanılır
                unlink('images/' . $old_img_name);
                //!Foto güncellediyse veritabanına yeni fotoğraf adını kaydet
                $sql = "UPDATE blogs SET title = :form_title, categoryid	 = :form_category, summary=:form_summary,startdate=:form_startdate,lastdate=:form_lastdate,content=:form_content, blogimage = '$new_img_name' WHERE blogid = :blogid";
            } else {
                $errors[] = "You can't upload files of this type";
            }
        }
    } else {
        //!Foto güncellemediysen eski fotoğrafı kullan
        $sql = "UPDATE blogs SET title = :form_title, categoryid	 = :form_category, summary=:form_summary,startdate=:form_startdate,lastdate=:form_lastdate,content=:form_content WHERE blogid = :blogid";
    }
    //! Hata yoksa veritabanına kaydet
    if (empty($errors)) {
        $SORGU = $DB->prepare($sql);

        $SORGU->bindParam(':form_title', $title);
        $SORGU->bindParam(':form_category', $categoryId);
        $SORGU->bindParam(':form_summary', $summary);
        $SORGU->bindParam(':form_startdate', $blogStartDate);
        $SORGU->bindParam(':form_lastdate', $blogLastDate);
        $SORGU->bindParam(':form_content', $content);

        $SORGU->bindParam(':blogid', $id);
        $SORGU->execute();
        echo '<script>';
        echo 'alert("Blog Update Successful!");';
        echo 'window.location.href = "blog.update.php?blogid=' . $blog['blogid'] . '";';
        echo '</script>';
    }
}

?>
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
  <label>Yazıyı Ekleyen</label>
</div>
<div class="form-floating mb-3">
  <input type="text" name="form_title" value="<?php echo $blog['title']; ?>"  class="form-control"required>
  <label>Başlık</label>
</div>
<?php
require_once 'db.php';
$sql = "SELECT * FROM categories";
$SORGU = $DB->prepare($sql);
$SORGU->execute();
$categories = $SORGU->fetchAll(PDO::FETCH_ASSOC);
//!Database'den gelen seçili kategori
$selectedCategoryId = $blog['categoryid'];
/* var_dump($categories);
die(); */

?>
<div class="form-floating mb-3">
<select class="form-select" name="form_category" required>
        <option disabled value="">Select Category</option>
        <!-- Chatgpt çözümü seçili categoriyi getirme ve listeleme -->
        <?php
foreach ($categories as $category) {
    $selected = ($category['categoryid'] == $selectedCategoryId) ? 'selected' : '';
    echo "<option value='{$category['categoryid']}' $selected>{$category['categoryname']}</option>";
}
?>
    </select>
</div>
<div class="form-floating mb-3">
  <input type="text" name="form_summary"  value="<?php echo $blog['summary']; ?>" class="form-control"required>
  <label>Kısa Özet</label>
</div>
<div class="form-floating mb-3">
<div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Start Publish Date</label>
  <input type="date" name="form_startdate" value="<?php echo $blog['startdate']; ?>" class="form-control" id="exampleFormControlInput1"  />
</div>
</div>
<div class="form-floating mb-3">
<div class="mb-3">
  <label for="exampleFormControlInput2" class="form-label">Last Published Date</label>
  <input type="date" name="form_lastdate" value="<?php echo $blog['lastdate']; ?>" class="form-control" id="exampleFormControlInput2"  min="<?php echo date('Y-m-d'); ?>" />
</div>
</div>
<div class="mb-3">
<label for="exampleFormControlInput2" class="form-label">Content</label>
  <textarea id="exampleFormControlInput2"  rows="15" cols="85" name="form_content" id="floatingTextarea"required>
  <?php echo $blog['content']; ?>
  </textarea>
</div>
<label>Blog Image</label>
                        <img src="images/<?php echo $blog['blogimage']; ?>" alt="User Image" class="img-thumbnail">
<div class="input-group mb-3">
  <input type="file"  name='form_image' class="form-control" id="inputGroupFile02">
  <label class="input-group-text" for="inputGroupFile02">Upload Blog Image</label>
</div>

                  <button type="submit" name="form_submit" class="btn btn-primary mb-3">Update Blog</button>
     </form>
     </div>
</div>

</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="./public/js/autoCloseAlert.js"></script>
  </body>
</html>