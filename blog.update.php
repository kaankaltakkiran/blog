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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
$isPublis = $blog['ispublish'];
/* echo "<pre>";
print_r($blogs);
die(); */
//!Eğer blog silme isteği gelirse silme işlemini yap ve tekrardan yazar sayfasına yönlendir.
if (isset($_GET['remove'])) {
    require 'db.php';
    $remove_id = $_GET['remove'];
    $id = $_SESSION['id'];

    $sql = "DELETE FROM blogs WHERE blogid = :remove";
    $SORGU = $DB->prepare($sql);

    $SORGU->bindParam(':remove', $remove_id);

    $SORGU->execute();
    echo "<script>
  alert('The blog has been deleted. You are redirected to the Writer page...!');
  window.location.href = 'blog.writer.php?writerid={$id}';
  </script>";
}
//! Giriş yapan kullanıcı id'si ile blog yazarının id'si aynı değilse yetkilendirme hatası ver
if ($_SESSION['id'] !== $blog['writerid']) {
    //!Yetkilendirme hatası durumunda bir hata sayfasına yönlendir
    header("Location: authorizationControl.php");
    exit();
}
if (isset($_POST['form_submit'])) {
    //!htmlspecialchars() kullanıcıdan alınan veriyi güvenli hale getirir
    //! eğer kullanıcı zararlı bir kod gönderirse bunu html etiketlerine dönüştürür
    //?Form elemanları
    $title = htmlspecialchars($_POST['form_title']);
    $categoryId = $_POST['form_category'];
    $summary = htmlspecialchars($_POST['form_summary']);
    $blogStartDate = $_POST['form_startdate'];
    $blogLastDate = $_POST['form_lastdate'];
    //?checkbox işaretli ise 1 değilse 0
    $isPublish = isset($_POST['form_ispublish']) ? 1 : 0;
    $content = htmlspecialchars($_POST['form_content']);

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
                $sql = "UPDATE blogs SET title = :form_title, categoryid	 = :form_category, summary=:form_summary,startdate=:form_startdate,lastdate=:form_lastdate,ispublish=:form_ispublish,content=:form_content, blogimage = '$new_img_name' WHERE blogid = :blogid";
            } else {
                $errors[] = "You can't upload files of this type";
            }
        }
    } else {
        //!Foto güncellemediysen eski fotoğrafı kullan
        $sql = "UPDATE blogs SET title = :form_title, categoryid	 = :form_category, summary=:form_summary,startdate=:form_startdate,lastdate=:form_lastdate,ispublish=:form_ispublish,content=:form_content WHERE blogid = :blogid";
    }
    //! Hata yoksa veritabanına kaydet
    if (empty($errors)) {
        $SORGU = $DB->prepare($sql);

        $SORGU->bindParam(':form_title', $title);
        $SORGU->bindParam(':form_category', $categoryId);
        $SORGU->bindParam(':form_summary', $summary);
        $SORGU->bindParam(':form_startdate', $blogStartDate);
        $SORGU->bindParam(':form_lastdate', $blogLastDate);
        $SORGU->bindParam(':form_ispublish', $isPublish);
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
<div class="mb-3">
<div class="form-check form-switch">
  <input class="form-check-input" <?php echo ($isPublis == 1) ? 'checked' : ''; ?>  name='form_ispublish' type="checkbox" role="switch" id="flexSwitchCheckChecked">
  <label class="form-check-label" for="flexSwitchCheckChecked">Publish My Blog</label>
</div>
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
     <!-- Modal  Start-->
     <div class='modal fade' id='staticBackdrop' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
       <div class='modal-dialog'>
         <div class='modal-content'>
           <div class='modal-header'>
             <h1 class='modal-title fs-5' id='exampleModalLabel'>Delete <?php echo $blog['title']; ?>? </h1>
             <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
           </div>
           <div class='modal-body'>
           <?php echo $_SESSION['userName'] ?>, Are you sure you want to delete the blog?
           </div>
           <div class='modal-footer'>
             <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
             <a href='blog.writer.php?remove=<?php echo $blog['blogid'] ?>' class='btn btn-danger'>Delete Blog </a>
           </div>
         </div>
       </div>
     </div>
 <!-- Modal End -->
     <p class='text-end '><a href='blog.update.php?remove=<?php echo $blog['blogid'] ?>' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#staticBackdrop'>Delete Blog
   <i class='bi bi-trash'></i>
   </a></p>
     </div>

</div>

</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="./public/js/autoCloseAlert.js"></script>
  </body>
</html>