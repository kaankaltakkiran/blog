<?php
@session_start();
$activePage = "categoryies";
?>
 <?php
require_once 'db.php';
$id = $_GET["categoryid"];
/* SELECT blogs.*,users.*
FROM users
INNER JOIN blogs
ON blogs.writerid=users.userid WHERE blogs.categoryid=:categoryid */
$SORGU = $DB->prepare("SELECT blogs.*, users.*, categories.*
FROM users
INNER JOIN blogs ON blogs.writerid = users.userid
INNER JOIN categories ON blogs.categoryid = categories.categoryid where blogs.categoryid=:categoryid and ispublish = 1");
$SORGU->bindParam(':categoryid', $id);
$SORGU->execute();
$categoryies = $SORGU->fetchAll(PDO::FETCH_ASSOC);
/* echo '<pre>'; print_r($users);
die(); */
// Kontrol: Eğer kayıt yoksa hata sayfasına yönlendir
//!Birinci yöntem
/* if (empty($categoryies)) {
header("Location: category.error.php");
exit();
} */
if (empty($categoryies)) {
    echo "<script>
          alert('There is no record in the database. You are redirected to the home page...!');
          window.location.href = 'index.php';
        </script>";
    exit();
}
?>
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo $categoryies[0]['categoryname'] ?></title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    </head>
    <body>
    <!--  Navbar için navbar.php dosyasını include ediyoruz. -->
      <?php include 'navbar.php';?>

  <!--     Header Start -->
      <div class="container">
        <div class="row justify-content-center ">
          <div class="col-6">
        <h1 class='alert alert-primary mt-3 text-center'><?php echo $categoryies[0]['categoryname'] ?></h1>
        </div>
        </div>
  <!--     Header End -->
    <div class="row">
    <?php
foreach ($categoryies as $category) {
    echo "
   <div class='col-6'>
   <div class='card mb-4 shadow-sm'>
   <a href='blog.show.php?blogid={$category["blogid"]}'> <img class='card-img-top' src='images/{$category['blogimage']}' height='400' width='100%'  />
   </a>
     <div class='card-body'>
       <p class='card-text'>
         <h3>{$category['title']}</h3>
         <p>{$category['summary']}</p>
       </p>
       <div class='d-flex justify-content-between align-items-center'>
         <div class='btn-group'>
         <a class='btn btn-outline-success' href='blog.show.php?blogid={$category["blogid"]}'>Read More...</a>
         </div>
         <div style='float: right;'><span style='color: DimGray;'>Writer:</span>
         <a href='blog.writer.php?writerid={$category["userid"]}' class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover'>{$category['username']}</a>
         <br>
         <span style='color: DimGray;'>Category:</span> {$category['categoryname']}
     </div>
       </div>
     </div>
   </div>
   </div>
   ";
}
?>

    </div>
  </div>
      <?php include 'footer.php';?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
  </html>
