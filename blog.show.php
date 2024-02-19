<?php
@session_start();
$activePage = "index";
require 'up.html.php';
require_once 'db.php';
$id = $_GET["blogid"];

/* SELECT blogs.*,users.*
FROM users
INNER JOIN blogs
ON blogs.writerid=users.userid WHERE users.userid=:writerid */
$SORGU = $DB->prepare("SELECT blogs.*, users.*, categories.*
FROM users
INNER JOIN blogs ON blogs.writerid = users.userid
INNER JOIN categories ON blogs.categoryid = categories.categoryid WHERE blogs.blogid=:blogid");
$SORGU->bindParam(':blogid', $id);
$SORGU->execute();
$blog = $SORGU->fetchAll(PDO::FETCH_ASSOC);
/* echo '<pre>';
print_r($blog);
die(); */
$sql = "UPDATE blogs SET readcount =readcount + 1  WHERE blogid=:blogid";
$SORGU = $DB->prepare($sql);
$SORGU->bindParam(':blogid', $id);
$SORGU->execute();
//!Format date
$startDate = new DateTime($blog[0]['startdate']);
$formattedStartDate = $startDate->format('d-m-Y');

//! Eğer urlden başka id yazıp bloga girmeye çalışırsa ve bu blog ispublish = 0 ise hata sayfasına yönlendir(No publish)
if ($_SESSION['id'] !== $blog[0]['userid'] and $blog[0]['ispublish'] == 0) {
    echo "<script>
        alert('This Blog is not published. You are redirected to the home page...!');
        window.location.href = 'index.php';
      </script>";
    exit();
}
//!Sadece blog sahibi yayından kaldırılan blogu görebilir
if ($_SESSION['id'] !== $blog[0]['userid'] and (strtotime($blog[0]['startdate']) > time() || strtotime($blog[0]['lastdate']) < time())) {
    echo "<script>
      alert('This blog has been removed from its publication dates.!!! You are redirected to the home page.!!!');
      window.location.href = 'index.php';
    </script>";
    exit();
}
?>
    <!--  Navbar için navbar.php dosyasını include ediyoruz. -->
      <?php include 'navbar.php';?>
  <div class="container mt-4">
      <div class="row justify-content-center ">
       <div class="col-6 border">
           <img class='img-thumbnail m-1 rounded float-left' src='images/<?php echo $blog[0]['blogimage']; ?>'/>
       <h1 class="text-danger text-center"><?php echo $blog[0]['title']; ?></h1>
            <div style='float: left;'><span style='color: DimGray;'>Writer:</span>
          <a href='blog.writer.php?writerid=<?php echo $blog[0]['userid']; ?>' class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover'><?php echo $blog[0]['username']; ?></a>
      </div>
      <span style='color: DimGray; margin-left: 24rem;'><?php echo $formattedStartDate ?></span>
<br>
<!-- nl2br boşlukları gösteriyor -->
<p class="mt-2"><?php echo nl2br($blog[0]['content']); ?></p>
<p class="text-center text-muted ">Read <?php echo ($blog[0]['readcount']); ?> times.</p>
       </div>
     </div>
</div>
<?php require 'modal.php';?>
<?php include 'footer.php';?>
  <?php require 'down.html.php';?>
