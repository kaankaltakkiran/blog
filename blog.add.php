<?php
@session_start();
$activePage = "blogAdd";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Add</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
    <?php include 'navbar.php';?>
  <div class="container">
  <div class="row justify-content-center mt-3">
  <div class="col-6">

<form method="POST">
<h1 class="alert alert-success text-center">Yazı Ekleme Formu</h1>
  <div class="form-floating mb-3">
  <input type="text"  class="form-control" value="<?php echo $_SESSION['userName'] ?>"disabled readonly>
  <label>Yazıyı Ekleyen</label>
</div>
<div class="form-floating mb-3">
  <input type="text" name="form_title"  class="form-control">
  <label>Başlık</label>
</div>
<div class="form-floating mb-3">
  <input type="text" name="form_summary"  class="form-control">
  <label>Kısa Özet</label>
</div>
<div class="form-floating mb-3">
  <input type="text" name="form_date"  class="form-control">
  <label>Yayın Tarihi</label>
</div>
<div class="form-floating mb-3">
  <textarea class="form-control" name="form_content" id="floatingTextarea"></textarea>
  <label for="floatingTextarea">İçerik</label>
</div>
                  <button type="submit" name="submit" class="btn btn-primary">Ekle</button>
     </form>
     </div>
</div>

</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>